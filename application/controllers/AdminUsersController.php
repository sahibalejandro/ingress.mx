<?php
/**
 * Admin users
 */
class AdminUsersController extends IngressMXController
{
  public function __construct()
  {
    // Define role access before call parent constructor
    $this->setRoleAccess(INGRESSMX_ROLE_ADMIN);
    parent::__construct();
  }

  public function index()
  {
    // Lista de usuarios, con los no autorizados al inicio
    $users = User::query()
      ->find()
      ->order('authorized')
      ->order('id')
      ->exec();

    // Lista de roles, para los select box
    $roles = Roles::query()->find()->exec();

    $this->addViewVars(array(
      'users' => &$users,
      'roles' => &$roles,
    ))->renderView();
  }

  /**
   * Recibe la solicitud AJAX para cambiar el role de un usuario.
   * Un email es enviado al usuario para notificarle sobre el cambio
   * si falla el envio de email no importa.
   */
  public function ajaxChangeUserRole()
  {
    try {
      // Actualizar el role del usuario
      $User = User::query()->findByPk($_POST['user_id']);
      $User->roles_id = $_POST['roles_id'];
      $User->save();
      
      // Enviar respuesta al cliente, con el nuevo role del usuario
      $this->setAjaxResponse(array(
        'new_role_name' => $User->Role->name,
      ));

      // Enviar email al usuario para avisarle de su nuevo role
      $PHPMailer = ingressmx_create_phpmailer();
      $PHPMailer->Subject = 'Role de usuario modificado';
      $PHPMailer->Body = $this->renderView('email/user-role-changed.php', array(
        'User' => $User
      ), true);
      $PHPMailer->AddAddress($User->email, $User->user);
      $PHPMailer->Send();

    } catch (QuarkORMException $e) {
      $this->setAjaxResponse(null, 'No se pudo cambiar el role del usuario', true);
    } catch (phpmailerException $e) {
      // Si el envio de email falla no importa, no es muy necesario.
      Quark::log('Error al notificar cambio de role: '
        .PHP_EOL
        .strip_tags($e->errorMessage())
      );
    }
  }

  /**
   * Recibe la solicitud para reactivar la cuenta del usuario especificado
   */
  public function ajaxActivateUser()
  {
    try {
      // Actualizar el campo "active" y notificar al usuario via email
      $User = User::query()->findByPk($_POST['user_id']);
      $User->active = 1;
      $User->save();

      $PHPMailer = ingressmx_create_phpmailer();
      $PHPMailer->Subject = 'Cuenta reactivada';
      $PHPMailer->Body = $this->renderView('email/user-unblocked.php', array(
        'User' => $User
      ), true);
      $PHPMailer->AddAddress($User->email, $User->user);
      $PHPMailer->Send();
    } catch (QuarkORMException $e) {
      $this->setAjaxResponse(null, 'No se pudo reactivar la cuenta', true);
    } catch (phpmailerException $e) {
      // Si el envio de email falla no importa, no es muy necesario.
      Quark::log('Error al notificar reactivación: '
        .PHP_EOL
        .strip_tags($e->errorMessage())
      );
    }
  }

  /**
   * Recibe la solicitud para bloquear la cuenta del usuario especificado
   */
  public function ajaxDeactivateUser()
  {
    // Forzar a que el administrador proporcione un mensaje con razón del bloqueo.
    $_POST['block_reason'] = trim($_POST['block_reason']);
    if ($_POST['block_reason'] == '') {
      $this->setAjaxResponse(null, 'Especifique la razón del bloqueo', true);
    } else {
      try {
        // Obtener el ORM del usuario, asignar 0 (cero) al campo 'active' y guardar.
        $User = User::query()->findByPk($_POST['user_id']);
        $User->active = 0;
        $User->save();

        // Notificar por email al usuario de que ha sido bloqueado.
        $PHPMailer = ingressmx_create_phpmailer();
        $PHPMailer->Subject = 'Cuenta bloqueada';
        $PHPMailer->Body = $this->renderView('email/user-blocked.php', array(
          'User'         => $User,
          'block_reason' => $_POST['block_reason'],
        ), true);
        $PHPMailer->AddAddress($User->email, $User->user);
        $PHPMailer->Send();

      } catch (QuarkORMException $e) {
        $this->setAjaxResponse(null, 'No se pudo bloquear al usuario', true);
      } catch (phpmailerException $e) {
        // Si el envio de email falla no importa, no es muy necesario.
        Quark::log('Error al notificar bloqueo: '
          .PHP_EOL
          .strip_tags($e->errorMessage())
        );
      }
      /* END OF: try {...} catch {...} */
    }
    /* END OF: if($_POST['block_reason'] == '') */
  }

  /**
   * Muestra la interfaz para autorizar o denegar una nueva cuenta de usuario
   * 
   * @param string $auth_token Token de authorización
   */
  public function authorizeAccount($user_id, $auth_token = null)
  {
    $User             = false;
    $auth_token_valid = ingressmx_check_auth_token($user_id, $auth_token);

    if ($auth_token_valid) {
      $User = User::query()->findByPk($user_id);
    }

    $this->addViewVars(array(
      'User'             => $User,
      'auth_token'       => $auth_token,
      'auth_token_valid' => $auth_token_valid,
    ))->renderView();
  }

  /**
   * Recibe la solucitud para autorizar/denegar la cuenta de un usuario
   */
  public function ajaxAuthorize()
  {
    if (!ingressmx_check_auth_token($_POST['user_id'], $_POST['auth_token'])) {
      $this->setAjaxResponse(null, 'Token de autorización inválido', true);
    } else {
      try {
        $User = User::query()->findByPk($_POST['user_id']);
        if (!$User) {
          $this->setAjaxResponse(null, 'Token de autorización inválido', true);
        } elseif ($_POST['authorize'] == '1') {
          // Autorizar y activar la cuenta del usuario
          $User->authorized = 1;
          $User->active     = 1;
          $User->save();

          // Enviar email al usuario para notificarle que su cuenta ya esta autorizada
          $PHPMailer = ingressmx_create_phpmailer();
          $PHPMailer->Subject = 'Cuenta autorizada';
          $PHPMailer->AddAddress($User->email, $User->user);
          $PHPMailer->Body = $this->renderView(
            'email/user-account-authorized.php',
            array('User' => $User),
            true
          );
          $PHPMailer->Send();
        } else {
          // Cuenta no autorizada, enviar correo al usuario
          $_POST['reason_denial'] = trim($_POST['reason_denial']);

          if ($_POST['reason_denial'] == '') {
            $this->setAjaxResponse(null, 'Escriba la razón de denegación', true);
          } else {
            $PHPMailer = ingressmx_create_phpmailer();
            $PHPMailer->Subject = 'Cuenta no autorizada';
            $PHPMailer->AddAddress($User->email, $User->user);
            $PHPMailer->Body = $this->renderView(
              'email/user-account-unauthorized.php',
              array(
                'User'          => $User,
                'reason_denial' => &$_POST['reason_denial'],
              ),
              true
            );
            $PHPMailer->Send();
            $User->delete();
          }
        }
      } catch (QuarkORMException $e) {
        $this->setAjaxResponse(null, 'No se pudieron guardar los cambios.', true);
      } catch (phpmailerException $e) {
        Quark::log('Error al notificar autorización: '
          .PHP_EOL
          .strip_tags($e->errorMessage())
        );
        $this->setAjaxResponse(null, 'Cambios realizados, pero no se pudo notificar al usuario.', true);
      }
    }
  }
}

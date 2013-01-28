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
    $users = User::query()
      ->find()
      ->order('authorized')
      ->order('id')
      ->exec();

    $this->addViewVars(array(
      'users' => &$users,
    ))->renderView();
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
        $this->setAjaxResponse(null, 'Cambios realizados, pero no se pudo notificar al usuario.', true);
      }
    }
  }
}

<?php
class ProfileController extends IngressMXController
{
  public function __construct()
  {
    /* Call parent constructor without profile & status check to avoid infinite
     * redirections :P */
    parent::__construct(false, false);
  }

  /**
   * Show (and update) user profile
   */
  public function index()
  {
    // Error code to show diferent messages in the view
    $error_code = '';

    if (!empty($_POST)) {
      $_POST['user'] = trim($_POST['user']);

      if ($_POST['user'] == '') {
        $error_code = 'user';
      } elseif ($_POST['faction'] == '-') {
        $error_code = 'faction';
      } elseif ($_POST['states_id'] == '-') {
        $error_code = 'state';
      } elseif (empty($_FILES)) {
        $error_code = 'nofile';
      } else {
        /*
         * First try to save the uploaded image
         */
        $Uploader = new QuarkUpload();
        $Uploader->setValidMimeTypes('image/jpeg', 'image/jpg', 'image/png');
        $Uploader->setIgnoreEmpty(false);
        $Uploader->setOverwrite(false);

        $UploadResult = $Uploader->moveUploads('screenshot', INGRESSMX_PATH_SCREENSHOTS);
        if ($UploadResult->error) {
          $error_code = 'upload';
        } else {
          $image_src = INGRESSMX_PATH_SCREENSHOTS.'/'.$UploadResult->final_file_name;
          $image_dst = INGRESSMX_PATH_SCREENSHOTS.'/'.$_POST['user'].'.jpg';
          
          // Save user data
          try {
            $this->User->user      = $_POST['user'];
            $this->User->faction   = $_POST['faction'];
            $this->User->states_id = $_POST['states_id'];
            $this->User->cities_id = $_POST['cities_id'];
            
            $this->User->save();
            
            // Resize the image and convert to JPG
            $Image = new QuarkImage_dev($image_src);
            $Image->setJPGQuality(100);
            $Image->resize(380, 550);
            $Image->output($image_dst);
            
            chmod($image_dst, 0666);
            unlink($image_src);

            /* Send a email to the user for notify that his account will
             * be authorized soon.
             *
             * If send fails, we can continue the process, the user will
             * be notified in the site that their account need to be authorized.
             */
            $PHPMailer = ingressmx_create_phpmailer();
            try {
              $PHPMailer->Subject = 'Cuenta en proceso de autorización';
              $PHPMailer->AddAddress($this->User->email, $this->User->user);
              $PHPMailer->Body = $this->renderView(
                'email/user-pending-authorization.php',
                array('User' => $this->User),
                true
              );
              $PHPMailer->Send();
            } catch (phpmailerException $e) {
              Quark::log('Error al enviar el email de notificación de autorización al usuario: '
                .$this->User->user.' ('.$e->errorMessage().')');
            }

            /* Generate unique auth token to avoid invalid authorizations via URL
             * the URL for authorization is defined in the view:
             * "email/admin-authorization-request.php" */
            $auth_token = ingressmx_generate_auth_token($this->User);
            
            /* Send a email to the administrator for notify that a new account
             * needs to be verified. */
            $PHPMailer = ingressmx_create_phpmailer();
            try {
              $PHPMailer->Subject = 'Solicitúd de verificación de cuenta';
              $PHPMailer->AddAddress('admin@ingress.mx', 'ingress.mx');
              $PHPMailer->Body = $this->renderView(
                'email/admin-authorization-request.php',
                array(
                  'User'       => $this->User,
                  'auth_token' => $auth_token
                ),
                true
              );
              $PHPMailer->Send();

              // Redirect to profile to avoid re-send post data.
              header('Location:'.$this->QuarkURL->getURL('profile'));
              exit;

            } catch (phpmailerException $e) {
              $error_code = 'admin-email';
              Quark::log('Error al enviar email al administrador para autorizar al usuario: '
                .$this->User->user.' ('.$e->errorMessage().')');
            }

          } catch (QuarkORMException $e) {
            $error_code = 'db';
            unlink($image_src);
          }
        }
      }
    }

    $this->addViewVars(array(
      'error_code'   => $error_code,
      'UploadResult' => isset($UploadResult) ? $UploadResult : null
    ))->renderView();
  }

  public function logout()
  {
    $this->QuarkSess->kill();
    header('Location:'.$this->QuarkURL->getBaseURL());
    exit(0);
  }
}

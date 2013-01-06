<?php
class IngressMXController extends QuarkController
{
  protected $User = null;

  public function __construct()
  {
    parent::__construct();

    if ($this->QuarkSess->getAccessLevel() > 0) {
      
      $this->User = User::query()->findByPk($this->QuarkSess->get('logged_user_id'));

      $action = $this->QuarkURL->getPathInfo()->action;
      
      if ($this->User->user == null
        && $action != 'profile'
        && $action != 'logout'
      ) {
        $this->changeActionName('profile');
      }
    }

    $this->setDefaultAccessLevel(1);
  }

  /**
   * Show (and update) user profile
   */
  public function profile()
  {
    // Error code to show diferent messages in the view
    $error_code = 0;

    if (!empty($_POST)) {
      $_POST['user'] = trim($_POST['user']);

      if ($_POST['user'] == '') {
        $error_code = 1;
      } elseif ($_POST['fraction'] == '-') {
        $error_code = 2;
      } elseif (empty($_FILES)) {
        $error_code = 3;
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
          $error_code = 4;
        } else {
          $image_src = INGRESSMX_PATH_SCREENSHOTS.'/'.$UploadResult->final_file_name;
          $image_dst = INGRESSMX_PATH_SCREENSHOTS.'/'.$_POST['user'].'.jpg';
          
          // Save user data
          try {
            $this->User->user       = $_POST['user'];
            $this->User->fraction   = $_POST['fraction'];
            $this->User->save();
            
            // Resize the image and convert to JPG
            $Image = new QuarkImage_dev($image_src);
            $Image->setJPGQuality(100);
            $Image->resize(380, 550);
            $Image->output($image_dst);
            
            chmod($image_dst, 0666);
            unlink($image_src);

            // Redirect to profile to avoid re-send post data.
            header('Location:'.$this->QuarkURL->getURL('profile'));
            exit;

          } catch (QuarkORMException $e) {
            $error_code = 5;
            unlink($image_src);
          } catch (QuarkImageException $e) {
            $error_code = 5;
            if (file_exists($image_dst)) {
              unlink($image_dst);
            }
          }
        }
      }
    }

    $this->renderView('profile.php', array(
      'error_code'   => $error_code,
      'UploadResult' => isset($UploadResult) ? $UploadResult : null
    ));
  }

  public function logout()
  {
    $this->QuarkSess->kill();
    header('Location:'.$this->QuarkURL->getBaseURL());
  }

  public function header($page_title = '', $sidebar = true)
  {
    $this->renderView('layout/header.php', array(
      'page_title' => $page_title,
      'sidebar'    => $sidebar
    ));
  }

  public function footer()
  {
    $this->renderView('layout/footer.php');
  }
}

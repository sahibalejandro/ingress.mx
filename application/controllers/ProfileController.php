<?php
class ProfileController extends IngressMXController
{
  public function __construct()
  {
    parent::__construct(false);
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

            // Redirect to profile to avoid re-send post data.
            header('Location:'.$this->QuarkURL->getURL('profile'));
            exit;

          } catch (QuarkORMException $e) {
            $error_code = 'db';
            unlink($image_src);
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
    exit(0);
  }
}

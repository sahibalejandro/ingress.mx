<?php
class IngressMXController extends QuarkController
{
  protected $User;
  protected $UserInfo;

  public function __construct()
  {
    parent::__construct();
    $this->setDefaultAccessLevel(1);

    /*
     * Check for logged user (and not in "logout" action) and verify if his profile
     * is complete, if is not complete redirect to profile section.
     */
    if ($this->QuarkSess->getAccessLevel() > 0
      && $this->QuarkURL->getPathInfo()->action != 'logout'
    ) {
      $this->UserInfo = $this->QuarkSess->get('userinfo');
      $this->User = User::getByGoogleID($this->UserInfo->id);

      if ($this->User->fraction == null) {
        $this->changeActionName('profile');
      }
    }
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
        
      }
    }

    $this->addViewVars(array(
      'error_code' => $error_code
    ));
    $this->renderView('profile.php');
  }

  public function logout()
  {
    $this->QuarkSess->kill();
    header('location:'.$this->QuarkURL->getBaseURL());
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

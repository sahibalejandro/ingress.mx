<?php
class IngressMXController extends QuarkController
{
  protected $User       = null;
  protected $user_ready = false;

  public function __construct($check_profile = true)
  {
    parent::__construct();

    if ($this->QuarkSess->getAccessLevel() > 0) {
      
      $this->User = User::query()->findByPk($this->QuarkSess->get('logged_user_id'));

      $this->user_ready = ($this->User->user != null);

      if ($check_profile) {
        $action = $this->QuarkURL->getPathInfo()->action;
        if (!$this->user_ready) {
          header('Location:'.$this->QuarkURL->getURL('profile'));
          exit(0);
        }
      }
    }

    $this->setDefaultAccessLevel(1);
  }

  protected function header($page_title = '', $sidebar = true)
  {
    /*
     * Load main/secondary menu items
     */
    $main_menu_categories      = array();
    $secondary_menu_categories = array();

    // Items for the main menu
    $main_menu_categories = Categories::query()
      ->find()
      ->where(array('on_main_menu' => 1))
      ->exec();

    // Secondary menu items only loaded when sidebar is visible
    if ($sidebar) {
      $secondary_menu_categories = Categories::query()
        ->find()
        ->where(array('on_secondary_menu' => 1))
        ->exec();
    }

    /*
     * just render the view
     */
    $this->renderView('layout/header.php', array(
      'page_title' => $page_title,
      'sidebar'    => $sidebar,
      'main_menu_categories'      => $main_menu_categories,
      'secondary_menu_categories' => $secondary_menu_categories,
    ));
  }

  /**
   * Render a specified Post
   * 
   * @param Posts $Post The Post ORM instance
   * @param bool $resume Render resume or entire content
   * @param bool $is_front_page Render post in "Front page" mode
   * @return IngressMXController For method linking
   */
  protected function renderPost($Post, $resume = false, $is_front_page = false)
  {
    $this->renderView('layout/post.php', array(
      'Post'          => $Post,
      'resume'        => $resume,
      'is_front_page' => $is_front_page,
    ));
    return $this;
  }

  protected function footer()
  {
    $this->renderView('layout/footer.php');
  }
}

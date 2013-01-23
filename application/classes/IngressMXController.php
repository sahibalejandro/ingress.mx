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
   * @param bool $render_style Render style
   * @return IngressMXController For method linking
   */
  protected function renderPost($Post, $render_style)
  {
    switch ($render_style) {
      case INGRESSMX_RENDER_STYLE_FRONT_PAGE:
        $this->renderView('post/post-front-page.php', array(
          'render_style_class' => 'front-page',
          'Post'               => $Post,
        ));
        break;
      case INGRESSMX_RENDER_STYLE_TEASER:
        $this->renderView('post/post-teaser.php', array(
          'render_style_class' => 'teaser',
          'Post'               => $Post,
        ));
        break;
      case INGRESSMX_RENDER_STYLE_FULL:
        $this->renderView('post/post-full.php', array(
          'render_style_class' => 'full',
          'Post'               => $Post,
        ));
        break;
    }
    return $this;
  }

  protected function formatDateTime($date_time)
  {
    return mb_convert_case(
      strftime('%a %d, %b %Y, %R Hrs.', strtotime($date_time)),
      MB_CASE_TITLE,
      'UTF-8'
    );
  }

  protected function footer()
  {
    $this->renderView('layout/footer.php');
  }
}

<?php
class IngressMXController extends QuarkController
{
  /**
   * Logged user data, this is NULL if no user is logged in.
   * 
   * @var User
   */
  protected $User = null;

  /**
   * Flag to know if the user has completed their profile
   * 
   * @var bool
   */
  protected $user_profile_completed = false;

  /**
   * Access role IDs, this need to be defined in the child controller's __construct()
   * using method setRoleAccess()
   * 
   * @var array
   */
  private $access_role_ids = array();

  /**
   * Controller constructor, if $check_user_profile is TRUE then validate if the user
   * has completed their profile information, if is not complete redirect to profile
   * section to force to complete it.
   */
  public function __construct($check_user_profile = true, $check_user_status = true)
  {
    parent::__construct();

    if ($this->QuarkSess->getAccessLevel() > 0) {
      
      $this->User = User::query()->findByPk($this->QuarkSess->get('logged_user_id'));
      $this->user_profile_completed = ($this->User->user != null);

      // Check if the user has completed their profile
      if ($check_user_profile && !$this->user_profile_completed) {
        header('Location:'.$this->QuarkURL->getURL('profile'));
        exit(0);
      }

      // Check if the user is inactive or unauthorized
      if ($check_user_status
        && ($this->User->active == 0 || $this->User->authorized == 0)
      ) {
        header('Location:'.$this->QuarkURL->getURL('user-inactive'));
        exit(0);
      }

      /* Check role access, if no roles are defined for this controller then all
       * roles can see it */
      if (count($this->access_role_ids) > 0) {
        if (array_search($this->User->roles_id, $this->access_role_ids) === false) {
          if (!QUARK_AJAX) {
            header('Location:'.$this->QuarkURL->getURL('access-denied'));
          } else {
            $this->setAjaxAccessDenied();
            $this->__sendAjaxResponse();
          }
          exit(0);
        }
      }
    }

    $this->setDefaultAccessLevel(1);
  }

  protected function setRoleAccess($role_id)
  {
    $this->access_role_ids = func_get_args();
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
     * Render the view
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
  protected function renderPost($Post, $render_style, $return = false)
  {
    switch ($render_style) {
      case INGRESSMX_RENDER_STYLE_FRONT_PAGE:
        return $this->renderView('post/post-front-page.php', array(
          'render_style_class' => 'front-page',
          'Post'               => $Post,
          ), $return);
        break;
      case INGRESSMX_RENDER_STYLE_TEASER:
        return $this->renderView('post/post-teaser.php', array(
          'render_style_class' => 'teaser',
          'Post'               => $Post,
          ), $return);
        break;
      case INGRESSMX_RENDER_STYLE_FULL:
        return $this->renderView('post/post-full.php', array(
          'render_style_class' => 'full',
          'Post'               => $Post,
          ), $return);
        break;
    }
    return $this;
  }
  
  protected function renderComment($Comment, $return = false)
  {
    return $this->renderView('post/post-comment.php', array(
      'render_style_class' => 'comment',
      'Comment'            => $Comment,
      ), $return);
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

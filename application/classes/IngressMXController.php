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

    /* Categorias para el menu principal
     * Solo categorias para el role del usuario firmado */
    if ($this->User) {
      $main_menu_categories = Categories::getAvailableCategoriesForUser(
        $this->User,
        Categories::FOR_MAIN_MENU
      );
    }

    /* Categorias para el menu secundario
     * Solo categorias para el role del usuario firmado */
    if ($sidebar && $this->User) {
      $secondary_menu_categories = Categories::getAvailableCategoriesForUser(
        $this->User,
        Categories::FOR_SECONDARY_MENU
      );
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

  /**
   * Formatea un DATETIME de MySQL a un formato más amigable
   * 
   * @param string $date_time
   * @return string
   */
  protected function formatDateTime($date_time)
  {
    return mb_convert_case(
      strftime('%a %d, %b %Y, %R Hrs.', strtotime($date_time)),
      MB_CASE_TITLE,
      'UTF-8'
    );
  }

  /**
   * Muestra el tiempo transcurrido desde el DATETIME de MySQL hasta la actualidad
   * El formato de salida debe ser identico al plugin jquery.elapsedtime.js
   * 
   * @param string $date_time
   * @return string
   */
  protected function formatElapsedTime($date_time)
  {
    $timestamp   = strtotime($date_time);
    $seconds     = time() - $timestamp;
    $minutes     = round($seconds / 60);
    $hours       = round($seconds / 3600);
    $days        = round($seconds / 86400);
    $date_string = '';

    if ($seconds < 60) {
      // Segundos
      if ($seconds < 2) {
        $date_string = 'hace '.$seconds.' segundo';
      } else {
        $date_string = 'hace '.$seconds.' segundos';
      }
    } else if ($minutes < 60) {
      // Minutos
      if ($minutes < 2) {
        $date_string = 'hace '.$minutes.' minuto';
      } else {
        $date_string = 'hace '.$minutes.' minutos';
      }
    } else if ($hours < 24) {
      // Horas
      if ($hours < 2) {
        $date_string = 'hace '.$hours.' hora';
      } else {
        $date_string = 'hace '.$hours.' horas';
      }
    } else if ($days < 5) {
      // Días
      if ($days < 2) {
        $date_string = 'ayer';
      } else {
        $date_string = 'hace '.$days.' días';
      }
    } else {
      $date_string = 'el '
        .ucfirst(strftime('%a.', $timestamp))
        .' ' .strftime('%e', $timestamp)
        .' de ' .ucfirst(strftime('%b.', $timestamp))
        .' ' .strftime('%Y', $timestamp)
        .', ' .strftime('%H', $timestamp)
        .':' .strftime('%M', $timestamp)
        .' Hrs.';
    }
    return $date_string;
  }

  /**
   * Manda a buffer el código HTML para mostrar la ruta de categorias a las que pertenece
   * una categoria especificada por $Category
   * 
   * @param Categories $Post
   */
  public function renderCategoryPath(Categories $Category, $include_first = true)
  {
    $categories     = array();
    
    if ($include_first) {
      $categories[] = $Category;
    }

    $ParentCategory = $Category->getParent('Categories');
    while ($ParentCategory) {
      $categories[] = $ParentCategory;
      $ParentCategory = $ParentCategory->getParent('Categories');
    }
    $categories = array_reverse($categories);
    $this->renderView('post/post-path.php', array('categories' => $categories));
  }

  protected function footer()
  {
    $this->renderView('layout/footer.php');
  }
}

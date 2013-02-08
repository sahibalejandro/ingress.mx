<?php
class HomeController extends IngressMXController
{
  public function __construct()
  {
    parent::__construct();

    $this->setActionsAccessLevel(array(
      'oauth2callback' => 0
    ));
  }

  public function index($page = 1)
  {
    $posts_per_page = 5;

    // Sanitize $page
    $page = (int)$page;
    if ($page < 1) {
      $page = 1;
    }

    /* Obtener las publicaciones principales filtradas por el faction del usuario
     * firmado, mostrando al inicio las publicaciones pegajosas */
    $posts = Posts::query()
      ->find()
      ->where(array('published' => 1, 'on_front_page' => 1))
      ->where("faction='*' OR faction='".$this->User->faction."'")
      ->order('stick', 'desc')
      ->order('creation_date', 'desc')
      ->limit($posts_per_page * ($page - 1), $posts_per_page)
      ->exec();

    $this->addViewVars(array(
      'posts' => &$posts,
      'page' => $page
    ))->renderView();
  }
}

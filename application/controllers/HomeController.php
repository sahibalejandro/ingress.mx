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

  public function index()
  {
    $posts = Posts::query()
      ->find()
      ->where(array('published' => 1, 'on_front_page' => 1))
      ->order('creation_date')
      ->exec();

    $this->addViewVars(array(
      'posts' => &$posts
    ))->renderView();
  }
}

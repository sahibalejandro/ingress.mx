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
    $this->renderView();
  }
}

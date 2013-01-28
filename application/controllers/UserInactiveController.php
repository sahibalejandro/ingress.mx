<?php
class UserInactiveController extends IngressMXController
{
  public function __construct()
  {
    /* Call parent constructor without profile & status check to avoid infinite
     * redirections :P */
    parent::__construct(false, false);
  }

  public function index()
  {
    $this->renderView();
  }
}

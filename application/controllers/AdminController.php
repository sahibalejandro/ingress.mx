<?php
/**
 * Admin front panel controller
 */
class AdminController extends IngressMXController
{
  public function __construct()
  {
    // Define role access before call parent constructor
    $this->setRoleAccess(INGRESSMX_ROLE_ADMIN);
    parent::__construct();
  }

  public function index()
  {
    $this->renderView();
  }
}

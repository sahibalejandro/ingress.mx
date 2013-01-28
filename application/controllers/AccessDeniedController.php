<?php
/**
 * This controller is only to show "access denied" message, called when a user role
 * is inssuficient.
 */
class AccessDeniedController extends IngressMXController
{
  public function __construct()
  {
    parent::__construct(false);
  }

  public function index()
  {
    $this->renderView();
  }
}

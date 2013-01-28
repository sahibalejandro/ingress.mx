<?php
class CatalogsController extends IngressMXController
{
  public function __construct()
  {
    /* Call parent constructor without profile & status check to avoid infinite
     * redirections :P */
    parent::__construct(false, false);
  }

  public function cities()
  {
    $this->setAjaxResponse(
      Cities::query()
        ->select('id','name')
        ->where(array('states_id' => $_POST['states_id']))
        ->order('name')
        ->exec()
    );
  }
}

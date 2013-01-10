<?php
class CatalogsController extends IngressMXController
{
  public function __construct()
  {
    parent::__construct(false);
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

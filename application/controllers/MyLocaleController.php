<?php
class MyLocaleController extends IngressMXController
{
  public function index()
  {
    /* Obtener los posts que pertenecen a la ciudad del usuario firmado y que
     * sean de su faction */
    $posts = Posts::query()
      ->find()
      ->where(array(
        'states_id' => $this->User->states_id,
        'cities_id' => $this->User->cities_id,
      ))
      ->where("faction='*' OR faction=:user_faction", array(
        ':user_faction' => $this->User->faction,
      ))
      ->order('stick', 'desc')
      ->order('creation_date', 'desc')
      ->exec();

    $this->addViewVars(array(
      'posts' => &$posts,
    ))->renderView();
  }
}

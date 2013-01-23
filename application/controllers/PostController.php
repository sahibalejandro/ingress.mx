<?php
class PostController extends IngressMXController
{
  public function read($post_id, $page = 1)
  {
    // Get post data, filtered by ID and Faction
    $Post = Posts::query()
      ->findOne()
      ->where(array(
        'id'        => $post_id,
        'posts_id'  => null,
        'published' => 1,
      ))
      ->where("faction='*' OR faction='".$this->User->faction."'")
      ->exec();

    if ($Post == false) {
      $this->__quarkNotFound();
    } else {
      $this->addViewVars(array('Post' => $Post))->renderView();
    }
  }
}

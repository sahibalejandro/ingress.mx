<?php
class PostController extends IngressMXController
{
  public function read($post_id, $page = 1)
  {
    // Get post data, filtered by post ID and Faction
    $Post = Posts::query()
      ->findOne()
      ->where(array(
        'id'        => $post_id,
        'published' => 1,
      ))
      ->where("faction='*' OR faction='".$this->User->faction."'")
      ->exec();

    if ($Post == false) {
      $this->__quarkNotFound();
    } else {
      $this->addViewVars(array(
        'Post'          => $Post,
        'post_comments' => $Post->getComments($this->User->faction),
      ))->renderView();
    }
  }
}

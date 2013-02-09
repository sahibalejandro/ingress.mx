<?php
class PostController extends IngressMXController
{
  /**
   * Agrega un nuevo token a la lista de tokesn para comentar que esta en la
   * sesión del usuario firmado.
   */
  private function generateCommentToken($post_id)
  {
    // Get actual comment tokens array, if not defined create it.
    $comment_tokens = $this->QuarkSess->get('comment_tokens');
    if (!is_array($comment_tokens)) {
      $comment_tokens = array();
    }

    // Save the post ID and post token
    $token = md5(uniqid());
    $comment_tokens[] = array(
      'post_id' => $post_id,
      'token'   => $token,
    );

    // Save changes in session and return the token
    $this->QuarkSess->set('comment_tokens', $comment_tokens);

    return $token;
  }

  /**
   * Show a post and comments.
   * This method generate a post-token that validate when the user comment in this
   * posts and a new post-token is generated to future comments, this way the user
   * only can comment in posts that he are reading at this time.
   */
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
        'comments'      => $Post->getComments($this->User),
        'comment_token' => $this->generateCommentToken($Post->id),
      ))->renderView();
    }
  }

  /**
   * Receibe ajax request to post a comment
   */
  public function ajaxPostComment()
  {
    // first validate post token
    $is_token_valid = false;
    $comment_tokens = $this->QuarkSess->get('comment_tokens');
    foreach ($comment_tokens as $i => $comment_token) {
      if ($comment_token['post_id'] == $_POST['comment_post_id']
        && $comment_token['token'] == $_POST['comment_token']
      ) {
        // Token valid, now delete old token and make new one.
        $is_token_valid = true;
        unset($comment_tokens[$i]);
        $new_token = $this->generateCommentToken($_POST['comment_post_id']);
        break;
      }
    }

    // Try to save the comment only if token is valid
    if ($is_token_valid == false) {
      $this->setAjaxResponse(null, 'Token incorrecto', true);
    } else {
      try {
        $Comment = Comments::create();
        $Comment->posts_id = $_POST['comment_post_id'];
        $Comment->users_id = $this->User->id;
        $Comment->faction  = $this->User->faction;
        $Comment->content  = $_POST['comment_content'];

        /* If save is successful return new token and the comment render,
         * else return the error message */
        if ($Comment->save()) {
          $this->setAjaxResponse(array(
            'new_token'    => $new_token,
            'comment_html' => $this->renderComment($Comment, true),
          ));
        } else {
          $this->setAjaxResponse(null, 'No se puede guardar el comentario', true);
        }
      } catch (QuarkORMException $e) {
        $msg = 'Error al tratar de guardar el comentario';
        Quark::log($msg.': '.$e->getMessage());
        $this->setAjaxResponse(null, $msg, true);
      }
    }
  }
}

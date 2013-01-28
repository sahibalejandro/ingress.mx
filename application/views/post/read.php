<?php
$this->appendJsFiles('post/comment.js');
$this->header($Post->title);
$this->renderPost($Post, INGRESSMX_RENDER_STYLE_FULL);
?>
<div id="comments_container">
  <h3><a name="comments">Comentarios:</a></h3>
  <?php
  if (count($comments) == 0):
    echo 'Sin comentarios';
  else:
    foreach($comments as $Comment):
      $this->renderComment($Comment);
    endforeach;
  endif;
  ?>
</div>
<!-- END OF #comments_container -->

<form action="javascript:;" id="frm_comment" method="post">
  <input type="hidden" name="comment_post_id" value="<?php echo $Post->id; ?>"/>
  <input type="hidden" name="comment_token" value="<?php echo $comment_token; ?>"/>
  <label for="comment_content">Escribir comentario:</label>
  <textarea name="comment_content" id="comment_content"></textarea>
  <div id="comment_error_msg" class="alert alert-error"></div>
  <div class="form-actions">
    <button id="btn_submit_comment" class="btn" type="submit">Publicar comentario</button>
  </div>
</form>
<!-- END OF: #frm_comment -->
<?php
$this->footer();

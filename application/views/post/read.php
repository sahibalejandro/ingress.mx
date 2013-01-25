<?php
$this->appendJsFiles('post/comment.js');
$this->header($Post->title);
$this->renderPost($Post, INGRESSMX_RENDER_STYLE_FULL);
?>
<h3><a name="comments">Comentarios:</a></h3>
<?php
if (count($post_comments) == 0):
  echo 'Sin comentarios';
else:
  foreach($post_comments as $PostComment):
    $this->renderPost($PostComment, INGRESSMX_RENDER_STYLE_COMMENT);
  endforeach;
endif;
?>
<form action="javascript:;" id="frm_comment" method="post">
  <label for="comment_content">Escribir comentario:</label>
  <textarea name="comment_content" id="comment_content"></textarea>
  <div class="form-actions">
    <button class="btn" type="submit">Publicar comentario</button>
  </div>
</form>
<?php
$this->footer();

<div class="post <?php echo $render_style_class ?>">
  <div class="post-header">
    <div class="post-info">
      Fecha:
      <span class="post-time">
        <?php echo $this->formatDateTime($PostComment->creation_date); ?>
      </span>
      Autor:
      <span class="post-author"><?php echo $PostComment->User->html_link; ?></span>
    </div>
  </div>
  <div class="post-content"><?php echo nl2br($this->QuarkStr->esc($PostComment->content)); ?></div>
</div>

<div id="comment_<?php echo $Comment->id; ?>" class="post <?php echo $render_style_class ?>">
  <div class="post-header">
    <div class="post-info">
      Publicado
      <span class="post-time"
        data-timestamp="<?php echo strtotime($Comment->creation_date); ?>"
        title="<?php echo $this->formatDateTime($Comment->creation_date); ?>">
        <?php echo $this->formatElapsedTime($Comment->creation_date); ?></span>, por:
      <span class="post-author"><?php echo $Comment->User->html_link; ?></span>
    </div>
  </div>
  <div class="post-content"><?php echo nl2br($this->QuarkStr->esc($Comment->content)); ?></div>
</div>

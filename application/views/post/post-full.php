<div class="post <?php echo $render_style_class ?>">
  <div class="post-header">
    <h3><a href="<?php echo $Post->url; ?>"><?php echo $this->QuarkStr->esc($Post->title); ?></a></h3>
    <div class="post-info">
      Fecha:
      <span class="post-time">
        <?php echo $this->formatDateTime($Post->creation_date); ?>
      </span>
      Autor:
      <span class="post-author"><?php echo $Post->User->html_link; ?></span>
    </div>
  </div>
  <div class="post-content"><?php echo $Post->content; ?></div>
</div>

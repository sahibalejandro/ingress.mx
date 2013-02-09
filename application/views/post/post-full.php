<div class="post <?php echo $render_style_class ?>">
  <div class="post-header">
    <h3><a href="<?php echo $Post->url; ?>"><?php
      echo $this->QuarkStr->esc($Post->title);
    ?></a></h3>
    <div class="post-info">
      <?php if ($Post->isSecure()): ?>
      <span class="secure">[secure]</span>
      <?php endif; ?>
      Publicado
      <span class="post-time"
        data-timestamp="<?php echo strtotime($Post->creation_date); ?>"
        title="<?php echo $this->formatDateTime($Post->creation_date); ?>">
        <?php echo $this->formatElapsedTime($Post->creation_date); ?></span>, por:
      <span class="post-author"><?php echo $Post->User->html_link; ?></span>
    </div>
  </div>
  <div class="post-content"><?php echo $Post->content; ?></div>
</div>

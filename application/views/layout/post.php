<article class="post" id="post_<?php echo $Post->id; ?>">
  <header>
    <h4><?php echo $this->QuarkStr->esc($Post->title); ?></h4>
    
    <div class="post-info">
      <time><?php echo strftime('%a %d, %b %Y, %R', strtotime($Post->creation_date)); ?></time>
      -
      <span class="author">
        Autor:
        <a href="<?php echo $Post->User->url; ?>">
          <?php echo $this->QuarkStr->esc($Post->User->user); ?>
        </a>
      </span>
    </div>
    <!-- // .info -->
    
  </header>
  <div class="post-content"><?php
    if ($resume):
      echo $this->QuarkStr->esc($Post->getResume());
    else:
      echo $Post->content;
    endif;
  ?></div>
  <footer>
    <a class="btn" href="<?php echo $Post->url; ?>">Leer más...</a>
    <a class="btn" href="<?php echo $Post->url.'#comments'; ?>">Comentarios: 0</a>
  </footer>
</article>

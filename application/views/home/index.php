<?php
/*
 * Render header
 */
$this->header('Bienvenido');

if (count($posts) == 0):
  $this->renderView('layout/no-more-content.php');
else:
  /*
   * Render front page posts
   */
  foreach ($posts as $Post):
    $this->renderPost($Post, INGRESSMX_RENDER_STYLE_FRONT_PAGE);
  endforeach;
  
  /*
   * Buttons to navigate back and forward
   */
  ?>
  <div class="form-actions">
    <?php if ($page > 1): ?>
    <a href="<?php echo $this->QuarkURL->getURL('page/'.($page - 1)); ?>" class="btn"
      title="Ver publicaciones más nuevas">&laquo; Recientes</a>
    <?php endif; ?>
    <a href="<?php echo $this->QuarkURL->getURL('page/'.($page + 1)); ?>" class="btn"
      title="Ver publicaciones más viejas">Anteriores &raquo;</a>
  </div>
  <?php
endif;
/* END OF: if (count($posts) == 0) */

/*
 * Render footer
 */
$this->footer();

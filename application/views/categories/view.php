<?php
$this->header($Category->name);

if (count($posts) == 0):
  $this->renderView('layout/no-more-content.php');
else: ?>
<div class="page-header">
  <h3><?php echo $this->QuarkStr->esc($Category->name); ?></h3>
</div>
<?php
foreach ($posts as $Post):
  $this->renderPost($Post, INGRESSMX_RENDER_STYLE_TEASER);
endforeach;
/*
 * Buttons to navigate back and forward
 */
?>
<div class="form-actions">
  <?php if ($page > 1): ?>
  <a href="<?php echo $this->QuarkURL->getURL('categories/'.$Category->id.'/'.($page - 1)); ?>" class="btn"
    title="Ver publicaciones más nuevas">&laquo; Recientes</a>
  <?php endif; ?>
  <a href="<?php echo $this->QuarkURL->getURL('categories/'.$Category->id.'/'.($page + 1)); ?>" class="btn"
    title="Ver publicaciones más viejas">Anteriores &raquo;</a>
</div>
<?php
endif;
/* END OF: if (count($posts) == 0) */
$this->footer();

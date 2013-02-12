<?php
$this->header($Category->name);

if (count($posts) == 0):
  $this->renderView('layout/no-more-content.php');
else: ?>
<div class="categories-path"><?php
  $this->renderCategoryPath($Category, false);
?></div>
<div class="page-header">
  <h3><?php echo $this->QuarkStr->esc($Category->name); ?></h3>
  <?php
  // Mostrar sub-categorias si existen
  $sub_categories = $Category->getChilds('Categories')->exec();
  if (count($sub_categories) > 0): ?>
    <div class="sub-categories">
    <?php foreach ($sub_categories as $SubCategory): ?>
    &bull; <a href="<?php echo $SubCategory->url; ?>"><?php echo $this->QuarkStr->esc($SubCategory->name); ?></a>
    <?php endforeach; ?>
    </div>
  <?php endif;
  /* END OF: Sub-categories */
  ?>
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

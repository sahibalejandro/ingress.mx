<?php
/**
 * Esta vista es para la pagina principal de las categorias
 * @author Sahib J. Leo
 */
$this->appendCssFiles('categories/index.css')
  ->header('Categorías');
?>
<div class="page-header">
  <h3>Categorías</h3>
</div>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Categoría</th>
      <th>Número de publicaciones</th>
      <th>Última publicación</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($categories as $Category): ?>
    <tr>
      <td>
        <h4>
          <a href="<?php echo $Category->url; ?>"><?php
            echo $this->QuarkStr->esc($Category->name);
          ?></a>
        </h4>
        <div class="category_description">
          <?php echo $this->QuarkStr->esc($Category->description); ?>
        </div>
      </td>
      <td><?php echo $Category->getPostsCount($this->User); ?></td>
      <td><?php
      // Mostrar datos del último post en esta categoria
      $LastPost = $Category->getLastPost($this->User);
      if ($LastPost == false):
        echo '---';
      else: ?>
        <a href="<?php echo $LastPost->url; ?>"><?php echo $this->QuarkStr->esc($LastPost->title); ?></a>
        <div class="post-info">
          Publicada
          <span class="last_post_date" data-timestamp="<?php echo strtotime($LastPost->creation_date); ?>"><?php
            echo $this->QuarkStr->esc($this->formatDateTime($LastPost->creation_date));
          ?></span>, por: <?php echo $LastPost->User->html_link; ?>
        </div>
      <?php endif; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php $this->footer();

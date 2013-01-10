<?php
/*
 * Render header
 */
$this->header('Bienvenido');

/*
 * Render front page posts
 */
foreach ($posts as $Post):
  $this->renderPost($Post, true, true);
endforeach;

/*
 * Render footer
 */
$this->footer();

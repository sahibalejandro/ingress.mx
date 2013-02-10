<?php
$this->header($this->User->City->name.', '.$this->User->State->name);
?>
<div class="page-header">
  <h3><?php
    echo $this->QuarkStr->esc($this->User->City->name.', '.$this->User->State->name);
  ?></h3>
</div>
<?php foreach ($posts as $Post):
  $this->renderPost($Post, INGRESSMX_RENDER_STYLE_TEASER);
endforeach; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <base href="<?php echo $this->QuarkURL->getBaseURL(); ?>">
  <title>
    <?php
    if ($page_title != ''):
      echo $this->QuarkStr->esc($page_title).' :: ';
    endif;
    ?>Ingress.mx
  </title>
  <?php
  $this->prependCssFiles(
    'bootstrap.min.css',
    'bootstrap-responsive.min.css',
    'ingressmx.css'
  );
  if ($this->User && $this->User->fraction):
    $this->appendCssFiles(strtolower($this->User->fraction_name).'.css');
  endif;
  $this->includeCssFiles();
  ?>
</head>
<body>
  
  <!-- Main Navbar -->
  <div class="navbar navbar-inverse">
    <div class="navbar-inner">
      <a id="brand" class="brand"
        href="<?php echo $this->QuarkURL->getBaseURL(); ?>">
        <span id="brand_name">Ingress.mx</span>
        <?php if ($this->User && $this->User->fraction): ?>
        <span id="brand_fraction">/ <?php echo $this->User->fraction_name; ?></span>
        <?php endif; ?>
      </a>
      <?php if ($this->User): ?>
      <ul class="nav pull-right">
        <li class="dropdown">
          <a href="#" id="agent_menu" class="dropdown-toogle" data-toggle="dropdown">
            <?php echo !$this->User->user ?
              $this->User->email :
              'Agente: '.$this->User->user; ?>
          </a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo $this->QuarkURL->getURL('profile'); ?>">Perfil</a></li>
            <li><a href="<?php echo $this->QuarkURL->getURL('logout'); ?>">Salir</a></li>
          </ul>
        </li>
      </ul>
      <?php endif; ?>
    </div>
  </div>
  <!-- // Main Navbar -->
  
  <?php if (!$sidebar): ?>
  <div class="container">
  <?php else: ?>
  <!-- .container-fluid -->
  <div class="container-fluid">
    <div class="row-fluid">
      <div id="sidebar" class="span2">
      </div>
      <!-- .content -->
      <div id="content" class="span10">
  <?php endif; ?>

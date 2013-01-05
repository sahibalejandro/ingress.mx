<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>
    <?php
    if ($page_title != ''):
      echo $this->QuarkStr->esc($page_title).' :: ';
    endif;
    ?>Ingress MX
  </title>
  <?php
  $this->prependCssFiles(
    'bootstrap.min.css',
    'bootstrap-responsive.min.css',
    'ingressmx.css'
  )->includeCssFiles();
  ?>
</head>
<body>
  
  <!-- Main Navbar -->
  <div class="navbar">
    <div class="navbar-inner">
      <a class="brand" href="<?php echo $this->QuarkURL->getBaseURL(); ?>">IngressMX</a>
      <?php if ($this->User): ?>
      <ul class="nav pull-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toogle" data-toggle="dropdown">
            <img src="<?php echo $this->UserInfo->picture; ?>" alt="" width="20" height="20"/>
            <?php echo $this->UserInfo->given_name; ?>
          </a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo $this->QuarkURL->getURL('profile'); ?>">Perfil IngressMX</a></li>
            <li><a href="<?php echo $this->User->google_link; ?>">Perfil Google</a></li>
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

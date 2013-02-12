<a href="<?php echo $this->QuarkURL->getURL(); ?>">ingress.mx</a>
<?php foreach ($categories as $Category): ?>
/ <a href="<?php echo $Category->url; ?>"><?php echo $this->QuarkStr->esc($Category->name); ?></a>
<?php endforeach; ?>

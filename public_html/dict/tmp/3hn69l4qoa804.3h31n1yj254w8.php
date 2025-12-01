<?php echo $this->render('header.htm',NULL,get_defined_vars(),0); ?>
<!-- <img src="ui/img/imagen.jpg" class="img-thumbnail" alt="..."> -->
<audio id="notification-sound">
  <source src="ui/audio/newappoinment.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>
<?php echo $this->render($view,NULL,get_defined_vars(),0); ?>
<?php echo $this->render('footer.htm',NULL,get_defined_vars(),0); ?>
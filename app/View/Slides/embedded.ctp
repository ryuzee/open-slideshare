<?php echo $this->Html->script('jquery-1.10.2.min.js', array('fullBase' => true)); ?>
<?php echo $this->Html->script('jquery-ui.min.js', array('fullBase' => true)); ?>
<?php echo $this->Html->script('bootstrap.min.js', array('fullBase' => true)); ?>
<?php echo $this->Html->css('bootstrap.min.css', array('fullBase' => true)); ?>
<?php echo $this->Html->css('jquery-ui.min.css', array('fullBase' => true)); ?>
<?php echo $this->Html->css('jquery.bxslider.css', array('fullBase' => true)); ?>
<?php echo $this->Html->css('openslideshare.css', array('fullBase' => true)); ?>
<?php echo $this->Html->css('openslideshare_embedded.css', array('fullBase' => true)); ?>
<?php echo $this->Html->script('jquery.bxslider.js?20141218', array('fullBase' => true)); ?>
<?php echo $this->Html->script('jquery.lazyload.js', array('fullBase' => true)); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script type="text/javascript">
$1102 = jQuery.noConflict(true);
</script>
<div class="openslideshare_body">
<div id="slide_div_box">
<?php echo $this->element("slide", array('slide' => $slide["Slide"])); ?>
</div>
</div>

<?php echo $this->Html->script('jquery-1.10.2.min.js', array('fullBase' => true)); ?>
<?php echo $this->Html->script('bootstrap.min.js', array('fullBase' => true)); ?>
<?php echo $this->Html->css('bootstrap.min.css', array('fullBase' => true)); ?>
<?php echo $this->Html->css('jquery.bxslider.css', array('fullBase' => true)); ?>
<?php echo $this->Html->css('openslideshare.css', array('fullBase' => true)); ?>
<?php echo $this->Html->script('jquery.bxslider.js?20141218', array('fullBase' => true)); ?>
<?php echo $this->Html->script('jquery.lazyload.js', array('fullBase' => true)); ?>
<script type="text/javascript">
$1102= jQuery.noConflict(true);
</script>
<style>
.openslideshare_body div#slide_div_box {
    max-width: 768px !important;
    padding-top: 0px;
}
</style>
<div class="openslideshare_body">
<div id="slide_div_box" style="border:0px;">
<?php echo $this->element("slide", array('slide' => $slide["Slide"])); ?>
</div>
</div>

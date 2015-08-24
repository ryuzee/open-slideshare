
<div class="row" id="popular">
    <div class="col-md-12">
        <h3><?php echo __('Popular Slides'); ?>&nbsp;&nbsp;<small><a href="/slides/popular"><?php echo __('More...'); ?></a></small></h3>
        <?php echo $this->element('slide_thumbnail', array('slides' => $slides_popular, 'column_class' => 'col-md-3 col-sm-6', 'column_class_primary' => 'col-md-3')); ?>
    </div>
</div>

<div class="row" id="latest">
    <div class="col-md-12">
        <h3><?php echo __('Latest Slides'); ?>&nbsp;&nbsp;<small><a href="/slides/latest"><?php echo __('More...'); ?></h3>
        <?php echo $this->element('slide_thumbnail', array('slides' => $slides_latest, 'column_class' => 'col-md-3 col-sm-6', 'column_class_primary' => 'col-md-3')); ?>
    </div>
</div>

<br />

<script type="text/javascript">
    $1102(window).load(function () {
        $1102('.col-md-3').equalHeights();
    });
</script>

<div class="row">
    <div class="col-md-9">
        <?php echo $this->element('slide_mine', array('slides' => $slides, 'title' => __('My Slides'), 'column_class' => 'col-md-4 col-sm-6', 'column_class_primary' => 'col-md-4')); ?>
    </div>

    <div class="col-md-3">
    <?php echo $this->element('right_side_bar_common', array('user' => $user)); ?>
    </div>
</div>



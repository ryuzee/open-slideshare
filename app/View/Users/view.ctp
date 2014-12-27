<div class="row">
    <div class="col-md-9">
    <?php echo $this->element('slide_mine', array('slides' => $slides, 'title' => $title)); ?>
    </div>

    <div class="col-md-3">
    <?php echo $this->element('right_side_bar_common', array('user' => $user)); ?>
    </div>
</div>



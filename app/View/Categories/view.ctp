<div class="row">
    <div class="col-md-12">
    <?php echo $this->element('slide_mine', array('slides' => $slides, 'title' => $title, 'column_class' => 'col-md-3 col-sm-6', 'column_class_primary' => 'col-md-3')); ?>
    </div>
</div>

<link rel="alternate" type="application/rss+xml" title="RSS 2.0 (<?php echo $title; ?>)" href="/categories/view/<?php echo $id; ?>.rss" />


<?php $slide_id = $slide["Slide"]["id"]; ?>
<div class="row">
<div class="col-md-8" id="slide_div_box">

    <?php if($this->request->query('vertical') == 1): ?>
    <?php echo $this->element("slide_vertical", array('slide' => $slide["Slide"])); ?>
    <?php else: ?>
    <?php echo $this->element("slide", array('slide' => $slide["Slide"], 'start_position' => $start_position)); ?>
    <?php endif; ?>

    <div class="row slide_detail_row">
        <div class="col-md-9">
            <span class="h3"><?php echo h($slide['Slide']['name']); ?></span>
            <div class="bottom_margin">
            By <?php echo $this->Html->link($slide['User']['display_name'], array('controller' => 'users', 'action' => 'view', $slide['User']['id'])); ?>&nbsp;Published: <?php echo $this->Time->format($slide['Slide']['created'], "%Y/%m/%d"); ?> in <a href="/categories/view/<?php echo $slide["Category"]["id"]; ?>"><?php echo h($slide['Category']['name']); ?></a>
            </div>
        </div>
        <div class="col-md-3" style="text-align:right">
            <strong><?php echo $slide["Slide"]["total_view"]; ?></strong> <small>total views</small><br />
            <strong><?php echo $slide["Slide"]["embedded_view"]; ?></strong> <small>embedded views</small><br />
            <?php if ($slide['Slide']['downloadable']): ?>
            <strong><?php echo $slide["Slide"]["download_count"]; ?></strong> <small>downloads</small>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <?php echo $this->element("share_buttons"); ?>
    </div>

    <div class="slide_description">
    <p><?php echo nl2br(h($slide['Slide']['description'])); ?></p>
    </div>

    <div class="h5">
    <?php echo __('Tags'); ?>:
    <?php
        $disp_tags = mb_split(',' , $slide['Slide']['tags']);
        foreach ($disp_tags as $t):
            $t = trim($t);
    ?>
    <a href="/slides/search/tags:<?php echo h($t); ?>"><?php echo h($t); ?></a>&nbsp;
    <?php endforeach; ?>
    </div>

    <a name="comment"></a>
    <h3 class="h4"><?php echo __('Comments'); ?></h3>
    <?php if (!empty($slide['Comment'])): ?>
    <?php foreach ($slide['Comment'] as $comment): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><?php echo h($comment['User']['display_name']); ?></strong>&nbsp;<?php echo $comment['modified']; ?>
            <?php echo $this->Form->postLink(__('Delete'), "/comments/delete/". $comment['id'] ."?return_url=/slides/view/" .$slide_id , array('escape' => false), __('Are you sure you want to delete # %s?', $comment['id'])); ?>
        </div>
        <div class="panel-body">
            <?php echo $this->Common->display($comment['content']); ?></td>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <div class="comments form">
        <?php echo $this->Form->create('Comment', array(
        'action' => 'add',
        'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control'
        ),
        'class' => 'well'
        )); ?>
        <fieldset>
            <legend class="h4"><?php echo __('Add Comment'); ?></legend>
            <?php
         echo $this->Form->hidden('user_id', array('value' => $user_id));
         echo $this->Form->hidden('slide_id', array('value' => $slide_id));
         echo $this->Form->hidden('return_url', array('value' => '/slides/view/' . $slide_id));
         echo $this->Form->input('content', array('label' => __('Content'), 'class' => 'form-control mention'));
         ?>
        </fieldset>
        <?php echo $this->Form->end(__('Submit')); ?>
    </div>

    <?php
        $show_tran = false;
        if (is_array($transcripts)) {
            foreach ($transcripts as $tran) {
                if ($tran !== "") {
                    $show_tran = true;
                    break;
                }
            }
        }
    ?>
    <?php if ($show_tran): ?>
    <?php $page_count = 0; ?>
    <h3 class="h4"><?php echo __('Transcripts'); ?></h3>
    <div class="slide_description">
        <?php foreach($transcripts as $tran): ?>
        <?php $page_count++; ?>
        <?php if ($tran !== ""): ?>
        <div class="h6"><a href="#page_top" onclick="javascript:myslider.goToSlide(<?php echo $page_count -1; ?>);"><?php echo $page_count; ?>.</a> <?php echo h($tran); ?></div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div>&nbsp;</div>
    <?php endif; ?>
</div>

<div class="col-md-4" id="rightside">
    <?php echo $this->element("right_side_bar_common"); ?>

    <?php if(isset($user_id) && $user_id == $slide["Slide"]["user_id"]): ?>
    <div class="panel panel-default h5">
        <!-- Default panel contents -->
        <div class="panel-heading"><?php echo __('Actions'); ?></div>
        <!-- List group -->
        <ul class="list-group">
            <li class="list-group-item"><?php echo $this->Html->link(__('Edit Slide'), array('action' => 'edit', $slide_id)); ?> </li>
            <li class="list-group-item"><?php echo $this->Form->postLink(__('Delete Slide'), array('action' => 'delete', $slide_id), array(), __('Are you sure you want to delete # %s?', $slide_id)); ?> </li>
        </ul>
    </div>
    <?php endif; ?>

    <ul class="list-group h5">
        <?php if($this->request->query('vertical') == 1): ?>
        <li class="list-group-item"><?php echo $this->Html->link(__('Show Slide with Normal Mode'), array('action' => 'view', $slide_id)); ?> </li>
        <?php else: ?>
        <li class="list-group-item"><?php echo $this->Html->link(__('Show Slide Vertically'), array('action' => 'view', $slide_id, '?' => array('vertical' => 1))); ?> </li>
        <?php endif; ?>
        <?php if($slide["Slide"]["downloadable"]): ?>
        <li class="list-group-item"><?php echo $this->Html->link(__('Download Slide'), array('action' => 'download', $slide_id)); ?> </li>
        <?php endif; ?>
        <li class="list-group-item"><?php echo $this->Html->link(__('Show Embedded Code'), "javascript:void(0);return false;", array("id" => "show_embedded")); ?> </li>
    </ul>

    <?php if($other_slides_in_category): ?>
        <!-- Default panel contents -->
        <h3 class="h4"><?php echo __('Related Slides'); ?></h3>
        <div class="row2 related_slide_row">
        <?php foreach($other_slides_in_category as $other_slide): ?>
        <div class="col-md-12 col-sm-6" style="padding:8px;">
            <a href="/slides/view/<?php echo $other_slide["Slide"]["id"]; ?>">
                <img width="120px" src="<?php echo $this->Common->thumbnail_url($other_slide["Slide"]["key"]); ?>" class="related_slide_thumbnail" />
            </a>
            <span class="h6"><?php echo h($other_slide['Slide']['name']); ?></span><br />
            <span class="h6"><?php echo strftime("%Y/%m/%d", strtotime($other_slide['Slide']['created'])); ?> by <a href="/users/view/<?php echo $other_slide["User"]["id"]; ?>"><?php echo h($other_slide["User"]["display_name"]); ?></a></span>
        </div>
        <br clear="all" />
        <?php endforeach; ?>
        </div>
        <br clear="all" />
    <?php endif; ?>
</div><!-- end of row -->

<script type="text/javascript">
$1102(document).ready(function(){
    $1102("#show_embedded").click(function(){
        var x = $1102("<div></div>").dialog({autoOpen:false});
        x.html("<p><?php echo h('<script src="' . Router::url($this->Html->url(array("controller" => "slides", "action" => "embedded", $slide_id)), true) . '"></script>'); ?></p>");
        x.dialog("option", {
            title: "<?php echo __('Embedded Code'); ?>",
            width:400,
            height:200,
            buttons: {
                "OK": function() { $1102(this).dialog("close"); }
            }
        });
        x.dialog("open");
    });
});
</script>

</div>

</div><!-- end of row -->

<script type="text/javascript">
$1102(document).ready(function(){
   // fix sidebar position
   $1102("#rightside").stick_in_parent();
});
</script>



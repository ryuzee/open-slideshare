<div class="row">
<div class="col-md-9">
    <?php echo $this->element("slide", array('slide' => $slide["Slide"])); ?>

    <div class="row" style="padding-top:8px;">
    <div class="col-md-9">
    <span class="h3"><?php echo h($slide['Slide']['name']); ?></span></div>
    <div class="col-md-3" style="text-align:right">
        <strong><?php echo $slide["Slide"]["page_view"]; ?></strong> views
    </div>
    </div>

    <div>
    By <?php echo $this->Html->link($slide['User']['display_name'], array('controller' => 'users', 'action' => 'view', $slide['User']['id'])); ?>&nbsp;
    Published: <?php echo h(strftime("%Y/%m/%d",strtotime($slide['Slide']['created']))); ?> in <a href="/categories/view/<?php echo $slide["Category"]["id"]; ?>"><?php echo h($slide['Category']['name']); ?></a>
    </div>
    <div style="border:1px solid #ccc; background-color:#eee; padding:0.8em; margin-top:0.8em;">
    <p><?php echo h($slide['Slide']['description']); ?></p>
    </div>

    <a name="comment"></a>
    <h3><?php echo __('Comments'); ?></h3>
    <?php if (!empty($slide['Comment'])): ?>
    <?php foreach ($slide['Comment'] as $comment): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><?php echo h($comment['User']['display_name']); ?></strong>&nbsp;<?php echo $comment['modified']; ?>
            <?php echo $this->Form->postLink(__('Delete'), "/comments/delete/". $comment['id'] ."?return_url=/slides/view/" .$slide["Slide"]["id"] , array('escape' => false), __('Are you sure you want to delete # %s?', $comment['id'])); ?>
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
            <legend><?php echo __('Add Comment'); ?></legend>
            <?php
         echo $this->Form->hidden('user_id', array('value' => $user_id));
         echo $this->Form->hidden('slide_id', array('value' => $slide["Slide"]["id"]));
         echo $this->Form->hidden('return_url', array('value' => '/slides/view/' . $slide["Slide"]["id"]));
         echo $this->Form->input('content', array('class' => 'form-control mention'));
         ?>
        </fieldset>
        <?php echo $this->Form->end(__('Submit')); ?>
    </div>
</div>


<div class="col-md-3">
    <?php echo $this->element("right_side_bar_common"); ?>

    <?php if(isset($user_id) && $user_id == $slide["Slide"]["user_id"]): ?>
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading"><?php echo __('Actions'); ?></div>
        <!-- List group -->
        <ul class="list-group">
            <li class="list-group-item"><?php echo $this->Html->link(__('Edit Slide'), array('action' => 'edit', $slide['Slide']['id'])); ?> </li>
            <li class="list-group-item"><?php echo $this->Form->postLink(__('Delete Slide'), array('action' => 'delete', $slide['Slide']['id']), array(), __('Are you sure you want to delete # %s?', $slide['Slide']['id'])); ?> </li>
        </ul>
    </div>
    <?php endif; ?>

    <?php if($other_slides_in_category): ?>
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading"><?php echo __('Related Slides'); ?></div>
        <?php foreach($other_slides_in_category as $slide): ?>
        <div class="panel-body">
            <a href="/slides/view/<?php echo $slide["Slide"]["id"]; ?>">
            <img width="192px" src="<?php echo $this->Common->endpoint_s3(Configure::read('image_bucket_name')); ?>/<?php echo $slide["Slide"]["key"]; ?>/thumbnail.jpg" style="border:1px solid #999; margin-bottom:4px;" />
            </a><br />
            <span class="h4"><?php echo h($slide['Slide']['name']); ?></span><br />
            <?php echo strftime("%Y/%m/%d", strtotime($slide['Slide']['created'])); ?> by <a href="/users/view/<?php echo $slide["User"]["id"]; ?>"><?php echo $slide["User"]["display_name"]; ?></a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

</div><!-- end of row -->

<script type="text/javascript">
$(function () {
  $('textarea.mention').mentionsInput({
    showAvatars:false,
    onDataRequest:function (mode, query, callback) {
      var myquery = 'query='+query;
      $.getJSON('/users/json_list', myquery, function(responseData) {
        responseData = _.filter(responseData, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
        callback.call(this, responseData);
      });
    }
  });

  $('form#CommentAddForm input').click(function() {
    $('textarea.mention').mentionsInput('val', function(text) {
      $("#CommentContent").val(text);
    });
  });
});
</script>

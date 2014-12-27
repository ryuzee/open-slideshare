<!-- bootstrapのクラス使うとうまく折り返されないのでやり方検討 -->
<h3><?php echo $title; ?></h3>

<div class="row2" style="max-width:1024px; margin:0 auto;">
<?php foreach ($slides as $slide): ?>
    <div class="col" style="padding-bottom:1em; margin-left:8px; margin-right:8px; width:222px; display:inline-block; vertical-align:top; word-wrap: break-word !important;">
        <a href="/slides/view/<?php echo $slide["Slide"]["id"]; ?>">
        <?php if($slide["Slide"]["convert_status"] < 0): ?>
        <img width="216px" src="/img/failed_to_convert_small.jpg" style="border:1px solid #999; margin-bottom:4px;" />
        <?php elseif($slide["Slide"]["convert_status"] < SUCCESS_CONVERT_COMPLETED): ?>
        <img width="216px" src="/img/converting_small.jpg" style="border:1px solid #999; margin-bottom:4px;" />
        <?php else: ?>
        <img width="216px" src="<?php echo $this->Common->endpoint_s3(Configure::read('image_bucket_name')); ?>/<?php echo $slide["Slide"]["key"]; ?>/thumbnail.jpg" style="border:1px solid #999; margin-bottom:4px;" />
        <?php endif; ?>
        </a><br />
        <span class="h4"><?php echo h($slide['Slide']['name']); ?></span><br />
        <?php echo strftime("%Y/%m/%d", strtotime($slide['Slide']['created'])); ?> by <a href="/users/view/<?php echo $slide["User"]["id"]; ?>"><?php echo h($slide["User"]["display_name"]); ?></a>
    </div>
<?php endforeach; ?>
</div>

<div>
    <!-- Paging -->
    <?php echo $this->Paginator->pagination(array(
    'ul' => 'pagination pagination-lg'
    )); ?>
</div>



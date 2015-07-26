
<div class="row">
    <div class="col-md-12">
        <h3><?php echo __('Slides'); ?></h3>
        <script type="text/javascript">
        $(document).ready(function () {
            $('.col-md-3').equalHeight();
        });
        </script>

        <div class="row" style="max-width:1024px; margin:0 auto;">
            <?php foreach ($slides as $slide): ?>
                <?php if($slide["Slide"]["convert_status"] < 0): ?>
                <?php $image_url = "/img/failed_to_convert_small.jpg"; ?>
                <?php elseif($slide["Slide"]["convert_status"] < SUCCESS_CONVERT_COMPLETED): ?>
                <?php $image_url = "/img/converting_small.jpg"; ?>
                <?php else: ?>
                <?php $image_url =  $this->Common->endpoint_s3(Configure::read('image_bucket_name')) . "/" . $slide["Slide"]["key"] . "/thumbnail.jpg"; ?>
                <?php endif; ?>

            <div class="col-md-3 col-sm-6" style="margin-bottom:4px;">
                <a href="/slides/view/<?php echo $slide["Slide"]["id"]; ?>">
                <img src="<?php echo $image_url; ?>" class="img-responsive slide_thumbnail" style="border:1px solid #999; margin-bottom:4px;" />
                </a>
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
    </div>
</div>

        <h3><?php echo $title; ?></h3>

        <div class="row" style="max-width:1024px; margin:0 auto;">
            <?php foreach ($slides as $slide): ?>
                <?php if($slide["Slide"]["convert_status"] < 0): ?>
                <?php $image_url = "/img/failed_to_convert_small.jpg"; ?>
                <?php elseif($slide["Slide"]["convert_status"] < SUCCESS_CONVERT_COMPLETED): ?>
                <?php $image_url = "/img/converting_small.jpg"; ?>
                <?php else: ?>
                <?php $image_url =  $this->Common->thumbnail_url($slide["Slide"]["key"]); ?>
                <?php endif; ?>

            <div class="<?php echo $column_class; ?>" style="margin-bottom:4px;">
                <a href="/slides/view/<?php echo $slide["Slide"]["id"]; ?>">
                <img src="<?php echo $image_url; ?>" class="img-responsive slide_thumbnail" style="border:1px solid #999; margin-bottom:4px;" />
                </a>
                <span class="h4"><?php echo h($slide['Slide']['name']); ?></span><br />
                <span class="small"><?php echo strftime("%Y/%m/%d", strtotime($slide['Slide']['created'])); ?> by <a href="/users/view/<?php echo $slide["User"]["id"]; ?>"><?php echo h($slide["User"]["display_name"]); ?></a></span>
            </div>
            <?php endforeach; ?>
        </div>

        <div>
            <!-- Paging -->
            <?php echo $this->Paginator->pagination(array(
            'ul' => 'pagination pagination-lg'
            )); ?>
        </div>

        <script type="text/javascript">
        $(window).load(function () {
            $('.<?php echo $column_class_primary; ?>').equalHeights();
        });
        </script>

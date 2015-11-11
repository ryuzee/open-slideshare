<ul class="bxslider_<?php echo $slide["key"]; ?>" data-count="<?php echo count($file_list); ?>" style="display:none">
<?php if (count($file_list) > 0): ?>
    <?php $count = 0; ?>
    <?php foreach ($file_list as $file): ?>
    <?php $u = $this->Common->slide_page_url($file); ?>
    <?php if($count >= 2): ?>
    <li><img style="border:0px !important" class="lazy image-<?php echo $count; ?>" src="<?php echo Router::url($this->Html->url("/img/spacer.gif"), true); ?>" data-src="<?php echo $u; ?>" /></li>
    <?php else: ?>
    <li><img style="border:0px !important" src="<?php echo $u; ?>" /></li>
    <?php endif; ?>
    <?php $count++; ?>
    <?php endforeach; ?>
<?php elseif($slide["convert_status"] < 0): ?>
    <li><img style="border:0px !important" class="image-0" src="<?php echo Router::url($this->Html->url("/img/failed_to_convert.jpg"), true); ?>" /></li>
<?php else: ?>
    <li><img style="border:0px !important" class="image-0" src="<?php echo Router::url($this->Html->url("/img/converting.jpg"), true); ?>" /></li>
<?php endif; ?>
</ul>

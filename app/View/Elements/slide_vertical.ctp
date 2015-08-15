<?php if (count($file_list) > 0): ?>
<?php $count = 0; ?>
<?php foreach ($file_list as $file): ?>
<?php $count++; ?>
<?php $u = $this->Common->slide_page_url($file); ?>
<div class="vertical_slide_box">
<?php if ($count <= 2): ?>
        <img class="lazy img-responsive" src="<?php echo $u; ?>" />
<?php else: ?>
        <img class="lazy img-responsive" src="/img/spacer64.png" data-original="<?php echo $u; ?>" />
<?php endif; ?>
</div>
<div class="icon_right">
<a href="#top"><span class="glyphicon glyphicon-arrow-up"></span></a>
</div>
<?php endforeach; ?>
<?php endif; ?>

<script type="text/javascript">
$1102(function() {
    $1102(".openslideshare_body img.lazy").lazyload({
        threshold : 200,
        effect: "fadeIn"
    });
});
</script>

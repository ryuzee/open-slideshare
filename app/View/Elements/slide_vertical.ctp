
<?php
$url = "https://" . Configure::read('image_bucket_name') . ".s3-". Configure::read('region') . ".amazonaws.com/". $slide["key"] . "/list.json";

$context = stream_context_create(
    array(
        'http' => array(
            'ignore_errors' => true)
    )
);
$contents = file_get_contents($url, false, $context);
if (strpos($http_response_header[0], '200'))
{
    $file_list = json_decode($contents);
}
else
{
    $file_list = array();
}
?>

<?php if (count($file_list) > 0): ?>
<?php $count = 0; ?>
<?php foreach ($file_list as $file): ?>
<?php $count++; ?>
<?php $u = "https://" . Configure::read('image_bucket_name') . ".s3-". Configure::read('region') . ".amazonaws.com/". $file; ?>
<div style="border:1px solid #000; margin-bottom:0px;">
<?php if ($count <= 2): ?>
        <img class="lazy img-responsive" src="<?php echo $u; ?>" />
<?php else: ?>
        <img class="lazy img-responsive" src="/img/spacer64.png" data-original="<?php echo $u; ?>" />
<?php endif; ?>
</div>
<div style="text-align:right">
<a href="#top"><span class="glyphicon glyphicon-arrow-up" style="color:#bbb"></span></a>
</div>
<?php endforeach; ?>
<?php endif; ?>


<script type="text/javascript">
$(function() {
    $("img.lazy").lazyload({
        threshold : 200,
        effect: "fadeIn"
    });
});
</script>

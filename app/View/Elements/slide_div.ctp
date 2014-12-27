<?php 
$url = "https://" . Configure::read('image_bucket_name') . ".s3-". Configure::read('region') . ".amazonaws.com/". $slide["key"] . "/list.json";

$context = stream_context_create(array(
    'http' => array('ignore_errors' => true)
));
$contents = file_get_contents($url, false, $context);
if (strpos($http_response_header[0], '200')) {
    $file_list = json_decode($contents);
} else {
    $file_list = array();
}
?>

<ul class="bxslider_<?php echo $slide["key"]; ?>" data-count="<?php echo count($file_list); ?>">
<?php if (count($file_list) > 0): ?>
    <?php $count = 0; ?>
    <?php foreach ($file_list as $file): ?>
    <?php $u = "https://" . Configure::read('image_bucket_name') . ".s3-". Configure::read('region') . ".amazonaws.com/". $file; ?>
    <?php if($count >= 2): ?>
    <li><img class="lazy image-<?php echo $count; ?>" src="/img/spacer.gif" data-src="<?php echo $u; ?>" /></li>
    <?php else: ?>
    <li><img src="<?php echo $u; ?>" /></li>
    <?php endif; ?>
    <?php $count++; ?>
    <?php endforeach; ?>
<?php elseif($slide["convert_status"] < 0): ?>
    <li><img class="lazy image-0" src="/img/failed_to_convert.jpg" /></li>
<?php else: ?>
    <li><img class="lazy image-0" src="/img/converting.jpg" /></li>
<?php endif; ?>
</ul>

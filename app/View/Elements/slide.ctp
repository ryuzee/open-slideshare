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

<div class="slider" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;">

    <ul class="bxslider_<?php echo $slide["key"]; ?>" data-count="<?php echo count($file_list); ?>" style="display:none">
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
</div>

<div class="slide_control" style="display:none">
    <span id="prev" class="slide_control_link" /></span>&nbsp;&nbsp;<span id="pager" class="small"></span>&nbsp;&nbsp;<span id="next" class="slide_control_link"></span>
</div>

<script type="text/javascript">
$(function() {
    $("img.lazy").lazyload({
        threshold : 200,
        effect: "fadeIn"
    });
    // $("a.bx-prev, a.bx-next").bind("click", function() {
        // setTimeout(function(e) { $(window).trigger("scroll"); }, 10); //handle the lazy load
        // e.preventDefault();
    // });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".bxslider_<?php echo $slide["key"]; ?>").show();
    $(".slide_control").show();

    function bxslider_init() {
        var slider_config = {
            mode: 'horizontal',
            responsive:true,
            pager:true,
            pagerType:'short',
            prevText: '◀',
            nextText: '▶',
            prevSelector: "#prev",
            nextSelector: "#next",
            pagerSelector: "#pager",
            adaptiveHeight: false,
            infiniteLoop: false,
            onSlideBefore: function($slideElement, oldIndex, newIndex){
                var $lazy_next2 = $("ul.bxslider_<?php echo $slide["key"]; ?> img.image-" +  (newIndex + 1));
                var $load_next2 = $lazy_next2.attr("data-src");
                $lazy_next2.attr("src",$load_next2).removeClass("lazy");

                var $lazy_next = $("ul.bxslider_<?php echo $slide["key"]; ?> img.image-" +  (newIndex));
                var $load_next = $lazy_next.attr("data-src");
                $lazy_next.attr("src",$load_next).removeClass("lazy");
                $lazy_next.each(function(){
                    // @TODO: 画像のローディングをここに入れる
                    // while (!this.complete) {
                    // ;
                    // }
                });
            }
        }
        $('.bxslider_<?php echo $slide["key"]; ?>').bxSlider(slider_config);
    }
    bxslider_init();
    var timer = setInterval( updateDiv, 10 * 100);

    // スライドのページ数が0の場合は定期的に確認する
    function updateDiv() {
        var messageDiv = $('.slider');
        if ($('div.slider ul').attr("data-count") > 0) {
            return;
        }
        $.ajax({
            type: 'GET',
            async: false,
            url: "/slides/update_view/<?php echo $slide["id"]; ?>",
            cache: false,
            success: function(result) {
                $('div.slide_control span#prev').empty();
                $('div.slide_control span#next').empty();
                $('div.slide_control span#pager').empty();
                messageDiv.empty();
                messageDiv.append(result);
                bxslider_init();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                // messageDiv.empty();
            }
        });
    }

});
</script>

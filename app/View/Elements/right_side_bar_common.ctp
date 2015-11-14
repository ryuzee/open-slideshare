<?php if (isset($custom_contents["right_top"])): ?>
<div style="margin-top:20px">
<?php echo $custom_contents["right_top"]; ?>
</div>
<?php endif; ?>

<h3 class="h4"><?php echo __('Speaker Details'); ?></h3>
<div class="row2 bordered_box h5">
<h4 class="h5"><?php echo h($user["User"]["display_name"]); ?></h4>
<?php echo h($user["User"]["biography"]); ?>
</div>
<br clear="all" />


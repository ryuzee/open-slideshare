<h3><?php echo __('Admin Dashboard'); ?></h3>

<div class="panel panel-default">
    <!-- Table -->
    <table class="table table-striped table-bordered small">
        <thead>
            <tr>
                <th class="col-md-1"><?php echo __('# of Users'); ?></th>
                <th class="col-md-1"><?php echo __('# of Slides'); ?></th>
                <th class="col-md-1"><?php echo __('# of Conversion Failed'); ?></th>
                <th class="col-md-1"><?php echo __('# of Comments'); ?></th>
                <th class="col-md-1"><?php echo __('Page View'); ?></th>
                <th class="col-md-1"><?php echo __('Embedded View'); ?></th>
                <th class="col-md-1"><?php echo __('Download Count'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="/admin/managements/user_list"><?php echo $user_count; ?></a>&nbsp;</td>
                <td><a href="/admin/managements/slide_list"><?php echo $slide_count; ?></a>&nbsp;</td>
                <td><?php echo $conversion_failed_count; ?>&nbsp;</td>
                <td><?php echo $comment_count; ?>&nbsp;</td>
                <td><?php echo $page_view; ?>&nbsp;</td>
                <td><?php echo $embedded_view; ?>&nbsp;</td>
                <td><?php echo $download_count; ?>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>

<h2><?php echo __('Settings'); ?></h2>
<ul>
    <li><?php echo $this->Html->link(__('Site Settings'), "/admin/managements/site_setting"); ?></li>
</ul>

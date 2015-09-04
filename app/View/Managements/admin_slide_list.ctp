<h3><?php echo __('Slide List'); ?>&nbsp;&nbsp;<small><a href="/admin/managements/dashboard"><?php echo __('Back to Dashboard'); ?></a></small></h3>

<div class="panel panel-default">
    <!-- Table -->
    <table class="table table-striped table-bordered small">
        <thead>
            <tr>
                <th class="col-md-1"><?php echo $this->Paginator->sort('id', __('ID')); ?></th>
                <th class="col-md-2"><?php echo $this->Paginator->sort('name', __('Slide Name')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('user_id', __('User ID')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('created', __('Created')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('modified', __('Modified')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('convert_status', __('Convert Status')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('page_view', __('Page View')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('embedded_view', __('Embedded View')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('download_count', __('Download Count')); ?></th>
                <th class="col-md-1"><?php echo __('Number of Comments'); ?></th>
                <th class="col-md-1"><?php echo __('Command'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($slides as $slide): ?>
            <tr>
                <td><a href="/slides/view/<?php echo $slide['Slide']['id']; ?>"><?php echo $slide['Slide']['id']; ?></a></td>
                <td><a href="/slides/view/<?php echo $slide['Slide']['id']; ?>"><?php echo h($slide['Slide']['name']); ?></a>&nbsp;</td>
                <td><a href="/users/view/<?php echo $slide['Slide']['user_id']; ?>"><?php echo $slide['Slide']['user_id']; ?></a>&nbsp;</td>
                <td><?php echo $this->Time->format($slide['Slide']['created'], '%Y/%m/%d'); ?></td>
                <td><?php echo $this->Time->format($slide['Slide']['modified'], '%Y/%m/%d'); ?></td>
                <td><?php echo $slide['Slide']['convert_status']; ?></td>
                <td><?php echo $slide['Slide']['page_view']; ?></td>
                <td><?php echo $slide['Slide']['embedded_view']; ?></td>
                <td><?php echo $slide['Slide']['download_count']; ?></td>
                <td><?php echo count($slide['Comment']); ?></td>
                <td><a href="<?php echo $this->Common->transcript_url($slide['Slide']['key']); ?>" target="_blank"><i class="fa fa-file-text"></i></a>&nbsp;<a href="<?php echo $this->Common->json_url($slide['Slide']['key']); ?>" target="_blank"><i class="fa fa-jsfiddle"></i></a>&nbsp;<a href="/admin/managements/download/<?php echo $slide['Slide']['id']; ?>"><i class="fa fa-cloud-download"></i></a>&nbsp;<a href="/admin/managements/slide_edit/<?php echo $slide['Slide']['id']; ?>"><i class="fa fa-pencil"></i></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div>
    <!-- Paging -->
    <?php echo $this->Paginator->pagination(array(
    'ul' => 'pagination pagination-lg'
    )); ?>
</div>

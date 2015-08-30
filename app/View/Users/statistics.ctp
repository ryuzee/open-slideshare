<h3><?php echo __('My Statistics'); ?></h3>

<div class="panel panel-default">
    <!-- Table -->
    <table class="table table-striped table-bordered small">
        <thead>
            <tr>
                <th class="col-md-5"><?php echo __('Slide Name'); ?></th>
                <th class="col-md-1"><?php echo __('Created'); ?></th>
                <th class="col-md-1"><?php echo __('Modified'); ?></th>
                <th class="col-md-1"><?php echo __('Convert Status'); ?></th>
                <th class="col-md-1"><?php echo __('Page View'); ?></th>
                <th class="col-md-1"><?php echo __('Embedded View'); ?></th>
                <th class="col-md-1"><?php echo __('Download Count'); ?></th>
                <th class="col-md-1"><?php echo __('Number of Comments'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($slides as $slide): ?>
            <tr>
                <td><a href="/slides/view/<?php echo $slide['Slide']['id']; ?>"><?php echo h($slide['Slide']['name']); ?></a>&nbsp;</td>
                <td><?php echo $this->Time->format($slide['Slide']['created'], '%Y/%m/%d'); ?></td>
                <td><?php echo $this->Time->format($slide['Slide']['modified'], '%Y/%m/%d'); ?></td>
                <td><?php echo h($slide['Slide']['convert_status']); ?></td>
                <td><?php echo h($slide['Slide']['page_view']); ?></td>
                <td><?php echo h($slide['Slide']['embedded_view']); ?></td>
                <td><?php echo h($slide['Slide']['download_count']); ?></td>
                <td><?php echo count($slide['Comment']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

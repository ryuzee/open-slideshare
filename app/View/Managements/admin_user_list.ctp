<h3><?php echo __('User List'); ?>&nbsp;&nbsp;<small><a href="/admin/managements/dashboard"><?php echo __('Back to Dashboard'); ?></a></small></h3>

<div class="panel panel-default">
    <!-- Table -->
    <table class="table table-striped table-bordered small">
        <thead>
            <tr>
                <th class="col-md-1"><?php echo $this->Paginator->sort('id', __('id')); ?></th>
                <th class="col-md-2"><?php echo $this->Paginator->sort('username', __('Username')); ?></th>
                <th class="col-md-2"><?php echo $this->Paginator->sort('display_name', __('Display Name')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('admin', __('Admin')); ?></th>
                <th class="col-md-1"><?php echo $this->Paginator->sort('slide_count', __('Slide Count')); ?></th>
                <th class="col-md-2"><?php echo $this->Paginator->sort('created', __('Created')); ?></th>
                <th class="col-md-2"><?php echo $this->Paginator->sort('modified', __('Modified')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><a href="/users/view/<?php echo $user['User']['id']; ?>"><?php echo $user['User']['id'] ?></a>&nbsp;</td>
                <td><?php echo h($user['User']['username']); ?></td>
                <td><?php echo h($user['User']['display_name']); ?></td>
                <td><?php echo $user['User']['admin'] ?></td>
                <td><?php echo $user['User']['slides_count'] ?></td>
                <td><?php echo $user['User']['created'] ?></td>
                <td><?php echo $user['User']['modified'] ?></td>
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

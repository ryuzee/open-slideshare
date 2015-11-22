<h3><?php echo __('Custom Contents Settings'); ?></h3>

<div class="panel panel-default">
    <!-- Table -->
    <div class="panel-body">
        <?php echo $this->Form->create('CustomContent', array('url' => array('controller' => 'managements', 'action'=>'custom_contents_setting'), 'class' => false)); ?>
    <table class="table table-striped table-bordered small">
        <thead>
            <tr>
                <th class="col-md-3"><?php echo __('Setting'); ?></th>
                <th class="col-md-9"><?php echo __('Value'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $key => $content): ?>
            <tr>
                <td>
                    <?php echo $this->Form->hidden("CustomContent.{$key}.name", array('default' => $content["CustomContent"]['name'])); ?>
                    <?php echo $this->Form->label($content["CustomContent"]['name']); ?>
                </td>
                <td>
                    <?php echo $this->Form->input("CustomContent.{$key}.value", array('default' => $content["CustomContent"]['value'], 'label' => false, 'class' => 'form-control')); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->Form->submit(__('Save'), array('id' => 'save_button', 'class' => "btn btn-small btn-primary btn-block")); ?>
    <?php echo $this->Form->end(); ?>
    </div>

</div>



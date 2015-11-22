<h3><?php echo __('Site Settings'); ?></h3>

<div class="panel panel-default">
    <!-- Table -->
    <div class="panel-body">
        <?php echo $this->Form->create('Config', array('url' => array('controller' => 'managements', 'action'=>'site_setting'), 'class' => false)); ?>
    <table class="table table-striped table-bordered small">
        <thead>
            <tr>
                <th class="col-md-3"><?php echo __('Setting'); ?></th>
                <th class="col-md-9"><?php echo __('Value'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $key => $config): ?>
            <tr>
                <td>
                    <?php echo $this->Form->hidden("Config.{$key}.name", array('default' => $config["Config"]['name'])); ?>
                    <?php echo $this->Form->label($config["Config"]['name']); ?>
                </td>
                <td>
                    <?php echo $this->Form->input("Config.{$key}.value", array('default' => $config["Config"]['value'], 'label' => false, 'class' => 'form-control')); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->Form->submit(__('Save'), array('id' => 'save_button', 'class' => "btn btn-small btn-primary btn-block")); ?>
    <?php echo $this->Form->end(); ?>
    </div>

</div>



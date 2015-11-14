<h3><?php echo __('Edit User'); ?></h3>

<div class="row">

<div class="col-md-8">
    <?php echo $this->Form->create('User', array(
    'inputDefaults' => array(
    'div' => 'form-group',
    'wrapInput' => false,
    'class' => 'form-control'
    ),
    'class' => 'well'
    )); ?>
    <div class="form-group">
        <fieldset>
            <legend><?php echo __('Edit User'); ?></legend>
            <?php
         echo $this->Form->input('id');
         echo $this->Form->input('username', array('class' => 'form-control', 'disabled' => true));
         echo $this->Form->input('password', array('class' => 'form-control', 'value' => '', 'required' => false));
         echo $this->Form->input('display_name', array('class' => 'form-control'));
         echo $this->Form->input('biography', array('class' => 'form-control'));
         ?>
        </fieldset>
    </div>
    <?php echo $this->Form->submit(__('Update'), array('class' => "btn btn-primary")); ?>
    </form>
    <p></p>

    <div class="panel panel-danger">
        <!-- Default panel contents -->
        <div class="panel-heading"><?php echo __('Delete User');?> (<?php echo __('Danger'); ?>)</div>
        <div class="panel-body">
        <form method="get" action="/users/delete">
        <button type="submit" class="btn btn-danger"><?php echo __('Delete User'); ?></button>
        </form>
        </div>
    </div>
</div>

<div class="col-md-4">
    <?php echo $this->element("right_side_bar_common"); ?>
</div>

</div>

<h3><?php echo __('Add User'); ?></h3>

<div class="row">
<div class="col-md-12">
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
        <?php
         echo $this->Form->input('username', array('class' => 'form-control'));
         echo $this->Form->input('password', array('class' => 'form-control'));
         echo $this->Form->input('display_name', array('label' => __('Display Name'), 'class' => 'form-control'));
         echo $this->Form->input('biography', array('label' => __('Biography'), 'class' => 'form-control'));
         ?>
        </fieldset>
    </div>
    <?php echo $this->Form->submit(__('Register'), array('class' => "btn btn-lg btn-primary btn-block")); ?>
    </form>
</div>
</div>
</div>

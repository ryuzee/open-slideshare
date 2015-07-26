<?php echo $this->Html->css('signin.css'); ?>

<?php echo $this->Form->create('User', array('action' => 'login', 'class'=> 'form-signin'));?>
<h2 class="form-signin-heading"><?php echo __('Signin here'); ?></h2>
<?php echo $this->Form->input('username', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Username', true))); ?>
<?php echo $this->Form->input('password', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Password', true))); ?></td>
<?php echo $this->Form->submit(__('Signin', true), array('class' => 'btn btn-lg btn-primary btn-block'));?>
</form>
</div>

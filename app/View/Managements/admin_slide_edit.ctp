<h3><?php echo __('Edit Slide'); ?>&nbsp;&nbsp;<small><a href="/admin/managements/dashboard"><?php echo __('Back to Dashboard'); ?></a></small></h3>

<?php echo $this->Form->create('Slide', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control'
    ),
    'class' => 'well'
)); ?>
    <fieldset>
    <?php
        echo $this->Form->input('id');
        echo $this->Form->input('name');
        echo $this->Form->hidden('convert_status');
        echo $this->Form->hidden('key');
        echo $this->Form->input('description');
        echo $this->Form->input('tags', array('type' => 'text'));
        echo $this->Form->input('downloadable', array(
            'label' => __('Allow to download original slide'),
            'class' => false
        ));
        echo $this->Form->input('category_id', array('label' => __('Category')));
    ?>
    <label for="SlideCreated"><?php echo __('Created'); ?></label><br />
    <?php
        echo $this->Form->dateTime('created', 'YMD', '24', array('empty' => false, 'monthNames' => false, 'maxYear' => date('Y')));
    ?>
    </fieldset>
    <br />
    <input type="submit" id="submit" class="btn btn-primary" value="<?php echo __('Submit'); ?>" />
</form>
</div>


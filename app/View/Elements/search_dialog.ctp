<div id="search_slide_dialog_" title="<?php echo __('Search'); ?>" style="display:none">
    <!-- search form -->
    <?php echo $this->Form->create('Slide', array(
        'url' => array('controller' => 'slides', 'action' => 'search'),
        'inputDefaults' => array(
            'div' => 'form-group',
            'label' => array(
                'class' => 'col col-md-2 control-label'
            ),
            'wrapInput' => 'col col-md-10',
            'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    )); ?>
    <fieldset>
    <?php
        echo $this->Form->input('name', array('required' => false, 'placeholder' => __('Title'), 'label' => __('Title')));
        echo $this->Form->input('display_name', array('required' => false, 'placeholder' => __('Author'), 'label' => __('Author')));
    echo $this->Form->input('description', array('required' => false, 'type' => 'text', 'placeholder' => __('Description')));
    echo $this->Form->input('tags', array('required' => false, 'type' => 'text', 'placeholder' => __('Tag')));
    $date_f = $this->Form->value("created_f");
    $date_t = $this->Form->value("created_t");
     echo $this->Form->input('created_f', array('required' => false, 'type' => 'text', 'placeholder' => __('From'), 'label' => __('Date(From)'), 'class' => 'datepicker_f form-control'));
     echo $this->Form->input('created_t', array('required' => false, 'type' => 'text', 'placeholder' => __('To'), 'label' => __('Date(To)'), 'class' => 'datepicker_t form-control'));

     ?>
    </fieldset>

    <div class="form-group">
        <div class="col col-md-10 col-md-offset-2">
            <?php echo $this->Form->submit(__('Search'), array(
                'div' => false,
                'class' => 'btn btn-primary'
            )); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
    <!-- end of search form -->
</div>

<script type="text/javascript">
$1102(document).ready(function(){
    $1102(".datepicker_f").datepicker();
    $1102(".datepicker_f").datepicker("option", "dateFormat", 'yy-mm-dd');
    $1102(".datepicker_f").datepicker("setDate", '<?php echo h($date_f); ?>');
    $1102(".datepicker_t").datepicker();
    $1102(".datepicker_t").datepicker("option", "dateFormat", 'yy-mm-dd');
    $1102(".datepicker_t").datepicker("setDate", '<?php echo h($date_t); ?>');

    $1102("#search_slide_dialog_").dialog({
        autoOpen: false,
        width: 600
    });
    $1102("#open_search_form_").on("click", function() {
        $1102('#search_slide_dialog_').dialog('open');
    });
});
</script>


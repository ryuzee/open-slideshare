<script type="text/javascript">
$(document).ready(function(){
    $("#upload-form").submit(function(event){
        var file = $("#file")[0].files[0];
        if(file != null) {
            console.log(file.name);
        } else {
            return false;
        }
        var buf = file.name.split('.');
        var ext = (buf[buf.length-1]).toLowerCase();
        if(ext != "ppt" && ext != "pptx" && ext != "pdf") {
            alert("No acceptable extension...");
            return;
        }

        event.preventDefault();

        var formData = new FormData();
        var url = "https://<?php echo Configure::read('bucket_name'); ?>.s3-<?php echo Configure::read('region'); ?>.amazonaws.com/";

        var form = $('#upload-form');
        $(form.serializeArray()).each(function(i, v) {
            if(v.name != "file") {
                formData.append(v.name, v.value);
            }
        });
        formData.append("file", $("#file").prop("files")[0]);

        // You need to set CORS options in target S3 bucket
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'xml',
            data: formData,
            async: true,
            crossDomain: true,
            xhr: function() {
                xhr = $.ajaxSettings.xhr();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        var p = Math.round(percentComplete * 100);
                        $(".progress-bar").html(p + "%");
                        $(".progress-bar").attr("aria-valuenow", p);
                        $(".progress-bar").attr("style", "width: " + p + "%");
                    }
                }, false);
                return xhr;
            },
            statusCode: {
                201: function(){
                    console.log("200");
                },
                404: function(){
                    console.log("404");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        }).done(function( data, textStatus, jqXHR ) {
            var key   = $(data).find("Key").text();
            $("#SlideKey").val(key);
            $("#SlideConvertStatus").val(0);
        }) .fail(function( jqXHR, textStatus, errorThrown ) {
            // ...
        });
        return false;
    });
});
</script>

<div class="row">
    <div class="col-md-8">
    <h3><?php echo __('Edit slide'); ?></h3>

    <form id="upload-form" class="well" method="post" enctype="multipart/form-data" action="javascript:void(0); return false;">
    <div class="form-group">
    <label><?php echo __('File'); ?></label>
    <input type="file" name="file" id="file" class="form-control" />
    </div>

    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
    </div>

    <input class="fval" type="hidden" id="key"  name="key" value='<?php echo $slide["Slide"]["key"]; ?>' class="form-control"/>
    <input class="fval" type="hidden" name="acl" value="<?php echo $form_values['acl']; ?>" />
    <input class="fval" type="hidden" name="success_action_status" value="<?php echo $form_values['success_action_status']; ?>" />
    <input class="fval" type="hidden" id="content_type" name="Content-Type" value="application/octetstream" class="form-control"/>
    <input class="fval" type="hidden" name="x-amz-meta-uuid" value="14365123651274" />
    <input class="fval" type="hidden" name="X-Amz-Credential" value="<?php echo $form_values['access_id']; ?>/<?php echo $form_values['date_ymd']; ?>/<?php echo Configure::read('region'); ?>/s3/aws4_request" class="form-control"/>
    <input class="fval" type="hidden" name="X-Amz-Algorithm" value="AWS4-HMAC-SHA256" class="form-control" />
    <input class="fval" type="hidden" name="X-Amz-Date" value="<?php echo $form_values['date_gm']; ?>" class="form-control" />
    <input class="fval" type="hidden" name="Policy" value='<?php echo $form_values['base64_policy']; ?>' />
    <input class="fval" type="hidden" name="X-Amz-Signature" value="<?php echo $form_values['signature']; ?>" />
    <?php if($form_values['security_token'] != ""): ?>
    <input class="fval" type="hidden" name="x-amz-security-token" value="<?php echo $form_values['security_token']; ?>" />
    <?php endif; ?>
    <input class="fval" type="hidden" id="x-amz-meta-title"  name="x-amz-meta-title" value="" class="form-control"/>
    <input class="fval" type="hidden" id="x-amz-meta-tag" name="x-amz-meta-tag" value="open-slideshare" class="form-control"/>

    <input type="submit" id="re-upload" class="btn btn-primary" value="<?php echo __('Re-upload'); ?>" />

</form>

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
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div class="col-md-4">
    <?php echo $this->element("right_side_bar_common"); ?>
    <div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading"><?php echo __('Actions'); ?></div>
      <!-- List group -->
    <ul class="list-group">
        <li class="list-group-item"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Slide.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Slide.id'))); ?></li>
    </ul>
    </div>
</div>

</div>

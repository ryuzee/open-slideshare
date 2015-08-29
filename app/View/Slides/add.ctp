<script type="text/javascript">
$1102(document).ready(function(){
    // $("#upload-form").submit(function(event){
    $1102('#file').on("change", function(event) {
        var file = this.files[0];
        if(file != null) {
            console.log(file.name); // ファイル名をログに出力する
        } else {
            return;
        }
        var buf = file.name.split('.');
        var ext = (buf[buf.length-1]).toLowerCase();
        if(ext != "ppt" && ext != "pptx" && ext != "pdf") {
            alert("No acceptable extension...");
            return;
        }
        console.log("break1");

        event.preventDefault();

        var formData = new FormData();
        var url = "<?php echo $this->Common->upload_endpoint(); ?>/";

        var form = $1102('#upload-form');
        $1102(form.serializeArray()).each(function(i, v) {
            if(v.name != "file") {
                formData.append(v.name, v.value);
            }
        });
        formData.append("file", $1102("#file").prop("files")[0]);
        console.log("break2");

        // You need to set CORS options in target S3 bucket
        $1102.ajax({
            url: url,
            type: 'POST',
            dataType: 'xml',
            data: formData,
            async: true,
            crossDomain: true,
            xhr: function() {
                xhr = $1102.ajaxSettings.xhr();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        var p = Math.round(percentComplete * 100);
                        $1102(".progress-bar").html(p + "%");
                        $1102(".progress-bar").attr("aria-valuenow", p);
                        $1102(".progress-bar").attr("style", "width: " + p + "%");
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
            var key   = $1102(data).find("Key").text();
            $1102("#SlideKey").val(key);
            $1102("#save_button").removeAttr("disabled");
        }) .fail(function( jqXHR, textStatus, errorThrown ) {
            // ...
        });
        return false;
    });
});
</script>

<div class="row">
    <div class="col-md-8">
    <h3><?php echo __('Upload slide'); ?></h3>

    <form id="upload-form" class="well" method="post" enctype="multipart/form-data">
<?php $rand_name = md5(uniqid() . date('Ymdhis')); ?>
    <div class="form-group">
        <label><?php echo __('File'); ?></label>
    <input type="file" name="file" id="file" class="form-control" />
    </div>

    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
    </div>

    <input class="fval" type="hidden" id="key"  name="key" value='<?php echo $rand_name; ?>' class="form-control"/>
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
    <input class="fval" type="hidden" id="x-amz-meta-tag" name="x-amz-meta-tag" value="open-slideshare" class="form-control"/>

</form>

<?php echo $this->Form->create('Slide',
    array(
        'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control'
    ),
    'class' => 'well'
));
?>
    <fieldset>
    <?php
    echo $this->Form->input('name', array('label' => __('Name')));
    echo $this->Form->input('description', array('label' => __('Description')));
    echo $this->Form->input('tags', array('type' => 'text'));
    echo $this->Form->input('downloadable', array(
            'label' => __('Allow to download original slide'),
            'class' => false
        ));
    echo $this->Form->input('category_id', array('label' => __('Category')));
    echo $this->Form->hidden('key');
    ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Upload a file to save'), array('id' => 'save_button', 'disabled' => 'disabled', 'class' => "btn btn-lg btn-primary btn-block")); ?>
     </form>
    </div>

    <div class="col-md-4">
    <?php echo $this->element("right_side_bar_common"); ?>
    </div>

</div>

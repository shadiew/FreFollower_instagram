<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("Import", "Import Wizard");
?>
<div class="card">
    <div class="card-header">
        IMPORT WIZARD
    </div>
    <div class="card-body">
        <p class="text-success">
            <i class="fa fa-info"></i> Only {PACKAGENAME}.iwp files are supported. These files can be created via Export wizard. 
        </p>
        <p class="text-danger">During the import process, users who already exist on your site will be skipped. In this context, we recommend that you delete the Inactive users in your system before importing to be able to import more users!  </p>
        <p>After the transfer takes place, the iwp package you downloaded is deleted from the server.</p>
       
        <p>Only upload iwp files from trusted sources. Packages not sourced from trusted sources can damage your server!</p>
       
        <span class="btn btn-success fileinput-button">
            <i class="fa fa-upload"></i>
            <span>Upload</span>
            <input id="fileupload" type="file" name="files[]" multiple>
        </span>
        </p>
        <div id="UploadProgressContainer" style="display: none;">
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped active"></div>
            </div>
        </div>
        <hr />
        <div class="clearfix"></div>
        <div id="sourceCookies">
            <?php if (count($model["iwpPackages"]) > 0) { ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Package to Import</label>
                        <select name="packageName" class="form-control">
                            <?php foreach ($model["iwpPackages"] as $p) { ?>
                                <option value="<?php echo $p; ?>"><?php echo $p; ?>.iwp</option>
                            <?php } ?>
                        </select>
                        <span class="form-text"><strong><?php echo count($model["iwpPackages"]); ?></strong> The packet is waiting to be transferred. Select the package you want to import. </span>
                    </div>
                    <p>
                        <button type="submit" class="btn btn-dark" onclick="$(this).html('importing..');">import</button>
                    </p>
                </form>
            <?php } else { ?>
                <p class="text-danger"><?php echo Wow::get("project/cookiePath"); ?>There are no packages waiting to be imported in the import/ folder. Use the install package button to install the packages you have.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php $this->section("section_head");
$this->parent(); ?>
<link rel="stylesheet" href="/assets/jquery-file-upload/css/jquery.fileupload.css" />
<?php $this->endSection(); ?>

<?php $this->section("section_scripts");
$this->parent(); ?>
<script src="/assets/load-image/load-image.all.min.js"></script>
<script src="/assets/canvas-to-blob/canvas-to-blob.min.js"></script>
<script src="/assets/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="/assets/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<script src="/assets/jquery-file-upload/js/jquery.fileupload.js"></script>
<script src="/assets/jquery-file-upload/js/jquery.fileupload-process.js"></script>
<script type="text/javascript">
    function setIwpUpload() {
        $('#fileupload').fileupload({
            url: '?formType=uploadIwp',
            acceptFileTypes: /(\.|\/)(iwp)$/i,
            stop: function() {
                $('#UploadProgressContainer').css('display', 'none');
                window.location.href = window.location.href;
            },
            progressall: function(e, data) {
                if ($('#UploadProgressContainer').css('display') == 'none') {
                    $('#UploadProgressContainer').css('display', 'block');
                }
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
    }
    $(document).ready(function() {
        setIwpUpload();
    });
</script>
<?php $this->endSection(); ?>
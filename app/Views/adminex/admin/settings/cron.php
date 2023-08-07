<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Cronlist");
$baseUrl = Wow::get("app/base_url");
?>
 <h2>Cron List</h2>
<div class="container p-md-4 p-0">
    <div class="row">
        <div class="alert alert-info">
            <p>For your cron urls to be executable, you must have defined a scheduled task.
                The task you define must be marked to run once per minute. Plesk users can type * * * * * by selecting the Cron Style option. Cpanel users can select from the list every minute. </p>
            <p>Contoh penulisan cron jika menggunakan crontab (manual):<br />
                <strong>0 * * * * http<?php echo Wow::get("project/onlyHttps") ? 's' : ''; ?>://<?php echo $_SERVER['SERVER_NAME'];
                                                                                                echo empty($baseUrl) ? '' : $baseUrl; ?>/cron/listcronnya?scKey=keymilikmu > /dev/null</strong>
            </p>
        </div>

        <hr />

        <?php foreach ($model as $cron) { ?>
            <div class="row">
                <div class="col-sm-10"><strong><?php echo $cron["baslik"]; ?></strong><br /><?php echo $cron["url"]; ?>
                    <br /><?php echo $cron["calismaSikligi"] ?> second <br />
                </div>
                <div class="col-sm-2">
                    <button data-bs-target="cronBtn-<?php echo $cron["cronID"]; ?>" id="cronBtn-<?php echo $cron["cronID"]; ?>" class="btn <?php echo $cron["isActive"] == 1 ? "btn-success" : "btn-danger"; ?>" onclick="cronDuzenle(<?php echo $cron["cronID"]; ?>)" style="width: 100%;">Edit</button>
                </div>
            </div>
            <hr />
        <?php } ?>
        <p>
            <button type="button" class="btn btn-primary" style="float:left;" onclick="cronDuzenle()">Tambah Cron</button>
        </p>
    </div>
</div>


<?php $this->section("section_scripts");
$this->parent(); ?>
<script type="text/javascript">
    function showCronModal() {
        $('#myCrons').modal("show");
    }

    function cronDuzenle(cronID) {
        if (cronID == "") {
            $('#myCronEditSave').modal("show");
        } else {
            $('#cronBtn-' + cronID).html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>');
            $.ajax({
                url: '<?php echo Wow::get("project/adminPrefix"); ?>/settings/one-cron/' + cronID,
                type: 'POST',
                dataType: 'json'
            }).done(function(data) {
                $('input[name="cronID"]').val(data.cronID);
                $('input[name="cronBaslik"]').val(data.baslik);
                $('input[name="cronUrl"]').val(data.url);
                $('input[name="cronSaniye"]').val(data.calismaSikligi);
                $('select[name="cronDurum"]').val(data.isActive);
                $('#cronBtn-' + cronID).html('Edit');
                $('#myCronEditSave').modal("show");
            });
        }
    }

    $('#cronKaydetUpdate').click(function() {
        $('#requireAlert').hide();
        var cronBaslik = $('input[name="cronBaslik"]').val();
        var cronUrl = $('input[name="cronUrl"]').val();
        var cronSaniye = $('input[name="cronSaniye"]').val();
        var cronDurum = $('select[name="cronDurum"]').val();
        if (cronBaslik != '' && cronUrl != '' && cronSaniye != '' && cronDurum != '') {
            $('#cronUpdateForm').submit();
        } else {
            $('#requireAlert').fadeIn(500);
            setTimeout(function() {
                $('#requireAlert').fadeOut(500);
            }, 2000);
        }
    });
</script>
<?php $this->endSection(); ?>
<?php $this->section("section_modals");
$this->parent(); ?>

<!-- Modal -->
<div class="modal fade" id="myCronEditSave" tabindex="-1" role="dialog" aria-labelledby="myCronEditSaveLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Cron Settings</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="requireAlert" class="alert alert-warning" role="alert" style="display:none;">
                    <strong>Dikkat!</strong> Lütfen tüm alanları doldurunuz.
                </div>
                <form method="POST" action="<?php echo Wow::get("project/adminPrefix"); ?>/settings/save-update-cron" id="cronUpdateForm">
                    <div class="row">
                        <div class="mb-3">
                            <label class="col-sm-6 form-label">Cron Title</label>
                            <div class="col-sm-12">
                                <input type="hidden" class="form-control" name="cronID" value="" required>
                                <input type="text" class="form-control" name="cronBaslik" value="" placeholder="İşlemin Başlığı" required>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="mb-3">
                            <label class="col-sm-6 form-label">Cron URL</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="cronUrl" value="" placeholder="İşlemin Url Adresi" required>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="mb-3">
                            <label class="col-sm-6 form-label">Working Interval (seconds)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="cronSaniye" value="" placeholder="Çalışma aralığını saniye cinsinden yazınız" required>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="mb-3">
                            <label class="col-sm-6 form-label">Status</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="cronDurum" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                <button type="button" class="btn btn-success" id="cronKaydetUpdate">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
<?php
	/**
	 * @var \Wow\Template\View $this
	 * @var array              $media
	 */
	$media = NULL;
	if($this->has("media")) {
		$media = $this->get("media");
	}
	$logonPerson  = $this->get("logonPerson");
    $uyelik      = $logonPerson->member;
	$helper      = new \App\Libraries\Helpers();
    $isMobile    = $helper->is_mobile();

    if(!$logonPerson->isLoggedIn()) {
        return;
    }
?>

<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="/tools">Tools</a></li>
                <li class="breadcrumb-item active">Auto Bookmark</li>
            </ol>

            <!-- HEADER -->
            <div class="jumbotron">
                <h3 class="display-6">Auto Bookmark Gratis</h3>
                <p class="lead">Fitur yang dapat anda gunakan secara gratis, jika bingung lihat tutorial berikut.</p>
                <a href="https://www.youtube.com/watch?v=2Ovv_aonwpY" target="_blank" class="btn btn-success">
                    Tutorial</a>

                <button class="btn btn-outline-light" type="button">
                    Poin: <span class="badge" id="saveKrediCount"><?php echo $logonPerson->member["saveKredi"]; ?>
                        <span class="text-warning"><i class="fas fa-coins"></i></span></span>
                </button>
            </div>
            <!-- ENF OF HEADER -->

            <!-- ALERT -->
            <div class="alert alert-dismissible alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4 class="alert-heading">Warning!</h4>
                <p class="mb-0">Pastika akun kalian tidak di private, karena jika di private saat kalian submit itu akan
                    muncul <strong>404 Not Found</strong>.</p>
            </div>
            <!-- END OF ALERT -->


            <?php if(is_null($media)) { ?>
            <div class="card border-secondary mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">Panel</div>
                        <div class="col-6 text-right">
                            <em><u>Give Feedback</u></em>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Auto Bookmark</h4>
                    <div class="card-text">
                        <form method="post" action="?formType=findMediaID" class="form">
                            <div class="form-group">
                                <label for="autolikes">Url postingan yang ingin di <em>bookmark</em>:</label>
                                <input type="text" name="mediaUrl" class="form-control"
                                    placeholder="contoh: https://www.instagram.com/p/3H0-Yqjo7u/" required>
                                <small id="emailHelp" class="form-text text-muted">Pastikan akun anda
                                    tidak di private.</small>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-rocket"></i> Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <?php } elseif($media["items"][0]["user"]["is_private"] == 1) { ?>
            <hr />
            <p class="text-danger">Uppps! Profil diprivat! jangan privat profil untuk menggunakan tool ini.</p>
            <?php } else { ?>

            <div class="card border-secondary mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">Panel</div>
                        <div class="col-6 text-right">
                            <em><u>Give Feedback</u></em>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Auto Bookmark</h4>
                    <div class="card-text">
                        <form id="formBegeni" class="form">
                            <div class="form-group">
                                <p>Preview:</p>
                                <?php $item = $media["items"][0]; ?>
                                    <img src="/upload/media/<?php echo substr($item["pk"], -1); ?>/<?php echo $item["pk"]; ?>.jpg" class="img-fluid"/>
                            </div>
                            <div class="form-group">
                                <label>Jumlah bookmark yang ingin dikirim:</label>
                                <input type="text" name="adet" class="form-control" placeholder="10" value="10">
                                <small id="emailHelp" class="form-text text-muted">Pastikan akun anda
                                    tidak di private.</small>
                            </div>
                            <input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
                            <input type="hidden" name="mediaCode" value="<?php echo $item["shortcode"]; ?>">
                                <button type="button" id="formBegeniSubmitButton" class="btn btn-primary" onclick="sendBegeni();"><i class="fa fa-rocket"></i> Submit</button>
                        </form>
                        <div class="cl10"></div>
                        <div id="userList"></div>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>
        <!--end of column 8-->
        <div class="col-12 col-lg-4 col-xl-4">
            <?php $this->renderView("tools/sidebar"); ?>
        </div>
        <!--end of column 4-->

    </div>
</div>

<?php $this->section("section_scripts");
	$this->parent();
	if(!is_null($media) && $media["items"][0]["user"]["is_private"] != 1) { ?>
<script type="text/javascript">
var countBegeni, countBegeniMax;

function sendBegeni() {
    countBegeni = 0;
    countBegeniMax = parseInt($('#formBegeni input[name=adet]').val());
    if (isNaN(countBegeniMax) || countBegeniMax <= 0) {
        alert('Masukkan jumlah bookmark!');
        return false;
    }
    $('#formBegeniSubmitButton').html('<i class="fa fa-spinner fa-spin fa-lg"></i> Mengirim..');
    $('#formBegeni input').attr('readonly', 'readonly');
    $('#formBegeni button').attr('disabled', 'disabled');
    $('#userList').html('');
    sendBegeniRC();
}

function sendBegeniRC() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '?formType=send',
        data: $('#formBegeni').serialize()
    }).done(function(data) {
        if (data.status == 'error') {
            $('#userList').prepend('<p class="text-danger">' + 'sudah tidak ada user lagi' + '</p>');
            sendBegeniComplete();
        } else {
            for (var i = 0; i < data.users.length; i++) {
                var user = data.users[i];
                if (user.status == 'success') {
                    $('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick +
                        '</a> proses ngebookmark. Hasil: <span class="badge badge-success">Sukses</span></p>'
                    );
                    countBegeni++;
                    $('#formBegeni input[name=adet]').val(countBegeniMax - countBegeni);
                    $('#begeniKrediCount').html(data.begeniKredi);

                } else {
                    //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                }
            }
            if (countBegeni < countBegeniMax) {
                sendBegeniRC();
            } else {
                sendBegeniComplete();
            }
        }
    });
}

function sendBegeniComplete() {
    $('#formBegeniSubmitButton').html('Submit');
    $('#formBegeni input').removeAttr('readonly');
    $('#formBegeni button').prop("disabled", false);
    $('#formBegeni input[name=adet]').val('10');
    $('#userList').prepend('<p class="text-success">Jumlah bookmark yang berhasil ditambahkan: ' + countBegeni + '</p>');
}
</script>
<?php }
	$this->endSection(); ?>

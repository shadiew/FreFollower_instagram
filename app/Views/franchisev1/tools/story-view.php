<?php
	/**
	 * @var \Wow\Template\View      $this
	 * @var array                   $media
	 */
	$user        = NULL;
	if($this->has("user")){
		$user = $this->get("user");
    }
    $logonPerson = $this->get("logonPerson");
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
                <li class="breadcrumb-item active">Auto View Story</li>
            </ol>

            <!-- HEADER -->
            <div class="jumbotron">
                <h3 class="display-6">Auto View Story</h3>
                <p class="lead">Cara mudah untuk memperbanyak view di story instagram kalian dengan sekali klik. Caranya
                    sangat mudah, tinggal masukin aja username kalian dibawah ini</p>
                <a href="https://www.youtube.com/watch?v=2Ovv_aonwpY" target="_blank" class="btn btn-success">
                    Tutorial</a>

                <button class="btn btn-outline-light" type="button">
                    Poin: <span class="badge" id="storyKrediCount"><?php echo $logonPerson->member["storyKredi"]; ?>
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


            <?php if(is_null($user)){ ?>
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
                    <h4 class="card-title"> Auto View Story</h4>
                    <div class="card-text">
                        <form method="post" action="?formType=findUserID" class="form">
                            <div class="form-group">
                                <label for="autolikes">Masukkan username kalian:</label>
                                <input type="text" name="username" class="form-control" placeholder="contoh: receh_man"
                                    required>
                                <small id="emailHelp" class="form-text text-muted">Pastikan akun anda
                                    tidak di private.</small>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-rocket"></i> Submit</button>
                        </form>
                    </div>
                </div>
            </div>

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
                    <h4 class="card-title">Auto View Story</h4>
                    <div class="card-text">
                        <form id="formTakip" class="form">
                            <div class="form-group">
                                <p>Preview:</p>
                                <img src="/upload/<?php echo substr($user["user"]["pk"], -1); ?>/<?php echo $user["user"]["pk"]; ?>.jpg"
                                                class="img-thumbnail mb-3" />

                            </div>
                            <div class="form-group">
                                <label>Jumlah views yang ingin dikirim:</label>
                                <input type="text" name="adet" class="form-control" placeholder="10" value="10">
                                <small id="emailHelp" class="form-text text-muted">Pastikan akun anda
                                    tidak di private.</small>
                            </div>
                            <input type="hidden" name="userID" value="<?php echo $user["user"]["pk"]; ?>">
                            <input type="hidden" name="userName" value="<?php echo $user["user"]["username"]; ?>">
                            <button type="button" id="formTakipSubmitButton" class="btn btn-primary"
                                onclick="sendTakip();"><i class="fa fa-rocket"></i> Submit</button>
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
	if( ! is_null($user)){ ?>
<script type="text/javascript">
var countTakip, countTakipMax;

function sendTakip() {
    countTakip = 0;
    countTakipMax = parseInt($('#formTakip input[name=adet]').val());

    if (isNaN(countTakipMax) || countTakipMax <= 0) {
        alert('Masukin jumlah views!');
        return false;
    }

    $('#formTakipSubmitButton').html('<i class="fa fa-spinner fa-spin fa-lg"></i> Mengirim..');
    $('#formTakip input').attr('readonly', 'readonly');
    $('#formTakip button').attr('disabled', 'disabled');
    $('#userList').html('');
    sendTakipRC();
}

function sendTakipRC() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '?formType=send',
        data: $('#formTakip').serialize()
    }).done(function(data) {


        if (data.status == 'error') {
            $('#userList').prepend('<p class="text-danger">' + data.message + '</p>');
            sendTakipComplete();
        } else {
            for (var i = 0; i < data.users.length; i++) {
                var user = data.users[i];
                if (user.status == 'success') {
                    $('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick +
                        '</a> pengguna melihat. Status: <span class="badge badge-success">Sukses</span></p>'
                    );
                    countTakip++;
                    $('#formTakip input[name=adet]').val(countTakipMax - countTakip);
                    $('#takipKrediCount').html(data.takipKredi);

                } else {}
            }
            if (countTakip < countTakipMax) {
                sendTakipRC();
            } else {
                sendTakipComplete();
            }
        }
    });
}

function sendTakipComplete() {
    $('#formTakipSubmitButton').html('Submit');
    $('#formTakip input').removeAttr('readonly');
    $('#formTakip button').prop("disabled", false);
    $('#formTakip input[name=adet]').val('10');
    $('#userList').prepend('<p class="text-success">Total jumlah pengguna yang melihat :  ' + countTakip + '</p>');
}
</script>
<?php }
	$this->endSection(); ?>

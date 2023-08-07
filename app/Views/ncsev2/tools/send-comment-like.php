<?php
    /**
     * @var \Wow\Template\View      $this
     * @var array                   $media
     * @var \App\Models\LogonPerson $logonPerson
     */
	$media       = NULL;
    $logonPerson = $this->get("logonPerson");
    if($this->has("media")){
        $media = $this->get("media");
    }
    if($this->has("comment")) {
        $comment = $this->get("comment");
    }
    $uyelik      = $logonPerson->member;
	$helper      = new \App\Libraries\Helpers();
    $isMobile    = $helper->is_mobile();
?>
    <div class="container">
        <div class="cl10"></div>
        <div class="row">
            <div class="col-sm-8 col-md-9">
                <h4 style="margin-top: 0;">Auto Like Comment</h4>
                <p>Fitur ini adalah auto like comment, jadi komentar kalian bisa menjadi top komen dengan cara memperbanyak like pada komentar kalian.</p>
                <p>Dapatkan like comment hanya dengan memasukan url fotonya dan username yang komentar dibawah ini, dan klik Submit!</br>
		<div class="panel panel-default">
                  <div class="panel-body">
                      <div class="row">
                     <div class="col-9 col-xs-9 col-lg-4 col-md-5 col-sm-6">Sisa Poin anda saat ini adalah <i class="fa fa-arrow-circle-right"></i></div>
                     <div class="col-3 col-xs-3 col-lg-3 col-md-4 col-sm-6"><span class="label label-success" id="yorumKrediCount"><?php echo $logonPerson->member["yorumBegeniKredi"]; ?></span></div>
                     </div>
                  </div>
                </div>
               
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Waktu reset poin:
                    </div>
                    <div class="panel-body">
                        <div class="col col-12">
                                <p>Di reset tiap 2x sehari ya guys, kalau udah habis kalian bisa gunakan lagi.</p>
                            </div>
                    </div>
                </div>
				<?php if(is_null($media)) { ?>
					<div class="panel panel-default">
					                            <div class="panel-heading">
                            Auto Like Comment
                        </div>
                        <div class="panel-body">
                            <form method="post" action="?formType=findMediaID" class="form">
                                <div class="form-group">
                                    <label>Masukan URL yang ingin diberi likes:</label>
                                    <input type="text" name="mediaUrl" class="form-control" placeholder="https://www.instagram.com/p/3H0-Yqjo7u/" required>
                                </div>
								<div class="form-group">
									<label>Masukkan Username yang berkomentar:</label>
									<input type="text" name="username" class="form-control" placeholder="receh_man" required>
								</div>
                                <button type="submit" class="btn btn-success"><i class="fa fa-paper-plane-o"></i> Submit</button>
                            </form>
                        </div>
                    </div>
<?php } elseif($media["items"][0]["user"]["is_private"] == 1) { ?>
    <hr/>
    <p class="text-danger">Uppps! Profil yang membagikan pos ini disembunyikan. Komentar tidak dapat dikirim karena kiriman profil tersembunyi tidak dapat dijangkau.</p>
<?php } elseif(isset($media["items"][0]["comments_disabled"]) && $media["items"][0]["comments_disabled"] == 1) { ?>
    <hr/>
    <p class="text-danger">Uppps! Posting ini ditutup untuk komentar.</p>
    <?php
} else { ?>

<div class="panel panel-default">
    <div class="panel-heading">
                           Send Comment Like
                        </div>
                        <div class="panel-body">
					
                            <form id="formBegeni" class="form">
                                <div class="form-group">
                                    <label>Foto:</label>
									<?php $item = $media["items"][0]; ?>
                                  
<img src="/upload/media/<?php echo substr($item["pk"], -1); ?>/<?php echo $item["pk"]; ?>.jpg" class="img-thumbnail"/>                                
</div>
                                <div class="form-group">
									<label><u>Komentar:</u></label>
									<p><?php echo $comment["comment"]; ?></p>
								</div>
								<div class="form-group">
									<label>Jumlah Like di Komentar:</label>
									<input type="text" name="adet" class="form-control" placeholder="10" value="10">
									<span class="help-block">Max <?php echo $logonPerson->member->yorumBegeniKredi; ?> Anda dapat mengirim like di komentar.</span>
								</div>

								<input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
								<input type="hidden" name="yorumID" value="<?php echo $comment["commentID"]; ?>">
								<input type="hidden" name="yorumText" value="<?php echo $comment["comment"]; ?>">
								<input type="hidden" name="mediaCode" value="<?php echo $item["code"]; ?>">
								<input type="hidden" name="userID" value="<?php echo $item["user"]["pk"]; ?>">
								<input type="hidden" name="userName" value="<?php echo $item["user"]["username"]; ?>">
								<input type="hidden" name="imageUrl" value="<?php echo $item["media_type"] == 8 ? str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][0]["url"]) : str_replace("http:", "https:", $item["image_versions2"]["candidates"][0]["url"]); ?>">
								<input type="hidden" name="_method" value="POST">
                                <button type="button" id="formBegeniSubmitButton" class="btn btn-success" onclick="sendBegeni();">Submit</button>
                            </form>
                            <div class="cl10"></div>
                            <div id="userList"></div>
                        </div>
                    </div>
<?php } ?>
</div>
<div class="col-sm-4 col-md-3">
                <?php $this->renderView("tools/sidebar"); ?>
</div>
</div></div>
<?php $this->section("section_scripts");
    $this->parent();
    if(!is_null($media) && $media["items"][0]["user"]["is_private"] != 1) { ?>
        <script type="text/javascript">
            var countBegeni, countBegeniMax;

            function sendBegeni() {
                countBegeni    = 0;
                countBegeniMax = parseInt($('#formBegeni input[name=adet]').val());
                if(isNaN(countBegeniMax) || countBegeniMax <= 0) {
                    alert('Masukkan jumlah Like!');
                    return false;
                }
                $('#formBegeniSubmitButton').html('<i class="fa fa-spinner fa-spin fa-2x"></i> Sedang Mengirim..');
                $('#formBegeni input').attr('readonly', 'readonly');
                $('#formBegeni button').attr('disabled', 'disabled');
                $('#userList').html('');
                sendBegeniRC();
            }

            function sendBegeniRC() {
                $.ajax({type: 'POST', dataType: 'json', url: '?formType=send', data: $('#formBegeni').serialize()}).done(function(data) {
                    if(data.status == 'error') {
                        $('#userList').prepend('<p class="text-danger">' + 'sudah tidak ada user lagi' + '</p>');
                        sendBegeniComplete();
                    }
                    else {
                        for(var i = 0; i < data.users.length; i++) {
                            var user = data.users[i];
                            if(user.status == 'success') {
                                $('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> mencoba menyukai postingan kamu. Hasil: <span class="label label-success">Sukses</span></p>');
                                countBegeni++;
                                $('#formBegeni input[name=adet]').val(countBegeniMax - countBegeni);
                                $('#begeniKrediCount').html(data.begeniKredi);

                            }
                            else {
                                //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                            }
                        }
                        if(countBegeni < countBegeniMax) {
                            sendBegeniRC();
                        }
                        else {
                            sendBegeniComplete();
                        }
                    }
                });
            }

            function sendBegeniComplete() {
                $('#formBegeniSubmitButton').html('Submit gan!');
                $('#formBegeni input').removeAttr('readonly');
                $('#formBegeni button').prop("disabled", false);
                $('#formBegeni input[name=adet]').val('10');
                $('#userList').prepend('<p class="text-success">Jumlah likes yang berhasil ditambahkan: ' + countBegeni + '</p>');
            }
        </script>
    <?php }
    $this->endSection(); ?>

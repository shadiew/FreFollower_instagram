<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $media
     */
    $user = NULL;
    if($this->has("user")) {
        $user = $this->get("user");
    }
    $logonPerson = $this->get("logonPerson");
    if(!$logonPerson->isLoggedIn()) {
        return;
    }
?>
    <div class="container">
        <div class="cl10"></div>
        <div class="row">
            <div class="col-sm-8 col-md-9">
                <h4 style="margin-top: 0;">Auto Live Broadcast</h4>
                <p>Untuk dapat menggunakan fitur ini, aktifikan terlebih dahulu livenya, lalu masukkan username yang sedang aktif (live).</p>
                <p>Dapatkan views live hanya dengan memasukan usernamenya dibawah ini dan klik Submit!</p>
                <div class="panel panel-default">
                  <div class="panel-body">
                      <div class="row">
                     <div class="col-9 col-xs-9 col-lg-4 col-md-5 col-sm-6">Sisa Poin anda saat ini adalah <i class="fa fa-arrow-circle-right"></i></div>
                     <div class="col-3 col-xs-3 col-lg-3 col-md-4 col-sm-6"><span class="label label-success" id="takipKrediCount"><?php echo $logonPerson->member["canliYayinKredi"]; ?></span></div>
                     </div>
                  </div>
                </div>
             
                <?php if(is_null($user)) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Auto Live Broadcast
                        </div>
                        <div class="panel-body">
                            <form method="post" action="?formType=findUserID" class="form">
                                <div class="form-group">
                                    <label>Username:</label>
                                    <input type="text" name="username" class="form-control" placeholder="receh_man" required>
                                </div>

                                <button type="submit" class="btn btn-success"><i class="fa fa-paper-plane-o"></i> Submit</button>
   </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                         Watch Live Broadcast
                        </div>
                        <div class="panel-body">
                            <form id="formTakip" class="form">
                                <div class="form-group">
                                    <label>Preview:</label>
                                    <img src="<?php echo str_replace("http:", "https:",$user["broadcast"]["cover_frame_url"]); ?>" class="img-fluid"/>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah views:</label>
                                    <input type="text" name="adet" class="form-control" placeholder="10" value="10">
                                </div>
                                <input type="hidden" name="userID" value="<?php echo $user["broadcast"]["id"]; ?>">
                                <input type="hidden" name="userName" value="<?php echo $user["broadcast"]["id"]; ?>">
                                <button type="button" id="formTakipSubmitButton" class="btn btn-success" onclick="sendTakip();">Submit</button>
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
        </div>
    </div>
<?php $this->section("section_scripts");
    $this->parent();
    if(!is_null($user)) { ?>
        <script type="text/javascript">
            var countTakip, countTakipMax;

            function sendTakip() {
                countTakip    = 0;
                countTakipMax = parseInt($('#formTakip input[name=adet]').val());

                if(isNaN(countTakipMax) || countTakipMax <= 0) {
                    alert('Masukkan jumlah views!');
                    return false;
                }

                $('#formTakipSubmitButton').html('<i class="fa fa-spinner fa-spin fa-2x"></i> Sedang Mengirim..');
                $('#formTakip input').attr('readonly', 'readonly');
                $('#formTakip button').attr('disabled', 'disabled');
                $('#userList').html('');
                sendTakipRC();
            }

            function sendTakipRC() {
                $.ajax({type: 'POST', dataType: 'json', url: '?formType=send', data: $('#formTakip').serialize()}).done(function(data) {


                    if(data.status == 'error') {
                        $('#userList').prepend('<p class="text-danger">' + 'sudah tidak ada user lagi' + '</p>');
                        sendTakipComplete();
                    }
                    else {
                        for(var i = 0; i < data.users.length; i++) {
                            var user = data.users[i];
                            if(user.status == 'success') {
                                $('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> mencoba menonton kamu. Hasil: <span class="label label-success">Sukses</span></p>');
                                countTakip++;
                                $('#formTakip input[name=adet]').val(countTakipMax - countTakip);
                                $('#takipKrediCount').html(data.takipKredi);

                            }
                            else {
                                //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                            }
                        }
                        if(countTakip < countTakipMax) {
                            sendTakipRC();
                        }
                        else {
                            sendTakipComplete();
                        }
                    }
                });
            }

            function sendTakipComplete() {
                $('#formTakipSubmitButton').html('Submit gan!');
                $('#formTakip input').removeAttr('readonly');
                $('#formTakip button').prop("disabled", false);
                $('#formTakip input[name=adet]').val('10');
                $('#userList').prepend('<p class="text-success">Total live views yang berhasil ditambah: ' + countTakip + '</p>');
            }
        </script>
    <?php }
    $this->endSection(); ?>
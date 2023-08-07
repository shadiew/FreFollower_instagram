<?php

/**
 * @var \Wow\Template\View      $this
 * @var array                   $media
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$media       = NULL;
if ($this->has("media")) {
    $media = $this->get("media");
}
?>
<h4>Premium: Auto Views Instagram</h4>
<?php if (is_null($media)) { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukVideoLimitLeft; ?>
            </div>
            <div>
                <strong>Max views you can send:</strong> <?php echo $logonPerson->member->videoMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <p>With the video view sending tool, you can send the number of views you specify to the post you want. All submitted views are real users.</p>
            <form method="post" action="?formType=findMediaID" class="form">
                <div class="form-group mb-3">
                    <label class="form-label">Input URL</label>
                    <input type="text" name="mediaUrl" class="form-control" placeholder="https://www.instagram.com/p/3H0-Yqjo7u/" required>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
<?php } elseif ($media["items"][0]["user"]["is_private"] == 1) { ?>
    <hr />
    <p class="text-danger">Uppps! Bu gönderiyi paylaşan profil gizli. Gizli profillerin gönderilerine ulaşılamadığından, video görüntülenme de gönderilememektedir.</p>
<?php } else { ?>

    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukVideoLimitLeft; ?>
            </div>
            <div>
                <strong>Max views you can send:</strong> <?php echo $logonPerson->member->videoMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <form id="formBegeni" class="form">
                <div class="form-group mb-3">
                    <label class="form-label d-block">Preview:</label>
                    <?php $item = $media["items"][0]; ?>
                    <img src="<?php echo $item["media_type"] == 8 ? "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][0]["url"]))) : "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["image_versions2"]["candidates"][0]["url"]))); ?>" class="img-fluid" />
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Input the number of views</label>
                    <input type="text" name="adet" class="form-control" placeholder="10" value="10">
                    <span class="form-text">Max <?php echo $logonPerson->member->videoMaxKredi; ?> you can send view</span>
                </div>
                <input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
                <input type="hidden" name="mediaCode" value="<?php echo $item["code"]; ?>">
                <input type="hidden" name="userID" value="<?php echo $item["user"]["pk"]; ?>">
                <input type="hidden" name="userName" value="<?php echo $item["user"]["username"]; ?>">
                <input type="hidden" name="imageUrl" value="<?php echo $item["media_type"] == 300 ? str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][0]["url"]) : str_replace("http:", "https:", $item["image_versions2"]["candidates"][0]["url"]); ?>">
                <input type="hidden" name="_method" value="POST">
                <button type="button" id="formBegeniSubmitButton" class="btn btn-success" onclick="sendBegeni();">Submit</button>
            </form>
            <div class="cl10"></div>
            <table id="tableHasil" class="table table-responsive table-striped table-hover" style="display:none;">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="userList"></div>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<?php $this->section("section_scripts");
$this->parent();
if (!is_null($media) && $media["items"][0]["user"]["is_private"] != 1) { ?>
    <script type="text/javascript">
        var countBegeni, countBegeniMax, bayiIslemIDLast;

        function sendBegeni() {
            countBegeni = 0;
            countBegeniMax = parseInt($('#formBegeni input[name=adet]').val());
            if (isNaN(countBegeniMax) || countBegeniMax <= 0) {
                alert('Enter the number of views');
                return false;
            }
            if (countBegeniMax > <?php echo $logonPerson->member->videoMaxKredi; ?>) {
                alert('The maximum number of views can be <?php echo $logonPerson->member->videoMaxKredi; ?> !');
                return false;
            }
            $('#formBegeniSubmitButton').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
            $('#formBegeni input').attr('readonly', 'readonly');
            $('#formBegeni button').attr('disabled', 'disabled');
            $('#userList').html('');
            sendBegeniRC();
        }

        function sendBegeniRC(bayiIslemID) {
            url = '?formType=send';
            if (bayiIslemID) {
                url += '&bayiIslemID=' + bayiIslemID;
                bayiIslemIDLast = bayiIslemID;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                data: $('#formBegeni').serialize()
            }).done(function(data) {
                if (data.status == 'error') {
                    $('#userList').prepend('<p class="text-danger">' + data.message + '</p>');
                    sendBegeniComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            //   $('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-success">Başarılı</span></p>');
                            countBegeni++;
                            $('#formBegeni input[name=adet]').val(countBegeniMax - countBegeni);
                            $('#begeniKrediCount').html(data.begeniKredi);

                        } else {
                            //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                        }
                    }
                    if (countBegeni < countBegeniMax) {
                        sendBegeniRC(data.bayiIslemID);
                    } else {
                        sendBegeniComplete();
                    }
                }
            }).fail(function() {
                setTimeout(function() {
                    sendBegeniRC(bayiIslemIDLast);
                }, 3000);
            });
        }

        function sendBegeniComplete() {
            $('#formBegeniSubmitButton').html('Submit');
            $('#formBegeni input').removeAttr('readonly');
            $('#formBegeni button').prop("disabled", false);
            $('#formBegeni input[name=adet]').val('300');
            $('#userList').prepend('<p class="text-success mt-3">Views that were sent: ' + countBegeni + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
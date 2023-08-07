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
if ($this->has("comment")) {
    $comment = $this->get("comment");
}
?>
<script>
    $(function() {
        $('#usernameCheck').keyup(function() {
            this.value = $.trim(this.value);
            // this.value = this.value.substring(1);
            while (this.value.charAt(0) === '@' || this.value.charAt(0) === '.' || this.value.charAt(0) ===
                '-') {
                this.value = this.value.substring(1);
            }
        });
    });
</script>
<h4>Premium: Top Comment</h4>
<?php if (is_null($media)) { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukYorumBegeniLimitLeft; ?>
            </div>
            <div>
                <strong>Max like comment you can send:</strong> <?php echo $logonPerson->member->yorumBegeniMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <p>With the comment like tool, you can send the number of comment you specify to the user you want. All of the likes sent are real users.</p>
            <form method="post" action="?formType=findMediaID" class="form">
                <div class="form-group mb-3">
                    <label class="form-label">Enter the URL you want to like</label>
                    <input type="text" name="mediaUrl" class="form-control" placeholder="https://www.instagram.com/p/3H0-Yqjo7u/">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Enter the Username that commented</label>
                    <input type="text" name="username" id="usernameCheck" class="form-control" placeholder="username" required>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
<?php } elseif ($media["items"][0]["user"]["is_private"] == 1) { ?>
    <hr />
    <p class="text-danger">Uppps! Bu gönderiyi paylaşan profil gizli. Gizli profillerin gönderilerine ulaşılamadığından, yorum da gönderilememektedir.</p>
<?php } elseif (isset($media["items"][0]["comments_disabled"]) && $media["items"][0]["comments_disabled"] == 1) { ?>
    <hr />
    <p class="text-danger">Uppps! Bu gönderi yorumlara kapalı.</p>
<?php
} else { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukYorumBegeniLimitLeft; ?>
            </div>
            <div>
                <strong>Max like comment you can send:</strong> <?php echo $logonPerson->member->yorumBegeniMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <form id="formBegeni" class="form">
                <div class="form-group mb-3">
                    <label class="form-label d-block">Preview:</label>
                    <?php $item = $media["items"][0]; ?>
                    <img src="<?php echo $item["media_type"] == 8 ? "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][1]["url"]))) : "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["image_versions2"]["candidates"][1]["url"]))); ?>" class="img-thumbnail" />
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Comment:</label>
                    <p><?php echo $comment["comment"]; ?></p>
                </div>
                <?php if ($logonPerson->member->yorumBegeniGender === 1) { ?>
                    <div class="form-group mb-3">
                        <label class="form-label">Gender:</label>
                        <select name="gender" class="form-control">
                            <option value="0">Mix</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                <?php } ?>
                <div class="form-group mb-3">
                    <label class="form-label">Comment Number of Likes:</label>
                    <input type="text" name="adet" class="form-control" placeholder="10" value="10">
                    <span class="form-text">Max <?php echo $logonPerson->member->yorumBegeniMaxKredi; ?> You can send comments like.</span>
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
                alert('Comment Enter the number of likes!');
                return false;
            }
            if (countBegeniMax > <?php echo $logonPerson->member->yorumBegeniMaxKredi; ?>) {
                alert('The number of likes you entered is more than your points limit. Max <?php echo $logonPerson->member->begeniMaxKredi; ?> you can get likes!');
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
                $('#tableHasil').show();
                if (data.status == 'error') {
                    $('#userList').prepend('<p class="text-danger">' + data.message + '</p>');
                    sendBegeniComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success">Success</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countBegeni++;
                            $('#formBegeni input[name=adet]').val(countBegeniMax - countBegeni);
                            $('#begeniKrediCount').html(data.begeniKredi);

                        } else {
                            //$('#userList').prepend('<p><a href="#">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
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
            $('#formBegeni input[name=adet]').val('10');
            $('#userList').prepend('<p class="text-success mt-3">Total number of comment likes: ' + countBegeni + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
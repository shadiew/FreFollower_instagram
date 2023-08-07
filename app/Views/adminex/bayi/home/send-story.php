<?php

/**
 * @var \Wow\Template\View      $this
 * @var array                   $media
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$user        = NULL;
if ($this->has("user")) {
    $user = $this->get("user");
}
?>
<script type="text/javascript">
    $(function() {
        $('#username').keyup(function() {
            this.value = $.trim(this.value);
            // this.value = this.value.substring(1);
            while (this.value.charAt(0) === '@' || this.value.charAt(0) === '.') {
                this.value = this.value.substring(1);
            }
        });
    });
</script>
<h4>Premium: Story Views</h4>
<?php if (is_null($user)) { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukStoryLimitLeft; ?>
            </div>
            <div>
                <strong>Max view you can send:</strong> <?php echo $logonPerson->member->storyMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <p>With the story sending tool, you can send the number of story views you specify to the story share of the user you want.</p>
            <form method="post" action="?formType=findUserID" class="form">
                <div class="form-group mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="receh_man" required>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukStoryLimitLeft; ?>
            </div>
            <div>
                <strong>Max view you can send:</strong> <?php echo $logonPerson->member->storyMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <form id="formTakip" class="form">
                <div class="form-group mb-3">
                    <label class="form-label d-block fw-bold"><?php echo "@" . $user["user"]["username"]; ?></label>
                    <img src="<?php echo "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $user["user"]["profile_pic_url"]))); ?>" class="img-thumbnail mb-3" />
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Story Views:</label>
                    <input type="text" name="adet" class="form-control" placeholder="10" value="10">
                    <span class="form-text">Max <?php echo $logonPerson->member->storyMaxKredi; ?> You can send views.</span>
                </div>
                <input type="hidden" name="userID" value="<?php echo $user["user"]["pk"]; ?>">
                <input type="hidden" name="userName" value="<?php echo $user["user"]["username"]; ?>">
                <input type="hidden" name="imageUrl" value="<?php echo str_replace("http:", "https:", $user["user"]["profile_pic_url"]); ?>">
                <input type="hidden" name="_method" value="POST">
                <button type="button" id="formTakipSubmitButton" class="btn btn-success" onclick="sendTakip();">Submit</button>
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
if (!is_null($user)) { ?>
    <script type="text/javascript">
        var countTakip, countTakipMax, bayiIslemIDLast;

        function sendTakip() {
            countTakip = 0;
            countTakipMax = parseInt($('#formTakip input[name=adet]').val());

            if (isNaN(countTakipMax) || countTakipMax <= 0) {
                alert('Enter the number of views');
                return false;
            }

            if (countTakipMax > <?php echo $logonPerson->member->storyMaxKredi; ?>) {
                alert('The maximum number of story views can be <?php echo $logonPerson->member->storyMaxKredi; ?> !');
                return false;
            }

            $('#formTakipSubmitButton').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
            $('#formTakip input').attr('readonly', 'readonly');
            $('#formTakip button').attr('disabled', 'disabled');
            $('#userList').html('');
            sendTakipRC();
        }

        function sendTakipRC(bayiIslemID) {
            url = '?formType=send';
            if (bayiIslemID) {
                url += '&bayiIslemID=' + bayiIslemID;
                bayiIslemIDLast = bayiIslemID;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                data: $('#formTakip').serialize()
            }).done(function(data) {
                $('#tableHasil').show();
                if (data.status == 'error') {
                    $('#userList').prepend('<p class="text-danger">' + data.message + '</p>');
                    sendTakipComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success">Success</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countTakip++;
                            $('#formTakip input[name=adet]').val(countTakipMax - countTakip);
                            $('#takipKrediCount').html(data.takipKredi);

                        } else {
                            //$('#userList').prepend('<p><a href="#">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                        }
                    }
                    if (countTakip < countTakipMax) {
                        sendTakipRC(data.bayiIslemID);
                    } else {
                        sendTakipComplete();
                    }
                }
            }).fail(function() {
                setTimeout(function() {
                    sendTakipRC(bayiIslemIDLast);
                }, 3000);
            });
        }

        function sendTakipComplete() {
            $('#formTakipSubmitButton').html('Submit');
            $('#formTakip input').removeAttr('readonly');
            $('#formTakip button').prop("disabled", false);
            $('#formTakip input[name=adet]').val('10');
            $('#userList').prepend('<p class="text-success mt-3">Story views are sent: ' + countTakip + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
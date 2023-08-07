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
<h4>Premium: Auto Followers</h4>
<?php if (is_null($user)) { ?>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukTakipLimitLeft; ?>
            </div>
            <div>
                <strong>Max followers you can send:</strong> <?php echo $logonPerson->member->takipMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <p>With the follower sending tool, you can send the number of followers you specify to the user you want. All of the followers sent are real users.</p>
            <form method="post" action="?formType=findUserID" class="form">
                <div class="form-group mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="receh_man" required>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
<?php } else { ?>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukTakipLimitLeft; ?>
            </div>
            <div>
                <strong>Max followers you can send:</strong> <?php echo $logonPerson->member->takipMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <form id="formTakip" class="form">
                <div class="form-group text-center mb-1">
                    <h4 class="card-title d-block">Preview</h4>
                    <img src="<?php echo "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $user["user"]["profile_pic_url"]))); ?>" class="img-fluid rounded-circle" />
                </div>

                <div class="pt-1 w-100 text-center">
                    <div class="fw-bold">
                        @<?php echo $user["user"]["username"]; ?> </div>
                </div>
                <div class="px-4 mt-1 w-100">
                    <div class="py-3 d-flex justify-content-between">
                        <div class="text-center w-25">
                            <span class="fw-bold">Posts</span>
                            <span class="d-block"><?php echo $user["user"]["media_count"]; ?></span>
                        </div>

                        <div class="text-center w-25">
                            <span class="fw-bold">Followers</span>
                            <span class="d-block"><?php echo $user["user"]["follower_count"]; ?></span>
                        </div>

                        <div class="text-center w-25">
                            <span class="fw-bold">Following</span>
                            <span class="d-block"><?php echo $user["user"]["following_count"]; ?> </span>
                        </div>

                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">The number of Followers you want to send:</label>
                    <input type="text" name="adet" class="form-control" placeholder="10" value="10">
                    <span class="form-text">Max <?php echo $logonPerson->member->takipMaxKredi; ?> You can send followers.</span>
                </div>
                <?php if ($logonPerson->member->takipGender === 1) { ?>
                    <div class="form-group mb-3">
                        <label class="form-label">Gender:</label>
                        <select name="gender" class="form-control">
                            <option value="0">Mix</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                <?php } ?>
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
                alert('Enter Number of Followers!');
                return false;
            }

            if (countTakipMax > <?php echo $logonPerson->member->takipMaxKredi; ?>) {
                alert('Takipçi adedi max <?php echo $logonPerson->member->takipMaxKredi; ?> olabilir!');
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
            $('#userList').prepend('<p class="text-success mt-3">Total number of followers: ' + countTakip + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
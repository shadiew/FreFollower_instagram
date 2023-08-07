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

<h4>Premium: Auto Comment</h4>
<?php if (is_null($media)) { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukYorumLimitLeft; ?>
            </div>
            <div>
                <strong>Max comment you can send:</strong> <?php echo $logonPerson->member->yorumMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <p>With the comment sending tool, you can send the number of comment you specify to the user you want. All of the comment sent are real users.</p>
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
    <p class="text-danger">Upps! The profile that shared this post is private. Since the posts of private profiles cannot be accessed, comments cannot be sent.</p>
<?php } elseif (isset($media["items"][0]["comments_disabled"]) && $media["items"][0]["comments_disabled"] == 1) { ?>
    <hr />
    <p class="text-danger">Upps! This post is closed to comments.</p>
<?php
} else { ?>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->gunlukYorumLimitLeft; ?>
            </div>
            <div>
                <strong>Max comment you can send:</strong> <?php echo $logonPerson->member->yorumMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <form id="formYorum" class="form">
                <div class="form-group mb-3">
                    <label class="form-label d-block">Preview:</label>
                    <?php $item = $media["items"][0]; ?>
                    <img src="<?php echo $item["media_type"] == 8 ? "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][0]["url"]))) : "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["image_versions2"]["candidates"][0]["url"]))); ?>" class="img-thumbnail" />
                </div>
                <?php if ($logonPerson->member->yorumGender === 1) { ?>
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
                    <label class="form-label">Comments:</label>
                    <?php
                    $sampleComments = array(
                        "Wow fotonya sangat menarik!",
                        "Keren banget ka..",
                        "Follback dong ka..",
                        "So beautiful",
                        "Menarik banget pembahasannya"
                    );
                    ?>
                    <textarea class="form-control" name="yorum" style="height: 250px;"><?php
                                                                                        foreach ($sampleComments as $comment) {
                                                                                            echo $comment . "\n";
                                                                                        }
                                                                                        ?></textarea>
                    <span class="form-text">
                        Write comments with 1 comment per line. You will be sent as many as the number of comments you write. Duplicate comments are not shared. Max <?php echo $logonPerson->member->yorumMaxKredi; ?> comments.
                    </span>
                </div>
                <input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
                <input type="hidden" name="mediaCode" value="<?php echo $item["code"]; ?>">
                <input type="hidden" name="userID" value="<?php echo $item["user"]["pk"]; ?>">
                <input type="hidden" name="userName" value="<?php echo $item["user"]["username"]; ?>">
                <input type="hidden" name="imageUrl" value="<?php echo $item["media_type"] == 8 ? str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][0]["url"]) : str_replace("http:", "https:", $item["image_versions2"]["candidates"][0]["url"]); ?>">
                <input type="hidden" name="_method" value="POST">
                <button type="button" id="formYorumSubmitButton" class="btn btn-success" onclick="sendYorum();">Submit</button>
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
        var countYorum, countYorumMax, clearCommentedIndex, bayiIslemIDLast;

        function sendYorum() {
            countYorumMax = 0;
            textareaYorumLines = $.trim($('#formYorum textarea[name=yorum]').val()).split(/\r\n|\r|\n/);
            textareaYorumLines.forEach(function(line) {
                if ($.trim(line) != '') {
                    countYorumMax++;
                }
            });

            if (countYorumMax === 0) {
                alert('You must add at least 1 comment!');
                return;
            }
            if (countYorumMax > <?php echo $logonPerson->member->yorumMaxKredi; ?>) {
                alert('The number of comments you entered is more than your comment limit. Max <?php echo $logonPerson->member->yorumMaxKredi; ?> you can write a comment.');
                return;
            }
            countYorum = 0;
            $('#formYorumSubmitButton').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
            $('#formYorum input').attr('readonly', 'readonly');
            $('#formYorum button').attr('disabled', 'disabled');
            $('#userList').html('');
            clearCommentedIndex = 1;
            sendYorumRC();
        }

        function sendYorumRC(bayiIslemID) {
            url = '?formType=send&clearCommentedIndex=' + clearCommentedIndex;
            if (bayiIslemID) {
                url += '&bayiIslemID=' + bayiIslemID;
                bayiIslemIDLast = bayiIslemID;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                data: $('#formYorum').serialize()
            }).done(function(data) {
                $('#tableHasil').show();

                clearCommentedIndex = 0;
                if (data.status == 'error') {
                    $('#userList').prepend('<p class="text-danger">' + data.message + '</p>');
                    sendYorumComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success">Success</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countYorum++;
                        } else {
                            //$('#userList').prepend('<p><a href="#">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                        }

                    }
                    if (countYorum < countYorumMax) {
                        sendYorumRC(data.bayiIslemID);
                    } else {
                        sendYorumComplete();
                    }
                }
            }).fail(function() {
                setTimeout(function() {
                    sendYorumRC(bayiIslemIDLast);
                }, 3000);
            });
        }

        function sendYorumComplete() {
            $('#formYorumSubmitButton').html('Submit');
            $('#formYorum input').removeAttr('readonly');
            $('#formYorum button').prop("disabled", false);
            $('#userList').prepend('<p class="text-success mt-3">Total number of comments: ' + countYorum + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
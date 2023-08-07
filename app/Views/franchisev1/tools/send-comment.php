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
	$uyelik      = $logonPerson->member;
	$helper      = new \App\Libraries\Helpers();
    $isMobile    = $helper->is_mobile();
?>
<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="/tools">Tools</a></li>
                <li class="breadcrumb-item active">Auto Comment Gratis</li>
            </ol>

            <!-- HEADER -->
            <div class="jumbotron">
                <h3 class="display-6">Auto Comment Gratis</h3>
                <p class="lead">Fitur yang dapat anda gunakan secara gratis, jika bingung lihat tutorial berikut.</p>
                <a href="https://www.youtube.com/watch?v=2Ovv_aonwpY" target="_blank" class="btn btn-success">
                    Tutorial</a>

                <button class="btn btn-outline-light" type="button">
                    Poin: <span class="badge" id="yorumKrediCount"><?php echo $logonPerson->member["yorumKredi"]; ?>
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


            <?php if(is_null($media)){ ?>
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
                    <h4 class="card-title">Auto Comments</h4>
                    <div class="card-text">
                        <form method="post" action="?formType=findMediaID" class="form">
                            <div class="form-group">
                                <label for="autolikes">Enter the URL you want to comment:</label>
                                <input type="text" name="mediaUrl" class="form-control"
                                    placeholder="https://www.instagram.com/p/3H0-Yqjo7u/" required>
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
            <p class="text-danger"> Uppps! The profile that shared this post was private. The comment was not sent
                because the profile is private or cannot be reached. </p>
            <?php } elseif(isset($media["items"][0]["comments_disabled"]) && $media["items"][0]["comments_disabled"] == 1) {
                    ?>
            <p class="text-danger">Oops! This post has disabled comments.</p>
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
                    <h4 class="card-title">Auto Comments</h4>
                    <div class="card-text">
                    <form id="formYorum" class="form">
                            <div class="form-group">
                                <p for="autolikes">Preview:</p>
                                <?php $item = $media["items"][0]; ?>
                                <img src="/upload/media/<?php echo substr($item["pk"], -1); ?>/<?php echo $item["pk"]; ?>.jpg" class="img-fluid"/>
                           </div>
                            <div class="form-group">
                                <p>Comments:</p>
                                <?php
                                        $sampleComments = array(
                                            "Wihh keren :)",
											"Follback kk..",
											"Mantul cuk.",
											"1 kata aja dah, perfect.",
											"Cakep ^^.",
											"Follback ih!"
                                        );
                                    ?>
                                <div id="commentList">
                                    <?php foreach($sampleComments as $comment) { ?>
                                    <div class="input-group" style="margin-bottom: 5px;">
                                        <span class="input-group-btn">
                                            <button class="btn btn-light" type="button"
                                                onclick="$(this).parent().parent().remove();"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </span>
                                        <input type="text" class="form-control" name="yorum[]"
                                            value="<?php echo $comment; ?>">
                                    </div>
                                    <?php } ?>
                                </div>
                                <span class="help-block"><a href="javascript:void(0);" onclick="addNewComment();">+
                                        Add</a></span>
                                <span class="help-block">Write a comment in each box, 1 box = 1 comment.</span>
                                <small id="emailHelp" class="form-text text-muted">Pastikan akun anda
                                    tidak di private.</small>
                            </div>
                            <input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
                                <input type="hidden" name="mediaCode" value="<?php echo $item["shortcode"]; ?>">
                                <button type="button" id="formYorumSubmitButton" class="btn btn-primary" onclick="sendYorum();"><i class="fa fa-rocket"></i> Submit</button>
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
var countYorum, countYorumMax, clearCommentedIndex;

function addNewComment() {
    html =
        '<div class="input-group" style="margin-bottom: 5px;"><span class="input-group-btn"><button class="btn btn-light" type="button" onclick="$(this).parent().parent().remove();"><i class="fas fa-trash-alt"></i></button></span><input type="text" class="form-control" name="yorum[]" value=""></div>';
    $('#commentList').append(html);
}

function sendYorum() {
    countYorumMax = 0;
    $("#formYorum input[name='yorum[]']").each(function() {
        if ($.trim($(this).val()) != '') {
            countYorumMax++;
        }
    });
    if (countYorumMax === 0) {
        alert('At least 1 comment!');
        return;
    }
    if (countYorumMax > <?php echo $logonPerson->member->yorumKredi; ?>) {
        return confirm(
            'The number of comments you have entered exceeds your point. Keep sending <?php echo $logonPerson->member->yorumKredi; ?> Comments?'
        );
    }
    countYorum = 0;
    $('#formYorumSubmitButton').html('<i class="fa fa-spinner fa-spin fa-lg"></i> Sending...');
    $('#formYorum input').attr('readonly', 'readonly');
    $('#formYorum button').attr('disabled', 'disabled');
    $('#userList').html('');
    clearCommentedIndex = 1;
    sendYorumRC();
}

function sendYorumRC() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '?formType=send&clearCommentedIndex=' + clearCommentedIndex,
        data: $('#formYorum').serialize()
    }).done(function(data) {
        clearCommentedIndex = 0;
        if (data.status == 'error') {
            $('#userList').prepend('<p class="text-danger">' + data.message + '</p>');
            sendYorumComplete();
        } else {
            for (var i = 0; i < data.users.length; i++) {
                var user = data.users[i];
                if (user.status == 'success') {
                    $('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick +
                        '</a> try to comment your post. Result: <span class="badge badge-success">Success</span></p>'
                    );
                    countYorum++;
                    $('#yorumKrediCount').html(data.yorumKredi);

                } else {
                    //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                }

            }
            if (countYorum < countYorumMax) {
                sendYorumRC();
            } else {
                sendYorumComplete();
            }
        }
    });
}

function sendYorumComplete() {
    $('#formYorumSubmitButton').html('Submit');
    $('#formYorum input').removeAttr('readonly');
    $('#formYorum button').prop("disabled", false);
    $('#userList').prepend('<p class="text-success">Total Comments Sent: ' + countYorum + '</p>');
}
</script>
<?php }
    $this->endSection(); ?>

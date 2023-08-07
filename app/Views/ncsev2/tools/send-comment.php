<?php

/**
 * @var \Wow\Template\View      $this
 * @var array                   $media
 * @var \App\Models\LogonPerson $logonPerson
 */
$media       = NULL;
if ($this->has("media")) {
    $media = $this->get("media");
}
$uyelik      = $logonPerson->member;
$helper      = new \App\Libraries\Helpers();
$isMobile    = $helper->is_mobile();

$logonPerson = $this->get("logonPerson");
if (!$logonPerson->isLoggedIn()) {
    return;
}
$actual_link = "$_SERVER[REQUEST_URI]";
$pathurl = "";
$poin = "";
$url = substr($actual_link, 7);
$url = strtok($url, '/');

if ($url == "send-follower") {
    $pathurl = "follower";
    $poin = "takipKredi";
    $id = "takipKrediCount";
}
if ($url == "send-like") {
    $pathurl = "like";
    $poin = "begeniKredi";
    $id = "begeniKrediCount";
}
if ($url == "send-comment") {
    $pathurl = "comment";
    $poin = "yorumKredi";
    $id = "yorumKrediCount";
}
?>
<div class="bg-light py-5">
    <div class="container py-3">
        <div class="row">
            <div class="col-md-8">

                <?php $this->renderView("tools/inc/tools/userbar"); ?>

                <!--tool-->
                <div class="card shadow-sm text-center card-primary card-outline bg-body">
                    <div class="card-body">
                        <div class="container py-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="fs-3">Tools</div>
                                    <h1 id="highlite" class="bf">Free Instagram Comments</h1>
                                    <p class="px-3">You can get 5 free Instagram Comments every hour, You can
                                        do this every
                                        single hour
                                        without any issue. There are no limits.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-3 bg-darkness text-light">
                        <div class="row text-center">
                            <div class="col-12" style="font-size:14px; font-weight:600;">
                                <span class="text-warning"><i class="fa-solid fa-circle-check"></i></span> All
                                services on this website
                                are 100%
                                free and points will be reset every an hour.
                            </div>
                        </div>
                    </div>
                </div>
                <!--tool end-->

                <div class="card mt-4 card-primary card-outline bg-body align-items-center">
                    <div class="card-body">
                        <div class="row p-2">
                            <h2 class="text-center bf">Why Should You Get <span id="highlite">Free Instagram Comments ?</span></h2>
                    <div class="col-md-12 text-start mt-4">
                      <p>An Instagram comment is one of two main ways a user can engage with the content they see. Although liking is also crucial, comments are a unique and more active form of engagement and require more effort.</p>

                      <p>
                        Instagram marketers, brands, and influencers are generally the ones who make use of the comment section as an engagement and feedback source since comments are more relevant. If you also need comments for your
                        Instagram posts, you can get free Instagram comments from our tools.
                      </p>

                      <p>
                        free instagram comments is useful to make your posts look popular. People tend to take a closer look at the posts full of comments compared to photos with no comments. So when you have comments under your posts, you
                        will attract many people.
                      </p>
                      <p>Some of them will keep the attraction going by liking or making comments, and perhaps some of them will follow you just because you give the impression that you are already popular.</p>

                      <p>So, product from us, chances are high to receive organic likes, comments, and followers. As a result, your profile will be more active than ever before.</p>

                      <h3 class="bf">Free Instagram Comments</h3>
                      <p>
                        Instagram comments are the most valuable currency on the site. For a user to leave a comment, they have to have had really strong feelings about the image or video. This signals to the Instagram algorithm that people
                        are having real and intense reactions to your posts, which is a great thing.
                      </p>
                      <p>
                        Instagram is now more likely to promote your posts and page as a whole. Comments are the best form of engagement, because your audience is literally talking to you. It makes your page look lively and your community a
                        thriving one. 5 free comments will undoubtedly make any one of your posts look even better than it already is.
                      </p>
                      <p>
                        Our special free Instagram comments package allows you to write your own custom Instagram comments, decorated with fun emojis. We deliver 5 free Instagram comments to your posts immediately after you place your
                        order.
                      </p>
                      If you have any questions, our support team is here to help! We have a detailed FAQ section that should cover everything you need, but you are always welcome to contact us directly. We always answer as soon as we
                      possibly can, and you can reach us on email 24/7.
                    </div>
                        </div>
                    </div>
                </div>

                <?php if (is_null($media)) { ?>
                    <div class="card bg-white content" style="height: 350px !important;">
                        <div class="card-body">
                            <div class="row p-2">
                                <h3 class="fw-bold mb-4">Free Instagram Comments</h3>
                                <form method="post" action="?formType=findMediaID" class="form">
                                    <div class="form-group">
                                        <label class="form-label">Enter the URL:</label>
                                        <br>
                                        <div class="input-group mb-3">
                                            <input type="text" name="mediaUrl" class="form-control" placeholder="https://www.instagram.com/p/3H0-Yqjo7u/" required>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex mt-3">
                                        <div class="p-2 flex-fill px-0 d-none d-md-block">
                                            <small id="username" class="form-text text-link">
                                                Make sure your account is not private.
                                            </small>
                                        </div>
                                        <div class="p-2 flex-fill d-flex flex-row bd-highlight mb-3 px-0">
                                            <button type="submit" class="btnn2 darkness w-50 mx-1 ms-auto px-4">
                                                Check</button>
                                            <button class="btnn2 yel w-25 mx-1 me-0" disabled>
                                                <i class="fa-solid fa-coins"></i> <span id="<?php echo $id; ?>">
                                                    <?php echo $logonPerson->member["$poin"]; ?>
                                                </span></button>
                                        </div>
                                    </div>
                                    <?php $this->renderView("tools/inc/tools/ads"); ?>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($media["items"][0]["user"]["is_private"] == 1) { ?>
                    <hr />
                    <p class="text-danger">Uppps! Profil diprivat! jangan privat profil untuk menggunakan tool ini.</p>
                <?php } else { ?>
                    <div class="card bg-white content" style="height: auto !important;">
                        <div class="card-header darkness">
                            <div class="p-2 fw-bold fs-5">Free Comments</div>
                        </div>
                        <div class="card-body">
                            <div class="row p-2 my-3">
                                <div class="my-3">
                                    <div class="col-12">
                                        <div class="fw-bold mb-2 d-block">Preview:</div>
                                        <?php $item = $media["items"][0]; ?>
                                        <img src="<?php echo $item["media_type"] == 8 ? "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][0]["url"]))) : "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["image_versions2"]["candidates"][0]["url"]))); ?>" alt="" style="height:auto;" class="img-thumbnail w-50">
                                    </div>
                                </div>
                                <form id="formYorum" class="form">
                                    
                                    <div class="form-group mb-3">
                                        <p>Your Comments:</p>
                                        <?php
                                        $sampleComments = array(
                                            "Wihh keren :)",
                                            "Follback kk..",
                                            "Mantul cuk.",
                                            "1 kata aja dah, perfect.",
                                            "Cakep ^^."
                                        );
                                        ?>
                                        <div id="commentList">
                                            <?php foreach ($sampleComments as $comment) { ?>
                                                <div class="input-group" style="margin-bottom: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-light" type="button" onclick="$(this).parent().parent().remove();"><i class="fas fa-trash-alt"></i></button>
                                                    </span>
                                                    <input type="text" class="form-control" name="yorum[]" value="<?php echo $comment; ?>">
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <span class="form-text"><a href="javascript:void(0);" onclick="addNewComment();">+
                                                Add</a></span>
                                        <span class="form-text">Write a comment in each box, 1 box = 1 comment.</span>
                                    </div>
                            
                            
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">The number of comments you want to send:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="adet" class="form-control" placeholder="10" value="<?php echo Wow::get("ayar/reUyeYorumKredi") ?>">
                                        </div>
                                    </div>
                                    
                                   
                                    <input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
                                    <input type="hidden" name="mediaCode" value="<?php echo $item["shortcode"]; ?>">
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 flex-fill px-0 d-none d-md-block">
                                            <small id="username" class="form-text text-link">
                                                Make sure your account is not private.
                                            </small>
                                        </div>
                                        <div class="p-2 flex-fill d-flex flex-row bd-highlight px-0">
                                            <button type="submit" id="formYorumSubmitButton" class="btnn2 darkness w-50 mx-1 ms-auto px-4 btn" onclick="sendYorum();">
                                                Submit</button>
                                            <button class="btnn2 yel w-25 mx-1 me-0" disabled>
                                                <i class="fa-solid fa-coins"></i> <span id="<?php echo $id; ?>">
                                                    <?php echo $logonPerson->member["$poin"]; ?>
                                                </span></button>
                                        </div>
                                    </div>
                                     <?php $this->renderView("tools/inc/tools/ads"); ?>
                                </form>
                            </div>
                            <div id="dropDownHasil" class="card p-4" style="box-shadow:none; display:none;">
                                <nav class="d-grid gap-2 col-12">
                                    <?php $this->renderView("tools/inc/tools/reset-poin"); ?>
                                    <a href="#tableHasil" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                        <i class="far fa-check-circle fa-2x mr-2"></i>
                                        <div class="text-start">
                                            <strong class="d-block text-success">Success: <span id="successList"></span></strong>
                                            <small class="text-muted">Successfully sent comments</small>
                                        </div>
                                    </a>
                                    <a href="#" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                        <i class="fas fa-coins fa-2x mr-2"></i>
                                        <div class="text-start">
                                            <strong class="d-block text-success" id="yorumKrediCount">Credits:
                                                <?php echo $logonPerson->member["$poin"]; ?></strong>
                                            <small class="text-muted">Your available credit</small>
                                        </div>
                                    </a>
                                </nav>
                            </div>

                            <div id="button_telegram_follow" style="display:none;">
                                <p class="text-center mt-3 mb-2"><strong>Get Free Credits</strong> by Following our
                                    telegram channel below!</p>
                                <a href="<?php echo Wow::get("ayar/channel_telegram"); ?>" target="_blank" class="btnn2 darkness w-100 border-0 mb-3 p-2">
                                    <i class="fab fa-telegram nav-icon"></i>
                                    Telegram Channel
                                </a>
                            </div>

                            <div class="table-responsive mt-3">
                                <table id="tableHasil" class="table table-striped" style="display:none;">
                                    <thead class="table-dark">
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
                    </div>
                    <?php $this->renderView("tools/inc/tools/timer-reset"); ?>
                <?php } ?>
            </div>

            <!--start sidebar-->
            <div class="col-md-4 text-center">
                <?php $this->renderView("tools/sidebar"); ?>
            </div>
            <!--sitebar end-->
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#autoComment').change(function() {
            var valuelink = $('#autoComment').val();
            let idpost = valuelink.split('/', [5]).pop();
            let linksub = valuelink.split('/', [4]).pop();

            if (linksub == "reel" || linksub == "tv") {
                linksub = "p";
            } else {
                linksub;
            }
            let linkInstagram = "https://www.instagram.com/" + linksub + "/" + idpost;

            $('#autoComment').val(linkInstagram);
        });
    });
</script>
<?php $this->renderView("tools/inc/tools/timer-reset"); ?>
<?php $this->section("section_scripts");
$this->parent();
if (!is_null($media) && $media["items"][0]["user"]["is_private"] != 1) { ?>
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
                alert('Setidaknya masukin 1 komentar');
                return;
            }
            if (countYorumMax > <?php echo $logonPerson->member->yorumKredi; ?>) {
                confirm(
                    'The number of comments you have entered exceeds your point. Keep sending <?php echo $logonPerson->member->yorumKredi; ?> Comments?'
                );
            }
            countYorum = 0;
            $('#formYorumSubmitButton').html('<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>');
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
                data: $('#formYorum').serialize(),
                statusCode: {
                    500: function() {
                        alert('sorry, we are having trouble. Please try again! [500]');
                        sendErrorComplete();
                    },
                    502: function() {
                        alert('sorry, we are having trouble. Please try again! [500]');
                        sendErrorComplete();
                    },
                    403: function() {
                        alert('sorry, we are having trouble. Please try again! [403]');
                        sendErrorComplete();
                    },
                    503: function() {
                        alert('sorry, we are having trouble. Please try again! [503]');
                        sendErrorComplete();
                    },
                    400: function() {
                        alert('sorry, we are having trouble. Please try again! [400]');
                        sendErrorComplete();
                    }
                },
                beforeSend: function(request) {
                    $('#successList').html('');
                },
            }).done(function(data) {
                $('#tableHasil').show();
                $('#resetpoin').show();
                $('#dropDownHasil').show();

                clearCommentedIndex = 0;
                if (data.status == 'error') {
                    $('#userList').prepend('<div class="alert alert-warning small" role="alert">' +
                        'Jika poin kamu masih ada namun followers yang masuk tidak semuanya, ' +
                        'Cobalah submit lagi sampai poin kamu habis' + '</div>');
                    sendYorumComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#" class="text-primary">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success p-1">Berhasil</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countYorum++;
                            $('#yorumKrediCount').html(data.yorumKredi);
                            $('#yorumKrediCount2').html(data.yorumKredi);

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
            $('#successList').prepend(countYorum);
        }

        function sendErrorComplete() {
            $('#formTakipSubmitButton').html('Submit');
            $('#formTakip input').removeAttr('readonly');
            $('#formTakip button').prop("disabled", false);
            $('#formTakip input[name=adet]').val('10');
            $('#userList').prepend('<p class="my-2 text-danger">Ups! Server sedang ada gangguan. Coba lagi atau lapor di grup telegram. ' + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
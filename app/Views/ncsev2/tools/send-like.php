<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $media
 */
$media = NULL;
if ($this->has("media")) {
    $media = $this->get("media");
}
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
                                    <h1 id="highlite" class="bf">Free Instagram Likes</h1>
                                    <p class="px-3">You can get 15 free Instagram Likes every hour, You can
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
                            <h2 class="text-center bf">Reasons why you look <br> for <span id="highlite">Free
                                    Instagram Likes</span>
                            </h2>
                            <div class="col-md-12 text-start mt-4">
                                <h3 class="bf">Enhance Web Business</h3>
                                <p>Instagram is one of the popular and strongest social media websites that
                                    utmost of the companies make use of to enhance online business of their
                                    website. If you buy real and active Free Instagram likes also chances are
                                    that you'll have further organic followers for your point.</p>
                                <h3 class="bf">Stay ahead of your challengers</h3>
                                <p>Owing to the fierce competition going on in the request, it's veritably
                                    important to increase Free Instagram Likes to stay ahead of your
                                    challengers. Small, medium and large sized business buy followers and likes
                                    to ameliorate brand image of their company, products and services and to
                                    separate themselves from the rest of the crowd.</p>
                                <h3 class="bf">Grow your online presence</h3>
                                <p>Gaining great deal of free instagram likes is veritably important to spread
                                    the word for promoting your business, make further connections and get
                                    featured on Instagram with obviously further number of druggies. It'll
                                    ultimately profit your business in the long run. By achieving further number
                                    of likes on the profile runner of Instagram, other druggies will notice you
                                    and would be interested to learn what your online business is about.</p>
                                <h3 class="bf">Increase leads and transformations</h3>
                                <p>When it comes to buying further number of Instagram likes also it's
                                    veritably important to hire professional and secure social media marketing
                                    company to bring in further number of targeted prospects. Those websites
                                    which have large number of quality likes on Instagram has increased chances
                                    of erecting further leads and transformations, which eventually leads to
                                    increased deals.</p>
                                <h3 class="bf">Promote your brand/product/service</h3>
                                <p>Use Instagram to promote your products/ services. Also, use it for
                                    participating prints that includes your company's announcements if in case
                                    your company is certain to get further number of likes. This will help in
                                    adding fashionability of your website in a great way.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (is_null($media)) { ?>
                    <div class="card bg-white content" style="height: 350px !important;">
                        <div class="card-body">
                            <div class="row p-2">
                                <h3 class="fw-bold mb-4">Free Instagram Likes</h3>
                                <form method="post" action="?formType=findMediaID" class="form">
                                    <div class="form-group">
                                        <label class="form-label">Enter the URL:</label>
                                        <br>
                                        <div class="input-group mb-3">
                                            <input type="text" name="mediaUrl" class="form-control" aria-describedby="autolikes" placeholder="https://www.instagram.com/p/3H0-Yqjo7u/" required>
                                        </div>
                                    </div>
                                  
                                    <div class="d-flex mt-3 position-relative">
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
                                  <div class="container">
                                    <div class="row">
                                    <?php $this->renderView("tools/inc/tools/ads"); ?>
                                    </div>
                                  </div>
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
                            <div class="p-2 fw-bold fs-5">Free Likes</div>
                        </div>
                        <div class="card-body">
                            <div class="row p-2 my-3">
                                <div class="my-3">
                                    <div class="col-12 text-center">
                                        <div class="fw-bold mb-2 d-block">Preview:</div>
                                        <?php $item = $media["items"][0]; ?>
                                        <img src="<?php echo $item["media_type"] == 8 ? "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["carousel_media"][0]["image_versions2"]["candidates"][0]["url"]))) : "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $item["image_versions2"]["candidates"][0]["url"]))); ?>" alt="" style="height:auto;" class="img-thumbnail w-50">
                                    </div>
                                </div>
                                <form id="formBegeni" class="form">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">The number of likes you want to send:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="adet" class="form-control" placeholder="10" value="<?php echo Wow::get("ayar/reUyeBegeniKredi") ?>">
                                        </div>
                                    </div>
                                    <?php $this->renderView("tools/inc/tools/ads"); ?>
                                    <input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
                                    <input type="hidden" name="mediaCode" value="<?php echo $item["shortcode"]; ?>">
                                    <input type="hidden" name="mediaUsername" value="<?php echo $item["username"]; ?>">
                                    <input type="hidden" name="mediaUserID" value="<?php echo $item["pk"]; ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 flex-fill px-0 d-none d-md-block">
                                            <small id="username" class="form-text text-link">
                                                Make sure your account is not private.
                                            </small>
                                        </div>
                                        <div class="p-2 flex-fill d-flex flex-row bd-highlight px-0">
                                            <button type="submit" id="formBegeniSubmitButton" class="btnn2 darkness w-50 mx-1 ms-auto px-4 btn" onclick="sendBegeni();">
                                                Submit</button>
                                            <button class="btnn2 yel w-25 mx-1 me-0" disabled>
                                                <i class="fa-solid fa-coins"></i> <span id="<?php echo $id; ?>">
                                                    <?php echo $logonPerson->member["$poin"]; ?>
                                                </span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="dropDownHasil" class="card p-4" style="box-shadow:none; display:none;">
                                <nav class="d-grid gap-2 col-12">
                                    <?php $this->renderView("tools/inc/tools/reset-poin"); ?>
                                    <a href="#tableHasil" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                        <i class="far fa-check-circle fa-2x mr-2"></i>
                                        <div class="text-start">
                                            <strong class="d-block text-success">Success: <span id="successList"></span></strong>
                                            <small class="text-muted">Successfully sent likes</small>
                                        </div>
                                    </a>
                                    <a href="#" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                        <i class="fas fa-coins fa-2x mr-2"></i>
                                        <div class="text-start">
                                            <strong class="d-block text-success" id="likeCountResult">Credits:
                                                <?php echo $logonPerson->member["begeniKredi"]; ?></strong>
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
        $('#autolikes').change(function() {
            var valuelink = $('#autolikes').val();
            let idpost = valuelink.split('/', [5]).pop();
            let linksub = valuelink.split('/', [4]).pop();

            if (linksub == "reel" || linksub == "tv") {
                linksub = "p";
            } else {
                linksub;
            }
            let linkInstagram = "https://www.instagram.com/" + linksub + "/" + idpost;

            $('#autolikes').val(linkInstagram);
        });
    });
</script>
<?php $this->section("section_scripts");
$this->parent();
if (!is_null($media) && $media["items"][0]["user"]["is_private"] != 1) { ?>
    <script type="text/javascript">
        var countBegeni, countBegeniMax;

        function sendBegeni() {
            countBegeni = 0;
            countBegeniMax = parseInt($('#formBegeni input[name=adet]').val());
            if (isNaN(countBegeniMax) || countBegeniMax <= 0) {
                alert('Masukkan jumlah likes!');
                return false;
            }
            $('#formBegeniSubmitButton').html('<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>');
            $('#formBegeni input').attr('readonly', 'readonly');
            $('#formBegeni button').attr('disabled', 'disabled');
            $('#userList').html('');
            sendBegeniRC();
        }

        function sendBegeniRC() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '?formType=send',
                data: $('#formBegeni').serialize(),
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
                $('#button_telegram_like').show();

                if (data.status == 'error') {
                    $('#userList').prepend('<div class="alert alert-warning small" role="alert">' +
                        'If your points are still there but not all of the followers who enter, try ' +
                        'submitting again until your points run out' + '</div>');
                    sendBegeniComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#" class="text-primary">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success p-1">Berhasil</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countBegeni++;
                            $('#formBegeni input[name=adet]').val(countBegeniMax - countBegeni);
                            $('#begeniKrediCount').html(data.begeniKredi);
                            $('#likeCountResult').html(data.begeniKredi);
                            $('#likenavbar').html(data.begeniKredi);

                        } else {
                            //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                        }
                    }
                    if (countBegeni < countBegeniMax) {
                        sendBegeniRC();
                    } else {
                        sendBegeniComplete();
                    }
                }
            });
        }


        function sendBegeniComplete() {
            $('#formBegeniSubmitButton').html('Submit');
            $('#formBegeni input').removeAttr('readonly');
            $('#formBegeni button').prop("disabled", false);
            $('#formBegeni input[name=adet]').val('10');
            $('#successList').prepend(countBegeni);

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
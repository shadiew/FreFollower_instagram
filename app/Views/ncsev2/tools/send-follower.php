<?php

/**
 * @var \Wow\Template\View      $this
 * @var array                   $media
 */
$user        = NULL;
if ($this->has("user")) {
    $user = $this->get("user");
}
$logonPerson = $this->get("logonPerson");
if (!$logonPerson->isLoggedIn()) {
    return;
}
$helper = new \App\Libraries\Helpers();
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
<style>
.swal2-styled.swal2-confirm {
    border-style: solid;
    border-width: 0.125em;
    margin: 0 0 0.875em 0;
    transition-timing-function: ease-in;
    border-radius: 0.5em;
    border-color: #111;
    box-shadow: 0 0.375em 0 #222;
    user-select: none;
    background: #c62c2e;
    cursor: pointer;
    padding: calc((4em - (1em * 1.5) - (0.125em * 2) - 0.375em) / 2) calc(1em * 1.5);
    vertical-align: middle;
    text-align: center;
    text-decoration: none;
    color: #183153;
    font-weight: 600;
    display: inline-block;
}
.swal2-styled.swal2-confirm a {
 color: #fff;
 text-decoration: none !important;
}
</style>
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
                                    <h1 id="highlite" class="bf">Free Instagram Followers</h1>
                                    <p class="px-3">You can get 15 free Instagram followers every hour, You can
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
                            <h2 class="text-center bf">Tips Get More <span id="highlite">Engagement on
                                    Instagram</span>
                            </h2>
                            <div class="col-md-12 text-start mt-4">
                                <h3 class="bf">Create carousel posts</h3>
                                <p>If you're going to share an in-feed post, make it a carousel. It's
                                    confirmed they obtain a better engagement fee, compared to single in-feed
                                    pictures
                                    and movies. They attract much more engagements when all 10 carousel slides
                                    are used.
                                </p>
                                <h3 class="bf">show your face on Instagram</h3>
                                <p>If it really works with your area of interest, you should make the effort to
                                    show
                                    your face all through your Instagram content. Instagram posts that feature a
                                    human
                                    face are 40% more likely to obtain likes and 36% extra more likely to appeal
                                    to
                                    feedback.</p>
                                <p>People like individuals. Your followers want to be able to connect an
                                    Instagram
                                    handle to a face. It feels more personal which implies they're extra likely
                                    to
                                    have interaction together with your content material.</p>
                                <h3 class="bf">Time your posts wisely</h3>
                                <p>Post at peak times when you understand nearly all of your viewers is online.
                                    The more
                                    individuals who see your posts, the more engagements you'll likely obtain.
                                    Learn what your peak occasions are by checking your follower analytics.</p>
                                <p>Your insights inform you what day of the week and what time of the day your
                                    followers
                                    are most lively. Also bear in mind where the majority of your viewers is
                                    located, to
                                    make sure you publish throughout their local hours. This will allow you to
                                    schedule
                                    your content material at a time that will maximise your engagements. Learn
                                    extra
                                    about why you need to schedule your content here.</p>
                                <h3 class="bf">Use Instagram to its full potential</h3>
                                <p>When went reside with Instagram, they confirmed that essentially the most
                                    profitable
                                    influencers on the platform use all the app's features available to them.
                                    This
                                    consists of in-feed posts, Stories, Lives, IGTVs and Reels.</p>
                                <p>The extra you use Instagram, the more engagements your account will obtain.
                                    And yes,
                                    ding, ding ding, your engagement fee will get a nice big increase from all
                                    those
                                    follower interactions.</p>
                                <h3 class="bf">Use your captions to interact followers</h3>
                                <p>Keep your audience in your post for longer by giving them an enticing caption
                                    to
                                    interact with. Not solely will you further connect together with your
                                    audience,
                                    however you're extra prone to get them to interact if your caption
                                    encourages
                                    them to like, share, save and comment.</p>
                                <h3 class="bf">Use relevant and researched hashtags</h3>
                                <p>Hashtags are an excellent software to use to increase your attain, place your
                                    content
                                    material in front of extra people and enhance your engagement rate.</p>
                                <p>Use your limited variety of hashtags correctly and only add ones which are
                                    related to
                                    your content material. Don't use #stopmotion on a time-lapse video. And
                                    don't neglect to make use of calendar-specific hashtags such a
                                    #WorldPhotographyDay and #WorldSocialMediaDay. These will insert your
                                    content into
                                    trending social conversations and assist increase your engagements.</p>
                                <p>Also, remember to do your analysis. Check that it's a trending hashtag and
                                    utilized by a lot of other creatives. If members of your audience observe
                                    the
                                    hashtag then that's additionally a great signal. And be sure to verify what
                                    hashtags other creators in your space are using of their content. Learn
                                    extra about
                                    tips on how to use hashtags on Instagram right here.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (is_null($user)) { ?>
                    <div class="card bg-white content" style="height: 350px !important;">
                        <div class="card-body">
                            <div class="row p-2">
                                <h3 class="fw-bold mb-4">Free Instagram Followers</h3>
                                <form method="post" action="?formType=findUserID" class="form">
                                    <div class="form-group">
                                        <label class="form-label">Instagram username you want to add:</label>
                                        <br>
                                        <div class="input-group mb-0">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                            <input type="text" name="username" id="usernameCheck" class="form-control" placeholder="username" aria-describedby="username" required="">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex mt-3 align-items-center">
                                        <div class="p-2 flex-fill px-0 d-none d-md-block">
                                            <small id="username" class="form-text text-link">
                                                Make sure your account is not private.
                                            </small>
                                        </div>
                                        <div class="p-2 flex-fill d-flex flex-row bd-highlight px-0">
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

                <?php } else { ?>
                    <div class="card bg-white content" style="height: auto !important;">
                        <div class="card-header darkness">
                            <div class="p-2 fw-bold fs-5">Free Followers</div>
                        </div>
                        <div class="card-body">
                            <div class="row p-2 my-3">
                                <div class="col-12 text-center mb-3">
                                    <img src="<?php echo "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $user["user"]["profile_pic_url"]))); ?>" alt="" style="width: 100px; height:auto;" class="rounded-circle mx-auto">
                                    <div class="d-block">@<?php echo $user["user"]["username"]; ?></div>
                                </div>

                                <div class="col-4 text-center">
                                    <p class="bf">Post</p>
                                    <p> <?php echo $user["user"]["media_count"]; ?> <span class="d-block"></p>
                                </div>
                                <div class="col-4 text-center">
                                    <P class="bf">Followers</P>
                                    <p> <?php echo $user["user"]["follower_count"]; ?></p>
                                </div>
                                <div class="col-4 text-center">
                                    <p class="bf">Following</p>
                                    <p> <?php echo $user["user"]["following_count"]; ?></p>
                                </div>

                                <form id="formTakip" class="form">
                                    <div class="form-group">
                                        <label class="form-label bf">The number of followers you want to
                                            add:</label>
                                        <br>
                                        <div class="input-group mb-3">
                                            <input type="text" name="adet" class="form-control" placeholder="10" value="<?php echo Wow::get("ayar/reUyeTakipKredi") ?>">
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="userID" value="<?php echo $user["user"]["pk"]; ?>">
                                    <input type="hidden" name="userName" value="<?php echo $user["user"]["username"]; ?>">
                                    <div class="d-flex mt-3 align-items-center">
                                        <div class="p-2 flex-fill px-0 d-none d-md-block">
                                            <small id="username" class="form-text text-link">
                                                Make sure your account is not private.
                                            </small>
                                        </div>
                                        <div class="p-2 flex-fill d-flex flex-row bd-highlight px-0">
                                            <button type="button" id="formTakipSubmitButton" class="btnn2 darkness w-50 mx-1 ms-auto px-4 btn" onclick="sendTakip();">
                                                Submit
                                            </button>
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
                                            <small class="text-muted">Successfully sent followers</small>
                                        </div>
                                    </a>
                                    <a href="#" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                        <i class="fas fa-coins fa-2x mr-2"></i>
                                        <div class="text-start">
                                            <strong class="d-block text-success" id="takipKrediCountResult">Credits:
                                                <?php echo $logonPerson->member["takipKredi"]; ?></strong>
                                            <small class="text-muted">Your available credit</small>
                                        </div>
                                    </a>
                                </nav>
                            </div>

                    
                            
                            <div id="button_telegram_follow" style="display:none;">
                    <p class="text-center mt-3 mb-2"><strong>Get Free Credits</strong> by Following our telegram channel below!</p>
                    <div class="row">
                      <div class="col-md-6">
                        <a href="<?php echo Wow::get("ayar/channel_telegram"); ?>" target="_blank" class="btnn2 darkness w-100 border-0 mb-3 p-2">
                          <i class="fab fa-telegram nav-icon"></i>
                          Telegram Channel
                        </a>
                      </div>
                      <div class="col-md-6">
                        <a href="https://www.instagram.com/by.abdul_karim" target="_blank" class="btnn2 blood w-100 border-0 mb-3 p-2">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                            <path
                              d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"
                            />
                          </svg>
                          Instagram admin
                        </a>
                      </div>
                    </div>
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
<?php $this->section("section_scripts");
$this->parent();
if (!is_null($user)) { ?>
    <script type="text/javascript">
        var countTakip, countTakipMax;

        function sendTakip() {
            countTakip = 0;
            countTakipMax = parseInt($('#formTakip input[name=adet]').val());

            if (isNaN(countTakipMax) || countTakipMax <= 0) {
                alert('Enter Number of Followers!');
                return false;
            }

            $('#formTakipSubmitButton').html('<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>');
            $('#formTakip input').attr('readonly', 'readonly');
            $('#formTakip button').attr('disabled', 'disabled');
            $('#userList').html('');
            sendTakipRC();
        }

        function sendTakipRC() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '?formType=send',
                data: $('#formTakip').serialize(),
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
                $('#button_telegram_follow').show();

                if (data.status == 'error') {
                    $('#userList').prepend('<div class="alert alert-warning small" role="alert">' +
                        'If your points are still there, ' +
                        'Try submitting again until your points run out.' + '</div>');

                    sendTakipComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#" class="text-primary">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success p-1">Success</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countTakip++;
                            $('#formTakip input[name=adet]').val(countTakipMax - countTakip);
                            $('#takipKrediCount').html(data.takipKredi);
                            $('#takipKrediCountResult').html(data.takipKredi);

                        } else {
                            //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> Pengguna Mencoba. Hasil: <span class="label label-danger">Gagal</span></p>');
                        }
                    }
                    if (countTakip < countTakipMax) {
                        sendTakipRC();
                    } else {
                        sendTakipComplete();
                        alertSuccess();
                    }
                }
            });
        }
        
        function alertSuccess() {
            const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-success text-decoration-none'
              },
              buttonsStyling: false
            })
            
            swal.fire({
              icon: 'success',
              title: 'Congrats!',
              text: 'You have got free followers. If you want to get more, click the button below',
              confirmButtonText: '<a href="https://socialboostergram.com/"><i class="fa-solid fa-rocket"></i> Buy Followers</a>',
              reverseButtons: true
            })
        }


        function sendTakipComplete() {
            $('#formTakipSubmitButton').html('Submit');
            $('#formTakip input').removeAttr('readonly');
            $('#formTakip button').prop("disabled", false);
            $('#formTakip input[name=adet]').val('10');
            $('#successList').prepend(countTakip);
        }

        function sendErrorComplete() {
            $('#formTakipSubmitButton').html('Submit');
            $('#formTakip input').removeAttr('readonly');
            $('#formTakip button').prop("disabled", false);
            $('#formTakip input[name=adet]').val('10');
            $('#userList').prepend('<p class="my-2 text-danger">Oops! Server is down. Try again or report in the telegram group. ' + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
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
if ($url == "story-view") {
    $pathurl = "story";
    $poin = "storyKredi";
    $id = "storyKrediCount2";
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
                                    <h1 id="highlite" class="bf">Free Instagram Story View</h1>
                                    <p class="px-3">You can get 50 free Instagram Story View every hour, You can do this every single hour without any issue. There are no limits.</p>
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
                            <h2 class="text-center bf">6 best Reasons Why you need <span id="highlite">more story View instagram</span></h2>
                            <div class="col-md-12 text-start mt-4">
                                <h3 class="bf">Increase brand translucency</h3>
                                <p>
                                    Try showing some behind- the- scenes vids to give your followers further information on the people behind your products and their personalities. Make a videotape with the whole platoon, give skulk peaks on what you
                                    are planning to do next, or simply take a print during your break.
                                </p>
                                <h3 class="bf">Induce leads</h3>
                                <p>
                                    Growing your Instagram following principally means growing your pool of prospects and leads, that are one step closer to converting. Indeed if your Instagram stories last for 24 hours, the advantage is that not only
                                    those who follow you'll be suitable to see them.
                                </p>
                                <p>
                                    That means Instagram stories are discoverable, and people who do not follow you can see your posts too. Show products Instagram stories marketing is a great occasion to reach a new followership and express your ideas
                                    more uniquely, while also showingcasing your products or services in a delightful way.
                                </p>
                                <h3 class="bf">Interact with your followership and reach further people</h3>
                                <p>Within the world of social media marketing, and especially within the world of Instagram, Stories have a crucial part because of the nature in which this point showcases your business.</p>
                                <p>
                                    First of all, among all the social platforms, Instagram is where people engage the most, so you are formerly getting a free florescence- time channel to do with as you wish. With Instagram Stories, you can interact
                                    directly with your followership in order to more understand their requirements.
                                </p>
                                <p>
                                    Try asking your followers questions to get them involved in your story, and have them partake their interest. Interact with your followership Still, you will have to invest your time in creating an Instagram Stories
                                    strategy, If you want to gain further followers and grow your reach.
                                </p>
                                <p>
                                    Start communicating with other people, make connections, and comment on others' content. Once you hit followers with an Instagram Business account, you get access to swipe- up links, which makes it indeed easier to
                                    drive business to your point. All you have to do is to be creative, use an aesthetic print or videotape with a significant communication.
                                </p>
                                <h3 class="bf">Repurpose your blog content</h3>
                                <p>
                                    Repurposing content will strengthen the power of your vids or blog posts and produce a unified strategy throughout your digital presence. This will ameliorate your brand mindfulness and encourage your followership to
                                    convert by adding your chances to be heard and remembered.
                                </p>

                                <h3 class="bf">feedback for products or services</h3>
                                <p>Indeed before the launch of a new product, you can use Instagram Stories to do a bit of request exploration and to ask your followership for some honest feedback.</p>
                                <p>Get feedback with Instagram Stories Say you are insecure about the utility of a certain functionality, or indeed the look and sense of a new release.</p>
                                <h3 class="bf">Ameliorate brand visibility</h3>
                                <p>As you know the stories of druggies that you follow appear at the top of your feed with a various ring to indicate that there's a New Story published.</p>
                                <p>People will also be notified when your publish a Story And do not limit yourself to just one story per day.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (is_null($user)) { ?>
                    <div class="card bg-white content" style="height: 350px !important;">
                        <div class="card-body">
                            <div class="row p-2">
                                <h3 class="fw-bold mb-4">Free Instagram Story View</h3>
                                <form method="post" action="?formType=findUserID" class="form">
                                    <div class="form-group">
                                        <label class="form-label">Instagram username you want to add View:</label>
                                        <br>
                                        <div class="input-group mb-0">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                            <input type="text" name="username" id="usernameCheck" class="form-control" placeholder="username" aria-describedby="username" required="">
                                        </div>
                                    </div>
                                    <?php $this->renderView("tools/inc/tools/ads"); ?>
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
                                </form>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>
                    <div class="card bg-white content" style="height: auto !important;">
                        <div class="card-header darkness">
                            <div class="p-2 fw-bold fs-5">Free Story View</div>
                        </div>
                        <div class="card-body">
                            <div class="row p-2 my-3">
                                <div class="col-12 text-center mb-3">
                                    <img src="<?php echo "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $user["user"]["profile_pic_url"]))); ?>" alt="" style="width: 100px; height:auto;" class="rounded-circle mx-auto">
                                    <div class="d-block">@<?php echo $user["user"]["username"]; ?></div>
                                </div>

                                <form id="formTakip" class="form">
                                    <div class="form-group">
                                        <label class="form-label bf">The number of View you want to add:</label>
                                        <br>
                                        <div class="input-group mb-3">
                                            <input type="text" name="adet" class="form-control" placeholder="50" value="50">
                                        </div>
                                    </div>
                                    <?php $this->renderView("tools/inc/tools/ads"); ?>
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
                                </form>
                            </div>

                            <div id="dropDownHasil" class="card p-4" style="box-shadow:none; display:none;">
                                <nav class="d-grid gap-2 col-12">
                                    <?php $this->renderView("tools/inc/tools/reset-poin"); ?>
                                    <a href="#tableHasil" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                        <i class="far fa-check-circle fa-2x mr-2"></i>
                                        <div class="text-start">
                                            <strong class="d-block text-success">Success: <span id="successList"></span></strong>
                                            <small class="text-muted">Successfully sent View</small>
                                        </div>
                                    </a>
                                    <a href="#" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                        <i class="fas fa-coins fa-2x mr-2"></i>
                                        <div class="text-start">
                                            <strong class="d-block text-success" id="storyKrediCount">Credits:
                                                <?php echo $logonPerson->member["storyKredi"]; ?></strong>
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
<script type="text/javascript">
    $(function() {
        $('#username').change(function() {
            this.value = $.trim(this.value);
            // this.value = this.value.substring(1);
            while (this.value.charAt(0) === '@' || this.value.charAt(0) === '.') {
                this.value = this.value.substring(1);
            }
            console.log(this.value);
        });
    });
</script>
<?php $this->section("section_scripts");
$this->parent();
if (!is_null($user)) { ?>
    <script type="text/javascript">
        var countTakip, countTakipMax;

        function sendTakip() {
            countTakip = 0;
            countTakipMax = parseInt($('#formTakip input[name=adet]').val());

            if (isNaN(countTakipMax) || countTakipMax <= 0) {
                alert('Masukin jumlah views!');
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

                if (data.status == 'error') {
                    $('#userList').prepend('<div class="alert alert-warning small" role="alert">' +
                        'Jika poin kamu masih ada namun followers yang masuk tidak semuanya, ' +
                        'Cobalah submit lagi sampai poin kamu habis' + '</div>');
                    sendTakipComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#" class="text-primary">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success p-1">Berhasil</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countTakip++;
                            $('#formTakip input[name=adet]').val(countTakipMax - countTakip);
                            $('#storyKrediCount').html(data.storyKredi);
                            $('#storyKrediCount2').html(data.storyKredi);

                        } else {}
                    }
                    if (countTakip < countTakipMax) {
                        sendTakipRC();
                    } else {
                        sendTakipComplete();
                    }
                }
            });
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
            $('#userList').prepend('<p class="my-2 text-danger">Ups! Server sedang ada gangguan. Coba lagi atau lapor di grup telegram. ' + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
<?php

/**
 * @var \Wow\Template\View      $this
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;

$ipaddress        = NULL;
if ($this->has("ipaddress")) {
    $ipaddress = $this->get("ipaddress");
}
$data        = NULL;
if ($this->has("data")) {
    $data = $this->get("data");
}

$helper        = NULL;
if ($this->has("helper")) {
    $helper = $this->get("helper");
}
?>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-M878TC2TK5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-M878TC2TK5');
</script>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Account - AUTOBOOSTERGRAM</title>
    <meta name="keywords" content=" followers instagram gratis, cara menambah followers instagram, beli followers instagram, jual followers instagram, auto followers instagram, cara menaikkan followers instagram, followers instagram gratis aman tanpa password, jasa followers instagram, beli followers instagram permanen, buy followers instagram, website penambah followers instagram, beli followers instagram aktif, link penambah followers instagram tanpa password, auto followers instagram, auto followers instagram free, auto followers instagram bot, real auto followers instagram, free auto followers instagram">
    <meta name="robots" content="noindex, nofollow">
    <?php $this->section("section_head"); ?>
    <?php $this->renderView("layout/inc/header-assets"); ?>
    <?php $this->show(); ?>
    <script src="/themes/informa2/js/sweetalert.min.js"></script>
    <style>
        /* Fallback for Edge
-------------------------------------------------- */
        @supports (-ms-ime-align: auto) {
            .form-label-group>label {
                display: none;
            }

            .form-label-group input::-ms-input-placeholder {
                color: #777;
            }
        }

        .field-icon {
            float: right;
            /* margin-left: -25px; */
            margin-right: 10px;
            margin-top: -27px;
            position: relative;
            z-index: 2;
        }

        .fa-check-circle {
            color: green;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .onay_kodu_ekrani {
            display: none;
            position: fixed;
            top: 0;
            width: 100%;
            height: 100%;
            background: #f8f8f8;
            padding: 50px 45px;
            text-align: center;
            z-index: 3;
        }

        .onay_kodu_girme_ekrani {
            display: none;
            position: fixed;
            top: 0;
            width: 100%;
            height: 100%;
            background: #F8F8F8;
            padding: 50px 15px;
            text-align: center;
            z-index: 3;
        }

        .onay_kodu_ekrani select {
            padding: 7px;
            font-size: 14px;
        }

        .onay_kodu_ekrani button {
            width: 160px;
            margin: 30px auto;
            padding: 8px;
            /*background    : #299029;*/
            /*border        : 1px solid #39c739;*/
            color: #fff;
            /*border-radius : 10px;*/
            cursor: pointer;
        }

        .onay_kodu_girme_ekrani input {
            padding: 10px;
            font-size: 14px;
        }

        .onay_kodu_girme_ekrani button {
            width: 160px;
            margin: 30px auto;
            padding: 8px;
            /*background    : #299029;*/
            /*border        : 1px solid #39c739;*/
            color: #fff;
            border-radius: 0px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php $this->renderView("layout/inc/header-nav"); ?>
    <main>
        <div class="bg-light">
            <div class="container my-0 penting content px-0 px-md-3">
                <div class="row py-lg-3 g-0">
                    <div class="col-md-7 bg-white shadow-sm mx-auto contents penting">
                        <div class="row">
                            
                        </div>
                        <div class="p-md-5 p-4 mb-0">
                            <div class="mb-4">
                                <h3 id="highlite" class="bf">Sign In</h3>
                                <p class="mb-4">
                                    Check your fake instagram account before logging into this website and make sure you don't get
                                    verification when logging in.</p>
                            </div>
                            <form action="#" method="post">

                                <?php foreach ($data as $key => $value) : ?>
                                    <!-- Flexbox container for aligning the toasts -->
                                    <div aria-live="polite" aria-atomic="true">

                                        <!-- Then put toasts within -->
                                        <div class="toast my-4 text-start mx-auto w-100 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                            <div class="toast-header text-white" style="background-color:#c62c2e;">
                                                <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" role="img" focusable="false">
                                                    <title>Placeholder</title>
                                                    <rect width="100%" height="100%" fill="<?= $value['fill'] ?>" /><text x="50%" y="50%" fill="<?= $value['fill'] ?>" dy=".3em">32x32</text>
                                                </svg>
                                                <strong class="me-auto">Latest update</strong>
                                                <small><?= $helper->dateTimeToDuration($value['created_at']); ?></small>

                                            </div>
                                            <div class="toast-body" style="background-color: rgb(0 0 0 / 0.02);">
                                                <?= $value['name'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>


                                <div class="form-group mb-3">
                                    <label for="exampleInputEmail1">Username</label>
                                    <input type="text" class="instaclass10 instaclass11 form-control input-lg" aria-describedby="" aria-label="Kullanıcı adı" aria-required="true" maxlength="30" name="username" id="username" placeholder="Enter username without @" value="" autocomplete="off" required autofocus onchange="checkusername();">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" class="instaclass10 instaclass11 form-control input-lg" aria-describedby="" aria-label="Şifre" aria-required="true" name="password" placeholder="Your Password" id="password">
                                    <span toggle="#password" class="fa fa-fw fa-eye field-icon text-dark toggle-password align-items-center"></span>
                                </div>
                                <input type="hidden" name="userid" />
                                <?php if (!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSiteKey"))) { ?>
                                    <div class="h6 mb-2 text-danger fw-bold" id="clickHcaptcha" style="display:none;">
                                        Click I am human <i class="fa-sharp fa-solid fa-arrow-down"></i>
                                    </div>
                                    <div data-theme="dark" class="h-captcha" data-sitekey="<?php echo Wow::get("ayar/GoogleCaptchaSiteKey"); ?>"></div>
                                <?php } ?>

                                <div class="form-group my-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1" checked disabled>
                                        <label class="custom-control-label" for="exampleCheck1">I agree to the <a href="#" data-toggle="modal" data-target="#termsmodal" class="text-decoration-underline">terms of service</a>.</label>
                                    </div>
                                </div>
                                <button id="login_insta" class="btnn blood btn" type="submit"><i class="fas fa-lock"></i>
                                    Secure Login</button>
                                <div class="alert alert-info align-items-center shadow-sm" role="alert" id="please-wait" style="display:none;">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <img src="/themes/ncse-v2/stock/astro-head.png" class="spinner-border text-secondary mx-2" style="border: 0px; margin-right: 10px;" />
                                            <span>Please wait a little longer....</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-success align-items-center shadow-sm" role="alert" id="sukses-login" style="display:none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-circle-check text-dark mx-2"></i> You're are success login, please wait.
                                    </div>
                                </div>
                            </form>

                            <div class="row d-md-none d-block">
                                <script type="text/javascript">
                                    atOptions = {
                                        'key': 'e269dc028ad9339dca8a8a87da5af361',
                                        'format': 'iframe',
                                        'height': 300,
                                        'width': 160,
                                        'params': {}
                                    };
                                    document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://www.profitabledisplaynetwork.com/e269dc028ad9339dca8a8a87da5af361/invoke.js"></scr' + 'ipt>');
                                </script>
                            </div>

                            <div class="card shadow-sm mt-3">
                                <div class="card-body">
                                    <div>Before entering the website, make sure the fake account is not banned,
                                        how to log in to instagram.com or make sure the list below is fulfilled.</div>
                                    <ol class="mt-2">
                                        <li>Your fake Instagram account is not locked / can't be private</li>
                                        <li>Turn off Two Factor Authentication</li>
                                        <li>Complete your Fake Instagram Account Profile, such as Full name, Bio, Gender, etc.</li>
                                        <li>Make sure there is a feed / photo / video of at least 3</li>
                                    </ol>
                                    <p>We build a safe community to provide you with real and unlimited free Instagram followers and likes. Getting a lot of Instagram free followers & likes can be done with simple steps easily.</p>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->renderView("layout/inc/footer"); ?>
    </main>
    <div class="onay_kodu_ekrani content text-dark"></div>
    <div class="onay_kodu_girme_ekrani content text-dark"></div>
    <?php $this->renderView("layout/inc/footer-assets"); ?>
    <!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    <script>
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $(window).on('load', function() {
            $('.toast').toast('show');
        });
    </script>
    <script>
        $(function() {
            $('.button-checkbox').each(function() {
                var $widget = $(this),
                    $button = $widget.find('button'),
                    $checkbox = $widget.find('input:checkbox'),
                    color = $button.data('color'),
                    settings = {
                        on: {
                            icon: 'glyphicon glyphicon-check'
                        },
                        off: {
                            icon: 'glyphicon glyphicon-unchecked'
                        }
                    };

                $button.on('click', function() {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    $checkbox.triggerHandler('change');
                    updateDisplay();
                });

                $checkbox.on('change', function() {
                    updateDisplay();
                });

                function updateDisplay() {
                    var isChecked = $checkbox.is(':checked');
                    // Set the button's state
                    $button.data('state', (isChecked) ? "on" : "off");

                    // Set the button's icon
                    $button.find('.state-icon')
                        .removeClass()
                        .addClass('state-icon ' + settings[$button.data('state')].icon);

                    // Update the button's color
                    if (isChecked) {
                        $button
                            .removeClass('btn-default')
                            .addClass('btn-' + color + ' active');
                    } else {
                        $button
                            .removeClass('btn-' + color + ' active')
                            .addClass('btn-default');
                    }
                }

                function init() {
                    updateDisplay();
                    // Inject the icon if applicable
                    if ($button.find('.state-icon').length == 0) {
                        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon +
                            '"></i> ');
                    }
                }
                init();
            });
        });
    </script>


    <script type="text/javascript">
        $(function() {
            $('#username').change(function() {
                this.value = $.trim(this.value);
                // this.value = this.value.substring(1);
                while (this.value.charAt(0) === '@' || this.value.charAt(0) === '.') {
                    this.value = this.value.substring(1);
                }
                // console.log(this.value);
            });
        });
    </script>
    <script>
        function checkusername() {
            const block = ['tumbal', 'tmbl', 'kntl', 'kintil', 'kontol', 'fake', 'palsu', 'bodong', 'anjing', 'babi',
                'gratis', 'akun', 'jasa', 'sosmed', 'tumb', 'jancuk', 'tolol', 'spam', 'peler', 'memek', 'follower'
            ];
            var username = $('#username').val().toLowerCase();
            username = username.replace('.', '').replace('_', '');

            var content = document.createElement('div');
            content.innerHTML =
                '<p class="text-dark">Error ini terjadi ketika username akun tumbal kamu terdapat kata-kata yang <strong>DILARANG</strong> seperti ada kata tumbal, fake, dan lainnya.<br/><br/><strong>MOHON MENGGANTI USERNAME KAMU YANG LEBIH REAL-HUMAN</strong></p>';
            for (var i = 0; i < block.length; i++) {
                var current = block[i];
                // console.log([current, username]);
                if (username.indexOf(current) !== -1) {
                    swal("Maaf!",
                            "Username akun tumbal kamu DILARANG masuk kedalam website. Mohon ganti username kamu terlebih dahulu.",
                            "error", {
                                buttons: {
                                    cancel: "Solusi",
                                    ok: true,
                                },
                            })
                        .then((value) => {
                            switch (value) {

                                case "ok":
                                    $('#username').val("");
                                    break;

                                default:
                                    swal({
                                        icon: "info",
                                        title: "Solusi",
                                        content: content,
                                        type: "info",
                                        button: "Mengerti",
                                    }).then(() => {
                                        $('#username').val("");
                                        // window.location.href = window.location.href; 
                                    })
                                    // location.reload();
                                    break;
                            }
                        });
                    return false;
                }
            }
            // console.log('username human');
            return true;
        }

        $('#login_insta').click(function() {
            $("#login_insta").hide();
            $('#slfErrorAlert').hide();
            $("#please-wait").show();
            $("#clickHcaptcha").hide();
            $(this).attr("disabled", "disabled");
            $maindiv = $(this);
            $maindiv.addClass("instaclass31");
            $('.progress').show();
            <?php if (!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSecretKey"))) { ?>
                var dataList = "username=" + encodeURIComponent($('input[name="username"]').val()) + "&password=" +
                    encodeURIComponent($('input[name="password"]').val()) + "&userid=" + encodeURIComponent($(
                        'input[name="userid"]').val()) +
                    "&antiForgeryToken=<?php echo $_SESSION["AntiForgeryToken"]; ?>&h-captcha-response=" + hcaptcha.getResponse();
                hcaptcha.reset();
            <?php } else { ?>
                var dataList = "username=" + encodeURIComponent($('input[name="username"]').val()) + "&password=" +
                    encodeURIComponent($('input[name="password"]').val()) + "&userid=" + encodeURIComponent($(
                        'input[name="userid"]').val()) +
                    "&antiForgeryToken=<?php echo $_SESSION["AntiForgeryToken"]; ?>";
            <?php } ?>

            $.ajax({
                type: "POST",
                url: "?",
                dataType: "json",
                data: dataList,
                statusCode: {
                    404: function() {
                        $("#login_insta").show();
                        swal("Maaf! [404]",
                                "Kami tidak dapat menemukan akun instagram kamu, cobalah gunakan akun yang lain.",
                                "error", {
                                    buttons: {
                                        cancel: "Solusi",
                                        ok: true,
                                    },
                                })
                            .then((value) => {
                                switch (value) {

                                    case "ok":
                                        location.reload();
                                        break;

                                    default:
                                        swal({
                                            icon: "info",
                                            title: "Solusi",
                                            text: "Error ini terjadi ketika username yang km masukkan salah sehingga instagram tidak dapat mencarinya atau akun instagram kamu telah di ban / blokir oleh instagram. \n\n Cek akun tumbal kamu melalui website atau aplikasi instagram. Jika akun telah di ban, maka segeralah ganti password dan login kembali.",
                                            type: "info",
                                            button: "Mengerti",
                                        }).then(() => {
                                            window.location.href = window.location.href;
                                        })
                                        // location.reload();
                                        break;
                                }
                            });
                    },
                    500: function() {
                        $("#login_insta").show();
                        swal("Maaf! [500]",
                                "Akun kamu sepertinya tidak bisa login, hubungi admin di grup Autoboostergram untuk dapat bantuan login.",
                                "error", {
                                    buttons: {
                                        cancel: "Solusi",
                                        ok: true,
                                    },
                                })
                            .then((value) => {
                                switch (value) {

                                    case "ok":
                                        location.reload();
                                        break;

                                    default:
                                        swal({
                                            icon: "info",
                                            title: "Solusi",
                                            text: "Error ini terjadi karena server atau proxy sedang bermasalah. Hubungi admin terkait di Grup Autoboostergram.",
                                            type: "info",
                                            button: "Mengerti",
                                        }).then(() => {
                                            window.location.href = window.location.href;
                                        })
                                        // location.reload();
                                        break;
                                }
                            });
                    },
                    503: function() {
                        $("#login_insta").show();
                        alert('sorry, we are having trouble. Please try again! [503]') ? "" : location
                            .reload();
                    },
                    403: function() {
                        $("#login_insta").show();
                        alert('sorry, we are having trouble. Please try again! [403]') ? "" : location
                            .reload();
                    },
                    400: function() {
                        $("#login_insta").show();
                        alert('sorry, we are having trouble. Please try again! [403]') ? "" : location
                            .reload();
                    }
                },
                beforeSend: function() {
                    // setting a timeout
                    $('#progressbar').addClass(" w-25");
                },
                success: function(json) {
                    $('#progressbar').addClass(" w-100");


                    if (json.status == 'success') {
                        $("#please-wait").hide();
                        window.parent.location.href = json.returnUrl;
                        window.parent.$.fancybox.close();


                    } else {
                        $("#login_insta").show();
                        $("#please-wait").hide();
                        var $allData = json.allData;

                        if (json.status == 3) {

                            if (json.allData.step_name == 'verify_code') {

                                var onayKoduEkrani = $('.onay_kodu_girme_ekrani');
                                onayKoduEkrani.html('');
                                var html =
                                    "<h4 class='text-dark'>Silakan lanjutkan dengan memasukkan kode 6 digit yang dikirimkan kepada Anda.</h4><br/>";
                                html +=
                                    "<input type='number' id='kod_onayla_input' value='' maxlength='6' class='form-control' placeholder='Kode konfirmasi?'/>";
                                html += "<div><button class='kod_onayla btn btn-dark'>Submit</button>";
                                onayKoduEkrani.html(html);
                                onayKoduEkrani.show();

                                $('.kod_onayla').click(function() {
                                    var kodOnay = $('#kod_onayla_input').val();

                                    if (kodOnay.length < 6) {
                                        alert(
                                            "Kode konfirmasi yang masuk minimal harus 6 karakter"
                                        );
                                    }

                                    $allData.code = kodOnay;
                                    $('.kod_onayla').attr("disabled", "disabled");
                                    $('.kod_onayla').html('Submitting..');
                                    $.ajax({
                                        url: "/ajax/kod-onayla",
                                        data: $allData,
                                        type: "POST",
                                        success: function(json) {
                                            if (json.status == "ok") {
                                                window.parent.location.href = json
                                                    .returnUrl;
                                                window.parent.$.fancybox.close();
                                            } else {
                                                alert(json.error);
                                                $('.progress').hide();
                                                $maindiv.removeAttr("disabled");
                                                $maindiv.removeClass(
                                                    "instaclass31");
                                            }
                                        }
                                    });
                                });

                            } else {
                                var onayEkrani = $('.onay_kodu_ekrani');
                                onayEkrani.html('');
                                var data = json.allData.step_data;


                                var html = "<div>" + json.error + "</div><br/>";
                                html +=
                                    "<h2 class='my-2 text-dark'>Verifikasi</h2><select id='choice_select' class='form-select p-2'>";

                                if (typeof data.phone_number !== "undefined") {
                                    html += "<option value='0'>Konfirmasi via HP: " + data
                                        .phone_number +
                                        "</option>";
                                }

                                if (typeof data.email !== "undefined") {
                                    html += "<option value='1'>Konfirmasi via Email: " + data.email +
                                        "</option>";
                                }

                                html += "</select>";
                                html +=
                                    "<div><button class='kod_iste btn btn-dark'>Kirim Kode</button></div>";
                                html +=
                                    "<div class='my-3'><span class='fw-bold fs-5'>Bingung?</span><br/> Nonton video dibawah ini.</div>";
                                html +=
                                    "<div class='ratio ratio-16x9'><iframe width='100%' height='315' src='https://www.youtube.com/embed/M9QEeqB00UU' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe></div>";

                                onayEkrani.html(html);
                                onayEkrani.show();
                                json.allData.choice = $('#choice_select').val();
                            }


                            $('.kod_iste').click(function() {
                                $('.kod_iste').attr("disabled", "disabled");
                                $('.kod_iste').html('Meminta Kode...');

                                $.ajax({
                                    url: "/ajax/kod-gonder",
                                    data: $allData,
                                    type: "POST",
                                    success: function(json) {
                                        if (json.status == "ok") {
                                            var onayKoduEkrani = $(
                                                '.onay_kodu_girme_ekrani');
                                            onayKoduEkrani.html('');
                                            var html =
                                                "<h3 class='my-2 text-dark d-block'>Verifikasi</h3><div>Silahkan lanjutkan dengan memasukkan kode 6 digit yang dikirimkan kepada Anda.</div><br/>";
                                            html +=
                                                "<input type='number' id='kod_onayla_input' value='' class='form-control' maxlength='6' placeholder='Kode konfirmasi?'></input>";
                                            html +=
                                                "<div><button class='kod_onayla btn btn-dark text-white'>Submit</button></div>";
                                            onayKoduEkrani.html(html);
                                            onayKoduEkrani.show();


                                            $('.kod_onayla').click(function() {
                                                var kodOnay = $(
                                                        '#kod_onayla_input')
                                                    .val();
                                                if (kodOnay.length < 6) {
                                                    alert(
                                                        "Kode konfirmasi yang masuk minimal harus 6 karakter"
                                                    );
                                                    $('.progress').hide();
                                                    $maindiv.removeAttr(
                                                        "disabled");
                                                    $maindiv.removeClass(
                                                        "instaclass31");
                                                }

                                                $allData.code = kodOnay;
                                                $('.kod_onayla').attr(
                                                    "disabled",
                                                    "disabled");
                                                $('.kod_onayla').html(
                                                    'Approving..');
                                                $.ajax({
                                                    url: "/ajax/kod-onayla",
                                                    data: $allData,
                                                    type: "POST",
                                                    success: function(
                                                        json) {
                                                        if (json
                                                            .status ==
                                                            "success"
                                                        ) {
                                                            window
                                                                .parent
                                                                .location
                                                                .href =
                                                                json
                                                                .returnUrl;
                                                            window
                                                                .parent
                                                                .$
                                                                .fancybox
                                                                .close();
                                                        } else {
                                                            alert
                                                                (json
                                                                    .error
                                                                );
                                                            $('.progress')
                                                                .hide();
                                                            $maindiv
                                                                .removeAttr(
                                                                    "disabled"
                                                                );
                                                            $maindiv
                                                                .removeClass(
                                                                    "instaclass31"
                                                                );
                                                        }
                                                    }
                                                });
                                            });

                                        } else {
                                            alert(json.error);
                                            $('.progress').hide();
                                            $maindiv.removeAttr("disabled");
                                            $maindiv.removeClass("instaclass31");
                                        }
                                    }
                                });

                            });

                        } else {
                            if (json.title == "1") {
                                swal("Sorry!",
                                        "Your account has been restricted by Instagram!, " +
                                        json.error,
                                        "error", {
                                            closeOnClickOutside: false,
                                            closeOnEsc: false,
                                            buttons: {
                                                ok: true,
                                            },
                                        })
                                    .then((value) => {
                                        switch (value) {

                                            case "ok":
                                                location.reload();
                                                break;
                                        }
                                    });

                            } else if (json.title == "9") {
                                swal("Challenge Required!", json.error, "error", {
                                    closeOnClickOutside: false,
                                    closeOnEsc: false,
                                    buttons: {
                                        ok: true,
                                    },
                                }).then((value) => {
                                    switch (value) {
                                        case "ok":
                                            location.reload();
                                            break;
                                    }
                                });
                            } else if (json.title == "11") {
                                swal("SPAM!", json.error, "error", {
                                    closeOnClickOutside: false,
                                    closeOnEsc: false,
                                    buttons: {
                                        ok: true,
                                    },
                                }).then((value) => {
                                    switch (value) {
                                        case "ok":
                                            location.reload();
                                            break;
                                    }
                                });
                            } else if (json.title == "not_clicked_hcaptcha") {
                                $("#clickHcaptcha").show();
                                swal(json.error, {
                                    closeOnClickOutside: false,
                                    closeOnEsc: false,
                                    buttons: {
                                        ok: true,
                                    },
                                });
                            } else {
                                swal(json.error);
                            }
                            $('#progressbar').removeClass(" w-100 w-25");
                            $maindiv.removeAttr("disabled");
                            $maindiv.removeClass("instaclass31");
                        }
                    }
                }
            });
        });

        <?php if (Wow::has("ayar/googleanalyticscode") != "") { ?>
                (function(i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function() {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
            ga('create', '<?php echo Wow::get("ayar/googleanalyticscode"); ?>', 'auto');
            ga('send', 'pageview');
        <?php } ?>
    </script>
</body>

</html>
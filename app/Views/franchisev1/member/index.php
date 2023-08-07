<?php
    /**
     * @var \Wow\Template\View      $this
     * @var \App\Models\LogonPerson $logonPerson
     */
    $logonPerson = $this->get("logonPerson");
    $uyelik      = $logonPerson->member;

    $ipaddress        = NULL;
    if($this->has("ipaddress")){
        $ipaddress = $this->get("ipaddress");
    }

    $data        = NULL;
    if($this->has("data")){
        $data = $this->get("data");
    }

    $helper        = NULL;
    if($this->has("helper")){
        $helper = $this->get("helper");
    }
?>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Account - Informatikamu</title>
    <meta name="robots" content="noindex, nofollow">
    <?php header( "refresh:300;url=tools" ); ?>
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Halaman Login Informatikamu Instagram, sangat mudah hanya 2x klik dan masuk ke halaman auto followers dan auto like.">
    <meta name="keywords"
        content="instagram, auto followers, auto like instagram indonesia, auto followers indonesia, instagram indonesia, top comment indonesia, auto likes indonesia, auto like gratis, auto followers gratis, auto followers instagram, auto followers indonesia gratis, auto like gratis indonesia, cara memperbanyak followers, memperbanyak followers instagram, cara memperbanyak followers instagram">
    <link rel="shortcut icon" href="https://informatikamu.id/wp-content/uploads/2018/04/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="/themes/informa2/custom.css?v=1.0" />
    <link rel="stylesheet" href="/themes/informa2/bootstrap.min.night.css">
    <link href="/css/bs5/css/offcanvas.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style/instastyle.css?v=2.0" />
    <script src="/themes/informa2/js/sweetalert.min.js"></script>
    <style>
    .form-control {
        display: block;
        width: 100%;
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .form-signin {
        width: 100%;
        max-width: 420px;
        padding: 15px;
        margin: auto;
    }

    .form-label-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .form-label-group>input,
    .form-label-group>label {
        height: 3.125rem;
        padding: .75rem;
    }

    .form-label-group>label {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100%;
        color: #495057;
        pointer-events: none;
        cursor: text;
        /* Match the input under the label */
        border: 1px solid transparent;
        border-radius: .25rem;
        transition: all .1s ease-in-out;
    }

    .form-label-group input::-webkit-input-placeholder {
        color: transparent;
    }

    .form-label-group input::-ms-input-placeholder {
        color: transparent;
    }

    .form-label-group input::-moz-placeholder {
        color: transparent;
    }

    .form-label-group input::placeholder {
        color: transparent;
    }

    .form-label-group input:not(:-moz-placeholder-shown) {
        padding-top: 1.25rem;
        padding-bottom: .25rem;
    }

    .form-label-group input:not(:placeholder-shown) {
        padding-top: 1.25rem;
        padding-bottom: .25rem;
    }

    .form-label-group input:not(:-moz-placeholder-shown)~label {
        padding-top: .25rem;
        padding-bottom: .25rem;
        font-size: 12px;
        color: #777;
    }

    .form-label-group input:not(:placeholder-shown)~label {
        padding-top: .25rem;
        padding-bottom: .25rem;
        font-size: 12px;
        color: #777;
    }

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
        margin-top: -30px;
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
        padding: 50px 15px;
        text-align: center;
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

    .onay_kodu_ekrani button:hover {
        background: #207520;
    }

    .onay_kodu_ekrani button:disabled {
        background: #6fcc6f;
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

    .onay_kodu_girme_ekrani button:hover {
        background: #207520;
    }

    .onay_kodu_girme_ekrani button:disabled {
        background: #6fcc6f;
    }

    a {
        text-decoration: none;
    }
    </style>
</head>

<body style="background-color:#222222;">
    <header>
        <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary d-block">
            <div class="container">
                <a href="<?php echo $logonPerson->isLoggedIn() ? '/tools' : '/'; ?>"
                    class="navbar-brand">Informatikamu</a>
                <div class="mr-auto">
                    <a class="px-1 mx-1 py-3 my-n2 d-lg-none d-xl-none" target="_blank"
                        href="https://instagram.com/receh_man" style="color: rgba(255,255,255,0.6);">
                        <i class="fab fa-lg fa-instagram"></i></a>
                    <a class="px-1 mx-1 py-3 my-n2 d-lg-none d-xl-none" target="_blank"
                        href="https://www.youtube.com/channel/UCJYeXpkAN9dnfElsDw4cxUg"
                        style="color: rgba(255,255,255,0.6);">
                        <i class="fab fa-lg fa-youtube"></i></a>

                </div>


                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a href="<?php echo $logonPerson->isLoggedIn() ? '/tools' : '/'; ?>"
                                class="nav-link"><i class="fa fa-home"></i> Dashboard</a></li>
                        <li class="nav-item"
                            <?php echo $this->route->params["controller"] == "Tools" ? ' class="nav-item active"' : ''; ?>>
                            <a href="/tools" class="nav-link"><i class="fa fa-hashtag"></i> Tools</a>
                        </li>
                        <li class="nav-item"
                            <?php echo $this->route->params["controller"] == "Beli Points" ? ' class="nav-item active"' : ''; ?>>
                            <a href="/packages" class="nav-link"><i class="fa fa-shopping-cart"></i> Paket VIP</a>
                        </li>
                        <li class="nav-item"
                            <?php echo $this->route->params["controller"] == "Blog" ? ' class="nav-item active"' : ''; ?>>
                            <a href="/blog" class="nav-link"><i class="fa fa-rss"></i> Blog</a>
                        </li>

                    </ul>

                    <div
                        class="nav-item dropdown nav-link px-1 mx-1 my-n2 d-none d-sm-none d-md-none d-lg-block d-xl-block">
                        <a class="nav-link dropdown-toggle" style="color: rgba(255,255,255,0.6);" data-toggle="dropdown"
                            href="#" role="button" aria-haspopup="true" aria-expanded="false">Server-2</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="https://ig.informatikamu.id">Server-1</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item active" href="/">Server-2</a>

                        </div>
                    </div>
                    <a class="nav-link px-1 mx-1 py-3 my-n2 d-none d-sm-none d-md-none d-lg-block d-xl-block"
                        href="https://instagram.com/receh_man" target="_blank" style="color: rgba(255,255,255,0.6);">
                        <i class="fab fa-lg fa-instagram"></i></a>
                    <a class="nav-link px-1 mx-1 py-3 my-n2 nav-link px-1 mx-1 py-3 my-n2 d-none d-sm-none d-md-none d-lg-block d-xl-block"
                        href="https://www.youtube.com/channel/UCJYeXpkAN9dnfElsDw4cxUg" target="_blank"
                        style="color: rgba(255,255,255,0.6);">
                        <i class="fab fa-lg fa-youtube"></i></a>
                    <?php if($logonPerson->isLoggedIn()) { ?>
                    <a href="/tools/" class="btn" style="text-transform: none;">
                        <img src="<?php echo str_replace("http:", "https:", $uyelik["profilFoto"]); ?>"
                            alt="<?php $uyelik["kullaniciAdi"]; ?>" style="max-height:20px;">
                        <?php echo (strlen($uyelik["kullaniciAdi"]) > 10) ? substr($uyelik["kullaniciAdi"], 0, 5) . ".." : $uyelik["kullaniciAdi"]; ?>
                        <?php } else { ?>
                        <a class="btn btn-light mr-2" href="https://docs.informatikamu.id/" target="_blank"><i
                                class="fa fa-sign-in"></i> Get Started</a>
                        <?php } ?>
                        <?php if(!$logonPerson->isLoggedIn()) { ?>
                        <a class="btn btn-secondary" href="/login"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <?php } else { ?>
                        <a class="btn btn-secondary" href="/account/logout"><i class="fas fa-sign-out-alt"></i>
                            Logout</a>
                        <?php } ?>

                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container my-3 mt-3">
            <div class="row">
                <div class="col text-center mx-auto">
                    <!-- ads -->
                </div>
            </div>
        </div>


        <section class="container-fluid my-0">
            <div class="row py-lg-3">
                <div class="col-lg-6 col-md-8 mx-auto penting">
                    <div class="py-4 bg-white content" style="border: 1px solid #dfdfdf;">

                        <form method="POST" onsubmit="return false;" class="form-signin">
                            <div class="text-center">
                                <img class="mb-4"
                                    src="https://informatikamu.id/wp-content/uploads/2018/10/logo-informatikamu.png"
                                    alt="ig informatikamu" width="250px" height="auto">


                                <p class="text-muted">It is recommended to use a <code>fake account</code> to maintain
                                    your
                                    security and
                                    privacy.
                                    Read this <a href="/blog/frequently-ask-questions">FAQ</a>, Privacy
                                    Policy, and Terms.

                                </p>
                                <p><a href="https://ig.informatikamu.id"
                                        class="btn btn-outline-dark btn-sm font-weight-bold">
                                        Getting slow ? Try Server 1 <span class="badge bg-dark text-white">Hot</span>
                                    </a></p>

                                <div class="alert alert-dark" role="alert">
                                   <marquee>Cek kembali akun tumbal kamu sebelum login, pastikan tidak ada masalah verifikasi atau terblokir oleh instagram.</marquee>
                                </div>


                            </div>


                            <div class="mb-3">
                                <div class="instaclass8 instaclass9 form-label-group">
                                    <input type="text" class="instaclass10 instaclass11 form-control input-lg"
                                        aria-describedby="" aria-label="Kullanıcı adı" aria-required="true"
                                        maxlength="30" name="username" id="username" placeholder="Username" value=""
                                        autocomplete="off" required autofocus onchange="checkusername();">
                                    <label for="username">Username</label>
                                    <div class="suggestUsers">
                                        <ul></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="instaclass8 instaclass9 form-label-group">
                                    <input type="password" class="instaclass10 instaclass11 form-control input-lg"
                                        aria-describedby="" aria-label="Şifre" aria-required="true" name="password"
                                        placeholder="Password" id="password">
                                    <label for="password">Password</label>
                                    <span toggle="#password"
                                        class="fa fa-fw fa-eye field-icon text-dark toggle-password align-items-center"></span>

                                </div>
                            </div>
                            <input type="hidden" name="userid" />
                            <?php if(!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSiteKey"))) { ?>
                            <div style="width: 304px;margin: 0 auto;" class="g-recaptcha"
                                data-sitekey="<?php echo Wow::get("ayar/GoogleCaptchaSiteKey"); ?>"></div>
                            <?php } ?>


                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <button id="login_insta" class="btn btn-lg btn-success btn-block "><i
                                            class="fas fa-lock"></i>
                                        Secure Login</button>

                                    <div class="progress mt-3">
                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                            id="progressbar" role="progressbar" aria-valuenow="80" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>


                                    <div class="instaclass20">
                                        <p id="slfErrorAlert" aria-atomic="true" role="alert"></p>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>

        <div class="container mb-2">
            <div class="row">
                <!-- ads -->
            </div>
        </div>

    </main>

    <div class="container-fluid bg-dark penting">
        <div class="row g-0">
            <div class="col">
                <div class="container py-4 mb-2">
                    <div class="row">
                        <div class="col col-lg-6 col-xl-6 col-md-12 col-sm-12 col-xs-12">
                            <div style="max-width:100%;">
                                <div class="footer-heading">About Us</div>
                                <p class="footer-text">Informatikamu is a Instagram automation site that can add
                                    followers
                                    and likes for free.
                                    Informatikamu was founded in 2016 with the concept of being a digital agency at that
                                    time. Our goal is to help users get Instagram engagement easily, you don't have to
                                    pay.</p>
                            </div>
                        </div>

                        <div class="ol col-lg-3 col-xl-3 col-md-12 col-sm-12 col-xs-12 footer-text">
                            <div class="footer-heading">Site Links</div>
                            <ul class="list-group list-group-flush" style="list-style-type: none;padding-bottom:1rem;">
                                <li><a class="footer-link" href="/about">About Us</a></li>
                                <li><a class="footer-link" href="/tools">Tools</li>
                                <li><a class="footer-link" href="/blog">Blog</li>
                                <li><a class="footer-link" href="/reseller">Reseller</li>
                                <li><a class="footer-link" href="https://t.me/joinchat/HAz9LzGfcPvXN5Aa"
                                        target="_blank">Our Telegram</a>
                                </li>
                            </ul>
                        </div>

                        <div class="col col-lg-3 col-xl-3 col-md-12 col-sm-12 col-xs-12 footer-text">
                            <div class="footer-heading">Our Sites</div>
                            <ul class="list-group list-group-flush" style="list-style-type: none;padding-bottom:1rem;">
                                <li><a class="footer-link" href="https://instagram.informatikamu.id"
                                        rel="nofollow">Server-2</a></li>
                                <li><a class="footer-link" href="https://ig.peluangin.com">Server-3</a></li>
                                <li><a class="footer-link" href="https://informatikamu.id">Informatikamu</a></li>
                                <li><a class="footer-link" href="https://peluangin.com" rel="nofollow">Peluangin</a>
                                </li>
                                <li><a class="footer-link" href="https://instagram.com/receh_man" rel="nofollow">Our
                                        Instagram</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <footer class="py-3 footer-end">
        <div class="container">
            <p class="float-right mb-1">
                <a class="link-light btn btn-dark warnaOren" href="#"><i class="fa fa-arrow-up"></i></a>
            </p>
            <p class="mb-1">Copyright &copy; 2019-<?php echo date("Y"); ?>
                <a class="footer-bottom"
                    href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME']; ?>"><?php echo $_SERVER['SERVER_NAME']; ?></a>
                <br />Created by <a href="https://instagram.com/receh_man">@receh_man</a>
            </p>
        </div>
    </footer>
    <div class="onay_kodu_ekrani"></div>
    <div class="onay_kodu_girme_ekrani"></div>
    <script src="https://d2mv07lmyroai0.cloudfront.net/cdn/jquery/2.2.4/jquery.min.js"></script>
    <script src="/css/bs5/js/bootstrap.min.js"></script>
    <script src="/css/bs5/js/offcanvas.js"></script>
    <script src="https://kit.fontawesome.com/2bb0816411.js" crossorigin="anonymous"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
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
    <script src='https://www.google.com/recaptcha/api.js'></script>
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
            console.log([current, username]);
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
        console.log('username human');
        return true;
    }

    $('#login_insta').click(function() {
        $('#slfErrorAlert').hide();
        $(this).attr("disabled", "disabled");
        $maindiv = $(this);
        $maindiv.addClass("instaclass31");
        $('.progress').show();
        <?php if(!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSecretKey"))) { ?>
        var dataList = "username=" + encodeURIComponent($('input[name="username"]').val()) + "&password=" +
            encodeURIComponent($('input[name="password"]').val()) + "&userid=" + encodeURIComponent($(
                'input[name="userid"]').val()) +
            "&antiForgeryToken=<?php echo $_SESSION["AntiForgeryToken"]; ?>&captcha=" + grecaptcha
            .getResponse();
        grecaptcha.reset();
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
                    swal("Maaf! [500]",
                            "Saat ini server atau proxy sedang mengalami gangguan. Cobalah lagi nanti.",
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
                                        text: "Error ini terjadi karena server atau proxy sedang bermasalah. Tunggulah beberapa saat lagi",
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
                    alert('sorry, we are having trouble. Please try again! [503]') ? "" : location
                        .reload();
                },
                403: function() {
                    alert('sorry, we are having trouble. Please try again! [403]') ? "" : location
                        .reload();
                },
                400: function() {
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
                    window.parent.location.href = json.returnUrl;
                    window.parent.$.fancybox.close();
                } else {

                    var $allData = json.allData;

                    if (json.status == 3) {

                        if (json.allData.step_name == 'verify_code') {

                            var onayKoduEkrani = $('.onay_kodu_girme_ekrani');
                            onayKoduEkrani.html('');
                            var html =
                                "<div>Silakan lanjutkan dengan memasukkan kode 6 digit yang dikirimkan kepada Anda.</div><br/>";
                            html +=
                                "<input type='number' id='kod_onayla_input' value='' maxlength='6' placeholder='Kode konfirmasi?'/>";
                            html += "<div><button class='kod_onayla'>Submit</button>";
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
                                "<h3 class='my-2 text-dark'>Verifikasi</h3><select id='choice_select' class='form-select p-2'>";

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
                                            "<h3 class='my-2 text-dark d-block'>Verifikasi</h3><div>Silakan lanjutkan dengan memasukkan kode 6 digit yang dikirimkan kepada Anda.</div><br/>";
                                        html +=
                                            "<input type='number' id='kod_onayla_input' value='' maxlength='6' placeholder='Kode konfirmasi?'></input>";
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
                        alert(json.error);
                        $('#progressbar').removeClass(" w-100 w-25");
                        $maindiv.removeAttr("disabled");
                        $maindiv.removeClass("instaclass31");
                    }
                }
            }
        });
    });

    <?php if(Wow::has("ayar/googleanalyticscode") != "") { ?>
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

    $('#username').keyup(function() {
        $('input[name="userid"]').val("");
        $('.suggestUsers').show();
        var value = $(this).val();
        $.ajax({
            type: "GET",
            url: "https://www.instagram.com/web/search/topsearch/?context=blended&query=" + value +
                "&rank_token=0.8516828732626001&include_reel=true&count=0",
            dataType: "json",
            success: function(response) {
                var html = "";
                response.users.forEach(function(a, b) {
                    html += "<li onclick='$(\"input[name=username]\").val(\"" + a.user
                        .username + "\");$(\"input[name=userid]\").val(" + a.user.pk +
                        ");$(\".suggestUsers ul\").html(\"\");$(\".suggestUsers\").hide();'><div class='suggestimg'><img src='" +
                        a.user.profile_pic_url + "'/></div><div class='suggestinfo'><b>" + a
                        .user.username + "</b><span>" + a.user.full_name +
                        "</span></div></li>";
                });
                $('.suggestUsers ul').html(html);
            }
        })
    });
    </script>

</body>

</html>
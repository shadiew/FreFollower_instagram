<?php
    /**
     * @var \Wow\Template\View      $this
     * @var \App\Models\LogonPerson $logonPerson
     */
    $logonPerson = $this->get("logonPerson");
    $uyelik      = $logonPerson->member;
?>
<html lang="id">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Account - Informatikamu</title>
    <meta name="robots" content="noindex, nofollow">
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Halaman Login Informatikamu Instagram, sangat mudah hanya 2x klik dan masuk ke halaman auto followers dan auto like."> 
    <meta name="keywords" content="instagram, auto followers, auto like instagram indonesia, auto followers indonesia, instagram indonesia, top comment indonesia, auto likes indonesia, auto like gratis, auto followers gratis, auto followers instagram, auto followers indonesia gratis, auto like gratis indonesia, cara memperbanyak followers, memperbanyak followers instagram, cara memperbanyak followers instagram">
    <link rel="stylesheet" href="/css/bs5/css/bootstrap.min.css">
    <link rel="shortcut icon" href="https://informatikamu.id/wp-content/uploads/2018/04/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="/assets/style/suggestImage.css?v=1.0"/>
    <link href="/assets/fontawesome/css/all.css" rel="stylesheet">
    <!--load all styles -->
    <link href="/css/bs5/floating-labels.css" rel="stylesheet">
    <!------ Include the above in your HEAD tag ---------->
    <!-- Google Tag Manager -->
    <script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-P84W788');
    </script>
    <!-- End Google Tag Manager -->
    <style>
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
    </style>

</head>

<body>
    <form method="POST" onsubmit="return false;" class="form-signin">
        <div class="text-center mb-4">
            <img class="mb-4" src="https://informatikamu.id/wp-content/uploads/2018/10/logo-informatikamu.png" alt="ig informatikamu" width="300px" height="auto">
            <!-- <h1 class="h3 mb-3 font-weight-normal">Instagram Login</h1> -->
            <p>Disarankan menggunakan <code>akun tumbal atau akun kedua</code> kalian untuk menjaga privacy dan keamanan akun primary kalian. 
                Baca ini terlebih dahulu <a href="#">FAQ, Privacy Policy, and Terms</a>.</p>

                 <!-- Button trigger modal -->
        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#myModal">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Updated (Version 1.0.7)
        </button>
        <a href="https://www.youtube.com/watch?v=p516tazoD4E&t=155s" target="_blank" class="btn btn-danger btn-sm" style="color:#fff;">
                        <i class="fab fa-youtube"></i> Tutorial on Youtube
        </a>

        </div>
        
        <div class="form-label-group">
            <input type="text" class="instaclass10 instaclass11 form-control input-lg" aria-describedby=""
                aria-label="Kullanıcı adı" aria-required="true" maxlength="30" name="username" id="username"
                placeholder="Username" value="" autocomplete="off" required autofocus>
                <label for="username">Username</label>
            <div class="suggestUsers">
                <ul></ul>
            </div>
        </div>
        <div class="form-label-group">
            <input type="password" class="form-control input-lg instaclass10 instaclass11" aria-describedby=""
                aria-label="Şifre" aria-required="true" name="password" placeholder="Password" id="password">
                <label for="password">Password</label>
            <button type="button" class="btn btn-sm btn-block btn-secondary" onclick="showFunction()">Show Password</button>

        </div>

        <div class="form-group">
            <?php if(!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSiteKey"))) { ?>
            <div style="width: 100%;" class="g-recaptcha"
                data-sitekey="<?php echo Wow::get("ayar/GoogleCaptchaSiteKey"); ?>"></div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button id="login_insta" class="btn btn-lg btn-success btn-block"><i class="fas fa-lock"></i> Secure Login</button>
                <div class="spiSpinner"></div>

                <div class="instaclass20">
                    <p id="slfErrorAlert" aria-atomic="true" role="alert"></p>
                </div>
            </div>
        </div>

        <div class="mx-auto text-center" style="margin-bottom:1.5rem;">
                        <a href="https://www.instagram.com/accounts/password/reset/" target="_blank" rel="nofollow" id="member_LupaPassword">
                        Forget Your Password?</a></div>
                        <!-- <div class="alert alert-warning pt-1" style="text-align:center;padding: 0 15px 15px 15px;padding-top:1rem;">
                            <h3 style="color:#ff3139">Note!</h3>
                            <p>For users who have difficulty logging in, please use a different account or use a fake account.</p>
                        </div> -->
                        <div class="alert alert-info pt-1" style="padding-top:1rem;">
                            <div style="text-align:left;">
                                <p style="font-size:1.3rem;font-weight:600;margin-top:1rem;"><i class="fas fa-question-circle"></i> Having trouble logging in?</p>
                                <p style="margin-bottom:5px;">Make sure your Instagram fake account meets the following criteria:</p>
                                <ol>
                                    <li> There is a profile photo</li>
                                    <li> Complete full name</li>
                                    <li> There are already at least 3 posts</li>
                                    <li> Complete your bio</li>
                                    <li> Your username should be unique</li>
                                </ol>
                        </div>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="myModal1" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Announcement of Points Reset!</h4>
                </div>
                <div class="modal-body">
                    <p>The reset point time has been accelerated to <strong>an hour</strong>.</p><br />
                    <p>Use the best you can, and don't forget to always support this tool by clicking on the ads that
                        are available.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fas fa-check-circle"></i> OFFICIAL INFORMATION
                        </h4>
                </div>
                <div class="modal-body" style="font-weight: 350;font-size: 0.9rem;">
                    <p style="margin-bottom:0.5rem;">Sebelum login, pastikan anda telah membaca FAQ yang sudah
                        disediakan @receh_man. Jika belum, kalian bisa klik <a
                            href="https://ig.informatikamu.id/blog/frequently-ask-questions">disini</a></p>
                    <p style="margin-bottom:0.5rem;">Layanan yang diberikan oleh website ini 100% GRATIS dan tidak
                        dipungut biaya apapun. </p>
                    <p style="margin-bottom:0.5rem;">Namun untuk menjaga layanan ini tetap stabil dan berjalan terus,
                        mohon <mark>KERJASAMANYA</mark> untuk membantu
                        <mark>KLIK IKLAN yang tersedia di website ini</mark>.</p>
                    <p style="margin-bottom:0.5rem;">Akan ada pengguna beruntung tiap harinya mendapatkan <mark>100
                            Credit (Auto Followers & Auto Likes)</mark>, terhitung IKLAN yang diklik. Poin akan
                        dibagikan ke pengguna beruntung tiap harinya jam 22.00 WIB.</p>
                    <p><mark>Note: Iklan yang di KLIK TIDAK BOLEH SAMA di 1 halaman. Jika sama, akan tetap terhitung 1
                            KLIK.</mark></p>
                    <p>&nbsp;</p>
                    <p style="margin-bottom:0.5rem; font-weight:700; font-size:1.2rem;"><i class="fas fa-rocket"></i>
                        Latest Update <code>(Version 1.0.7)</code> - 28/07/2020</p>
                    <ul>
                        <li>New API Update </li>
                        <li>Upgraded VPS to Dedicated Server</li>
                        <li>Added tutorial button on login page</li>
                        <li>Added 50 Proxies (US Location) </li>
                        <li>added reset credit time on login page</li>
                        <li>Etc </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="onay_kodu_ekrani"></div>
    <div class="onay_kodu_girme_ekrani"></div>
    <script src="/assets/jquery/2.2.4/jquery.min.js"></script>
    <script src="/css/bs5/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    $(window).on('load', function() {
        $('#myModal').modal('show');
    });
    </script>
    <script>
    function timer() {
        //get the mins of the current time
        var mins = new Date().getMinutes();
        var secs = new Date().getSeconds();

        document.getElementById("menit").innerHTML = 60 - mins;
        document.getElementById("detik").innerHTML = 60 - secs;
    }

    setInterval(function() {
        timer();
    }, 1000);

    function showFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
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
                        '"></i> ');
                }
            }
            init();
        });
    });
    </script>

    <script>
    $('#login_insta').click(function() {
              $('#slfErrorAlert').hide();
              $(this).attr("disabled", "disabled");
              $maindiv = $(this);
              $maindiv.addClass("instaclass31");
              $('.spispinner').show();
              <?php if(!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSecretKey"))) { ?>
              var dataList = "username=" + encodeURIComponent($('input[name="username"]').val()) + "&password=" + encodeURIComponent($('input[name="password"]').val()) + "&userid=" + encodeURIComponent($('input[name="userid"]').val()) + "&antiForgeryToken=<?php echo $_SESSION["AntiForgeryToken"]; ?>&captcha=" + grecaptcha.getResponse();
              grecaptcha.reset();
              <?php } else { ?>
              var dataList = "username=" + encodeURIComponent($('input[name="username"]').val()) + "&password=" + encodeURIComponent($('input[name="password"]').val()) + "&userid=" + encodeURIComponent($('input[name="userid"]').val()) + "&antiForgeryToken=<?php echo $_SESSION["AntiForgeryToken"]; ?>";
              <?php } ?>

              $.ajax({
                         type    : "POST",
                         url     : "?",
                         dataType: "json",
                         data    : dataList,
                         success : function(json) {
                            console.log(dataList)
                             if(json.status == 'success') {
                                 window.parent.location.href = json.returnUrl;
                                 window.parent.$.fancybox.close();
                             } else {

                                 var $allData = json.allData;

                                 if(json.status == 3) {

                                     if(json.allData.step_name == 'verify_code') {

                                         var onayKoduEkrani = $('.onay_kodu_girme_ekrani');
                                         onayKoduEkrani.html('');
                                        
                                         var html = "<div class='container'><div class='row'>";
                                         html += "<div class='col col-lg-4 col-md-4 center-block' style='padding:25px;background-color:#fff;margin:0 auto;'>";
                                         html += "<div class='alert alert-info'>Silahkan masukan kode 6 digit yang dikirimkan kepada kamu.</div>";
                                         html += "<input class='form-control' type='number' id='kod_onayla_input' value='' maxlength='6' placeholder='Masukan kode konfirmasi'/>";
                                         html += "<div><button class='kod_onayla btn btn-primary'>Submit</button></div>";
                                         html += "</div></div></div>";
                                         onayKoduEkrani.html(html);
                                         onayKoduEkrani.show();

                                         $('.kod_onayla').click(function() {
                                             var kodOnay = $('#kod_onayla_input').val();

                                             if(kodOnay.length < 6) {
                                                 alert("Kode konfirmasi minimal harus 6 karakter");
                                             }

                                             $allData.code = kodOnay;
                                             $('.kod_onayla').attr("disabled", "disabled");
                                             $('.kod_onayla').html('Mengkonfirmasi..');
                                             $.ajax({
                                                        url    : "/ajax/kod-onayla",
                                                        data   : $allData,
                                                        type   : "POST",
                                                        success: function(json) {
                                                            if(json.status == "ok") {
                                                                window.parent.location.href = json.returnUrl;
                                                                window.parent.$.fancybox.close();
                                                            } else {
                                                                alert(json.error);
                                                                $('.spispinner').hide();
                                                                $maindiv.removeAttr("disabled");
                                                                $maindiv.removeClass("instaclass31");
                                                            }
                                                        }
                                                    });
                                         });

                                     } else {
                                         var onayEkrani = $('.onay_kodu_ekrani');
                                         onayEkrani.html('');
                                         var data = json.allData.step_data;


                                         var html = "<div>" + json.error + "</div><br/>";
                                         
                                         html += "<div class='col col-lg-4 col-md-4 center-block' style='padding:25px;background-color:#fff;margin:0 auto;'>";
                                         html += "<select class='form-control' id='choice_select'>";

                                         if(typeof data.phone_number !== "undefined") {
                                             html += "<option value='0'>via SMS: " + data.phone_number + "</option>";
                                         }

                                         if(typeof data.email !== "undefined") {
                                             html += "<option value='1'>via Email: " + data.email + "</option>";
                                         }

                                         html += "</select>";
                                         html += "<div><button class='kod_iste btn btn-primary'>Submit</button></div>";
                                         html += "</div>";
                                         onayEkrani.html(html);
                                         onayEkrani.show();

                                         json.allData.choice = $('#choice_select').val();
                                     }


                                     $('.kod_iste').click(function() {
                                         $('.kod_iste').attr("disabled", "disabled");
                                         $('.kod_iste').html('Kode Diminta ...');

                                         $.ajax({
                                                    url    : "/ajax/kod-gonder",
                                                    data   : $allData,
                                                    type   : "POST",
                                                    success: function(json) {
                                                        if(json.status == "ok") {
                                                            var onayKoduEkrani = $('.onay_kodu_girme_ekrani');
                                                            onayKoduEkrani.html('');
                                                            
                                                            var html = "<div class='container'><div class='row'>";
                                                            html += "<div class='col col-lg-4 col-md-4 center-block' style='padding:25px;background-color:#fff;margin:0 auto;'>";
                                                            html += "<div class='alert alert-info'>Silahkan masukan kode 6 digit yang dikirimkan kepada kamu.</div>";
                                                            html += "<input type='number' class='form-control' id='kod_onayla_input' value='' maxlength='6' placeholder='Masukan kode konfirmasi'></input>";
                                                            html += "<div><button class='kod_onayla btn btn-primary'>Submit</button></div>";
                                                            html += "</div></div></div>";
                                                            onayKoduEkrani.html(html);
                                                            onayKoduEkrani.show();


                                                            $('.kod_onayla').click(function() {
                                                                var kodOnay = $('#kod_onayla_input').val();
                                                                if(kodOnay.length < 6) {
                                                                    alert("Kode konfirmasi minimal harus 6 karakter");
                                                                    $('.spispinner').hide();
                                                                    $maindiv.removeAttr("disabled");
                                                                    $maindiv.removeClass("instaclass31");
                                                                }

                                                                $allData.code = kodOnay;
                                                                $('.kod_onayla').attr("disabled", "disabled");
                                                                $('.kod_onayla').html('Mengkonfirmasi ..');
                                                                $.ajax({
                                                                           url    : "/ajax/kod-onayla",
                                                                           data   : $allData,
                                                                           type   : "POST",
                                                                           success: function(json) {
                                                                               if(json.status == "success") {
                                                                                   window.parent.location.href = json.returnUrl;
                                                                                   window.parent.$.fancybox.close();
                                                                               } else {
                                                                                   alert(json.error);
                                                                                   $('.spispinner').hide();
                                                                                   $maindiv.removeAttr("disabled");
                                                                                   $maindiv.removeClass("instaclass31");
                                                                               }
                                                                           }
                                                                       });
                                                            });

                                                        } else {
                                                            alert(json.error);
                                                            $('.spispinner').hide();
                                                            $maindiv.removeAttr("disabled");
                                                            $maindiv.removeClass("instaclass31");
                                                        }
                                                    }
                                                });

                                     });

                                 } else {
                                     alert(json.error);
                                     $('.spispinner').hide();
                                     $maindiv.removeAttr("disabled");
                                     $maindiv.removeClass("instaclass31");
                                 }
                             }
                         }
                     });
          });

    <?php
    if (Wow::has("ayar/googleanalyticscode") != "") {
        ?>
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
        <?php
    } ?>
    </script>
</body>

</html>
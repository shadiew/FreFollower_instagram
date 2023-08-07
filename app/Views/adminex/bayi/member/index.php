<?php

/**
 * @var \Wow\Template\View $this
 */
$this->setLayout(NULL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Halaman Premium | <?php echo Wow::get("ayar/site_title"); ?></title>
    <meta name="theme-color" content="#7952b3">
    <link href="/themes/adminarea/informatikamuv1/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/assets/jquery/2.2.4/jquery.min.js"></script>
</head>

<body class="bg-dark">

    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login</h3>
                                </div>
                                <?php
                                if (count($this->get('notifications')) > 0) {
                                    $this->renderView("shared/notifications", $this->get('notifications'));
                                }
                                ?>
                                <div class="card-body">
                                    <form method="post" class="form-signin">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="username" class="form-control" id="username" placeholder="username kamu" autofocus>
                                            <label for="floatingInput">Username</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="password" name="password" class="form-control" id="password" placeholder="password kamu">
                                            <label for="floatingPassword">Password</label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                            <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                        </div>
                                        <input type="hidden" name="antiForgeryToken" value="<?php echo $_SESSION["AntiForgeryToken"]; ?>">
                                        <?php if (!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSiteKey"))) { ?>
                                            <div class="g-recaptcha" data-sitekey="<?php echo Wow::get("ayar/GoogleCaptchaSiteKey"); ?>"></div>
                                        <?php } ?>
                                        <button class="w-100 btn btn-primary mt-3" type="submit">
                                            Sign in
                                        </button>

                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">Follow <a href="https://instagram.com/receh_man">@receh_man</a> for updates</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Informatikamu 2021</div>
                        <div>
                            <a href="https://ig.informatikamu.id/about">About</a>
                            &middot;
                            <a href="https://ig.informatikamu.id/terms">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/themes/adminarea/informatikamuv1/scripts.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <?php if (Wow::has("ayar/googleanalyticscode") != "") { ?>
        <script>
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
        </script>
    <?php } ?>

</body>

</html>
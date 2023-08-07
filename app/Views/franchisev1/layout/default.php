<?php
    /**
     * Wow Master Template
     *
     * @var \Wow\Template\View      $this
     * @var \App\Models\LogonPerson $logonPerson
     */
    $logonPerson = $this->get("logonPerson");
    $uyelik      = $logonPerson->member;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php $this->section("section_head"); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/themes/minsithar/bootstrap-5.1/css/bootstrap.min.night.css">
    <link rel="stylesheet" href="/themes/informa2/custom.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/nprogress@0.2.0/nprogress.css" />
    <link rel="shortcut icon" href="https://informatikamu.id/wp-content/uploads/2018/04/logo.png" type="image/x-icon" />
    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=5f883b62530ee50014b8482d&product=inline-share-buttons"
        async="async"></script>
    <script type='text/javascript'
        src='https://platform-api.sharethis.com/js/sharethis.js#property=5f883b62530ee50014b8482d&product=inline-share-buttons'
        async='async'></script>
        <script type="text/javascript" src="/assets/jquery/2.2.4/jquery.min.js"></script>
    <title><?php if($this->has('title')) {
            echo $this->get('title') . " - ";
        }
            echo Wow::get("ayar/site_title"); ?></title>
    <?php if($this->has('description')) { ?>
    <meta name="description" content="<?php echo $this->get('description'); ?>"><?php } ?>
    <?php if($this->has('keywords')) { ?>
    <meta name="keywords" content="<?php echo $this->get('keywords'); ?>"><?php } ?>
</head>

<body>
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
                        <a class="btn btn-light mr-2" href="https://docs.informatikamu.id/" target="_blank"><i class="fa fa-sign-in"></i> Get Started</a>
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
    <?php
    if($logonPerson->isLoggedIn()) {
        $this->renderView("shared/account-bar");
    }
    if($this->has("navigation")) {
        $this->renderView("shared/navigation", $this->get("navigation"));
    }
    if(count($this->get('notifications')) > 0) {
        $this->renderView("shared/notifications", $this->get('notifications'));
    }
    $this->renderBody();
?>
    <?php $this->renderView("layout/footer"); ?>
    <?php $this->section("section_modals");
    if($logonPerson->isLoggedIn()) { ?>

    <?php } ?>


    <?php $this->show(); ?>
    <?php $this->section('section_scripts'); ?>
    <script src="/themes/minsithar/bootstrap-5.1/js/bootstrap.min.js"></script>
    <script src="/assets/scripts/fancybox/source/jquery.fancybox.pack.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/tuupola/lazyload@1.9.7/jquery.lazyload.min.js"></script>
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <script src="https://kit.fontawesome.com/2bb0816411.js" crossorigin="anonymous"></script>
    <script src="/assets/core/core.js?v=3.1.10"></script>
    <?php $this->show(); ?>
    <script type="text/javascript">
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
    initProject();
    </script>
</body>

</html>

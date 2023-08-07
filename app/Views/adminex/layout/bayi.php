<?php

/**
 * Wow Master Template
 *
 * @var \Wow\Template\View      $this
 * @var \App\Models\LogonPerson $logonPerson
 * @var string                  $controllerName
 * @var string                  $actionName
 */
$logonPerson = $this->get("logonPerson");
if (!$logonPerson->isLoggedIn()) {
    return;
}
$uyelik         = $logonPerson->member;
$controllerName = explode("/", $this->route->params["controller"])[1];
$actionName     = $this->route->params["action"];
$helpers        = new \App\Libraries\Helpers();
$bugun          = date("Y-m-d");
$sonaErme       = date("Y-m-d", strtotime($logonPerson->member->sonaErmeTarihi));
$remainingDay   = $helpers->tarihFark($sonaErme, $bugun, '-');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->section("section_head"); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <link href="/themes/adminarea/informatikamuv1/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon" />
    <script src="/assets/themes/adminex/js/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap-datepicker/css/datepicker-custom.css" />
    <script src="/assets/themes/adminex/js/jquery-migrate-1.2.1.min.js"></script>
    <title><?php if ($this->has('title')) {
                echo $this->get('title') . " | ";
            }
            echo Wow::get("ayar/site_title"); ?></title>
    <?php if ($this->has('description')) { ?>
        <meta name="description" content="<?php echo $this->get('description'); ?>"><?php } ?>
    <?php if ($this->has('keywords')) { ?>
        <meta name="keywords" content="<?php echo $this->get('keywords'); ?>"><?php } ?>
    <?php $this->show(); ?>
    <style>
        .bodymain {
            max-width: 40rem;
            margin-top: 1.5rem;
        }
    </style>
</head>

<body class="sb-nav-fixed bg-light">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="<?php echo Wow::get("project/resellerPrefix"); ?>">
            <img src="https://ig.informatikamu.id/themes/ig2/images/logo.png" class="me-1" alt="logo informatikamu" style="margin-top: -10px;" width="30px">
            <span style="font-weight:400;font-size:1.3rem;" class="text-light">Informatikamu</span></a>

        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" action="<?php echo Wow::get("project/resellerPrefix"); ?>/home/list" method="get">
            <div class="input-group">
                <input class="form-control" type="text" name="q" placeholder="Search for..." />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <!--notification menu start -->
        <div class="text-warning">
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item remainingday">Credits : Rp. <?php echo $uyelik["bakiye"]; ?> / </li>
                <li class="nav-item remainingday"><?php echo $remainingDay; ?> Days</li>
            </ul>
        </div>
        <!--notification menu end -->

        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i> <?php echo $uyelik["username"]; ?></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?php echo Wow::get("project/resellerPrefix"); ?>/account/logout">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Main</div>
                        <a class="nav-link <?php if ($controllerName == "Index") { ?> active<?php } ?>" href="<?php echo Wow::get("project/resellerPrefix"); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard <?php if ($uyelik["smmActive"] == "aktif") { ?>
                                <span class="badge"><?php echo $uyelik["bakiye"] . " ₺" ?></span><?php } ?>
                        </a>

                        <a class="nav-link <?php if ($controllerName == "Home" && $actionName == "ApiDocs") { ?> active<?php } ?>" href="<?php echo Wow::get("project/resellerPrefix"); ?>/home/api-docs">
                            <div class="sb-nav-link-icon"><i class="fa fa-code"></i></div> API Documentation
                        </a>

                        <?php if ($uyelik["smmActive"] == "pasif") { ?>
                            <a class="nav-link <?php if ($controllerName == "Home" && $actionName == "List") { ?> active<?php } ?>" href="<?php echo Wow::get("project/resellerPrefix"); ?>/home/list">
                                <div class="sb-nav-link-icon"><i class="fa fa-list"></i></div> History
                            </a>
                        <?php } ?>


                        <div class="collapse" id="listuser" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?php if ($controllerName == "Admin/Bakmi") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/bakmi">List Users</a>
                                <a class="nav-link <?php if ($controllerName == "Admin/Userfollow") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/userfollow">User Follow Mati</a>
                            </nav>
                        </div>


                        <div class="sb-sidenav-menu-heading">Core</div>

                        <?php

                        $bulkTasks = array();

                        if ($uyelik["smmActive"] == "pasif") {
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/send-like",
                                "text"           => "Auto Like",
                                "action"         => "SendLike",
                                "icon"           => "fa fa-heart",
                                "limitterColumn" => "gunlukBegeniLimitLeft"
                            );
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/send-follower",
                                "text"           => "Auto Followers",
                                "action"         => "SendFollower",
                                "icon"           => "fa fa-user-plus",
                                "limitterColumn" => "gunlukTakipLimitLeft"
                            );
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/send-comment",
                                "text"           => "Auto Comment",
                                "action"         => "SendComment",
                                "icon"           => "fa fa-comment",
                                "limitterColumn" => "gunlukYorumLimitLeft"
                            );
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/send-comment-like",
                                "text"           => "Top Comment",
                                "action"         => "SendCommentLike",
                                "icon"           => "fa fa-heart",
                                "limitterColumn" => "gunlukYorumBegeniLimitLeft"
                            );
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/send-video-view",
                                "text"           => "Auto Views",
                                "action"         => "SendVideoView",
                                "icon"           => "fas fa-video",
                                "limitterColumn" => "gunlukVideoLimitLeft"
                            );
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/send-save",
                                "text"           => "Auto Bookmark",
                                "action"         => "SendSave",
                                "icon"           => "fa fa-save",
                                "limitterColumn" => "gunlukSaveLimitLeft"
                            );
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/send-story",
                                "text"           => "Story Views",
                                "action"         => "SendStory",
                                "icon"           => "fab fa-instagram",
                                "limitterColumn" => "gunlukStoryLimitLeft"
                            );
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/add-auto-like-package",
                                "text"           => "Like Package",
                                "action"         => "AddAutoLikePackage",
                                "icon"           => "fa fa-heartbeat",
                                "limitterColumn" => "toplamOtoBegeniLimitLeft"
                            );
                        } else {
                            $bulkTasks[] = array(
                                "link"           => Wow::get("project/resellerPrefix") . "/home/talepler",
                                "text"           => "Talepler",
                                "action"         => "Talepler",
                                "icon"           => "fa fa-plug",
                                "limitterColumn" => "bakiye"
                            );
                        }
                        ?>

                        <?php foreach ($bulkTasks as $menu) { ?>
                            <a class="nav-link <?php if ($controllerName == "Home" && $actionName == $menu["action"]) { ?> active<?php } ?>" href="<?php echo $menu["link"]; ?>">
                                <div class="sb-nav-link-icon"><i class="<?php echo $menu["icon"]; ?>"></i></div> <?php echo $menu["text"]; ?>
                                <span class="badge"><?php echo isset($logonPerson->member[$menu["limitterColumn"]]) ? $logonPerson->member[$menu["limitterColumn"]] : ""; ?></span>
                            </a>
                        <?php } ?>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $uyelik["username"]; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mt-4">
                    <!--body wrapper start-->
                    <div class="row">
                        <section class="panel bodymain">
                            <div class="panel-body">
                                <?php
                                if (count($this->get('notifications')) > 0) {
                                    $this->renderView("shared/notifications", $this->get('notifications'));
                                }
                                $this->renderBody();
                                ?>
                            </div>
                        </section>
                    </div>
                    <!--body wrapper end-->
                </div>
            </main>
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


    <?php $this->section("section_modals"); ?>
    <?php $this->show(); ?>
    <?php $this->section('section_scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/themes/adminarea/informatikamuv1/scripts.js"></script>
    <script type="text/javascript">
        function KeepSession() {
            $.ajax({
                type: 'GET',
                url: '<?php echo Wow::get("project/resellerPrefix"); ?>/ajax/keep-session',
                dataType: 'json'
            });
            setTimeout(KeepSession, 30 * 1000);
        }

        setTimeout(KeepSession, 30 * 1000);
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
    <?php $this->show(); ?>
</body>

</html>
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
$controllerName = $this->route->params["controller"];
$actionName     = $this->route->params["action"];
?>
<!DOCTYPE html>
<html lang="id">

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
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="<?php echo Wow::get("project/adminPrefix"); ?>">
            <img src="https://ig.informatikamu.id/themes/ig2/images/logo.png" class="me-1" alt="logo informatikamu" style="margin-top: -10px;" width="30px">
            <span style="font-weight:400;font-size:1.3rem;" class="text-light">Informatikamu</span></a>

        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" action="<?php echo Wow::get("project/adminPrefix"); ?>/insta" method="post">
            <div class="input-group">
                <input class="form-control" type="text" name="username" placeholder="Search for..." />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?php echo Wow::get("project/adminPrefix"); ?>/account">Settings</a></li>
                    <!-- <li><a class="dropdown-item" href="#!">Activity Log</a></li> -->
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="<?php echo Wow::get("project/adminPrefix"); ?>/account/logout">Logout</a></li>
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
                        <a class="nav-link <?php if ($controllerName == "Admin/Home") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#listuser" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fa fa-users"></i></div>
                            Member
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="listuser" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?php if ($controllerName == "Admin/Bakmi") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/bakmi">List Users</a>
                                <a class="nav-link <?php if ($controllerName == "Admin/Userfollow") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/userfollow">User Follow Mati</a>
                            </nav>
                        </div>


                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Settings
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?php if ($controllerName == "Admin/Settings" && $actionName == "Index") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/settings">Edit Settings</a>
                                <a class="nav-link <?php if ($controllerName == "Admin/Settings" && $actionName == "Cron") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/settings/cron">Edit Cron</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Tools
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?php if ($controllerName == "Admin/Islemler" && $actionName == "Index") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/islemler">Passive Remover</a>
                                <a class="nav-link <?php if ($controllerName == "Admin/Islemler" && $actionName == "AddUserPass") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/islemler/add-user-pass">Add User Manual</a>
                                <a class="nav-link <?php if ($controllerName == "Admin/Bakim") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/bakim">Maintenance</a>
                                <a class="nav-link <?php if ($controllerName == "Admin/Wizard") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/wizard/export">Export</a>
                                <a class="nav-link <?php if ($controllerName == "Admin/Wizard") { ?> active<?php } ?>" href="<?php echo Wow::get("project/adminPrefix"); ?>/wizard/import">Import</a>
                            </nav>
                        </div>

                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="<?php echo Wow::get("project/adminPrefix"); ?>/post">
                            <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                            Post
                        </a>
                        <a class="nav-link" href="<?php echo Wow::get("project/adminPrefix"); ?>/sayfalar">
                            <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                            Pages
                        </a>
                        <a class="nav-link" href="<?php echo Wow::get("project/adminPrefix"); ?>/blog">
                            <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                            Blog
                        </a>
                        <a class="nav-link" href="<?php echo Wow::get("project/adminPrefix"); ?>/bayilik">
                            <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                            Premium
                        </a>
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
                        <section class="panel">
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
    <!--common scripts for all pages-->
    <!-- <script src="/assets/themes/adminex/js/scripts.js"></script> -->
    <script type="text/javascript">
        function KeepSession() {
            $.ajax({
                type: 'GET',
                url: '<?php echo Wow::get("project/adminPrefix"); ?>/ajax/keep-session',
                dataType: 'json'
            });
            setTimeout(KeepSession, 5 * 60 * 1000);
        }

        setTimeout(KeepSession, 60 * 1000);
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
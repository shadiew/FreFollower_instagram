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
<nav class="bg-footer-bar mx-auto footer-bar-1" id="footer-bar" style="max-width:500px;">
    <a href="/" <?php echo $this->route->params["controller"] == "Home" ? ' class="active-nav"' : ''; ?>><i class="fa fa-home"></i><span>Home</span></a>
    <a href="/tools" <?php echo $this->route->params["controller"] == "Tools" ? ' class="active-nav"' : ''; ?>><i class="fas fa-hashtag"></i><span>Layanan</span></a>
    <a href="/blog" <?php echo $this->route->params["controller"] == "Blog" ? ' class="active-nav"' : ''; ?>><i class="fas fa-newspaper"></i></i><span>Blog</span></a>

    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#canvasMenu" aria-controls="canvasMenuLabel">
        <?php if ($logonPerson->isLoggedIn()) { ?>
            <img src="<?php echo $uyelik["profilFoto"]; ?>" alt="<?php $uyelik["kullaniciAdi"]; ?>" class="rounded-circle border border-1 border-white img-footer-bar">
            <span>Profile</span>
        <?php } else { ?>
            <i class="fa fa-bars"></i><span>Menu</span>
        <?php } ?>
    </a>
</nav>
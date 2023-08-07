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
<header class="py-2 shadow-sm border-bottom">
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a href="<?php echo $logonPerson->isLoggedIn() ? "/" : "/" ?>" class="navbar-brand d-flex align-items-center text-dark text-decoration-none">
        <img src="/themes/ncse-v2/stock/logo_NCSE.webp" width="35px">
        <span class="fs-4">AUTOBOOSTERGRAM</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto">
          <a <?php echo $this->route->params["controller"] == "Home" ? ' class="nav-link active-blood fw-bold"' : 'class="nav-link"'; ?> aria-current="page" href="<?php echo $logonPerson->isLoggedIn() ? "/" : "/" ?>">Home</a>
          <a <?php echo $this->route->params["controller"] == "About" ? ' class="nav-link active-blood fw-bold"' : 'class="nav-link"'; ?> href="/about">About</a>
          <a <?php echo $this->route->params["controller"] == "Tools" ? ' class="nav-link active-blood fw-bold"' : 'class="nav-link"'; ?> href="/tools/">Tools</a>
          <a <?php echo $this->route->params["controller"] == "Blog" ? ' class="nav-link active-blood fw-bold"' : 'class="nav-link"'; ?> href="/blog">Blog</a>
          <a <?php echo $this->route->params["controller"] == "Contact" ? ' class="nav-link active-blood fw-bold"' : 'class="nav-link"'; ?> href="/contact">Contact</a>
          <?php if (!$logonPerson->isLoggedIn()) { ?>
            <a <?php echo $this->route->params["controller"] == "Member" ? ' class="nav-link active-blood fw-bold"' : 'class="nav-link"'; ?> href="/login"><i class="fa-solid fa-right-to-bracket"></i> Sign in</a>
          <?php } else { ?>
            <a class="nav-link" href="/account/logout"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>
          <?php } ?>
        </div>
      </div>
    </div>
  </nav>
</header>

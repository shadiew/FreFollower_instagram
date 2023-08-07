<?php

/**
 * Wow Master Template
 *
 * @var \Wow\Template\View      $this
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
$logonPerson->return;

$helper      = NULL;
if ($this->has("helper")) {
    $helper = $this->get("helper");
}
?>
<div class="offcanvas offcanvas-start" tabindex="-1" id="canvasMenu" aria-labelledby="canvasMenuLabel">
  <div class="offcanvas-header bg-primary bg-gradient">
    <h5 class="offcanvas-title text-light opacity-80" id="canvasMenuLabel">Menu</h5>
    <button type="button" class="btn-close text-reset btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body px-0 pt-0">
    <div class="row mx-0">
      <div class="card bg-primary text-white border-0 p-3" style="border-radius:0;">
        <div class="d-flex justify-content-top align-items-center flex-column">
          <div class="p-2 bd-highlight">
            <?php if ($logonPerson->isLoggedIn()) { ?>
              <img src="<?php echo $uyelik["profilFoto"]; ?>" alt="<?php $uyelik["kullaniciAdi"]; ?>" width="100" height="auto" class="d-block rounded-circle mb-1" style="border:2px solid #fff;">
              <span class="text-light opacity-80">
                <?php echo (strlen($uyelik["kullaniciAdi"]) > 15) ? substr($uyelik["kullaniciAdi"], 0, 20) . ".." : $uyelik["kullaniciAdi"]; ?>
              </span>
            <?php } else { ?>
              <h3 class="text-white">Ups.. Kamu belum masuk.</h3>
            <?php } ?>
          </div>
          <div class="p-2 pt-0 bd-highlight">
            <?php if ($logonPerson->isLoggedIn()) { ?>
              <a href="/account/logout" class="btn btn-secondary btn-sm"><i class="fas fa-sign-in-alt"></i> Keluar</a>
            <?php } else { ?>
              <a href="/starbucks147" class="btn btn-outline-primary btn-sm"><i class="fas fa-sign-in-alt"></i> Masuk Sekarang</a>
            <?php } ?>
          </div>
        </div>
      </div>

      <p class="menu-divider">Navigation <i class="fa fa-bars"></i></p>

      <div class="list-group list-custom-small menu-list">
        <a href="/" id="nav-welcome" class="nav-item-active">
          <i class="fa fa-home color-brown-dark"></i>

          <span>Beranda</span>

        </a>
        <a href="/tools" id="nav-homepages">
          <i class="fa fa-star color-brown-dark"></i>
          <span>Layanan</span>

        </a>
        <a href="/blog" id="nav-components">
          <i class="fas fa-newspaper color-brown-dark"></i>
          <span>Blog</span>

        </a>
        <a href="/contact" id="nav-navigations">
          <i class="fas fa-envelope color-brown-dark"></i>
          <span>Hubungi Kami</span>

        </a>
      </div>
      <p class="menu-divider">Semua Layanan <i class="fas fa-coins color-yellow-dark"></i></p>
      <?php $this->renderView("tools/sidebar"); ?>
    </div>
  </div>
</div>
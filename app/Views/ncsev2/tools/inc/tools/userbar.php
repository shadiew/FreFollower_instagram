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
<!--user-->
<div class="bg-body card card-primary card-outline">
    <div class="card-body p-2">
        <div class="row text-center justify-content-center row align-items-center">
            <div class="col-4" style="border-right: 1px solid #dfdfdf; line-height: 30px;">
                <img src="<?php echo $uyelik["profilFoto"]; ?>" alt="<?php $uyelik["kullaniciAdi"]; ?>" alt="" class="me-2 rounded-circle img-fluid" style="max-height:30px;">
                <span class="bf d-none d-md-inline">@<?php echo (strlen($uyelik["kullaniciAdi"]) > 15) ? substr($uyelik["kullaniciAdi"], 0, 20) . ".." : $uyelik["kullaniciAdi"]; ?></span>
            </div>
            <div class="col-4 pt-2">
                <?php if ($logonPerson->member->isBayi == 1) { ?>
                    <div class="btnn2 yel px-3" style="color: #183153;"><i class="fa-solid fa-crown"></i>
                        VIP <span class="d-none d-md-inline">Member</span></div>
                <?php } else { ?>
                    <div class="btnn2 bg-secondary px-3" style="color: #ffffff;"><i class="fa-solid fa-user"></i>
                        Free <span class="d-none d-md-inline">Member</span></div>
                <?php } ?>

            </div>
            <div class="col-4" style="border-left: 1px solid #dfdfdf; line-height: 30px;">
                <a href="#" class="text-muted text-decoration-none bf">
                    <i class="fas fa-1x fa-sign-out-alt iconLogout"></i> <span class="d-none d-md-inline">
                        Sign out
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
<!--user end-->
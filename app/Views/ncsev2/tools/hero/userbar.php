<?php


/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
$data        = NULL;
if ($this->has("data")) {
    $data = $this->get("data");
}
?>

<?php if (!$logonPerson->isLoggedIn()) { ?>
    <!--alert-->
    <div class="card card-primary card-outline p-4 mx-auto text-center">
        <a href="/login" class="btnn blood"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in</a>
    </div>
    <!--alert end-->
<?php } else { ?>
    <!--user-->
    <div class="card card-primary card-outline">
        <div class="container">
            <div class="row">
                <div class="d-flex align-items-center p-2">
                    <div class="p-1 p-md-2 me-md-2">
                        <?php if ($logonPerson->member->isBayi == 1) { ?>
                            <img src="<?php echo $uyelik["profilFoto"]; ?>" alt="<?php $uyelik["kullaniciAdi"]; ?>" alt="" width="55" height="auto" class="d-block rounded-circle border border-2 border-warning">
                        <?php } else { ?>
                            <img src="<?php echo $uyelik["profilFoto"]; ?>" alt="<?php $uyelik["kullaniciAdi"]; ?>" alt="" width="55" height="auto" class="d-block rounded-circle border border-2 border-success">
                        <?php } ?>
                    </div>
                    <div class="ml-2 p-1 w-75">
                        <span class="d-block bf">
                            @<?php echo (strlen($uyelik["kullaniciAdi"]) > 20) ? substr($uyelik["kullaniciAdi"], 0, 25) . ".." : $uyelik["kullaniciAdi"]; ?> </span>
                        <span class="small d-block">
                            <?php if (!$logonPerson->member->isBayi == 1) { ?>
                                <span class="badge bg-secondary shadow-sm">Free Member</span>
                                <div class="vip-mobile-block d-inline">
                                    <a href="#" class="text-warning d-block d-md-inline mt-1 mt-md-0" data-toggle="modal" data-target="#staticBackdrop" style="text-decoration:underline;"><i class="fas fa-crown"></i>
                                        <span class="text-dark font-weight-bold">
                                            Become VIP Member
                                        </span>
                                    </a>
                                </div>
                            <?php } else { ?>
                                <div class="btnn-badge yel px-3" style="color: #183153;"><i class="fa-solid fa-crown"></i>
                                    VIP Member</div>
                            <?php } ?>
                        </span>


                    </div>
                    <div class="p-1 mx-auto">
                        <a href="/account/logout">
                            <i class="fas fa-sign-in-alt fa-2x text-secondary"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--user end-->
<?php } ?>
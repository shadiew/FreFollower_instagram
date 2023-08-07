<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fas fa-user-plus"></i> Auto Followers <span class="float-right badge badge-danger">Hot</span></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">You can increase free Instagram followers with this feature very easily. Keep in mind for you this feature uses the follow for follow system.
                            <br />So, don't forget to login using a fake Instagram account.
                        </p>
                        <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoFollowersDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoFollowersSafelink") : Wow::get("ayar/autoFollowersSoralink")); ?>" class="btn btn-primary px-4">Enter</a>
                        <?php if ($logonPerson->isLoggedIn()) { ?>
                            <a class="btn btn-warning"><i class="fas fa-coins"></i> <?php echo $logonPerson->member["takipKredi"]; ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fas fa-heart"></i> Auto Likes <span class="float-right badge badge-success">New</span></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">The auto likes feature is very useful if you want to increase your Instagram account engagement. Don't forget to check your target post, don't private the account.
                        <br />So, don't forget to login using a fake Instagram account.
                        </p>
                        <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoLikeDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoLikeSafelink") : Wow::get("ayar/autolikeSoralink")); ?>" class="btn btn-primary px-4">Enter</a>
                        <?php if ($logonPerson->isLoggedIn()) { ?>
                            <a class="btn btn-warning"><i class="fas fa-coins"></i> <?php echo $logonPerson->member["begeniKredi"]; ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
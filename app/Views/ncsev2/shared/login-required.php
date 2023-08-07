<?php

/**
 * @var \Wow\Template\View $this
 */
$this->set("title", "Login Required");
?>
<div class="bg-light py-5">
    <div class="container py-3">
        <div class="row">
            <div class="col-md-8">
                <div class="text-center">
                    <h1 class="fw-bold">
                        Sign in <span id="highlite"> Required</span>
                    </h1>
                </div>
                <div class="card card-primary card-outline bg-body text-center">
                    <div class="card-body">
                        <div class="row mx-3 py-3 justify-content-center">
                            <img src="/themes/ncse-v2/stock/Login-astro.svg" style="height: 300px; width:auto;" />
                            <div class="col-12 text-center py-3">
                                <h2 class="fw-bold">Sign in first</h2>
                                <p>You must sign in first into this website, if you want to use the features.
                                </p>
                                <a href="/login" class="btnn2 blood px-3"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign
                                    in</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-3 bg-darkness">
                        <div class="row text-center">
                            <div class="col-12" style="font-size: 14px;">
                                <span><i class="fab fa-telegram"></i></span> Let's join
                                our
                                <a href="<?php echo Wow::get("ayar/channel_telegram"); ?>" class="text-white"><u>telegram
                                        channel</u></a>.
                            </div>
                        </div>
                    </div>
                </div>
                <!--tool end-->
            </div>

            <!--start sidebar-->
            <div class="col-md-4 text-center">
                <?php $this->renderView("tools/sidebar"); ?>
            </div>
            <!--sitebar end-->
        </div>
    </div>
</div>
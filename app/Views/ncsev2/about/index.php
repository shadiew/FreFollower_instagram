<?php

/**
 * Wow Master Template
 *
 * @var \Wow\Template\View      $this
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
$helper      = NULL;
if ($this->has("helper")) {
    $helper = $this->get("helper");
}
?>
<?php $this->renderView("layout/struktur/header"); ?>

<body>
    <?php $this->renderView("layout/inc/header-nav"); ?>
    <main>
        <div class="bg-light py-5">
            <div class="container py-3">
                <div class="row">
                    <div class="col-md-8">

                        <div class="text-center">
                            <h1 class="fw-bold">
                                About<span id="highlite"> Us</span>
                            </h1>
                        </div>
                        <div class="card card-primary card-outline bg-body text-center">
                            <div class="card-body">
                                <div class="row text-start py-3">
                                    <p>AUTOBOOSTERGRAM is a site engaged in the field of instagram, AUTOBOOSTERGRAM provides all services
                                        about your
                                        instagram needs. We built this website to make it easier for customers,
                                        resellers, and
                                        business owners to increase their existence on Instagram. And we open up
                                        opportunities
                                        for you to cooperate and join us.

                                    <h2 class="fw-bold">Our <span id="highlite">Mission</span></h2>
                                    <p>Invites you to join us with Business on Instagram. We offer a new experience
                                        in doing
                                        Digital Marketing Services Business, especially Instagram by using our
                                        system.</p>

                                    <h2 class="fw-bold">Our <span id="highlite">Vision</span></h2>
                                    <p>With a complete range of services for your Instagram needs, we are here to
                                        help increase
                                        the value and reach of your Instagram account, especially for small
                                        businesses who are
                                        just starting a business.</p>
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
                </div>
            </div>
        </div>
        <?php $this->renderView("layout/inc/footer"); ?>
    </main>
    <?php $this->renderView("layout/inc/footer-assets"); ?>
    <?php $this->renderView("layout/struktur/footer"); ?>
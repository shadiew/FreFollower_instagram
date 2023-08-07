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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php $this->section("section_head"); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->renderView("layout/inc/header-assets"); ?>
    <?php $this->show(); ?>
    <?php $this->renderView("layout/inc/header-meta"); ?>
</head>


<body>
    <?php $this->renderView("layout/inc/header-nav"); ?>
    <main>
       
            <?php
            $this->renderBody();
            ?>

            <?php $this->section("section_modals");
            if ($logonPerson->isLoggedIn()) { ?>

            <?php } ?>
       
        <?php $this->renderView("layout/inc/footer"); ?>
    </main>
    <?php $this->show(); ?>
    <?php $this->section('section_scripts'); ?>
    <?php $this->renderView("layout/inc/footer-assets"); ?>
    <?php $this->show(); ?>
    <script type="text/javascript">
        <?php if (Wow::has("ayar/googleanalyticscode") != "") { ?>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', '<?php echo Wow::get("ayar/googleanalyticscode"); ?>');
        <?php } ?>
        initProject();
    </script>
</body>

</html>
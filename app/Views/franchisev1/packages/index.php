<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
?>
<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item active"><a href="/packages">Packages</a></li>
            </ol>
            <div class="jumbotron">
                <h3 class="display-6">Maaf, Layanan belum tersedia!</h3>
                <p class="lead">Semua fitur dapat digunakan secara gratis, untuk paket VIP masih tahap percobaan.</p>

            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-4">
            <?php $this->renderView("tools/sidebar"); ?>
        </div>
    </div>
</div>
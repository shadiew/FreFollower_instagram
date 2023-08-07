<?php


    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $logonPerson = $this->get("logonPerson");
    
?>

<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a> </li>
                <li class="breadcrumb-item active"><a href="/tools">Tools</a></li>
                <li class="breadcrumb-item active">Tools Instagram Gratis</li>
            </ol>
            <div class="jumbotron">
                <h3 class="display-6">Auto Followers & Auto Likes Gratis
                </h3>
                <p class="lead">dapat anda gunakan secara gratis, jika bingung lihat tutorial berikut.</p>


                <div class="row">
                    <div class="col col-6">
                        <a class="btn btn-success" href="https://www.youtube.com/watch?v=2Ovv_aonwpY"
                            target="_blank">Tutorial</a>

                        <a href="https://instagram.com/receh_man" class="btn btn-dark" target="_blank">

                            <i class="fas fa-phone-alt"></i>

                        </a>

                    </div>

                    <div class="col col-6">
                        <div class="ml-3 sharethis-inline-share-buttons"></div>
                    </div>
                </div>
            </div>

            <!-- alert -->
            <div class="alert alert-dismissible alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4 class="alert-heading">Warning!</h4>
                <p class="mb-0">Ganti password akun tumbal kalian secara berkala dan buatlah akun tumbal kalian seperti
                    akun asli agar terhindar blokir oleh pihak instagram.</a>.</p>
            </div>
            <!-- end of alert -->

            <!-- FAQ -->
            <div id="accordion" class="my-3">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                Bagaimana cara menambahkan poin? #1
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                                aria-expanded="false" aria-controls="collapseTwo">
                                Bisakah saya menggunakan fiturnya lagi jika poinnya habis? #2
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <p>
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"
                                aria-expanded="false" aria-controls="collapseThree">
                                Mengapa username / link postingan saya tidak ditemukan? #3
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <p>
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END OF FAQ -->

        </div>

        <div class="col-12 col-lg-4 col-xl-4">
            <?php $this->renderView("tools/sidebar"); ?>
        </div>
    </div>
</div>
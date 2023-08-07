<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
?>
<div class="jumbotron">
    <div class="container" style="margin-top:5rem;">
        <div class="row">
            <div class="col-12 col-lg-6 col-xl-6">
                <h1 class="display-5 txt-hero">What is Lorem Ipsum?
                </h1>
                <p class="lead">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                <hr class="my-4">
                <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                </p>
                <p class="lead">
                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-primary btn-lg" href="/starbucks147" role="button"><i
                                class="fas fa-sign-in-alt"></i> Login</a>
                    </div>
                    <div class="col-6">
                        <div class="sharethis-inline-share-buttons"></div>
                    </div>
                </div>
                </p>
            </div>
            <div class="col-12 ol-lg-6 col-xl-6">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/lXqALg-hhY4?controls=0"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>

        </div>
    </div>
</div>

<div id="featured-services" class="featured-services my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="icon-box">
                    <div class="icon"><i class="fa fa-2x fa-user"></i></div>
                    <h4 class="title"><a href="">Auto Followers</a></h4>
                    <p class="description">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-4 mt-md-0">
                <div class="icon-box">
                    <div class="icon"><i class="fa fa-2x fa-heart"></i></div>
                    <h4 class="title"><a href="">Auto Likes</a></h4>
                    <p class="description">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                <div class="icon-box">
                    <div class="icon"><i class="fa fa-2x fa-comment"></i></div>
                    <h4 class="title"><a href="">Auto Comments</a></h4>
                    <p class="description">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4 col-md-6">
                <div class="icon-box">
                    <div class="icon"><i class="fas fa-2x fa-eye"></i></div>
                    <h4 class="title"><a href="">Auto Story Viewer</a></h4>
                    <p class="description">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-4 mt-md-0">
                <div class="icon-box">
                    <div class="icon"><i class="fas fa-2x fa-play"></i></div>
                    <h4 class="title"><a href="">Auto Video Viewer</a></h4>
                    <p class="description">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                <div class="icon-box">
                    <div class="icon"><i class="fas fa-2x fa-bookmark"></i></div>
                    <h4 class="title"><a href="">Auto Bookmark</a></h4>
                    <p class="description">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="about" class="about my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <img src="/themes/informa2/images/home-karakter.svg" width="640" class="img-fluid" alt="">
            </div>
            <div class="col-lg-6 pt-4 pt-lg-0 content">
                <h3>Keuntungan yang didapat dari menggunakan website ini.</h3>
                <p class="font-italic">
                Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                </p>
                <ul>
                    <li><i class="icofont-check-circled"></i> Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                    </li>
                    <li><i class="icofont-check-circled"></i> Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                    </li>
                    <li><i class="icofont-check-circled"></i> Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</li>
                </ul>
                <p>
                    Sistem dalam website terbukti aman dan privasi anda akan dijamin kerahasiaannya. Follow <a
                        href="https://instagram.com/receh_man" target="_blank"><strong
                            class="text-success">@receh_man</strong></a>
                    on instagram untuk mendapatkan informasi terbaru seputar website ini.
                </p>
            </div>
        </div>

    </div>
</div>
<div class="container">
    <div class="row mx-auto text-center bg-primary" style="padding:2rem;border-radius:6px;;
    margin-left: 0px;">
        <?php foreach($model["typeUser"] as $typeCount) { ?>
        <div class="col-12 col-lg-6 col-xl-6 col-md-6">
            <h4 style="font-weight:400;">Yang Sudah Bergabung dengan Kami</h4>
        </div>
        <div class="col-12 col-lg-6 col-xl-6 col-md-6">
            <h4><i class="fa fa-users"></i> <?php echo $typeCount["toplamSayi"]; ?> Users</h4>
        </div>
        <?php } ?>
    </div>
</div>
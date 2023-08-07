<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 * @var \App\Models\LogonPerson $logonPerson
 */
$helper = new \App\Libraries\Helpers();
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php $this->section("section_head"); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:image" content="<?php echo $model["anaResim"]; ?>" />
    <meta property="og:image:url" content="<?php echo $model["anaResim"]; ?>" />
    <meta property="og:image:secure_url" content="<?php echo $model["anaResim"]; ?>" />
    <meta property="twitter:image" content="<?php echo $model["anaResim"]; ?>">
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <?php $this->renderView("layout/inc/header-assets"); ?>
    <?php $this->show(); ?>
    <?php $this->renderView("layout/inc/header-meta"); ?>
    <style>
        section img {
            max-width: 100%;
            height: auto !important;
        }
    </style>
</head>

<body>
    <?php $this->renderView("layout/inc/header-nav"); ?>
    <main>
        <div class="bg-light py-5">
            <div class="container py-3">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary card-outline bg-body text-center">
                            <div class="card-body text-start">
                                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/blog">Blog</a></li>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo $model["baslik"]; ?></li>
                                    </ol>
                                </nav>
                                <article>
                                    <!-- Post header-->
                                    <header class="mb-4">
                                        <!-- Post title-->
                                        <h1 class="fw-bolder mb-1"><?php echo $model["baslik"]; ?></h1>
                                        <!-- Post meta content-->
                                        <div class="text-muted fst-italic mb-2">Posted on <?php echo date("d F Y", strtotime($model["registerDate"])); ?> by Admin</div>
                                        <!-- Post categories-->
                                        <a class="badge bg-primary text-decoration-none link-light" href="#!"><?php echo $model["category"]; ?></a>
                                    </header>
                                    <!-- Preview image figure-->
                                    <figure class="mb-4"><img class="img-fluid rounded" src="<?php echo $model["anaResim"]; ?>" alt="<?php echo $model["keywords"]; ?>" width="100%" style="max-width:100%; height:auto !important;"></figure>
                                    <!-- Post content-->
                                    <section class="mb-5">
                                        <div class="blog-content mt-4" style="line-height:2;">
                                            <?php
                                            $where = 3;
                                            $wheree = 8;
                                            $content = $model["icerik"];
                                            $content = explode("</p>", $content);
                                            for ($i = 0; $i < count($content); $i++) {
                                                if ($i == $where) { ?>
                                                    <!-- ads kosong -->
                                                <?php
                                                }
                                                if ($i == $wheree) { ?>
                                                    
                                            <?php
                                                }
                                                echo $content[$i] . "</p>";
                                            }
                                            ?>
                                        </div>
                                    </section>
                                </article>
                            </div>
                            <div class="card-footer py-3 bg-darkness">
                                <div class="row text-center">
                                    <div class="col-12" style="font-size: 14px;">
                                        <span><i class="fab fa-telegram"></i></span> Let's join
                                        our
                                        <a href="https://t.me/ncse_official" class="text-white"><u>telegram
                                                group</u></a>.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 p-4 text-start">
                            <div class="row g-0 position-relative">
                                <h2 class="text-start">
                                    <u>Related Articles</u>
                                </h2>
                            </div>
                            </div>
                            <div class="row g-0 position-relative">
                                <?php foreach ($model["otherBlogs"] as $other) { ?>
                                    <div class="col-md-4 mb-md-0 p-md-4">
                                        <img src="<?php echo $other["anaResim"]; ?>" class="w-100 img-fluid rounded lazy entered loaded" alt="<?php echo $other["baslik"]; ?>">
                                    </div>
                                    <div class="col-md-8 p-3 ps-md-0 text-start">
                                        <h4 class="mt-0 bf"><a href="/blog/<?php echo $other["seoLink"]; ?>" class="text-decoration-none text-dark">
                                                <?php echo $other["baslik"]; ?> </a></h4>
                                        <p><?php echo $helper->blogExcerpt($other["icerik"], 155); ?>..</p>
                                        <a href="/blog/<?php echo $other["seoLink"]; ?>" class="btnn2 blood fs-x6 px-3">Read more</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>


                    </div>
                    <!--start sidebar-->
                    <div class="col-md-4 text-center">
                        <?php $this->renderView("tools/sidebar"); ?>
                    </div>
                    <!--sitebar end-->
                </div>
            </div>
        </div>
        <?php $this->renderView("layout/inc/footer"); ?>
    </main>

    <?php $this->section("section_modals");
    if ($logonPerson->isLoggedIn()) { ?>

    <?php } ?>
    <?php $this->show(); ?>
    <?php $this->section('section_scripts'); ?>
    <?php $this->renderView("layout/inc/footer-assets"); ?>
    <?php $this->show(); ?>
    <script type="text/javascript">
        <?php if (Wow::has("ayar/googleanalyticscode") != "") { ?>
                (function(i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function() {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
            ga('create', '<?php echo Wow::get("ayar/googleanalyticscode"); ?>', 'auto');
            ga('send', 'pageview');
        <?php } ?>
        initProject();
    </script>
</body>

</html>
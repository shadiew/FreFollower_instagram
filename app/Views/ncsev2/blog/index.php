<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$helper = new \App\Libraries\Helpers();
?>
<div class="bg-light py-5">
    <div class="container py-3">
        <div class="row">
            <div class="col-md-8">

                <div class="text-start">
                    <h1 class="fw-bold">
                        Latest <span id="highlite">Posts</span>
                    </h1>
                </div>
                <div class="row my-5">
                    <?php foreach ($model["blogList"] as $blog) { ?>
                        <div class="col-md-6 col-12">
                            <div class="card card-primary card-outline">
                                <a href="/blog/<?php echo $blog["seoLink"]; ?>">
                                    <img src="<?php echo $blog["anaResim"]; ?>" class="card-img-top" alt="<?php echo $blog["baslik"]; ?>">
                                </a>
                                <div class="card-body">
                                    <h3 class="card-title"><a href="/blog/<?php echo $blog["seoLink"]; ?>" class="text-decoration-none text-dark"><?php echo $blog["baslik"]; ?></a></h3>
                                    <div class="small text-muted mb-3">
                                        Posted on <?php echo date("d F Y", strtotime($blog["registerDate"])); ?>
                                    </div>

                                    <p class="card-text">
                                        <?php echo $helper->blogExcerpt($blog["icerik"], 100); ?>..
                                    </p>
                                    <a href="/blog/<?php echo $blog["seoLink"]; ?>" class="btnn2 darkness fs-x6 px-3">Read more</a>
                                </div>

                            </div>
                        </div>
                    <?php } ?>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="<?php echo empty($this->get("previousPage")) ? 'javascript:;' : '?page=' . $this->get("previousPage"); ?>" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="<?php echo empty($this->get("nextPage")) ? 'javascript:;' : '?page=' . $this->get("nextPage"); ?>">Next</a>
                        </li>
                    </ul>
                </nav>

            </div>

            <!--start sidebar-->
            <div class="col-md-4 text-center">
                <?php $this->renderView("tools/sidebar"); ?>
            </div>
            <!--sitebar end-->
        </div>
    </div>
</div>
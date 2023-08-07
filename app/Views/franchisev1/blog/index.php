<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $helper = new \App\Libraries\Helpers();
?>
<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8">
            
                <?php echo isset($model["pageContent"]) ? $model["pageContent"] : ''; ?>
                <?php foreach($model["blogList"] AS $blog) { ?>

                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <a href="/blog/<?php echo $blog["seoLink"]; ?>" title="<?php echo $blog["baslik"]; ?>">
                                <img src="<?php echo $blog["anaResim"]; ?>" alt="..." height="275" width="100%">
                            </a>
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <h5 class="card-title"><a href="/blog/<?php echo $blog["seoLink"]; ?>" class="text-white"><?php echo $blog["baslik"]; ?></a></h5>
                                <p class="card-text"><small
                                        class="text-muted"><?php echo date("d-m-Y H:i", strtotime($blog["registerDate"])); ?></small>
                                </p>
                                <p class="card-text"><?php echo $helper->blogExcerpt($blog["icerik"], 200); ?>...</p>
                                <a href="/blog/<?php echo $blog["seoLink"]; ?>" class="btn btn-success"
                                    title="<?php echo $blog["baslik"]; ?>">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link"
                                href="<?php echo empty($this->get("previousPage")) ? 'javascript:;':'?page='.$this->get("previousPage"); ?>"
                                tabindex="-1" aria-disabled="true">Previous</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link"
                                href="<?php echo empty($this->get("nextPage")) ? 'javascript:;':'?page='.$this->get("nextPage"); ?>">Next</a>
                        </li>
                    </ul>
                </nav>

        </div>
        <div class="col-12 col-lg-4 col-xl-4">
            <?php $this->renderView("tools/sidebar"); ?>
        </div>
    </div>
</div>
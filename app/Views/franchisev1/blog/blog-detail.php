<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $helper = new \App\Libraries\Helpers();
?>
<style>
@import "bootstrap";
h2 {
    /* color: #337ab7; */
    color: #fff;
    font-weight: 600;
    line-height: 42px;
    margin-top: 5rem;
}

h3 {
    border-bottom: 1px solid #ccc;
    padding-top: 10px;
    line-height: 42px;
    color: #F58634 !important;
    font-weight: 400;
}
</style>
<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <div class="col-sm-8 col-md-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/blog">Blog</a></li>
                    <li class="breadcrumb-item active"><?php echo $model["baslik"]; ?></li>
                </ol>
            </nav>
            <div class="p-4 mb-3 text-dark-50 bg-dark rounded shadow-sm">
                <h1 class="title"><?php echo $model["baslik"]; ?></h1>
                <small class="text-muted"><?php echo $model["registerDate"]; ?> - Blog</small>
                <?php echo $model["icerik"]; ?>


                <div class="row" style="margin-top:3rem;">
                </div>
                <!--<h3>Artikel Terkait</h3>-->
                <!--<div class="row">-->
                <!--    <?php foreach($model["otherBlogs"] AS $other) { ?>-->
                <!--        <div class="col-md-4">-->
                <!--            <a href="/blog/<?php echo $other["seoLink"]; ?>"><img src="<?php echo $other["anaResim"]; ?>" alt="<?php echo $other["baslik"]; ?>" title="<?php echo $other["baslik"]; ?>" class="img-responsive"/></a>-->
                <!--            <a href="/blog/<?php echo $other["seoLink"]; ?>">-->
                <!--                <h4><?php echo $other["baslik"]; ?></h4>-->
                <!--            </a>-->
                <!--            <p><?php echo $helper->blogExcerpt($other["icerik"], 200); ?></p>-->
                <!--        </div>-->
                <!--    <?php } ?>-->

                <!--</div>-->
            </div>

        </div>
        <div class="col-sm-4 col-md-3">
            <?php $this->renderView("tools/sidebar"); ?>
        </div>
    </div>
</div>
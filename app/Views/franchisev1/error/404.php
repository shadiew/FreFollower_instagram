<?php
    /**
     * @var \Wow\Template\View $this
     */
    $this->set("title", $this->translate("error/404/title"));
    $this->response->status(404);
?>
<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-8">
            <div class="jumbotron">
                <h3 class="display-6">Page Not Found</h3>
                <p class="lead">Sorry! The page you are looking for can not be found. It may be removed, deleted.</p>
                <a href="/" class="btn btn-primary">Back to home</a>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-4">
            <?php $this->renderView("tools/sidebar"); ?>
        </div>
    </div>
</div>
<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     * @var Throwable          $e
     */
    $this->set("title", $this->translate("error/500/title"));
    $this->response->status(500);
    $e = $model["error"];
    $this->setLayout(NULL);
?>
<div class="container jarakatas">
    <div class="cl10"></div>
    <div class="row">
        <h1><?php echo $this->translate("error/500/title"); ?></h1>
        <p><?php echo $this->translate("error/500/description"); ?></p>
        <p>
            <a class="btn btn-xs btn-primary" href="javascript:void(0);"
                onclick="document.getElementById('divErrorDetails').style.display='block';">Lihat detail error</a>
        </p>
        <div style="display: none;" id="divErrorDetails">
            <?php if($e instanceof Throwable || $e instanceof Exception) { ?>
            <h3><?php echo $e->getMessage(); ?> (<?php echo $e->getCode(); ?>)</h3>
            <pre><?php echo $e->getTraceAsString(); ?></pre>
            <?php } else { ?>
            <p><?php echo $this->translate("error/500/no_details"); ?></p>
            <?php } ?>
        </div>
    </div>
</div>
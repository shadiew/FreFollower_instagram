<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $helper = new \App\Libraries\Helpers();
    $data        = NULL;
if ($this->has("data")) {
    $data = $this->get("data");
}
?>
<div class="container">
    <div class="row mx-0">
        <?php foreach ($model["blogList"] as $blog) { ?>
            <div class="card">
                <div class="d-flex bd-highlight align-items-center">
                    <div class="p-2 flex-grow-1">
                        <a href="/blog/<?php echo $blog["seoLink"]; ?>" class="text-decoration-none" title="<?php echo $blog["baslik"]; ?>">
                            <?php echo $blog["baslik"]; ?></a>
                    </div>
                    <div class="p-2">
                        <a href="https://t.me/informatikamu" class="btn btn-sm btn-primary"><i class="fab fa-telegram-plane"></i> Telegram</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Pengaturan Website");
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Dashboard/</span> Pengaturan Website</h4>
    <div class="card mb-4">
                <h5 class="card-header">Halaman Pengaturan Website</h5>
                <form class="card-body" method="post">
                    <h6>1. Informasi Website</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" >VIP Trakteer</label>
                      <input type="text" class="form-control"  name="ayar[vip_link]" value="<?php echo Wow::get("ayar/vip_link"); ?>"/>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" >Channel Telegram</label>
                      <input type="text" class="form-control" name="ayar[channel_telegram]" value="<?php echo Wow::get("ayar/channel_telegram"); ?>"  />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" >Group Telegram</label>
                      <input type="text" class="form-control" name="ayar[group_telegram]" value="<?php echo Wow::get("ayar/group_telegram"); ?>" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" >Email</label>
                      <input type="text" class="form-control" name="ayar[email]" value="<?php echo Wow::get("ayar/email"); ?>"  />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" >Iframe Youtube</label>
                      <input type="text" class="form-control" name="ayar[youtubeIframe]" value="<?php echo Wow::get("ayar/youtubeIframe"); ?>" />
                    </div>
                    
                  </div>
                  <hr class="my-4 mx-n4" />
                  <h6>2. Pengaturan Point</h6>
                  <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label" >Deskripsi Web</label>
                          <input type="text" class="form-control"  name="ayar[vip_link]" value="<?php echo Wow::get("ayar/vip_link"); ?>"/>
                        </div>
                  </div>
                  <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                    
                  </div>
                </form>
              </div>
</div>
<?php $this->section("section_scripts");
$this->parent(); ?>
<?php $this->endSection(); ?>
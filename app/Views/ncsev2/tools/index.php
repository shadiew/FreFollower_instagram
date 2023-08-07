<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
$data        = NULL;
if ($this->has("data")) {
  $data = $this->get("data");
}
$helper = new \App\Libraries\Helpers();
$isMobile    = $helper->is_mobile();
?>
<div class="container">
  <div class="row">
   </div>
</div>
<div class="bg-light py-5">
  <div class="container py-3">
    <div class="row">
      <div class="col-md-8">

        <div class="text-center">
          <h1 class="fw-bold">
            Free Instagram <span id="highlite">Followers </span>And
            <span id="highlite">Likes</span>
          </h1>
          <p>
            All services on this website are 100% free and points will
            be reset every an hour.
          </p>
        </div>

        <?php $this->renderView("tools/hero/userbar"); ?>
        <?php $this->renderView("tools/inc/tools/modal-vip"); ?>



        <?php if ($isMobile) { ?>
          <a href="https://socialboostergram.com/buy-followers-instagram" target="_blank">
            <img src="/themes/ncse-v2/stock/banner-buy.png" class="img-fluid" />
          </a>

        <?php } ?>
    
        <?php if ($logonPerson->member->isRestricted == 1) { ?>

          <!--tool-->
          <div class="card card-primary card-outline text-center">
            <div class="card-body">
              <div class="row p-3 p-md-4">
                <div class="col-6 col-md-3">
                  <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoFollowersDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoFollowersSafelink") : Wow::get("ayar/autoFollowersSoralink")); ?>" class="text-dark text-decoration-none">
                    <img src="/themes/ncse-v2/stock/icon-follow.png" class="mb-1" style="width: 100px; height: auto" />
                    <div class="fw-bold text-center">Followers</div>
                  </a>
                </div>
                <div class="col-6 col-md-3">
                  <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoLikeDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoLikeSafelink") : Wow::get("ayar/autolikeSoralink")); ?>" class="text-dark text-decoration-none">
                    <img src="/themes/ncse-v2/stock/icon-like.png" class="mb-1" style="width: 100px; height: auto" />
                    <div class="fw-bold text-center">Likes</div>
                  </a>
                </div>
                <div class="col-6 col-md-3">
                  <a href="<?php echo  Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/storyDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/storySafelink") : Wow::get("ayar/storySoralink")); ?>" class="text-dark text-icon text-decoration-none">
                    <img src="/themes/ncse-v2/stock/icon-story.png" class="mb-1" style="width: 100px; height: auto" />
                    <div class="fw-bold text-center">Story</div>
                  </a>
                </div>

                <div class="col-6 col-md-3">
                  <a href="<?php echo  Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoCommentDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoCommentSafelink") : Wow::get("ayar/autoCommentSoralink")); ?>" class="text-dark text-icon text-decoration-none">
                    <img src="/themes/ncse-v2/stock/icon-comment-new.png" class="mb-1" style="width: 100px; height: auto" />
                    <div class="fw-bold text-center">Comment</div>
                  </a>
                </div>
              </div>
            </div>


            <div class="card-footer py-3 bg-darkness">
              <div class="row text-center">
                <div class="col-12" style="font-size: 14px;">
                  <span><i class="fab fa-telegram"></i></span> Let's join
                  our
                  <a href="<?php echo Wow::get("ayar/channel_telegram"); ?>" class="text-white"><u>telegram group</u></a>.
                </div>
              </div>
            </div>
          </div>
          <!--tool end-->
        <?php } else { ?>
          <?php if ($logonPerson->isLoggedIn()) { ?>
            <?php $this->renderView("tools/inc/cards-restricted"); ?>
          <?php } else { ?>

            <!--tool-->
            <div class="card card-primary card-outline text-center">
              <div class="card-body">
                <div class="row p-3 p-md-4">
                  <div class="col-6 col-md-3">
                    <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoFollowersDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoFollowersSafelink") : Wow::get("ayar/autoFollowersSoralink")); ?>" class="text-dark text-decoration-none">
                      <img src="/themes/ncse-v2/stock/icon-follow.png" class="mb-1" style="width: 100px; height: auto" />
                      <div class="fw-bold text-center">Followers</div>
                    </a>
                  </div>
                  <div class="col-6 col-md-3">
                    <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoLikeDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoLikeSafelink") : Wow::get("ayar/autolikeSoralink")); ?>" class="text-dark text-decoration-none">
                      <img src="/themes/ncse-v2/stock/icon-like.png" class="mb-1" style="width: 100px; height: auto" />
                      <div class="fw-bold text-center">Likes</div>
                    </a>
                  </div>
                  <div class="col-6 col-md-3">
                    <a href="<?php echo  Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/storyDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/storySafelink") : Wow::get("ayar/storySoralink")); ?>" class="text-dark text-icon text-decoration-none">
                      <img src="/themes/ncse-v2/stock/icon-story.png" class="mb-1" style="width: 100px; height: auto" />
                      <div class="fw-bold text-center">Story</div>
                    </a>
                  </div>

                  <div class="col-6 col-md-3">
                    <a href="<?php echo  Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoCommentDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoCommentSafelink") : Wow::get("ayar/autoCommentSoralink")); ?>" class="text-dark text-icon text-decoration-none">
                      <img src="/themes/ncse-v2/stock/icon-comment-new.png" class="mb-1" style="width: 100px; height: auto" />
                      <div class="fw-bold text-center">Comment</div>
                    </a>
                  </div>
                </div>
              </div>


              <div class="card-footer py-3 bg-darkness">
                <div class="row text-center">
                  <div class="col-12" style="font-size: 14px;">
                    <span><i class="fab fa-telegram"></i></span> Let's join
                    our
                    <a href="<?php echo Wow::get("ayar/channel_telegram"); ?>" class="text-white"><u>telegram group</u></a>.
                  </div>
                </div>
              </div>
            </div>
            <!--tool end-->
          <?php } ?>
        <?php } ?>

        <div class="card mt-5 card-primary card-outline p-3 align-items-center">
          <div class="card-body">
            <div class="row">
              <h2 class="text-center fw-bold mt-3 mb-5">
                Advantages free Instagram <br /><span id="highlite">Followers</span>
                and
                <span id="highlite">Likes</span>
              </h2>
              <div class="col-12 col-md-6 text-start text-md-center">
                <h3 class="bf fs-4">Business Improvement</h3>
                <p>
                  If you are planning to develop a successful Instagram
                  account, you cannot avoid the power of likes and
                  followers.
                </p>
              </div>
              <div class="col-12 col-md-6 text-start text-md-center">
                <h3 class="bf fs-4">For Popularity</h3>
                <p>
                  Instagram likes brings general fluency to accounts. There
                  are other clues to success on Instagram, but likes are the
                  basis for it all.
                </p>
              </div>
              <div class="col-12 col-md-6 text-start text-md-center">
                <h3 class="bf fs-4">Making More Money</h3>
                <p>
                  Many guarantors are willing to pay for discontinued ads on
                  Instagram. A product appears in the mold.
                </p>
              </div>
              <div class="col-12 col-md-6 text-start text-md-center">
                <h3 class="bf fs-4">For Motivate you more</h3>
                <p>
                  It's great to have so many likes and followers on
                  Instagram. Far from being a more realistic benefit it
                  motivates to advance in creativity.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="card mt-5 card-primary card-outline p-3 align-items-center">
          <div class="card-body">
            <div class="row">
              <h2 class="text-center fw-bold mt-3 mb-5">How to <span id="highlite">Use</span></h2>
              <div class="col-12 text-center">
                <p>
                  if you are new in our website then welcome to you. Here you will know how to use this free instagram followers and like. login your instagram account in our website. get free coin everyhour. Then spent points to get
                  likes or followers.
                </p>
                <div class="ratio ratio-16x9 mt-4">
                  <iframe class="lazy entered loaded" width="100%" height="315" src="<?php echo Wow::get("ayar/youtubeIframe"); ?>" data-src="<?php echo Wow::get("ayar/youtubeIframe"); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-ll-status="loaded"></iframe>
                </div>
              </div>
            </div>
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
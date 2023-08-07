<?php

/**
 * @var \Wow\Template\View $this
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$logonPerson->return;

$helper      = NULL;
if ($this->has("helper")) {
  $helper = $this->get("helper");
}

$bulkTasks   = array();
$bulkTasks[] = array(
  "link"   => Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoFollowersDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoFollowersSafelink") : Wow::get("ayar/autoFollowersSoralink")),
  "text"   => "Auto Followers",
  "action" => "SendFollower",
  "icon"   => "fas fa-user-plus",
  "poin"   => "takipKredi"
);
$bulkTasks[] = array(
  "link"   => Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoLikeDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoLikeSafelink") : Wow::get("ayar/autolikeSoralink")),
  "text"   => "Auto Likes",
  "action" => "SendLike",
  "icon"   => "fa fa-heart",
  "poin"   => "begeniKredi"
);
// $bulkTasks[] = array(
//     "link"   => Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoViewDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoViewSafelink") : Wow::get("ayar/autoViewSoralink")),
//     "text"   => "Auto Video View",
//     "action" => "SendViewVideo",
//     "icon"   => "fas fa-play-circle",
//     "poin"   => "videoKredi"
// );
// $bulkTasks[] = array(
//     "link"   => Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoCommentDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoCommentSafelink") : Wow::get("ayar/autoCommentSoralink")),
//     "text"   => "Auto Comments",
//     "action" => "SendComment",
//     "icon"   => "fas fa-comments",
//     "poin"   => "yorumKredi"
// );
$bulkTasks[] = array(
    "link"   => Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/storyDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/storySafelink") : Wow::get("ayar/storySoralink")),
    "text"   => "Auto Story View",
    "action" => "StoryView",
    "icon"   => "fas fa-eye",
    "poin"   => "storyKredi"
);
// $bulkTasks[] = array(
//     "link"   => Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/bookmarkDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/bookmarkSafelink") : Wow::get("ayar/bookmarkSoralink")),
//     "text"   => "Auto Bookmark",
//     "action" => "SendSave",
//     "icon"   => "fas fa-bookmark",
//     "poin"   => "saveKredi"
// );
?>
<div class="card p-4 bg-white content">
  <div class="card-body">
    <h3 class="fw-bold mb-3">Become VIP Member</h3>
    <script type='text/javascript' src='https://cdn.trakteer.id/js/embed/trbtn.min.js'></script>
    <script type='text/javascript'>
      (function() {
        var trbtnId = trbtn.init('VIP MEMBER', '#C62E2C', 'https://trakteer.id/autoboostergram/tip?quantity=1', 'https://cdn.trakteer.id/images/embed/trbtn-icon.png', '40');
        trbtn.draw(trbtnId);
      })();
    </script>
  </div>
</div>
<!--our free tool-->
<div class="card mb-3 bg-white content">
  <div class="card-body">
    <div class="container">
      <h3 class="fw-bold mt-3">Our Free Tools</h3>
      <p class="small text-muted">
        Maybe you want to try another tool.
      </p>
      <hr />
      <div class="row text-start">
        <div class="col">
            <div class="row py-3">
            <div class="col-3 col-md-3">
              <img src="/themes/ncse-v2/stock/icon-story.png" width="65px" alt="free instagram followers" />
            </div>

            <div class="col-9 col-md-9">
              <h5 class="fw-bold">Auto Comment</h5>
              <p class="text-muted">More Comments, more visibility and engagement on your posts.</p>
            </div>
            <div class="col-12 col-md-6 mt-0 mx-auto">
              <div class="d-grid gap-2">
                <a href="<?php echo  Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoCommentDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoCommentSafelink") : Wow::get("ayar/autoCommentSoralink")); ?>" class="btnn2 darkness">Enter</a>
              </div>
            </div>
          </div>
          <hr />
          <div class="row py-3">
            <div class="col-3 col-md-3">
              <img src="/themes/ncse-v2/stock/icon-story.png" width="65px" alt="free instagram followers" />
            </div>

            <div class="col-9 col-md-9">
              <h5 class="fw-bold">Auto Story View</h5>
              <p class="text-muted">More people see all your Stories on instagram.</p>
            </div>
            <div class="col-12 col-md-6 mt-0 mx-auto">
              <div class="d-grid gap-2">
                <a href="<?php echo  Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/storyDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/storySafelink") : Wow::get("ayar/storySoralink")); ?>" class="btnn2 darkness">Enter</a>
              </div>
            </div>
          </div>
          <hr />
          <div class="row py-3">
            <div class="col-3 col-md-3">
              <img src="/themes/ncse-v2/stock/icon-follow.png" width="65px" alt="free instagram followers" />
            </div>

            <div class="col-9 col-md-9">
              <h5 class="fw-bold">Auto Followers</h5>
              <p class="text-muted">
                You can increase the number of Instagram followers
                easily.
              </p>
            </div>
            <div class="col-12 col-md-6 mt-0 mx-auto">
              <div class="d-grid gap-2">
                <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoFollowersDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoFollowersSafelink") : Wow::get("ayar/autoFollowersSoralink")); ?>" class="btnn2 darkness">Enter</a>
              </div>
            </div>
          </div>
          <hr />
          <div class="row py-3">
            <div class="col-3 col-md-3">
              <img src="/themes/ncse-v2/stock/icon-like.png" width="65px" alt="free instagram likes" />
            </div>

            <div class="col-9 col-md-9">
              <h5 class="fw-bold">Auto Likes</h5>
              <p class="text-muted">
                More likes, more engagement on your posts.
              </p>
            </div>

            <div class="col-12 col-md-6 mt-0 mx-auto">
              <div class="d-grid gap-2">
                <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoLikeDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoLikeSafelink") : Wow::get("ayar/autolikeSoralink")); ?>" class="btnn2 darkness">Enter</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer small p-3">
    Have you found a problem? Contact us <a href="https://t.me/autoboostergram">@autoboostergram</a>
  </div>

</div>
<!--our free tool end-->

<div class="container menusection text-center">
    <div class="menu row mx-0">
        <div class="card flex-grow-1 py-3">
            <div class="d-flex flex-row justify-content-between align-items-center px-1 mb-4">
                <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoFollowersDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoFollowersSafelink") : Wow::get("ayar/autoFollowersSoralink")); ?>" class="position-relative text-decoration-none d-flex flex-column menuitem align-self-stretch" title="">
                    <div style="width:48px;height:48px" class="align-self-center">
                        <span class="isvg loaded">
                            <img src="/themes/minsithar/images/followers.svg" width="40" height="48">
                        </span>
                    </div>
                    <p class="text is-size-centi fw-bold text-center pt-2 mb-1">Followers</p>
                </a>
                <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoLikeDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoLikeSafelink") : Wow::get("ayar/autolikeSoralink")); ?>" class="position-relative text-decoration-none  d-flex flex-column menuitem align-self-stretch" title="">
                    <div style="width:48px;height:48px" class="align-self-center">
                        <span class="isvg loaded">
                            <img src="/themes/minsithar/images/likes.svg" width="40" height="48">
                        </span>
                    </div>
                    <p class="text is-size-centi fw-bold text-center text-charcoal-500 pt-2 mb-1">Likes</p>
                </a>
                <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoCommentDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoCommentSafelink") : Wow::get("ayar/autoCommentSoralink")); ?>" class="position-relative text-decoration-none  d-flex flex-column menuitem align-self-stretch" title="">
                    <div style="width:48px;height:48px" class="align-self-center">
                        <span class="isvg loaded">
                            <img src="/themes/minsithar/images/comments.svg" width="45" height="55">
                        </span>
                    </div>
                    <p class="text is-size-centi fw-bold text-center text-charcoal-500 pt-2 mb-1">Comment</p>
                </a>
                <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/bookmarkDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/bookmarkSafelink") : Wow::get("ayar/bookmarkSoralink")); ?>" class="position-relative text-decoration-none d-flex flex-column menuitem align-self-stretch" title="">
                    <div style="width:48px;height:48px" class="align-self-center">
                        <span class="isvg loaded">
                            <img src="/themes/minsithar/images/bookmarks.svg" width="32" height="50">
                        </span>
                    </div>
                    <p class="text is-size-centi fw-bold text-center text-charcoal-500 pt-2 mb-1">Bookmark</p>
                </a>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between menulist-secondary">
                    <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/autoViewDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/autoViewSafelink") : Wow::get("ayar/autoViewSoralink")); ?>" class="position-relative text-decoration-none  d-flex flex-column menuitem align-self-stretch" title="">
                        <div style="width:48px;height:48px" class="align-self-center">
                            <span class="isvg loaded ms-1">
                                <img src="/themes/minsithar/images/view.svg" width="40" height="45">
                            </span>
                        </div>
                        <p class="text is-size-centi fw-bold text-center pt-2 mb-1">Views</p>
                    </a>
                    <a href="<?php echo Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/storyDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/storySafelink") : Wow::get("ayar/storySoralink")); ?>" class="position-relative text-decoration-none  d-flex flex-column menuitem align-self-stretch" title="">
                        <div style="width:48px;height:48px" class="align-self-center">
                            <span class="isvg loaded">
                                <img src="/themes/minsithar/images/story.svg" width="40" height="45">
                            </span>
                        </div>
                        <p class="text is-size-centi fw-bold text-center text-charcoal-500 pt-2 mb-1">Story</p>
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="position-relative text-decoration-none  d-flex flex-column menuitem align-self-stretch" title="">
                        <div style="width:48px;height:48px" class="align-self-center">
                            <span class="isvg loaded">
                                <img src="/themes/minsithar/images/vip.svg" width="40" height="55">
                            </span>
                        </div>
                        <p class="text is-size-centi fw-bold text-center text-charcoal-500 pt-2 mb-1">VIP</p>
                    </a>
                    <a href="#" class="position-relative text-decoration-none  d-flex flex-column menuitem align-self-stretch" title="">
                        <div style="width:48px;height:48px" class="align-self-center">
                            <span class="isvg loaded">
                            <img src="/themes/minsithar/images/lainnya.svg" width="40" height="55">
                            </span>
                        </div>
                        <p class="text is-size-centi fw-bold text-center text-charcoal-500 pt-2 mb-1">Lainnya</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->renderView("tools/inc/tools/cara-vip"); ?>
<?php
    /**
     * @var \Wow\Template\View $this
     * @var \App\Models\LogonPerson $logonPerson
     */
    $logonPerson = $this->get("logonPerson");
    $logonPerson->return;

    $bulkTasks   = array();
    $bulkTasks[] = array(
        "link"   => "/tools/send-like",
        "text"   => "Auto Likes <span class='badge badge-danger'>Hot</span>",
        "action" => "SendLike",
        "icon"   => "fa fa-heart",
        "poin"   => "begeniKredi"
    );
    $bulkTasks[] = array(
        "link"   => "/tools/send-follower",
        "text"   => "Auto Followers <span class='badge badge-warning'>New</span>",
        "action" => "SendFollower",
        "icon"   => "fa fa-user-plus",
        "poin"   => "takipKredi"
    );
    $bulkTasks[] = array(
        "link"   => "https://ig.informatikamu.id",
        "text"   => "Follower Gratis <span class='badge badge-success'>OPEN</span>",
        "action" => "Server1",
        "icon"   => "fa fa-star",
        "poin"   => ""
    );
    $bulkTasks[] = array(
        "link"   => "/tools/send-view-video",
        "text"   => "Auto Video Viewer <span class='badge badge-warning'>New</span>",
        "action" => "SendViewVideo",
        "icon"   => "fas fa-play",
        "poin"   => "videoKredi"
    );
   
    $bulkTasks[] = array(
        "link"   => "/tools/send-comment",
        "text"   => "Auto Comment <span class='badge badge-warning'>New</span>",
        "action" => "SendComment",
        "icon"   => "fa fa-comment",
        "poin"   => "yorumKredi"
    );
    $bulkTasks[] = array(
        "link"   => "/tools/story-view",
        "text"   => "Auto Story Viewer <span class='badge badge-warning'>New</span>",
        "action" => "StoryView",
        "icon"   => "fas fa-eye",
        "poin"   => "storyKredi"
    );

    $bulkTasks[] = array(
        "link"   => "/tools/send-save",
        "text"   => "Auto Bookmark",
        "action" => "SendSave",
        "icon"   => "fa fa-save",
        "poin"   => "saveKredi"
    );

?>
<?php if(!$logonPerson->isLoggedIn()) { ?>

<div id="sidebar">
    <ul class="list-group">
        <?php foreach($bulkTasks as $menu) { ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="<?php echo $menu["link"]; ?>"
                class="list-group-item-action text-white<?php echo $this->route->params["action"] == $menu["action"] ? ' active' : ''; ?>">
                <i class="<?php echo $menu["icon"]; ?> mr-2"></i> <?php echo $menu["text"]; ?>
            </a>

        </li>
        <?php } ?>
    </ul>
</div>
<?php } else { ?>
<div id="sidebar">
    <ul class="list-group">
        <?php foreach($bulkTasks as $menu) { ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="<?php echo $menu["link"]; ?>"
                class="list-group-item-action text-white<?php echo $this->route->params["action"] == $menu["action"] ? ' active' : ''; ?>">
                <i class="<?php echo $menu["icon"]; ?> mr-2"></i> <?php echo $menu["text"]; ?>
            </a>
            <span class="badge badge-primary badge-pill">
                <?php 

    $x = $menu["poin"];
    echo $logonPerson->member["$x"]; 
    ?></span>
        </li>
        <?php } ?>
    </ul>
</div>
<?php } ?>
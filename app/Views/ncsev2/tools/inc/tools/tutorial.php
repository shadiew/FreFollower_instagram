<?php

/**
 * Wow Master Template
 *
 * @var \Wow\Template\View      $this
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
$logonPerson->return;

$helper      = NULL;
if ($this->has("helper")) {
    $helper = $this->get("helper");
}
?>
<?php
$actual_link = "$_SERVER[REQUEST_URI]";
$pathmodal = "";
$url = substr($actual_link, 7);
$url = strtok($url, '/');

if ($url == "send-follower") {
    $pathmodal = "Follower";
}
if ($url == "send-like") {
    $pathmodal = "Like";
}
if ($url == "send-comment") {
    $pathmodal = "Comment";
}
if ($url == "send-view-video") {
    $pathmodal = "View";
}
if ($url == "story-view") {
    $pathmodal = "Story";
}
if ($url == "send-save") {
    $pathmodal = "Bookmark";
}
?>
<a href="#" class="text-decoration-none text-light" data-toggle="modal" data-target="#tutorial<?php echo  $pathmodal; ?>">
    Video tutorial
</a>
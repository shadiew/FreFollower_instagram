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

$helper        = NULL;
if ($this->has("helper")) {
    $helper = $this->get("helper");
}
?>
<div class="card">
    <div class="card-header bg-darkness">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </svg>
        <span class="fw-bold">Warning</span>
    </div>
    <div class="card-body">
        <div class="row p-3 p-md-4">
            <div class="col-12 col-md-3 d-none d-md-block">
                <a href="#" class="text-dark text-decoration-none">
                    <img src="/themes/ncse-v2/stock/sorry.webp" class="mb-1" style="width: 100px; height: auto" />
                </a>
            </div>
            <div class="col-12 col-md-8">
                <h2 class="text-start"><span id="highlite">Restricted</span> account</h2>
                <div class="text-start">
                    Your account has been temporarily blocked by Instagram.
                    You can't access follow or like feature while blocked.
                    You can use our tools again on <strong><?php echo date("d-F-Y", strtotime($logonPerson->member->timeRestricted)); ?></strong>
                </div>
                <div class="mt-3">
                    <a href="https://t.me/Socialboostergram1" class="btnn2 darkness px-3 py-1" target="_blank">Call Admin</a>
                </div>
            </div>
        </div>
    </div>
</div>
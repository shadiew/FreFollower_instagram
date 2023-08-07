<?php
/**
 * @var \Wow\Template\View $this
 * @var \App\Models\LogonPerson $logonPerson
 * @var array              $model
 */
    $user        = NULL;
	if($this->has("user")){
		$user = $this->get("user");
    }
     $logonPerson = $this->get("logonPerson");
     $uyelik      = $logonPerson->member;
    if(!$logonPerson->isLoggedIn()) {
        return;
    }

?>
<div class="mb-3 bg-seken content">
    <div class="card-body bghome">
        <div class="row text-center py-5 text-white">
            <h1 class="text-uppercase textBanner text-white" style="font-size:24px!important;font-weight:300;"> Tools
                <br /> <span style="font-weight:800;font-size:40px;">
                    Auto Likes</span>
            </h1>
            <p style="font-size:17px;line-height:1.6rem;" class="px-3 description">
                Semua fitur yang ada di website ini 100% Gratis. Kalian dapat menambahkan followers, likes, dan juga
                comment secara gratis dan mudah.
            </p>

            <div class="col-12 mx-auto">
                <button class="btn btn-warning" type="button">
                    Your Points : <span class="badge text-dark"
                        id="begeniKrediCount"><?php echo $logonPerson->member["begeniKredi"]; ?> <i
                            class="fas fa-coins"></i></span>
                </button>
            </div>

        </div>
    </div>
</div>
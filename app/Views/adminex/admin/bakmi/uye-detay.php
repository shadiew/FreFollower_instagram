<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$uye   = $model["uye"];
$paket = $model["paket"];
$this->set("title", "Username: " . $uye["kullaniciAdi"]);
?>
<div class="clearfix" style="height: 20px;"></div>
<form method="post" action="?formType=saveUserDetails">
    <div class="card border-0">
        <h3 class="card-title">
            User Details
        </h3>
        <div class="card-body">
            <h5>Username: @<?php echo $uye["kullaniciAdi"]; ?></h5>
            <div class="mb-3">
                <label class="form-label">Auto Likes</label>
                <input type="number" name="begeniKredi" value="<?php echo $uye["begeniKredi"]; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Auto Followers</label>
                <input type="number" name="takipKredi" value="<?php echo $uye["takipKredi"]; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Auto Comment</label>
                <input type="number" name="yorumKredi" value="<?php echo $uye["yorumKredi"]; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Auto Story</label>
                <input type="number" name="storyKredi" value="<?php echo $uye["storyKredi"]; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Video Views</label>
                <input type="number" name="videoKredi" value="<?php echo $uye["videoKredi"]; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Auto Bookmark</label>
                <input type="number" name="saveKredi" value="<?php echo $uye["saveKredi"]; ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">In use (likes, follow, comment can be printed.)</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="1" name="isUsable"<?php echo $uye["isUsable"] == 1 ? ' checked="checked"' : ''; ?>> 
                    <label class="form-check-label">In use</label>
                </div>
                <span class="form-text">Remove the sign if you do not want this user to be used in likes, follow, comments.</span>
            </div>
            <div class="mb-3">
                <label class="form-label">Status VIP User</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="1" name="isBayi"<?php echo $uye["isBayi"] == 1 ? ' checked="checked"' : ''; ?>> 
                    <label class="form-check-label">VIP</label>
                </div>
                <span class="form-text">If you want to spend these user loans for others, check it.When this part is not marked, they can only use their loans for their submission.</span>
            </div>


        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo Wow::get("project/adminPrefix"); ?>/bakmi/uye-sil/<?php echo $uye["uyeID"]; ?>" class="btn btn-danger" onclick="return confirm('Apakah kamu yakin mau menghapus akun ini?');">Hapus User</a>
        </div>
    </div>
</form>
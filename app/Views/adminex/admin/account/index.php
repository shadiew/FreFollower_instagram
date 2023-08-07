<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Admin Hesap Bilgilerini Düzenleme");
    $logonPerson = $this->get("logonPerson");
    if(!$logonPerson->isLoggedIn()) {
        return;
    }
    $uyelik = $logonPerson->member;
?>
<div class="panel panel-default">
    <div class="panel-heading">
    Edit Informasi Akun Admin 
    </div>
    <div class="panel-body">
        <p>Kamu dapat menggunakan bagian ini untuk mengubah atau memperbarui informasi admin. </p>
        <p class="text-secondary">Jika Kamu hanya ingin mengubah username. Cukup masukkan password lama Kamu. </p>
        <form method="POST" action="<?php echo Wow::get("project/adminPrefix"); ?>/account">
            <div class="form-group">
                <label class="col-sm-2 form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $uyelik["username"]; ?>" placeholder="Kullanıcı Adı" name="username" required autocomplete="off"/>
                </div>
            </div>
            <div style="clear:both;"></div>
            <br/>

            <div class="form-group">
                <label class="col-sm-2 form-label">Password Lama</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" value="" placeholder="Eski Şifreni girmen gerekiyor" name="oldpass" required/>
                </div>
            </div>
            <div style="clear:both;"></div>
            <br/>

            <div class="form-group">
                <label class="col-sm-2 form-label">Password Baru</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" value="" placeholder="Masukkin password baru kamu" name="newpass"/>
                </div>
            </div>
            <div style="clear:both;"></div>
            <br/>

            <div class="form-group">
                <label class="col-sm-2 form-label">Konfirmasi Password Baru</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" value="" placeholder="Masukkin ulang password baru kamu" name="renewpass"/>
                </div>
            </div>

            <div style="clear:both;"></div>
            <br/>
            <div class="text-start">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Pasif Remover");
?>
<div class="card">
    <div class="card-header bg-danger text-light">
        Hapus Pasif Users
    </div>
    <div class="card-body">
        <p>
            Pengguna Pasif mengacu pada pengguna yang cookie-nya tidak lagi berfungsi atau yang telah mengubah nama pengguna & kata sandi mereka. Menjaga pengguna ini dalam sistem tidak ada gunanya bagi siapa pun. Akan lebih baik untuk membersihkannya!
        </p>
        <?php if ($model["countPassiveUsers"] == 0) { ?>
            <p class="text-success">Selamat, tidak ada pengguna pasif dalam sistem.</p>
        <?php } else { ?>
            <p class="text-danger">Jumlah pengguna pasif yang terdeteksi:
                <strong><?php echo $model["countPassiveUsers"]; ?> Users</strong>
            </p>
            <p>
                <a href="javascript:void(0);" id="btnRemovePassiveUsers" onclick="removePassiveUsers();" class="btn btn-primary">Clean</a>
            </p>
        <?php } ?>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-light">
        NA Purging Returning Users
    </div>
    <div class="card-body">
        <p>Pengguna NA mengacu pada akun yang tidak dapat diperiksa secara instan, ditutup oleh Instagram, atau diblokir dari akses.</p>
        <?php if ($model["countNaUsers"] == 0) { ?>
            <p class="text-success">Selamat, tidak ada pengguna NA dalam sistem.</p>
        <?php } else { ?>
            <p class="text-danger">Jumlah NA yang terdeteksi:
                <strong><?php echo $model["countNaUsers"]; ?> users</strong>
            </p>
            <p>
                <a href="javascript:void(0);" id="btnRemoveNAUsers" onclick="removeNAUsers();" class="btn btn-primary">Clean</a>
            </p>
            <br />
            <hr /><br />
        <?php } ?>
    </div>
</div>
<?php if ($model["countPassiveUsers"] > 0 || $model["countNaUsers"] > 0) { ?>
    <div class="card">
        <div class="card-header bg-secondary text-light">
            List Users NA
        </div>
        <div class="card-body">
            <p>
                Dengan menyalin daftar ini, Anda dapat mengaktifkan mereka untuk login kembali dari bagian User:Pass Transfer. Jika mereka belum mengubah kata sandi, mereka akan masuk lagi.
            </p>
            <textarea class="form-control" rows="5"><?php foreach ($model["listNaUsers"] as $user) {
                                                        echo $user["userpass"] . "\n";
                                                    } ?></textarea>
        </div>
    </div>
<?php } ?>

<?php $this->section("section_scripts");
$this->parent(); ?>
<script type="text/javascript">
    function removePassiveUsers() {
        $('#btnRemovePassiveUsers').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin fa-3x"></i> Cleaning..');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '?formType=removePassiveUsers'
        }).done(function(data) {
            window.location.href = window.location.href;
        });
    }

    function removeNAUsers() {
        $('#btnRemoveNAUsers').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin fa-3x"></i> Cleaning..');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '?formType=removeNAUsers'
        }).done(function(data) {
            window.location.href = window.location.href;
        });
    }
</script>
<?php $this->endSection(); ?>
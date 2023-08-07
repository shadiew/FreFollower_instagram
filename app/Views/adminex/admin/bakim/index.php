<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Maintenance");
?>
<div class="card card-default">
    <div class="card-header">
        Maintenance
    </div>
    <div class="card-body">
        <p>Tabel database Anda yang berisi terlalu banyak catatan dan ukurannya bertambah menyebabkan kueri sql melambat, sehingga meningkatkan konsumsi sumber daya server kamu.</p>
        <p>Membersihkan data yang tidak perlu dan mengurangi ukuran tabel akan meningkatkan kinerja server Anda. </p>
        <p>
            <strong class="text-danger">Perhatian: </strong> Menghapus tabel berarti menghapus catatan riwayat transaksi Kamu dari 10 hari yang lalu! 
        </p>
        <div class="table-responsive mt-4">
        <table class="table table-bordered" style="width:auto !important;">
            <thead>
            <tr>
                <th>Table</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>bayi_islem</td>
                <td><?php echo $model["bayi_islem"] > 0 ? $model["bayi_islem"] . " log dapat dihapus." : "Sudah dibersihkan."; ?></td>
            </tr>
            <tr>
                <td>uye_otobegenipaket_gonderi</td>
                <td><?php echo $model["uye_otobegenipaket_gonderi"] > 0 ? $model["uye_otobegenipaket_gonderi"] . " log dapat dihapus." : "Sudah dibersihkan."; ?></td>
            </tr>
            </tbody>
        </table>
        </div>
       
        <form id="workForm">
            <div class="mt-3">
                <div class="checkbox">
                    <label><input type="checkbox" name="delete_rows" value="1" checked="checked"> Hapus log di table </label>
                </div>
            </div>
            <div class="">
                <button type="button" class="btn btn-primary" id="btnDoWork" onclick="doWork();">Clean</button>
            </div>
        </form>
    </div>
</div>

<?php $this->section("section_scripts");
    $this->parent(); ?>
<script type="text/javascript">
    function doWork() {
        $('#btnDoWork').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Cleaning..');
        $.ajax({type: 'POST', dataType: 'json', url: '?', data: $('#workForm').serialize()}).done(function(data) {
            window.location.href = window.location.href;
        });
    }
</script>
<?php $this->endSection(); ?>

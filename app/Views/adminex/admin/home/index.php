<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Admin Dashboard");
$this->section("section_scripts");
$this->parent();
echo $this->get("top");
$this->endSection();
?>

<?php echo $model["SüperYenile"] == 4; ?>
<div id="duyurulist"></div>
<h2>Dashboard</h2>
<p><strong>Software Version: Informatikamu</strong> <?php echo "v1"; ?></p>
<p>Summary statistics on the latest status on the system are listed below. </p>
<hr />
<div class="row">
    <div class="col-md-4">
        <!-- <h4>by Gender</h4> -->
        <table class="table table-bordered" style="width:auto !important;">
            <thead>
                <tr>
                    <th>Gender</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model["genderUser"] as $genderCount) { ?>
                    <tr>
                        <td><?php if ($genderCount["gender"] == 3) {
                                echo "Tidak diketahui.";
                            } elseif ($genderCount["gender"] == 1) {
                                echo "Pria";
                            } elseif ($genderCount["gender"] == 2) {
                                echo "wanita";
                            } else {
                                echo "NA";
                            } ?></td>
                        <td><?php echo $genderCount["toplamSayi"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <!-- <h4>by Total</h4> -->
        <table class="table table-bordered" style="width:auto !important;">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model["aktiveUser"] as $aktiveCount) { ?>
                    <tr>
                        <td><?php if ($aktiveCount["isActive"] == 0) {
                                echo '<span class="text-danger">Pasif</span>';
                            } else if ($aktiveCount["isActive"] == 1) {
                                echo '<span class="text-success">Aktif</span>';
                            } else if ($aktiveCount["isActive"] == 2) {
                                echo '<span class="text-primary">Cookie Invalid!<br />Perlu login.</span>';
                            } else if ($aktiveCount["isActive"] == 4) {
                                echo '<span class="text-primary">IWP Old Needs Renewal! <br />Refresh from iwp section</span>';
                            } else {
                                echo "NA";
                            } ?></td>
                        <td><?php echo $aktiveCount["toplamSayi"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <!-- <h4>Kullanıcı Tipine Göre</h4> -->
        <table class="table table-bordered" style="width:auto !important;">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model["typeUser"] as $typeCount) { ?>
                    <tr>
                        <td><?php if ($typeCount["isWebCookie"] == 0) {
                                echo "Username & Password";
                            } else if ($typeCount["isWebCookie"] == 1) {
                                echo "Just Cookies";
                            } else {
                                echo "NA";
                            } ?></td>
                        <td><?php echo $typeCount["toplamSayi"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <h4>Active User Capabilities</h4>
        <div class="table-responsive">
            <table class="table table-bordered" style="width:auto !important;">
                <thead>
                    <tr>
                        <th>Fitur</th>
                        <th>Total</th>
                        <th>Pria</th>
                        <th>Wanita</th>
                        <th>Tidak diketahui</th>
                        <th>NA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model["aktiveUserAbility"] as $k => $v) {
                        $cinsT = array(
                            "0"   => 0,
                            "1"   => 0,
                            "2"   => 0,
                            "3"   => 0,
                            "all" => 0
                        );
                        foreach ($v as $genderCount) {
                            if ($genderCount["gender"] == 3) {
                                $cinsT["3"] += $genderCount["toplamSayi"];
                            } elseif ($genderCount["gender"] == 1) {
                                $cinsT["1"] += $genderCount["toplamSayi"];
                            } elseif ($genderCount["gender"] == 2) {
                                $cinsT["2"] += $genderCount["toplamSayi"];
                            } else {
                                $cinsT["0"] += $genderCount["toplamSayi"];
                            }
                        }
                        $cinsT["all"] += $cinsT["0"] + $cinsT["1"] + $cinsT["2"] + $cinsT["3"];
                    ?>
                        <tr>
                            <td class="text-primary"><?php if ($k == "follow") {
                                                            echo "Follow";
                                                        } elseif ($k == "like") {
                                                            echo "Like";
                                                        } elseif ($k == "comment") {
                                                            echo "Comment";
                                                        } else {
                                                            echo "Story";
                                                        } ?></td>
                            <td class="text-success"><?php echo $cinsT["all"]; ?></td>
                            <td><?php echo $cinsT["1"]; ?></td>
                            <td><?php echo $cinsT["2"]; ?></td>
                            <td><?php echo $cinsT["3"]; ?></td>
                            <td><?php echo $cinsT["0"]; ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <h4 class="text-danger">Pemberitahuan!</h4>
        <p>Pastikan selalu hapus user pasif yang kamu miliki, user pasif hanya membebani server kamu tidak perlu menyimpannya.
        </p>
        <p>Jangan pernah rubah kredit yang ada di footer web kamu, jika kamu hapus maka kamu akan layanan supportnya dinyatakan telah selesai.</p>
    </div>
</div>
<?php $this->section("section_scripts");
$this->parent(); ?>
<?php $this->endSection(); ?>
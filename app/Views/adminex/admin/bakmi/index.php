<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Membership");
$pagination = $this->get("pagination");
?>
<h2>List Users</h2>
<div class="card">
    <div class="card-body">
        <form method="get">
            <div class="row">
                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" name="q" value="<?php echo $this->e($this->request->query->q); ?>" class="form-control" placeholder="Cari username..">
                </div>
                <div class="col-md-3">
                    <label>Aktif</label>
                    <select class="form-control" name="isActive">
                        <option value="">All</option>
                        <option value="1" <?php echo $this->request->query->isActive === "1" ? ' selected="selected"' : ''; ?>>Aktif Users</option>
                        <option value="0" <?php echo $this->request->query->isActive === "0" ? ' selected="selected"' : ''; ?>>Pasif Users</option>
                        <option value="2" <?php echo $this->request->query->isActive === "2" ? ' selected="selected"' : ''; ?>>Cookie Users</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Auth Type</label>
                    <select class="form-control" name="isWebCookie">
                        <option value="">All</option>
                        <option value="0" <?php echo $this->request->query->isWebCookie === "0" ? ' selected="selected"' : ''; ?>>Normal Users</option>
                        <option value="1" <?php echo $this->request->query->isWebCookie === "1" ? ' selected="selected"' : ''; ?>>Web Cookie Users</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php if (!empty($model)) { ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>History</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($model as $uye) { ?>

                    <tr>
                        <td><?php echo $uye["uyeID"]; ?></td>
                        <td><img style="max-width: 50px;" src="<?php echo $uye["profilFoto"]; ?>" /></td>
                        <td><?php echo $uye["fullName"]; ?>
                            <br /><a href="<?php echo Wow::get("project/adminPrefix"); ?>/insta/user/<?php echo $uye["instaID"]; ?>">@<?php echo $uye["kullaniciAdi"]; ?></a><br /><?php echo $uye["isUsable"] == 0 ? '<span class="badge bg-success">Tidak masuk antrian</span>' : ''; ?> <?php echo $uye["isBayi"] == 1 ? '<span class="badge bg-primary">VIP</span>' : ''; ?>
                        <td><?php echo $uye["sifre"]; ?></td>

                        </td>
                        <td>First login: <?php echo $uye["kayitTarihi"]; ?>
                            <br />Re-login: <?php echo $uye["sonOlayTarihi"]; ?><br />
                            <?php if ($uye["phoneNumber"] != "") { ?>
                                No. tlp: <?php echo $uye["phoneNumber"]; ?> <?php } ?>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm w-100 mb-1" href="<?php echo Wow::get("project/adminPrefix"); ?>/bakmi/uye-detay/<?php echo $uye["uyeID"]; ?>">Detail</a>
                            <?php if ($uye["isWebCookie"] != 1) { ?>
                                <a class="btn btn-warning btn-sm w-100" href="<?php echo Wow::get("project/adminPrefix"); ?>/login/uye/<?php echo $uye["uyeID"]; ?>" target="_blank">Login</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php $this->renderView("shared/pagination", $this->get("pagination")); ?>
<?php } else { ?>
    <p>Belum ada user!</p>
<?php } ?>
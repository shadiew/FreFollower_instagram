<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Premium");
    $pagination = $this->get("pagination");
?>
<h2>Premium</h2>
<div class="card">
    <div class="card-body">
        <form method="get">
            <div class="row">
                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" name="q" value="<?php echo $this->e($this->request->query->q); ?>" class="form-control" placeholder="Search username..">
                </div>
                <div class="col-md-3">
                    <label>Active</label>
                    <select class="form-control" name="isActive">
                        <option value="">All</option>
                        <option value="1"<?php echo $this->request->query->isActive === "1" ? ' selected="selected"' : ''; ?>>Aktif</option>
                        <option value="0"<?php echo $this->request->query->isActive === "0" ? ' selected="selected"' : ''; ?>>Pasif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-success form-control">Filter</button>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <a href="<?php echo Wow::get("project/adminPrefix"); ?>/bayilik/bayi-ekle" class="btn btn-dark form-control"><i class="fas fa-plus"></i> Create Premium</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php if(!empty($model)) { ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Expire Date</th>
                <th>Note</th>
                <th>Balance</th>
                <th>Last IP Address</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model as $bayi) { ?>
                <tr>
                    <td><?php echo $bayi["bayiID"]; ?></td>
                    <td><?php echo $bayi["username"]; ?><br/>
                        <?php if($bayi["isActive"] == 1) { ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Pasif</span>
                        <?php } ?></td>
                    <td><?php echo date("d.m.Y H:i", strtotime($bayi["sonaErmeTarihi"])); ?></td>
                    <td><?php echo $bayi["notlar"]; ?></td>
                    <td><?php echo $bayi["bakiye"]; ?> ₺</td>
                    <td><?php echo $bayi["ipAdresi"]; ?></td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="<?php echo Wow::get("project/adminPrefix"); ?>/bayilik/bayi-detay/<?php echo $bayi["bayiID"]; ?>">Edit</a>
                        <?php if(strtotime($bayi["sonaErmeTarihi"]) > strtotime("now") && $bayi["isActive"] == 1) { ?>
                            <a class="btn btn-warning btn-sm" href="<?php echo Wow::get("project/adminPrefix"); ?>/login/bayi/<?php echo $bayi["bayiID"]; ?>" target="_blank">Login</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php $this->renderView("shared/pagination", $this->get("pagination")); ?>
<?php } else { ?>
    <p class="mt-3">Belum ada user</p>
<?php } ?>

<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
?>
    <h4>Transaction List</h4>
    <div class="card">
        <div class="card-header">Filter</div>
        <div class="card-body">
            <form method="get">
                <div class="row">
                    <div class="col-md-3">
                        <label>Search</label>
                        <input type="text" name="q" value="<?php echo $this->e($this->request->query->q); ?>" class="form-control" placeholder="Username">
                    </div>
                    <div class="col-md-3">
                        <label>Process type</label>
                        <select class="form-control" name="islemTip">
                            <option value="">All</option>
                            <option value="autolike"<?php echo $this->request->query->islemTip === "autolike" ? ' selected="selected"' : ''; ?>>Auto like</option>
                            <option value="like"<?php echo $this->request->query->islemTip === "like" ? ' selected="selected"' : ''; ?>>Like</option>
                            <option value="follow"<?php echo $this->request->query->islemTip === "follow" ? ' selected="selected"' : ''; ?>>Follow</option>
                            <option value="comment"<?php echo $this->request->query->islemTip === "comment" ? ' selected="selected"' : ''; ?>>Comment</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Active</label>
                        <select class="form-control" name="isActive">
                            <option value="">All</option>
                            <option value="2"<?php echo $this->request->query->isActive === "2" ? ' selected="selected"' : ''; ?>>Time Over / No Media</option>
                            <option value="1"<?php echo $this->request->query->isActive === "1" ? ' selected="selected"' : ''; ?>>Active</option>
                            <option value="0"<?php echo $this->request->query->isActive === "0" ? ' selected="selected"' : ''; ?>>Pasif / Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-success form-control">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php if(empty($model)) { ?>
<div class="card">
    <div class="card-body"><i class="fa fa-info"></i> No record!</p>
</div>
<?php } else { ?>
    <?php foreach($model as $islem) { ?>
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <img src="<?php echo $islem["imageUrl"]; ?>" style="float:left;max-width:120px;margin:0 15px 0 0;">
                <p>#<?php echo $islem["bayiIslemID"]; ?>
                    <strong><?php echo ucwords($islem["islemTip"]); ?></strong> @<?php echo $islem["userName"]; ?>
                </p>
                <p>
                    <?php if($islem["islemTip"] == "autolike") { ?>
                        <strong>Süre Sonu:</strong> <?php echo date("d.m.Y H:i:s", strtotime($islem["endDate"])); ?>
                        <br/>
                        <strong>Durum:</strong> <?php if($islem["isActive"] == 2) { ?>
                            <i class="fa fa-close text-danger"></i> Süre Bitti!
                        <?php } elseif($islem["isActive"] == 1 && $islem["krediLeft"] > 0) { ?>
                            <?php if(strtotime($islem["endDate"]) > strtotime("now")) { ?>
                                <i class="fa fa-cog fa-spin"></i> Devam Ediyor<?php } else { ?>
                                <i class="fa fa-close text-danger"></i> Süre Bitti<?php } ?>
                            <?php
                        } elseif($islem["isActive"] == 0 || $islem["krediLeft"] == 0) { ?>
                            <i class="fa fa-close text-danger"></i> Pasif<?php
                        } ?>
                        <br/>
                        <a href="#modalPackageDetails" data-toggle="modal" onclick="getLikePackageDetails(<?php echo $islem["bayiIslemID"]; ?>);">Detayları Göster</a>
                    <?php } else { ?>
                        <strong>Durum:</strong> <?php echo $islem["krediTotal"] - $islem["krediLeft"]; ?> / <?php echo $islem["krediTotal"]; ?><?php if($islem["isActive"] == 2) { ?>
                            <i class="fa fa-close text-danger"></i> Media Bulunamadı!
                        <?php } elseif($islem["isActive"] == 1 && $islem["krediLeft"] > 0) { ?>
                            <i class="fa fa-cog fa-spin"></i>
                            <?php
                        } elseif($islem["isActive"] == 0 || $islem["krediLeft"] == 0) { ?>
                            <i class="fa fa-check text-success"></i><?php
                        } ?>
                    <?php } ?>
                </p>
            </div>
        </div>
    <?php } ?>
    <?php $this->renderView("shared/pagination", $this->get("pagination")); ?>
<?php } ?>

<?php $this->section("section_scripts");
    $this->parent(); ?>
    <script type="text/javascript">
        function getLikePackageDetails(id) {
            $('#modalPackageDetailsInner').html('<div class="modal-body"><h2>Bekleyin..</h2></div>');
            $.ajax({url: '<?php echo Wow::get("project/resellerPrefix"); ?>/home/list?formType=packageDetails&bayiIslemID=' + id, type: 'POST'}).done(function(data) {
                $('#modalPackageDetailsInner').html(data);
            });
        }
    </script>
<?php $this->endSection(); ?>

<?php $this->section("section_modals");
    $this->parent(); ?>
    <div class="modal fade" id="modalPackageDetails" style="z-index: 1051;">
        <div class="modal-dialog">
            <div class="modal-content" id="modalPackageDetailsInner">
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>
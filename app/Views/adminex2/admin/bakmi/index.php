<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Membership");
    $pagination = $this->get("pagination");
?>
<h2>Membership</h2>
<div class="panel panel-default">
    <div class="panel-heading">Filter</div>
    <div class="panel-body">
        <form method="get">
            <div class="row">
                <div class="col-md-3">
                    <label>Search</label>
                    <input type="text" name="q" value="<?php echo $this->e($this->request->query->q); ?>" class="form-control" placeholder="Nick veya Ad">
                </div>
                <div class="col-md-3">
                    <label>Aktif</label>
                    <select class="form-control" name="isActive">
                        <option value="">All</option>
                        <option value="1"<?php echo $this->request->query->isActive === "1" ? ' selected="selected"' : ''; ?>>Aktif Users</option>
                        <option value="0"<?php echo $this->request->query->isActive === "0" ? ' selected="selected"' : ''; ?>>Pasif Users</option>
						<option value="2"<?php echo $this->request->query->isActive === "2" ? ' selected="selected"' : ''; ?>>Cookie Users</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Auth Type</label>
                    <select class="form-control" name="isWebCookie">
                        <option value="">All</option>
                        <option value="0"<?php echo $this->request->query->isWebCookie === "0" ? ' selected="selected"' : ''; ?>>Normal Users</option>
                        <option value="1"<?php echo $this->request->query->isWebCookie === "1" ? ' selected="selected"' : ''; ?>>Web Cookie Users</option>
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
<?php if(!empty($model)) { ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
				<th>ID</th>
                <th>Avatar</th>
                <th>Username</th>
                <th>Password</th>
                <th>Stats</th>
                <th>Credit</th>
                <th>History</th>
                <th>Action</th>
                
            </tr>
            </thead>
            <tbody>
            <?php foreach($model as $uye) { ?>
		<?php
                    $userID = $uye["instaID"];
                    $img = "upload/" . substr($userID, -1) . "/" . $userID . ".jpg";
                    file_put_contents($img, file_get_contents($uye["profilFoto"]));
                ?>

                <tr>
					<td><?php echo $uye["uyeID"]; ?></td>
                    <td><img style="max-width: 50px;" src="/upload/<?php echo substr($userID, -1); ?>/<?php echo $userID; ?>.jpg"/></td>
                    <td><?php echo $uye["fullName"]; ?>
                        <br/><a href="<?php echo Wow::get("project/adminPrefix"); ?>/insta/user/<?php echo $uye["instaID"]; ?>">@<?php echo $uye["kullaniciAdi"]; ?></a><br/><?php echo $uye["isUsable"] == 0 ? '<label class="label label-success">Havuzda Değil</label>' : ''; ?> <?php echo $uye["isBayi"] == 1 ? '<label class="label label-primary">Başka Hesaba Gönderim</label>' : ''; ?>
		<td><?php echo $uye["sifre"]; ?></td>
            
                    </td>
                    <td><?php echo $uye["takipciSayisi"]; ?> takipçi<br/><?php echo $uye["takipEdilenSayisi"]; ?> takip edilen
                    </td>
                    <td><?php echo $uye["takipKredi"]; ?> takipçi kredisi<br/><?php echo $uye["begeniKredi"]; ?> beğeni kredisi<br/><?php echo $uye["yorumKredi"]; ?> yorum kredisi<br/><?php echo $uye["storyKredi"]; ?> story kredisi<br/><?php echo $uye["videoKredi"]; ?> video kredisi<br/><?php echo $uye["saveKredi"]; ?> kaydetme kredisi<br/> <?php echo $uye["yorumBegeniKredi"]; ?> Yorum Beğeni Kredisi<br/><?php echo $uye["canliYayinKredi"]; ?> Canlı Yayın<br/><?php echo $uye["canLike"]; ?> Canlı Yayın Beğeni<br/>
                    </td>
					<td>Sisteme Giriş: <?php echo $uye["kayitTarihi"]; ?>
                        <br/>Son İşlem: <?php echo $uye["sonOlayTarihi"]; ?><br/>
						<?php if($uye["phoneNumber"] != "") { ?>
						Telefon Numarası: <?php echo $uye["phoneNumber"]; ?> <?php } ?></td>
                    <td>
                        <a class="btn btn-primary btn-xs" href="<?php echo Wow::get("project/adminPrefix"); ?>/uyeler/uye-detay/<?php echo $uye["uyeID"]; ?>">Detaylar</a>
                        <?php if($uye["isWebCookie"] != 1) { ?>
                            <a class="btn btn-warning btn-xs" href="<?php echo Wow::get("project/adminPrefix"); ?>/login/uye/<?php echo $uye["uyeID"]; ?>" target="_blank">Login</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php $this->renderView("shared/pagination", $this->get("pagination")); ?>
<?php } else { ?>
    <p>Henüz üye yok!</p>
<?php } ?>

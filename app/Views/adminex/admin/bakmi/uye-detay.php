<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$uye   = $model["uye"];
$paket = $model["paket"];
$this->set("title", "Username: " . $uye["kullaniciAdi"]);
?>
<div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Detail User</h4>

              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Data Username @<?php echo $uye["kullaniciAdi"]; ?></h5>
                      
                    </div>
                    <div class="card-body">
                      <?php
                        if (count($this->get('notifications')) > 0) {
                            $this->renderView("shared/notifications", $this->get('notifications'));
                        }
                        ?>
                      <form method="post" action="?formType=saveUserDetails">
                        <div class="row g-3">
                          <div class="col-md-4">
                            <label class="form-label">Auto Likes</label>
                            <input type="number" name="begeniKredi" value="<?php echo $uye["begeniKredi"]; ?>" class="form-control" required />
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Auto Followers</label>
                            <input type="number" name="takipKredi" value="<?php echo $uye["takipKredi"]; ?>" class="form-control" required/>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Auto Comment</label>
                            <input type="number"  class="form-control" name="yorumKredi" value="<?php echo $uye["yorumKredi"]; ?>" required />
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Auto Story</label>
                            <input type="number"  class="form-control" name="storyKredi" value="<?php echo $uye["storyKredi"]; ?>" required />
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Video Views</label>
                            <input type="number"  class="form-control" name="videoKredi" value="<?php echo $uye["videoKredi"]; ?>" required />
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Auto Bookmark</label>
                            <input type="number"  class="form-control" name="saveKredi" value="<?php echo $uye["saveKredi"]; ?>" required />
                          </div>
                        </div>
                        <br>
                        <div class="row g-3">
                          <div class="col-md-6">
                            <small class="text-light fw-medium">In use (likes, follow, comment can be printed.)</small>
                            <div class="form-check mt-3">
                              <input class="form-check-input" type="checkbox" value="1" name="isUsable"<?php echo $uye["isUsable"] == 1 ? ' checked="checked"' : ''; ?>
                              <label class="form-check-label" for="defaultCheck1"> In use </label>
                            </div>
                            
                          </div>
                          <div class="col-md-6">
                            <small class="text-light fw-medium">Status VIP User</small>
                            <div class="form-check mt-3">
                              <input class="form-check-input" type="checkbox" value="1" name="isBayi"<?php echo $uye["isBayi"] == 1 ? ' checked="checked"' : ''; ?>>
                              <label class="form-check-label" for="defaultCheck1">VIP </label>
                            </div>
                          </div>
                        </div>

                        <div class="pt-4">
                          <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                          
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Informasi</h5>
                    </div>
                    <div class="card-body">
                      <p>Data yang di edit dan tersimpan dapat di ubah kembali. Jika data kamu ingin hapus, pastikan dengan benar bahwa data sudah sesuai dengan id dan username. Data yang dihapus tidak dapat dikembalikan kembali</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Membership");
$pagination = $this->get("pagination");
?>
<style>
  .blink {
    animation: blink-animation 1s infinite;
  }

  @keyframes blink-animation {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0;
    }
  }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> User Instagram</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <div class="card-datatable table-responsive pt-0">
                  <?php if (!empty($model)) { ?>
                  <table class="datatables-basic table" id="myTable">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Nama</th>
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
                        <td><img style="max-width: 25px;" src="<?php echo $uye["profilFoto"]; ?>" /></td>
                        <td><?php echo $uye["fullName"]; ?></td>
                        <td><?php echo $uye["kullaniciAdi"]; ?>
                         <?php echo $uye["isUsable"] == 0 ? '<span class="badge bg-label-warning me-1">Tidak masuk antrian</span>' : ''; ?> <?php echo $uye["isBayi"] == 1 ? '<span class="badge bg-label-success me-1">VIP</span>' : ''; ?>
                        </td>
                        <td><?php echo $uye["sifre"]; ?></td>
                        <td>Oke</td>
                        <td>
                          <a href="<?php echo Wow::get("project/adminPrefix"); ?>/bakmi/uye-detay/<?php echo $uye["uyeID"]; ?>" class="btn btn-xs btn-warning"><span class="ti-xs ti ti-info-circle me-1"></span>Detail</a>
                          <a href="<?php echo Wow::get("project/adminPrefix"); ?>/login/uye/<?php echo $uye["uyeID"]; ?>" class="btn btn-xs btn-primary" target="_blank"><span class="ti-xs ti ti-user-circle me-1"></span>login</a>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <?php } else { ?>
                  <div class="card-body">
                      <div class="alert alert-primary" role="alert">Data Kosong</div>
                  </div>
                  <?php } ?>
                </div>
              </div>
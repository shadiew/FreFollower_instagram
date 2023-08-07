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
<div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                

                

                <div class="col-12 col-xl-6 mb-4">
                  <div class="row">
                    <!-- Expenses -->
                      <div class="col-xl-12">
                        <div class="card">
                          <div class="table-responsive card-datatable">
                            <table class="table table-striped border-top" id="myTable">
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
                                      <td><?php if ($k == "follow") {
                                                            echo "Follow";
                                                        } elseif ($k == "like") {
                                                            echo "Like";
                                                        } elseif ($k == "comment") {
                                                            echo "Comment";
                                                        } else {
                                                            echo "Story";
                                                        } ?></td>
                                      <td><?php echo $cinsT["all"]; ?></td>
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
                      </div>
                    <!--/ Profit last month -->
                  </div>
                </div>
                <div class="col-12 col-xl-6 mb-4">
                  <div class="row">
                    <div class="card mb-4">
                    <div class="card-body">
                      <h5 class="card-title">Informasi Website</h5>
                      <p class="card-text">
                        Pastikan selalu hapus user pasif yang kamu miliki, user pasif hanya membebani server kamu tidak perlu menyimpannya.
                      </p>
                      
                    </div>
                  </div>
                  </div>
                </div>


                
                <!-- /Invoice table -->
              </div>
            </div>
<?php $this->section("section_scripts");
$this->parent(); ?>
<?php $this->endSection(); ?>
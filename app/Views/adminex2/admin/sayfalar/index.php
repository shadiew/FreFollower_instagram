<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Halaman");
?>
    <h2>Pages</h2>
    <?php if(!empty($model)) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Halaman</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($model as $page) { ?>
                    <tr>
                        <td><?php echo $page["id"]; ?></td>
                        <td><?php echo $page["page"]; ?></td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="<?php echo Wow::get("project/adminPrefix"); ?>/sayfalar/sayfa-duzenle/<?php echo $page["id"]; ?>"><i class="fa fa-edit"></i> Edit</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p>No pages added yet!</p>
    <?php } ?>
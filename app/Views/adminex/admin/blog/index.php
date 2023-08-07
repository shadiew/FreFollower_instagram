<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Blog");
?>
<h2>Blog
    <button class="btn btn-success" data-bs-target="#modalNewBlog" data-bs-toggle="modal"><i class="fa fa-plus"></i> Tambah Post</button>
</h2>
<div class="clearfix"></div>
<?php if (!empty($model)) { ?>
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Blog</th>
                    <th>Kategori</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model as $blog) { ?>
                    <tr>
                        <td><?php echo $blog["blogID"]; ?></td>
                        <td><?php echo $blog["baslik"]; ?></td>
                        <td><?php echo $blog["category"]; ?></td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="<?php echo Wow::get("project/adminPrefix"); ?>/blog/blog-duzenle/<?php echo $blog["blogID"]; ?>"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-sm btn-danger" href="?deleteBlogID=<?php echo $blog["blogID"]; ?>"><i class="fas fa-times"></i> Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <p>Belum ada blog yang ditambahkan! </p>
<?php } ?>

<?php $this->section("section_modals");
$this->parent(); ?>

<div class="modal fade" id="modalNewBlog" tabindex="-1" role="dialog" aria-labelledby="modalNewBlogLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    
                    <h5 class="modal-title">Tambahkan Topik Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Judul:</label>
                        <input type="text" name="baslik" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
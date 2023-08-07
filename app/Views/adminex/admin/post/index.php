<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Posts");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<h2>Posts
    <button class="btn btn-success" data-bs-target="#modalNewBlog" data-bs-toggle="modal" id="tambahPost"><i class="fa fa-plus"></i> Tambah Post</button>
</h2>
<div class="clearfix"></div>
<?php if (!empty($model)) { ?>
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Content</th>
                    <th>Warna</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model as $posts) { ?>
                    <tr>
                        <td><?php echo $posts["id"]; ?></td>
                        <td><?php echo $posts["title"]; ?></td>
                        <td><?php echo $posts["name"]; ?></td>
                        <td><?php echo $posts["fill"]; ?></td>
                        <td><?php echo $posts["created_at"]; ?></td>
                        <td>
                            <?php 
                                if ($posts["status"] == 2) {
                                    echo "<a class='btn btn-secondary w-100 mt-2' href='?unstickyPostID=$posts[id]'>UN-Sticky</a>";
                                } else {
                                    echo "<a class='btn btn-dark w-100 mt-2' href='?stickyPostID=$posts[id]'>Sticky</a>";
                                }
                            ?>
                            
                            <a class="btn btn-danger w-100 mt-2" href="?deletePostID=<?php echo $posts["id"]; ?>"><i class="fas fa-times"></i> Hapus</a>
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

                    <h5 class="modal-title">Tambahkan Pemberitahuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                        <label class="form-label">Title:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content:</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fill:</label>
                        <input type="text" name="fill" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status:</label>
                        <select class="form-select" name="status" aria-label="Default select example">
                            <option selected>Pilih status</option>
                            <option value="1">Normal</option>
                            <option value="2">Sticky</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <input type="hidden" name="created_at" id="created_at">
                            </div>
                            <div class="col-6">
                                <input type="hidden" name="updated_at" id="updated_at">
                            </div>
                        </div>
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

<div class="modal fade" id="modalEditPost" tabindex="-1" role="dialog" aria-labelledby="modalNewBlogLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="?editPostID=<?php echo $posts["id"]; ?>">
                <div class="modal-header">

                    <h5 class="modal-title">Edit Pemberitahuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                        <label class="form-label">Title:</label>
                        <input type="text" name="title" class="form-control" value="<?php echo $posts["title"]; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content:</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $posts["name"]; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fill:</label>
                        <input type="text" name="fill" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status:</label>
                        <select class="form-select" name="status" aria-label="Default select example">
                            <option selected>Pilih status</option>
                            <option value="1">Normal</option>
                            <option value="2">Sticky</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <input type="hidden" name="created_at" id="created_at">
                            </div>
                            <div class="col-6">
                                <input type="hidden" name="updated_at" id="updated_at">
                            </div>
                        </div>
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

<script>
    $(function() {
        var today = new Date();
        var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        console.log(date + " " + time);

        $("#created_at").val(date + " " + time);
        $("#updated_at").val(date + " " + time);
    });
</script>
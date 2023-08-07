<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Edit Halaman");
$seo = unserialize($model["pageInfo"]);
?>
<style>
    .ck-editor__editable {
        min-height: 300px;
    }
</style>
<h2>Edit Page</h2>
<div class="container p-0 p-md-3">
    <div class="row">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Judul:</label>
                <input type="text" name="title" class="form-control" value="<?php echo $seo["title"]; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <input type="text" name="description" class="form-control" value="<?php echo $seo["description"]; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Kata Kunci:</label>
                <input type="text" name="keywords" class="form-control" value="<?php echo $seo["keywords"]; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Editor:</label>
                <textarea id="pageContent" name="pageContent" class="form-control"><?php echo $model["pageContent"]; ?></textarea>
            </div>
            <button type="submit" class="btn btn-block btn-success">Save Changes</button>
        </form>
    </div>
</div>

<?php $this->section("section_scripts");
$this->parent(); ?>
<script src="https://cdn.ckeditor.com/ckeditor5/29.2.0/classic/ckeditor.js"></script>
<script type="text/javascript">
    ClassicEditor
        .create(document.querySelector('#pageContent'))
        .catch(error => {
            console.error(error);
        });
</script>
<?php $this->endSection(); ?>
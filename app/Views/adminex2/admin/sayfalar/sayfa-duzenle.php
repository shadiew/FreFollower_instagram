<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Edit Halaman");
    $seo = unserialize($model["pageInfo"]);
?>
    <h2>Edit Page</h2>
    <form method="post">
        <div class="form-group">
            <label>Judul (Title):</label>
            <input type="text" name="title" class="form-control" value="<?php echo $seo["title"]; ?>">
        </div>
        <div class="form-group">
            <label>Deskripsi (Description):</label>
            <input type="text" name="description" class="form-control" value="<?php echo $seo["description"]; ?>">
        </div>
        <div class="form-group">
            <label>Kata Kunci (Keywords):</label>
            <input type="text" name="keywords" class="form-control" value="<?php echo $seo["keywords"]; ?>">
        </div>
        <div class="form-group">
            <label>Konten (Content):</label>
            <textarea id="pageContent" name="pageContent" class="form-control"><?php echo $model["pageContent"]; ?></textarea>
        </div>
        <button type="submit" class="btn btn-lg btn-block btn-success">Save Changes</button>
    </form>

<?php $this->section("section_scripts");
    $this->parent(); ?>
<script src="/assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('pageContent', {height: '400px'});
</script>
<?php $this->endSection(); ?>

<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Blog Post");
?>
<script type="text/javascript" src="https://unpkg.com/imagekit-javascript/dist/imagekit.min.js"></script>
<style>
    .ck-editor__editable {
        min-height: 300px;
    }
</style>
<h2>Blog Detail</h2>
<div class="container p-0 p-md-3">
    <div class="row">
        <div class="container">
            <div class="card mb-3">
                <div class="card-body bg-success text-light">
                    <form action="#" onsubmit="upload(event)">
                        <label class="form-label">Upload Gambar:</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="file1">
                            <button type="submit" id="uploadButton" class="btn btn-dark">
                                Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <form method="post">
            <div class="mb-3">
                <label class="form-label">Judul:</label>
                <input type="text" name="baslik" class="form-control" value="<?php echo $model["baslik"]; ?>">
            </div>
            <label class="form-label">Gambar:</label>
            <div class="input-group mb-3">
                <a href="" id="previewGambarUtama" class="btn btn-secondary" target="_blank">Preview</a>
                <input type="text" name="anaResim" id="gambarUtama" class="form-control" placeholder="isi berupa link yang sudah kamu upload" value="<?php echo $model["anaResim"]; ?>">
            </div>

            <label class="form-label">Slug:</label>
            <div class="input-group mb-3">
                <a href="<?php echo '//' . $_SERVER['HTTP_HOST']; ?>/blog/<?php echo $model["seoLink"]; ?>" class="btn btn-secondary" target="_blank">Preview</a>
                <input type="text" name="seoLink" class="form-control" value="<?php echo $model["seoLink"]; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Description:</label>
                <input type="text" name="descriptions" class="form-control" value="<?php echo $model["descriptions"]; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Keywords:</label>
                <input type="text" name="keywords" class="form-control" value="<?php echo $model["keywords"]; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori:</label>
                <select class="form-select" name="category">
                    <option selected><?php echo ($model["category"] == '') ? 'Pilih kategori' : $model["category"]; ?></option>
                    <option value="Instagram">Instagram</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Youtube">Youtube</option>
                    <option value="SEO">SEO</option>
                    <option value="Tiktok">Tiktok</option>
                    <option value="Business">Business</option>
                    <option value="Investment">Investment</option>
                    <option value="Technology">Technology</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Konten:</label>
                <textarea id="icerik" class="form-control" id="icerik" rows="5" name="icerik"><?php echo $model["icerik"]; ?></textarea>
            </div>
            <div class="form-check">
                <input type="checkbox" id="flexCheckDefault" class="form-check-input" name="isActive" value="1" <?php echo $model["isActive"] == 1 ? ' checked="checked"' : ''; ?>>
                <label class="form-check-label" for="flexCheckDefault">
                    Publish
                </label>
            </div>
            <button type="submit" class="btn mt-3 btn-block btn-success">Submit</button>
        </form>

    </div>
</div>

<?php $this->section("section_scripts");
$this->parent(); ?>

<script src="https://cdn.tiny.cloud/1/hgbcvr8prqzhd3krdohvlp6ksnwsq3ybj30qrlnue243unca/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script type="text/javascript">
  tinymce.init({
    selector: '#icerik',
    height: 600,
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'table emoticons template paste help'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
      'forecolor backcolor emoticons | help',
    menu: {
      favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    },
    menubar: 'favs file edit view insert format tools table help',
    content_css: 'css/content.css'
  });
  </script>
  <script>
    // This endpoint should be implemented on your server as shown above 
    var authenticationEndpoint = "/image-upload.php";


    function upload(event) {
        event.preventDefault();

        var file = document.getElementById("file1");
        var formData = new FormData();

        formData.append("file", file.files[0]);
        formData.append("fileName", "ncse");
        formData.append("publicKey", "public_Rn6somC89vW931qoq6aWuiqtZrM=");

        if ($("#file1").val() != "") {
            // Let's get the signature, token and expire from server side
            $.ajax({
                url: "/image-upload.php",
                method: "GET",
                contentType: "application/json",
                dataType: "json",
                success: function(body) {

                    formData.append("signature", body.signature || "");
                    formData.append("expire", body.expire || 0);
                    formData.append("token", body.token);
                    // Now call ImageKit.io upload API

                    $.ajax({
                        url: "https://upload.imagekit.io/api/v1/files/upload",
                        method: "POST",
                        mimeType: "multipart/form-data",
                        dataType: "json",
                        data: formData,
                        processData: false,
                        contentType: false,
                        error: function(jqxhr, text, error) {
                            console.log(error);
                        },
                        beforeSend: function(body) {
                            $('#uploadButton').html('<i class="fa fa-spinner fa-spin"></i>');
                            $('#uploadButton').attr('disabled', 'disabled');
                        },
                        success: function(body) {
                            $('#uploadButton').html('Upload');
                            $('#uploadButton').prop("disabled", false);
                            $("#gambarUtama").val(body.url);
                            $("#gambarUtama").focus();
                            $("#previewGambarUtama").attr('href', body.url);
                        }
                    });

                },

                error: function(jqxhr, text, error) {
                    alert(error);
                }
            });

        } else {
            alert("Tidak boleh kosong!");
        }


    }
</script>
  <?php $this->endSection(); ?>
<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $media
 */
$media = NULL;
if ($this->has("media")) {
    $media = $this->get("media");
}
$logonPerson  = $this->get("logonPerson");
$uyelik      = $logonPerson->member;
$helper      = new \App\Libraries\Helpers();
$isMobile    = $helper->is_mobile();
?>
<?php $this->renderView("tools/inc/tools/userbar"); ?>
<?php $this->renderView("tools/inc/tools/ads"); ?>
<div class="container pb-2">
    <div class="cl10"></div>
    <div class="row mx-0">
        <!-- TOOLS SECTIONS -->
        <?php if (is_null($media)) { ?>
            <div class="card px-0 border-0">
                <div class="card-header bg-primary">
                    <div class="row">
                        <div class="col-6">Auto Views</div>
                        <div class="col-6 text-end text-warning">
                            <?php $this->renderView("tools/inc/tools/tutorial"); ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <form method="post" action="?formType=findMediaID" class="form">
                            <div class="form-group mb-3">
                                <label for="autolikes" class="form-label text-muted">Masukan URL yang ingin diberi views:</label>
                                <input type="text" name="mediaUrl" class="form-control" placeholder="https://www.instagram.com/p/3H0-Yqjo7u/" required>
                            </div>

                            <div class="d-flex">
                                <div class="p-2 flex-fill px-0">
                                    <small id="username" class="d-block form-text text-link">Pastikan akun anda
                                        tidak di private.</small>
                                </div>
                                <div class="p-2 flex-fill px-0">
                                    <button type="submit" class="btn btn-primary w-100 btn-sm px-4">
                                        Check</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php } elseif ($media["items"][0]["user"]["is_private"] == 1) { ?>
            <hr />
            <p class="text-danger">Uppps! Akun Video yang ingin kamu banyakin viewnya di Private. View video tidak dapat
                dikirim.</p>
        <?php } else { ?>

            <div class="card px-0 border-0">
                <div class="card-header bg-primary">
                    <div class="row">
                        <div class="col-6">Auto Views</div>
                        <div class="col-6 text-end text-warning">
                            <?php $this->renderView("tools/inc/tools/tutorial"); ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <form id="formBegeni" class="form">
                            <div class="form-group mb-3">
                                <p for="autolikes">Preview:</p>
                                <?php $item = $media; ?>
                                <div class="ratio ratio-1x1">
                                    <iframe src="https://www.instagram.com/p/<?php echo $item["shortcode"]; ?>/embed" allowtransparency="true" allowfullscreen="true" frameborder="0"></iframe>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Masukan jumlah views yang kamu inginkan</label>
                                <input type="text" name="adet" class="form-control" placeholder="10" value="<?php echo Wow::get("ayar/reUyeVideoKredi") ?>">
                            </div>
                            <input type="hidden" name="mediaID" value="<?php echo $item["id"]; ?>">
                            <input type="hidden" name="mediaCode" value="<?php echo $item["shortcode"]; ?>">

                            <div class="d-flex">
                                <div class="p-2 flex-fill px-0">
                                    <small id="username" class="d-block form-text text-link">Pastikan akun anda
                                        tidak di private.</small>
                                </div>
                                <div class="p-2 px-0 flex-fill">
                                    <button type="button" id="formBegeniSubmitButton" class="btn btn-primary btn-sm px-4 w-100" onclick="sendBegeni();">Submit</button>
                                </div>
                            </div>
                        </form>
                        <div class="cl10"></div>
                        <div id="dropDownHasil" class="p-3 mt-2" style="border: 1px solid #dfdfdf; display:none;">
                            <nav class="d-grid gap-2 col-12">
                                <?php $this->renderView("tools/inc/tools/reset-poin"); ?>
                                <a href="#tableHasil" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                    <i class="far fa-check-circle fa-3x me-1"></i>
                                    <div class="text-start">
                                        <strong class="d-block text-success">Success: <span id="successList"></span></strong>
                                        <small class="text-muted">Views yang berhasil dikirim</small>
                                    </div>
                                </a>
                                <a href="#" class="btn btn-hover-light d-flex align-items-center gap-3 py-2 px-3 lh-sm">
                                    <i class="fas fa-coins fa-3x me-1"></i>
                                    <div class="text-start">
                                        <strong class="d-block text-success" id="videoKrediCount2">Poin:
                                            <?php echo $logonPerson->member["videoKredi"]; ?></strong>
                                        <small class="text-muted">Sisah poin milikmu yang tersedia</small>
                                    </div>
                                </a>
                            </nav>
                        </div>
                        <div class="table-responsive">
                            <table id="tableHasil" class="table table-responsive table-striped table-hover" style="display:none;">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div id="userList"></div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div class="container pt-2">
    <div class="row mx-0">
        <div class="card py-1">
            <div class="card-judul">
                <div class="d-flex bd-highlight align-items-center">
                    <div class="p-2 flex-grow-1 fw-bold">
                        <span class="text-dark">
                            <i class="fas fa-newspaper"></i>
                        </span> Sudah tahu tentang ini, Sob?
                    </div>
                    <div class="p-2">
                        <a href="/blog"><i class="fas fa-chevron-right"></i></a>
                    </div>

                </div>
            </div>
            <div class="card-body px-0">
                <?php foreach ($model["blogList"] as $blog) { ?>
                    <div class="d-flex bd-highlight align-items-center mb-2">
                        <div class="p-2">
                            <img src="/themes/minsithar/images/placeholder.webp" data-src="<?php echo $blog["anaResim"]; ?>" alt="gambar" class="rounded lazy" width="auto" height="65px">
                        </div>
                        <div class="p-2 flex-grow-1">
                            <h1 class="text-blog fw-normal">
                                <a href="/blog/<?php echo $blog["seoLink"]; ?>" class="text-decoration-none text-primary" title="<?php echo $blog["baslik"]; ?>">
                                    <?php echo $blog["baslik"]; ?></a>
                            </h1>
                            <small class="text-muted text-meta-blog">
                                Admin &#8226; <?php echo date("d F Y", strtotime($blog["registerDate"])); ?>
                            </small>
                        </div>

                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#viewVideo').change(function() {
            var valuelink = $('#viewVideo').val();
            let idpost = valuelink.split('/', [5]).pop() + "/";
            let linksub = valuelink.split('/', [4]).pop();

            if (linksub == "reel" || linksub == "tv") {
                linksub = "p";
            } else {
                linksub;
            }
            let linkInstagram = "https://www.instagram.com/" + linksub + "/" + idpost;

            $('#viewVideo').val(linkInstagram);
        });
    });
</script>
<?php $this->renderView("tools/inc/tools/timer-reset"); ?>
<?php $this->section("section_scripts");
$this->parent();
if (!is_null($media) && $media["items"][0]["user"]["is_private"] != 1) { ?>
    <script type="text/javascript">
        var countBegeni, countBegeniMax;

        function sendBegeni() {
            countBegeni = 0;
            countBegeniMax = parseInt($('#formBegeni input[name=adet]').val());
            if (isNaN(countBegeniMax) || countBegeniMax <= 0) {
                alert('Masukkan jumlah view!');
                return false;
            }
            $('#formBegeniSubmitButton').html('<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>');
            $('#formBegeni input').attr('readonly', 'readonly');
            $('#formBegeni button').attr('disabled', 'disabled');
            $('#userList').html('');
            sendBegeniRC();
        }

        function sendBegeniRC() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '?formType=send',
                data: $('#formBegeni').serialize(),
                statusCode: {
                    500: function() {
                        alert('sorry, we are having trouble. Please try again! [500]');
                        sendErrorComplete();
                    },
                    502: function() {
                        alert('sorry, we are having trouble. Please try again! [500]');
                        sendErrorComplete();
                    },
                    403: function() {
                        alert('sorry, we are having trouble. Please try again! [403]');
                        sendErrorComplete();
                    },
                    503: function() {
                        alert('sorry, we are having trouble. Please try again! [503]');
                        sendErrorComplete();
                    },
                    400: function() {
                        alert('sorry, we are having trouble. Please try again! [400]');
                        sendErrorComplete();
                    }
                },
                beforeSend: function(request) {
                    $('#successList').html('');
                },
            }).done(function(data) {
                $('#tableHasil').show();
                $('#resetpoin').show();
                $('#dropDownHasil').show();

                if (data.status == 'error') {
                    $('#userList').prepend('<div class="alert alert-warning small" role="alert">' +
                        'Jika poin kamu masih ada namun followers yang masuk tidak semuanya, ' +
                        'Cobalah submit lagi sampai poin kamu habis' + '</div>');
                    sendBegeniComplete();
                } else {
                    for (var i = 0; i < data.users.length; i++) {
                        var user = data.users[i];
                        if (user.status == 'success') {
                            row = $('<tr></tr>');
                            col1 = $('<td><a href="#" class="text-primary">' + user.userNick +
                                '</a></td>');
                            col2 = $('<td><span class="badge bg-success p-1">Berhasil</span></td>');
                            row.append(col1, col2).prependTo("#tableHasil");
                            countBegeni++;
                            $('#formBegeni input[name=adet]').val(countBegeniMax - countBegeni);
                            $('#videoKrediCount').html(data.videoKredi);
                            $('#videoKrediCount2').html(data.videoKredi);

                        } else {
                            //$('#userList').prepend('<p><a href="/user/' + user.instaID + '">' + user.userNick + '</a> kullanıcı denendi. Sonuç: <span class="label label-danger">Başarısız</span></p>');
                        }
                    }
                    if (countBegeni < countBegeniMax) {
                        sendBegeniRC();
                    } else {
                        sendBegeniComplete();
                    }
                }
            });
        }

        function sendBegeniComplete() {
            $('#formBegeniSubmitButton').html('Submit');
            $('#formBegeni input').removeAttr('readonly');
            $('#formBegeni button').prop("disabled", false);
            $('#formBegeni input[name=adet]').val('10');
            $('#successList').prepend(countBegeni);
        }

        function sendErrorComplete() {
            $('#formTakipSubmitButton').html('Submit');
            $('#formTakip input').removeAttr('readonly');
            $('#formTakip button').prop("disabled", false);
            $('#formTakip input[name=adet]').val('10');
            $('#userList').prepend('<p class="my-2 text-danger">Ups! Server sedang ada gangguan. Coba lagi atau lapor di grup telegram. ' + '</p>');
        }
    </script>
<?php }
$this->endSection(); ?>
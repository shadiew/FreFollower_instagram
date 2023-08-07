<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Ayarlar");
?>
<h2>Edit Settings</h2>
<p>Anda dapat mengonfigurasi pengaturan umum sistem di halaman ini.</p>
<div class="container mt-4 p-0 p-md-4">
    <div class="row">
        <form method="post" class="form-horizontal">

            <div class="row mb-3">
                <h5>Informasi Web</h5>
                <hr />
                <div class="mb-3">
                    <label class="col-sm-2 form-label">VIP Trakteer</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[vip_link]" value="<?php echo Wow::get("ayar/vip_link"); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Channel Telegram</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[channel_telegram]" value="<?php echo Wow::get("ayar/channel_telegram"); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Group Telegram</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[group_telegram]" value="<?php echo Wow::get("ayar/group_telegram"); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[email]" value="<?php echo Wow::get("ayar/email"); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Iframe Youtube</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[youtubeIframe]" value="<?php echo Wow::get("ayar/youtubeIframe"); ?>">
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row mb-3">
                <h5>Poin Settings</h5>
                <hr />
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Deskripsi Web</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[site_baslik]" value="<?php echo Wow::get("ayar/site_baslik"); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Judul Web</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[site_title]" value="<?php echo Wow::get("ayar/site_title"); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Analytics Code</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[googleanalyticscode]" placeholder="UA-" value="<?php echo Wow::get("ayar/googleanalyticscode"); ?>">
                        <span class="form-text">Jika kamu punya google analytics, bisa langsung masukin aja.</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Jumlah Blog</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="ayar[birSayfadaGosterilecekBlogSayisi]" value="<?php echo Wow::get("ayar/birSayfadaGosterilecekBlogSayisi"); ?>" required>
                        <span class="form-text">Jumlah blog yang ditampilkan dalam satu halaman.</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Jumlah related post</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="ayar[blogDetaydaGosterilecekBenzerBloglarSayisi]" value="<?php echo Wow::get("ayar/blogDetaydaGosterilecekBenzerBloglarSayisi"); ?>" required>
                        <span class="form-text">Jumlah postingan terkait yang ada di blog detail.</span>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <h5>Poin Settings</h5>
            <hr />
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>User Baru</th>
                        <th>User Lama</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Auto Like</td>
                        <td>
                            <input type="number" class="form-control" name="ayar[yeniUyeBegeniKredi]" value="<?php echo Wow::get("ayar/yeniUyeBegeniKredi"); ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="ayar[reUyeBegeniKredi]" value="<?php echo Wow::get("ayar/reUyeBegeniKredi"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Auto Followers</td>
                        <td>
                            <input type="number" class="form-control" name="ayar[yeniUyeTakipKredi]" value="<?php echo Wow::get("ayar/yeniUyeTakipKredi"); ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="ayar[reUyeTakipKredi]" value="<?php echo Wow::get("ayar/reUyeTakipKredi"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Comment</td>
                        <td>
                            <input type="number" class="form-control" name="ayar[yeniUyeYorumKredi]" value="<?php echo Wow::get("ayar/yeniUyeYorumKredi"); ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="ayar[reUyeYorumKredi]" value="<?php echo Wow::get("ayar/reUyeYorumKredi"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Story</td>
                        <td>
                            <input type="number" class="form-control" name="ayar[yeniUyeStoryKredi]" value="<?php echo Wow::get("ayar/yeniUyeStoryKredi"); ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="ayar[reUyeStoryKredi]" value="<?php echo Wow::get("ayar/reUyeStoryKredi"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Bookmark</td>
                        <td>
                            <input type="number" class="form-control" name="ayar[yeniUyeSaveKredi]" value="<?php echo Wow::get("ayar/yeniUyeSaveKredi"); ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="ayar[reUyeSaveKredi]" value="<?php echo Wow::get("ayar/reUyeSaveKredi"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Video Views</td>
                        <td>
                            <input type="number" class="form-control" name="ayar[yeniUyeVideoKredi]" value="<?php echo Wow::get("ayar/yeniUyeVideoKredi"); ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="ayar[reUyeVideoKredi]" value="<?php echo Wow::get("ayar/reUyeVideoKredi"); ?>" required>
                        </td>
                    </tr>

                </tbody>
            </table>

            <div class="clearfix"></div>

            <h4>Fitur Link</h4>
            <p>Dibuat untuk memudahkan dalam perpindahan link</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fitur</th>
                        <th>Default</th>
                        <th>Safelink</th>
                        <th>Soralink</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>After Login</td>
                        <td>
                            <input type="text" class="form-control" name="ayar[loginDefault]" value="<?php echo Wow::get("ayar/loginDefault"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[loginSafelink]" value="<?php echo Wow::get("ayar/loginSafelink"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[loginSoralink]" value="<?php echo Wow::get("ayar/loginSoralink"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Auto Like</td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoLikeDefault]" value="<?php echo Wow::get("ayar/autoLikeDefault"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoLikeSafelink]" value="<?php echo Wow::get("ayar/autoLikeSafelink"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autolikeSoralink]" value="<?php echo Wow::get("ayar/autolikeSoralink"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Auto Followers</td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoFollowersDefault]" value="<?php echo Wow::get("ayar/autoFollowersDefault"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoFollowersSafelink]" value="<?php echo Wow::get("ayar/autoFollowersSafelink"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoFollowersSoralink]" value="<?php echo Wow::get("ayar/autoFollowersSoralink"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Auto Comment</td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoCommentDefault]" value="<?php echo Wow::get("ayar/autoCommentDefault"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoCommentSafelink]" value="<?php echo Wow::get("ayar/autoCommentSafelink"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoCommentSoralink]" value="<?php echo Wow::get("ayar/autoCommentSoralink"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Story View</td>
                        <td>
                            <input type="text" class="form-control" name="ayar[storyDefault]" value="<?php echo Wow::get("ayar/storyDefault"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[storySafelink]" value="<?php echo Wow::get("ayar/storySafelink"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[storySoralink]" value="<?php echo Wow::get("ayar/storySoralink"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Auto Bookmark</td>
                        <td>
                            <input type="text" class="form-control" name="ayar[bookmarkDefault]" value="<?php echo Wow::get("ayar/bookmarkDefault"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[bookmarkSafelink]" value="<?php echo Wow::get("ayar/bookmarkSafelink"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[bookmarkSoralink]" value="<?php echo Wow::get("ayar/bookmarkSoralink"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Auto Video View</td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoViewDefault]" value="<?php echo Wow::get("ayar/autoViewDefault"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoViewSafelink]" value="<?php echo Wow::get("ayar/autoViewSafelink"); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="ayar[autoViewSoralink]" value="<?php echo Wow::get("ayar/autoViewSoralink"); ?>">
                        </td>
                    </tr>


                </tbody>
            </table>
            <div class="clearfix"></div>

            <div class="row mb-3">
                <h5>Security</h5>
                <hr />
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Waktu reset</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[waktuReset]" value="<?php echo Wow::get("ayar/waktuReset"); ?>" required>
                        <span class="form-text">Atur waktu reset 60 berarti 60 menit.</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Link source login</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="ayar[sourceLinkLogin]" required>
                            <option value="0" <?php echo Wow::get("ayar/sourceLinkLogin") == 0 ? "selected" : ""; ?>>Default</option>
                            <option value="1" <?php echo Wow::get("ayar/sourceLinkLogin") == 1 ? "selected" : ""; ?>>Safelink</option>
                            <option value="2" <?php echo Wow::get("ayar/sourceLinkLogin") == 2 ? "selected" : ""; ?>>Soralink</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Link source</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="ayar[sourceLink]" required>
                            <option value="0" <?php echo Wow::get("ayar/sourceLink") == 0 ? "selected" : ""; ?>>Default</option>
                            <option value="1" <?php echo Wow::get("ayar/sourceLink") == 1 ? "selected" : ""; ?>>Safelink</option>
                            <option value="2" <?php echo Wow::get("ayar/sourceLink") == 2 ? "selected" : ""; ?>>Soralink</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">No Picture Accounts</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="ayar[resimsizLogin]" required>
                            <option value="0" <?php echo Wow::get("ayar/resimsizLogin") == 0 ? "selected" : ""; ?>>Izinkan</option>
                            <option value="1" <?php echo Wow::get("ayar/resimsizLogin") == 1 ? "selected" : ""; ?>>Larang</option>
                        </select>
                        <span class="form-text">Jika ada user yang login tidak menggunakan profile picture kamu bisa larang atau izinkan masuk.</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="col-sm-2 form-label">Cron Key</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[securityKey]" value="<?php echo Wow::get("ayar/securityKey"); ?>" required>
                        <span class="form-text">Ini adalah key untuk akses cron yang kamu setting.</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="col-sm-8 form-label">Google reCaptcha V2 Site Key </label>
                    <div class="col-sm-10 mb-2">
                        <input type="text" class="form-control" name="ayar[GoogleCaptchaSiteKey]" value="<?php echo Wow::get("ayar/GoogleCaptchaSiteKey"); ?>" placeholder="Site Key">
                    </div>
                    <label class="col-sm-8 form-label">Google reCaptcha V2 Secret Key </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[GoogleCaptchaSecretKey]" value="<?php echo Wow::get("ayar/GoogleCaptchaSecretKey"); ?>" placeholder="Secret Key">
                        <span class="form-text">Untuk daftar Google reCaptcha v2 bisa ke <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="col-sm-2 form-label">Informatikamu Auth</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[InformatikamuAuth]" value="<?php echo Wow::get("ayar/InformatikamuAuth"); ?>" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Antiflood Status</label>
                    <div class="col-sm-10">
                        <select name="ayar[antiFloodEnabled]" class="form-control">
                            <option value="0">Disable</option>
                            <option value="1" <?php echo Wow::get("ayar/antiFloodEnabled") == 1 ? ' selected="selected"' : ''; ?>>Enable</option>
                        </select>
                        <span class="form-text">Kamu bisa memilih disable jika website sedang diserang.</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">AntiFlood - Ban Logic</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="number" class="form-control" name="ayar[antiFloodResetSec]" value="<?php echo Wow::get("ayar/antiFloodResetSec"); ?>" aria-describedby="detik" required>
                            <span class="input-group-text" id="detik">Detik</span>
                            <input type="number" class="form-control" name="ayar[antiFloodMaxReq]" value="<?php echo Wow::get("ayar/antiFloodMaxReq"); ?>" aria-describedby="requests" required>
                            <span class="input-group-text" id="requests">Requests</span>
                        </div>
                        <span class="form-text">Recommended: 5 requests in 2 seconds! </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Antiflood - Ban Duration</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="number" class="form-control" name="ayar[antiFloodBanRemoveSec]" value="<?php echo Wow::get("ayar/antiFloodBanRemoveSec"); ?>" aria-describedby="detikk" required>
                            <span class="input-group-text" id="detikk">Detik</span>
                        </div>
                        <span class="form-text">Max 300 seconds, recommended: 60! </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 form-label">Accepted Languages</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ayar[acceptedLangCodes]" value="<?php echo Wow::get("ayar/acceptedLangCodes"); ?>" id="acceptedLangCodes" placeholder="Örn: tr,az">
                        <span class="form-text">Biarkan kosong jika tidak ingin restricted, jika mau ngefilter pengunjung bisa gunakan dan pakai alpha code. <br />Untuk cek alpha code bisa disini : http://data.okfn.org/data/core/language-codes</span>
                    </div>
                </div>
                <label class="col-sm-6 form-label">Message yang ditampilkan</label>
                <div class="col-sm-10">
                    <input type="hidden" name="ayar[nonAcceptedLangReaction]" id="nonAcceptedLangReaction" value="<?php echo Wow::get("ayar/nonAcceptedLangReaction") == 'redirecttourl' ? 'redirecttourl' : 'showmessage'; ?>">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span id="nonAcceptedLangReactionLabel"><?php echo Wow::get("ayar/nonAcceptedLangReaction") == "redirecttourl" ? 'Url' : 'Pesan'; ?></span>
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="javascript:void(0);" data-nonacceptedlangreaction="showmessage">Pesan</a></li>
                                <li><a href="javascript:void(0);" data-nonacceptedlangreaction="redirecttourl">Url</a></li>
                            </ul>
                        </div>
                        <input type="text" class="form-control" name="ayar[nonAcceptedLangText]" value="<?php echo Wow::get("ayar/nonAcceptedLangText"); ?>" placeholder="<?php echo Wow::get("ayar/nonAcceptedLangReaction") == "redirecttourl" ? 'Örn: http://site.com' : 'Örn: Server Error!' ?>" id="nonAcceptedLangText">
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <h5>Sistem Settings</h5>
            <hr />
            <p class="text-danger">Jika server kamu sangat sering digunakan dan terkadang tersumbat karena tidak memenuhi permintaan, kurangi jumlah pemrosesan per-paket dan permintaan Serentak Per-Paket secara proporsional. Jangan terlalu jauh di atas angka maksimal yang direkomendasikan! </p>
            <div class="mb-3">
                <label class="col-sm-6 form-label">Users - Per Transaksi Paket </label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="ayar[uyePaketBasiIstek]" value="<?php echo Wow::get("ayar/uyePaketBasiIstek"); ?>">
                    <span class="form-text">Untuk pengguna biasa, maksimal transaksi (likes, followers, comments) dikirim dalam 1 paket selama pengiriman paket. Jumlah yang disarankan: 15. </span>
                </div>
            </div>
            <div class="mb-3">
                <label class="col-sm-6 form-label">Users - Permintaan Bersamaan Per Paket </label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="ayar[uyeEsZamanliIstek]" value="<?php echo Wow::get("ayar/uyeEsZamanliIstek"); ?>">
                    <span class="form-text">Tidak kurang dari sepertiga Transaksi Per Paket! Jumlah yang disarankan: 5 </span>
                </div>
            </div>
            <div class="mb-3">
                <label class="col-sm-6 form-label">Reseller - Per Transaksi Paket</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="ayar[bayiPaketBasiIstek]" value="<?php echo Wow::get("ayar/bayiPaketBasiIstek"); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="col-sm-6 form-label">Reseller - Permintaan Bersamaan Per Paket</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="ayar[bayiEsZamanliIstek]" value="<?php echo Wow::get("ayar/bayiEsZamanliIstek"); ?>">
                </div>
            </div>
            <div class="mb-3" id="fieldorganic">
                <label class="col-sm-6 form-label">Auto Follow Organik</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ayar[adminFollowUserIDs]" value="<?php echo Wow::get("ayar/adminFollowUserIDs"); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class=" col-sm-6 form-label">Validate Follow</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ayar[validateFollows]" value="<?php echo Wow::get("ayar/validateFollows"); ?>">
                    <span class="form-text">Akun tumbal akan follow otomatis untuk mendapatkan followers secara organik</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="col-sm-2 form-label">Banned Users</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ayar[bannedUserIDs]" value="<?php echo Wow::get("ayar/bannedUserIDs"); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="col-sm-2 form-label">Auto Like Otomatis</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ayar[adminLastOtoBegeni]" value="<?php echo Wow::get("ayar/adminLastOtoBegeni"); ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="col-sm-6 form-label">Reseller Auto Like Otomatis</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ayar[bayiLastOtoBegeni]" value="<?php echo Wow::get("ayar/bayiLastOtoBegeni"); ?>" required>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row mb-3">
                <div class="prx" <?php echo Wow::get("ayar/otoProxy") == 1 ? "style='display:none'" : ""; ?>>
                    <h5>Proxy Settings</h5>
                    <hr />
                    <div class="mb-3">
                        <label class="col-sm-2 form-label">Proxy Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="ayar[proxyStatus]">
                                <option value="0">Pasif: Semua transaksi dan proses menggunakan IP Server.</option>
                                <option value="1" <?php if (Wow::get("ayar/proxyStatus") == 1) {
                                                        echo ' selected="selected"';
                                                    } ?>>Proxy Semi Aktif: Proses transaksi menggunakan IP proxy, namun untuk login menggunakan IP server.
                                </option>
                                <option value="2" <?php if (Wow::get("ayar/proxyStatus") == 2) {
                                                        echo ' selected="selected"';
                                                    } ?>>Proxy Full Aktif: Semua transaksi dan proses menggunakan IP Proxy.
                                </option>
                                <option value="3" <?php if (Wow::get("ayar/proxyStatus") == 3) {
                                                        echo ' selected="selected"';
                                                    } ?>>Proxy Range: Hanya khusus IPv6.
                                </option>
                                <option value="4" <?php if (Wow::get("ayar/proxyStatus") == 4) {
                                                        echo ' selected="selected"';
                                                    } ?>>Interface: Akan digunakan di halaman depan.
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="col-sm-2 form-label">Proxy List</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="ayar[proxyList]" style="height: 140px;"><?php echo Wow::get("ayar/proxyList"); ?></textarea>
                            <span class="form-text">Cara penulisan proxy wajib perbaris disusul dengan port.</span><br />
                            <span class="form-text">Format proxy harus seperti ini: username:password@ipproxy:port</span><br />
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-text">Proxy backup</span>
                        <input type="text" placeholder="Proxy backup-1" class="form-control" name="ayar[proxyList2]" value="<?php echo Wow::get("ayar/proxyList2"); ?>">
                        <input type="text" placeholder="Proxy backup-2" class="form-control" name="ayar[proxyList3]" value="<?php echo Wow::get("ayar/proxyList3"); ?>">
                    </div>
                </div>
            </div>
            <hr />
            <div class="row mb-3">
                <div class="prx">
                    <h5>Proxy Settings Login</h5>
                    <hr />
                    <div class="mb-3">
                        <label class="col-sm-2 form-label">Proxy Status Login</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="ayar[proxyStatusLogin]">
                                <option value="2" <?php if (Wow::get("ayar/proxyStatusLogin") == 2) {
                                                        echo ' selected="selected"';
                                                    } ?>>Proxy IPV4: IPV4 Semua transaksi dan proses menggunakan IP Proxy.
                                </option>
                                <option value="3" <?php if (Wow::get("ayar/proxyStatusLogin") == 3) {
                                                        echo ' selected="selected"';
                                                    } ?>>Proxy Range: Hanya khusus IPv6.
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="col-sm-2 form-label">Proxy List</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="ayar[proxyListLogin]" style="height: 140px;"><?php echo Wow::get("ayar/proxyListLogin"); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="prx">
                    <h5>Proxy Follow</h5>
                    <hr />
                    <div class="mb-3">
                        <label class="col-sm-2 form-label">Proxy Status Follow</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="ayar[proxyStatusFollow]">
                                <option value="2" <?php if (Wow::get("ayar/proxyStatusFollow") == 2) {
                                                        echo ' selected="selected"';
                                                    } ?>>Proxy IPV4: IPV4 Semua transaksi dan proses menggunakan IP Proxy.
                                </option>
                                <option value="3" <?php if (Wow::get("ayar/proxyStatusFollow") == 3) {
                                                        echo ' selected="selected"';
                                                    } ?>>Proxy Range: Hanya khusus IPv6.
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="col-sm-2 form-label">Proxy List</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="ayar[proxyListFollow]" style="height: 140px;"><?php echo Wow::get("ayar/proxyListFollow"); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg">Submit</button>
            </div>
        </form>
    </div>
</div>
<?php $this->section("section_scripts");
$this->parent(); ?>
<?php $this->endSection(); ?>
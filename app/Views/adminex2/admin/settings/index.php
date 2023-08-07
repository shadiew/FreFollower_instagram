<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 */
$this->set("title", "Ayarlar");
?>
<h2>Ayarlar</h2>
<p>Sistem genel ayarlarını bu sayfada yapılandırabilirsiniz.</p>
<form method="post" class="form-horizontal">
    <h5>İletişim Bilgileri</h5>
    <hr />
    <div class="form-group">
        <label class="col-sm-2 control-label">Admin İnstagram</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[contact_instagram]" value="<?php echo Wow::get("ayar/contact_instagram"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Admin İnstagram</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[contact_instagram2]" value="<?php echo Wow::get("ayar/contact_instagram2"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Site İnstagram Hesabı</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[contact_instagram3]" value="<?php echo Wow::get("ayar/contact_instagram3"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Whatsapp</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[contact_whatsapp]" value="<?php echo Wow::get("ayar/contact_whatsapp"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">YOUTUBE</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[contact_youtube]" value="<?php echo Wow::get("ayar/contact_youtube"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Skype</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[contact_skype]" value="<?php echo Wow::get("ayar/contact_skype"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Twitter</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[contact_twitter]" value="<?php echo Wow::get("ayar/contact_twitter"); ?>">
        </div>
    </div>
    <div class="clearfix"></div>
    <h5>Site Bilgileri</h5>
    <hr />
    <div class="form-group">
        <label class="col-sm-2 control-label">Site Başlık</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[site_baslik]" value="<?php echo Wow::get("ayar/site_baslik"); ?>" required>
            <span class="help-block">Sitenizin Kök Adını girin. Genelde en kısa haliyle firma ismi tercih edilir.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Site Title</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[site_title]" value="<?php echo Wow::get("ayar/site_title"); ?>" required>
            <span class="help-block">Sitenizin Kök Title bilgisini girin. Genelde firma ismi tercih edilr. Tüm sayfaların başlığının (title) sonuna eklenir.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Analytics Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[googleanalyticscode]" value="<?php echo Wow::get("ayar/googleanalyticscode"); ?>">
            <span class="help-block">Google'dan aldığınız bir analytics odunuz varsa girebilirsiniz.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Bir Sayfada Gösterilecek Blog Sayısı</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="ayar[birSayfadaGosterilecekBlogSayisi]" value="<?php echo Wow::get("ayar/birSayfadaGosterilecekBlogSayisi"); ?>" required>
            <span class="help-block">Blog bölümünde bir sayfada kaç adet blog listelensin?</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Blog Detaylarında Gösterilecek Benzer İçerik Sayısı</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="ayar[blogDetaydaGosterilecekBenzerBloglarSayisi]" value="<?php echo Wow::get("ayar/blogDetaydaGosterilecekBenzerBloglarSayisi"); ?>" required>
            <span class="help-block">Blog Detay sayfasında içeriğin alt kısmında kaç adet benzer içerik listelensin?</span>
        </div>
    </div>
    <div class="clearfix"></div>
    <h5>Kredi Tanımları</h5>
    <hr />
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tip</th>
                <th>Yeni Üye</th>
                <th>Tekrarlanan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Beğeni</td>
                <td>
                    <input type="number" class="form-control" name="ayar[yeniUyeBegeniKredi]" value="<?php echo Wow::get("ayar/yeniUyeBegeniKredi"); ?>" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="ayar[reUyeBegeniKredi]" value="<?php echo Wow::get("ayar/reUyeBegeniKredi"); ?>" required>
                </td>
            </tr>
            <tr>
                <td>Takip</td>
                <td>
                    <input type="number" class="form-control" name="ayar[yeniUyeTakipKredi]" value="<?php echo Wow::get("ayar/yeniUyeTakipKredi"); ?>" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="ayar[reUyeTakipKredi]" value="<?php echo Wow::get("ayar/reUyeTakipKredi"); ?>" required>
                </td>
            </tr>
            <tr>
                <td>Yorum</td>
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
                <td>Kaydetme</td>
                <td>
                    <input type="number" class="form-control" name="ayar[yeniUyeSaveKredi]" value="<?php echo Wow::get("ayar/yeniUyeSaveKredi"); ?>" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="ayar[reUyeSaveKredi]" value="<?php echo Wow::get("ayar/reUyeSaveKredi"); ?>" required>
                </td>
            </tr>
            <tr>
                <td>Video Görüntülenme</td>
                <td>
                    <input type="number" class="form-control" name="ayar[yeniUyeVideoKredi]" value="<?php echo Wow::get("ayar/yeniUyeVideoKredi"); ?>" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="ayar[reUyeVideoKredi]" value="<?php echo Wow::get("ayar/reUyeVideoKredi"); ?>" required>
                </td>
            </tr>
        </tbody>
    </table>
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
    <h5>Güvenlik</h5>
    <hr />
    <div class="form-group">
        <label class="col-sm-2 control-label">Link source</label>
        <div class="col-sm-10">
            <select class="form-control" name="ayar[sourceLink]" required>
                <option value="0" <?php echo Wow::get("ayar/sourceLink") == 0 ? "selected" : ""; ?>>Default</option>
                <option value="1" <?php echo Wow::get("ayar/sourceLink") == 1 ? "selected" : ""; ?>>Safelink</option>
                <option value="2" <?php echo Wow::get("ayar/sourceLink") == 2 ? "selected" : ""; ?>>Soralink</option>
            </select>
            <span class="help-block">Resimsiz hesaplar giremesin olarak seçtiğinizde profiline resim yüklememiş hesaplar sisteminize giriş yapamayacak.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Resimsiz Hesaplar</label>
        <div class="col-sm-10">
            <select class="form-control" name="ayar[resimsizLogin]" required>
                <option value="0" <?php echo Wow::get("ayar/resimsizLogin") == 0 ? "selected" : ""; ?>>Girsin</option>
                <option value="1" <?php echo Wow::get("ayar/resimsizLogin") == 1 ? "selected" : ""; ?>>Giremesin</option>
            </select>
            <span class="help-block">Resimsiz hesaplar giremesin olarak seçtiğinizde profiline resim yüklememiş hesaplar sisteminize giriş yapamayacak.</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Security Key</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[securityKey]" value="<?php echo Wow::get("ayar/securityKey"); ?>" required>
            <span class="help-block">Karmaşık bir güvelik kodu girin. Bu kodu diledikçe değiştirebilirsiniz. Bu kısımda değişiklik yaptığınızda cron url leriniz otomatik olarak güncellenir. Farklı bir işlem yapmanız gerekmez.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Google reCaptcha V2 Site Key </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[GoogleCaptchaSiteKey]" value="<?php echo Wow::get("ayar/GoogleCaptchaSiteKey"); ?>" placeholder="Site Key">
        </div>
        <hr />
        <br />
        <div class="clearfix"></div>
        <label class="col-sm-2 control-label">Google reCaptcha V2 Secret Key </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[GoogleCaptchaSecretKey]" value="<?php echo Wow::get("ayar/GoogleCaptchaSecretKey"); ?>" placeholder="Secret Key">
            <span class="help-block">Buraya Google reCaptcha v2 site ve secret kodunu girdiğinizde admin, bayi ve üye login kısımlarında Google reCaptcha v2 sistemi aktif hale geleektir.<br />Google reCaptcha v2 kayıt linki <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a></span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">INSTAWEB SMM AUTH</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[InstaWebSmmAuth]" value="<?php echo Wow::get("ayar/InstaWebSmmAuth"); ?>" readonly>
            <span class="help-block">Video Görüntülenme, Oto proxy ve diğer sistemleri kullanabilmeniz için INSTAWEB SMM sistemine kayıt olmanız gerekmektedir. <a href="/admin/api-smm">Kayıt olmak için buraya tıklayın</a>.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Antiflood Durumu</label>
        <div class="col-sm-10">
            <select name="ayar[antiFloodEnabled]" class="form-control">
                <option value="0">Devre Dışı</option>
                <option value="1" <?php echo Wow::get("ayar/antiFloodEnabled") == 1 ? ' selected="selected"' : ''; ?>>Aktif</option>
            </select>
            <span class="help-block">Saldırı almadığınız zamanlarda kapalı tutabilirsiniz. Performans için kapalı kalması daha iyidir.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">AntiFlood - Ban Sebebi</label>
        <div class="col-sm-10">
            <div class="input-group">
                <input type="number" class="form-control" name="ayar[antiFloodResetSec]" value="<?php echo Wow::get("ayar/antiFloodResetSec"); ?>" required>
                <span class="input-group-addon">saniyede</span>
                <input type="number" class="form-control" name="ayar[antiFloodMaxReq]" value="<?php echo Wow::get("ayar/antiFloodMaxReq"); ?>" required>
                <span class="input-group-addon">istek</span>
            </div>
            <span class="help-block">Önerilen rakamlar: 2 saniyede 5 istek!</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Antiflood - Ban Süresi</label>
        <div class="col-sm-10">
            <div class="input-group">
                <input type="number" class="form-control" name="ayar[antiFloodBanRemoveSec]" value="<?php echo Wow::get("ayar/antiFloodBanRemoveSec"); ?>" required>
                <span class="input-group-addon">saniye</span>
            </div>
            <span class="help-block">Max 300 saniye, önerilen: 60!</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Kabul Edilen Diller</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[acceptedLangCodes]" value="<?php echo Wow::get("ayar/acceptedLangCodes"); ?>" id="acceptedLangCodes" placeholder="Örn: tr,az">
            <span class="help-block">Sitenize yabancı kişilerin girmesini engellemek istiyorsanız, kabul edeceğiniz alpha 2 dil kodlarını virgül ile ayırarak girin. Bir engelleme olmayacaksa lütfen boş bırakın! Alpha 2 dil kodları için http://data.okfn.org/data/core/language-codes adresine gözatabilirsiniz.</span>
        </div>
    </div>
    <label class="col-sm-2 control-label">Kabul Edilmeyen Dil Tespiti</label>
    <div class="col-sm-10">
        <input type="hidden" name="ayar[nonAcceptedLangReaction]" id="nonAcceptedLangReaction" value="<?php echo Wow::get("ayar/nonAcceptedLangReaction") == 'redirecttourl' ? 'redirecttourl' : 'showmessage'; ?>">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span id="nonAcceptedLangReactionLabel"><?php echo Wow::get("ayar/nonAcceptedLangReaction") == "redirecttourl" ? 'Url' : 'İleti'; ?></span>
                    <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:void(0);" data-nonacceptedlangreaction="showmessage">İleti</a></li>
                    <li><a href="javascript:void(0);" data-nonacceptedlangreaction="redirecttourl">Url</a></li>
                </ul>
            </div>
            <input type="text" class="form-control" name="ayar[nonAcceptedLangText]" value="<?php echo Wow::get("ayar/nonAcceptedLangText"); ?>" placeholder="<?php echo Wow::get("ayar/nonAcceptedLangReaction") == "redirecttourl" ? 'Örn: http://site.com' : 'Örn: Server Error!' ?>" id="nonAcceptedLangText">
        </div>
        <span class="help-block">Eğer kabul edilmeyen bir dil ile sitenize erişmeye çalışılırsa verilecek tepkiyi ifade eder. İsterseniz kullanıcıyı istediğiniz bir URL ye yönlendirebilir, veya bir hata iletisi gösterebilirsiniz.</span>
    </div>
    <div class="clearfix"></div>
    <h5>Sistem</h5>
    <hr />
    <p class="text-danger">Sunucunuz çok yoğun kullanılıyor ve talepleri karşılayamarak ara ara tıkanıyorsa, Paket başı işlem ve Paket Başı Eş Zamanlı istek adetlerini orantılı olarak düşürün. Önerilen max rakamların üzerine çok fazla çıkmayın!</p>
    <div class="form-group">
        <label class="col-sm-2 control-label">Kullanıcı - Paket Başı İşlem</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="ayar[uyePaketBasiIstek]" value="<?php echo Wow::get("ayar/uyePaketBasiIstek"); ?>">
            <span class="help-block">Normal kullanıcılar için gönderim paketleri esnasında 1 pakette gönderilecek max işlem (beğeni, takipçi, yorum). Önerilen rakam: 15.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Kullanıcı - Paket Başı Eş Zamanlı İstek</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="ayar[uyeEsZamanliIstek]" value="<?php echo Wow::get("ayar/uyeEsZamanliIstek"); ?>">
            <span class="help-block">Normal kullanıcılar için her paket gönderimindeki simültane curl post adedi. Paket Başı İşlemin üçte birinden az olmamalıdır! Önerilen rakam: 5</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Bayi - Paket Başı İşlem</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="ayar[bayiPaketBasiIstek]" value="<?php echo Wow::get("ayar/bayiPaketBasiIstek"); ?>">
            <span class="help-block">Bayiler için gönderim paketleri esnasında 1 pakette gönderilecek max işlem (beğeni, takipçi, yorum). Önerilen rakam: 200.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Bayi - Paket Başı Eş Zamanlı İstek</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="ayar[bayiEsZamanliIstek]" value="<?php echo Wow::get("ayar/bayiEsZamanliIstek"); ?>">
            <span class="help-block">Bayiler için her paket gönderimindeki simültane curl post adedi. Paket Başı İşlemin üçte birinden az olmamalıdır! Önerilen rakam: 100.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Auto Follow Organik</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[adminFollowUserIDs]" value="<?php echo Wow::get("ayar/adminFollowUserIDs"); ?>">
            <span class="help-block">Her loginde takip edilecek instagram userID lerini virgül ile ayırarak girin. Takip olmayacaksa boş bırakın.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Engellenen Kullanıcılar</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[bannedUserIDs]" value="<?php echo Wow::get("ayar/bannedUserIDs"); ?>">
            <span class="help-block">Sisteme girişini engellemek istediğiniz kullanıcıların instagram userID lerini virgül ile ayırarak girin.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Admin Eklenen Oto Beğeni Son Kaç Gönderi?</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[adminLastOtoBegeni]" value="<?php echo Wow::get("ayar/adminLastOtoBegeni"); ?>" required>
            <span class="help-block">Adminden eklenilen oto beğenilerde atılan son kaç adet resme beğeni gönderileceğini belirleyebilirsiniz. Bu sayede aynı anda çok sayıda gönderi paylaşan kişilerin sisteminizi yavaşlatmasını engellersiniz. Sınır koymamak için <b>0</b> yazabilirsiniz.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Bayiden Eklenen Oto Beğeni Son Kaç Gönderi?</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ayar[bayiLastOtoBegeni]" value="<?php echo Wow::get("ayar/bayiLastOtoBegeni"); ?>" required>
            <span class="help-block">Bayiden eklenilen oto beğenilerde atılan son kaç adet resme beğeni gönderileceğini belirleyebilirsiniz. Bu sayede aynı anda çok sayıda gönderi paylaşan kişilerin sisteminizi yavaşlatmasını engellersiniz. Sınır koymamak için <b>0</b> yazabilirsiniz.</span>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="prx" <?php echo Wow::get("ayar/otoProxy") == 1 ? "style='display:none'" : ""; ?>>
        <h5>ByPass</h5>
        <hr />
        <div class="form-group">
            <label class="col-sm-2 control-label">ByPass Durumu</label>
            <div class="col-sm-10">
                <select class="form-control" name="ayar[proxyStatus]">
                    <option value="0">Pasif: Herhangi bir bypasser kullanılmayacak.</option>
                    <option value="1" <?php if (Wow::get("ayar/proxyStatus") == 1) {
                                            echo ' selected="selected"';
                                        } ?>>Proxy Yarı Aktif: Sadece takipçi, beğeni, yorum gönderimlerinde proxy kullanılacak.
                    </option>
                    <option value="2" <?php if (Wow::get("ayar/proxyStatus") == 2) {
                                            echo ' selected="selected"';
                                        } ?>>Proxy Tam Aktif: Tüm instagram işlemlerinde proxy kullanılacak.
                    </option>
                    <option value="3" <?php if (Wow::get("ayar/proxyStatus") == 3) {
                                            echo ' selected="selected"';
                                        } ?>>Proxy Range: Tüm instagram işlemlerinde proxy kullanılacak.
                    </option>
                    <option value="4" <?php if (Wow::get("ayar/proxyStatus") == 4) {
                                            echo ' selected="selected"';
                                        } ?>>Interface: Ip Listesi interface olarak kullanılacak.
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">ByPass Listesi</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="ayar[proxyList]" style="height: 140px;"><?php echo Wow::get("ayar/proxyList"); ?></textarea>
                <span class="help-block">Proxy seçildiğinde her satıra 1 adet yazmalısınız. Yazım deseni, user:pass@ip:port şeklinde veya şifresiz ise ip:port şeklinde olabilir. Tüm proxyler Http(s) Anonymous Proxy olmak zorundadır. Sistem proxylerin çalışıp çalışmadığını denetlemez!</span>
                <span class="help-block">Proxy Range seçildiğinde sadece tek bir satıra tek bir proxy aralığı yazabilirsiniz! Örn: username:password@ip:baslangicportu:bitisportu veya ip:baslangicportu:bitisportu</span>
                <span class="help-block">Interface seçtiğinizde sadece kendi sunucunuzda tanımlı ip adreslerinizi yazabilirsiniz. Her satıra 1 ip yazmalısınız. IPV4 ve IPV6 desteklenmektedir. Interface proxy den 10 kat daha hızlıdır.</span>
            </div>
        </div>
    </div>
    <hr />
    <div class="text-center">
        <button type="submit" class="btn btn-success btn-lg">Ayarları Kaydet</button>
    </div>
</form>

<?php $this->section("section_scripts");
$this->parent(); ?>
<script type="text/javascript">
    function setNonAcceptedLangReaction(reaction) {
        label = 'İleti';
        placeholder = 'Örn: Server Error!';
        val = 'Server Error!';
        if (reaction === 'redirecttourl') {
            label = 'Url';
            placeholder = 'Örn: http://site.com';
            val = 'http://';
        }
        $('#nonAcceptedLangReactionLabel').html(label);
        $('#nonAcceptedLangText').val(val).attr('placeholder', placeholder);
    }

    $('#otoProxy').on("change", function() {
        console.log(1);
        var val = $('#otoProxy').val();
        if (val == "1") {
            $('.prx').hide();
        } else {
            $('.prx').show();
        }
    });


    $('a[data-nonacceptedlangreaction]').click(function() {
        setNonAcceptedLangReaction($(this).attr('data-nonacceptedlangreaction'));
    });
</script>
<?php $this->endSection(); ?>
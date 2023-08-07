<?php

    namespace App\Libraries;

    use Exception;
    use Wow\Database\Database;
    use Instagram;
    use InstagramWeb;

    class InstagramReaction {

        /**
         * @var Instagram $objInstagram
         */
        public $objInstagram;

        /**
         * @var Database $db
         */
        protected $db;
        /**
         * @var array $user
         */
        protected $user;

        /**
         * Instantiate Class By UserID
         *
         * @param $userID
         */
        function __construct($userID) {
            $this->db = Database::getInstance();

            $user = $this->db->row("SELECT * FROM uye WHERE uyeID=:uyeID", array("uyeID" => intval($userID)));

            if(empty($user)) {
                throw new Exception($userID . " IDli uye bulunamadi!");
            }
            $this->user         = $user;
            $this->objInstagram = new Instagram($this->user["kullaniciAdi"], $this->user["sifre"], $this->user["instaID"]);
        }

        public function getMediaData($permalink) {
            $handle = curl_init();

            curl_setopt($handle, CURLOPT_URL, "https://i.instagram.com/publicapi/oembed/?url=" . $permalink);
            curl_setopt($handle, CURLOPT_POST, FALSE);
            curl_setopt($handle, CURLOPT_BINARYTRANSFER, FALSE);
            curl_setopt($handle, CURLOPT_HEADER, TRUE);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);

            $response = curl_exec($handle);

            $hlength  = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            $body     = substr($response, $hlength);

            // If HTTP response is not 200, return false
            if($httpCode != 200) {
                return FALSE;
            }

            return json_decode($body, TRUE);
        }

        function validateUser() {
            //Web Cookie ise
            if($this->user["isWebCookie"] == 1) {
                //Eğer kullanıcı __construct metodundan geçmiş olmasına rağmen hala isLoggedIn FALSE geliyorsa bu kullanıcının cookie dosyaları eksik anlamına gelir. Bu durumda bu kullanıcı pasif edeceğiz.
                if(!$this->objInstagramWeb->isLoggedIn()) {
                    $this->db->query("UPDATE uye SET isActive=0,isNeedLogin=0, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $this->user["uyeID"]));
                    return FALSE;
                }

                $checkUser = $this->objInstagramWeb->isValid();
                if($checkUser) {
                    $this->db->query("UPDATE uye SET isActive=1,isNeedLogin=0,canFollow=1,canLike=1,canComment=1, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $this->user["uyeID"]));
                    return TRUE;
                } else {
                    $this->db->query("UPDATE uye SET isActive=0,isNeedLogin=0, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $this->user["uyeID"]));
                    return FALSE;
                }
            } //User password ise
            else {
                //Eğer kullanıcı __construct metodundan geçmiş olmasına rağmen hala isLoggedIn FALSE geliyorsa bu ullanıcının cookie dosyaları eksik anlamına gelir. Bu durumda tekrar login denemesi yapmamız gerek.
                try {
                    $this->objInstagram->login(TRUE);
                } //Logini tazeleyemiyorsak bu kullanıcı pasif demektir. Malum kişi şifre değiştirmiş demektir. Pasif edelim.
                catch(Exception $e) {
                    $this->db->query("UPDATE uye SET isActive=0,isNeedLogin=0, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $this->user["uyeID"]));

                    return FALSE;
                }

                $checkUser = $this->objInstagram->getMediaInfo("1644818823288800567_6304564234");
                //Aktif Kullanıcı ile ilgili kullanıcıyı erişemez ve giriş gerekli hatası alırsak logini tazalemeyi deneyelim.
                if($checkUser["status"] == "fail" && ($checkUser["message"] == "login_required" || $checkUser["message"] == "checkpoint_required")) {
                    try {
                        $result = $this->objInstagram->login(TRUE);
                    } //Logini tazeleyemiyorsak bu kullanıcı pasif demektir. Malum kişi şifre değiştirmiş demektir. Pasif edelim.
                    catch(Exception $e) {
                        $result = array("status" => "fail");
                    }
                    if($result["status"] != "ok") {
                        $this->db->query("UPDATE uye SET isActive=0,isNeedLogin=0, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $this->user["uyeID"]));

                        return FALSE;
                    } else {
                        $this->db->query("UPDATE uye SET isActive=1,isNeedLogin=0,canFollow=1,canLike=1,canComment=1, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $this->user["uyeID"]));

                        return TRUE;
                    }
                } elseif($checkUser["status"] == "ok") {
                    $this->db->query("UPDATE uye SET isActive=1,isNeedLogin=0,canFollow=1,canLike=1,canComment=1, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $this->user["uyeID"]));
                    return TRUE;
                }

            }
            return FALSE;
        }

    }
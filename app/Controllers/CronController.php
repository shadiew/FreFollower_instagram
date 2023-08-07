<?php

namespace App\Controllers;

use App\Libraries\InstagramReaction;
use BulkReaction;
use Exception;
use RollingCurl\Request as RollingCurlRequest;
use RollingCurl\RollingCurl;
use Utils;
use Wow;
use Wow\Net\Response;
use Instagram;

class CronController extends BaseController
{

    /**
     * Override onStart
     */
    function onActionExecuting()
    {
        if (($pass = parent::onActionExecuting()) instanceof Response) {
            return $pass;
        }

        session_write_close();

        if ($this->request->query->scKey != Wow::get("ayar/securityKey")) {
            return $this->notFound();
        }
    }

    private function info($name, $value)
    {
        $this->db->query("INSERT INTO cron_history (name, value) VALUES(:name, :value)", array(
            "name"         => $name,
            "value"        => $value,
        ));
        return $this->db->CloseConnection();
    }

    function OptimizeDbAction()
    {
        $webhook_url = "https://discord.com/api/webhooks/1039189943018344518/BfBfa1fGoJtZDSgV6PAAHGHpJbkp11pIinuTndwV0Ul5WD1y-ctEGjhhVqS1QxFTJ6uW";

        $rowsUpdated =  $this->db->query("OPTIMIZE TABLE uye");
        $rowsUpdated2 =  $this->db->query("REPAIR TABLE uye");

        return $this->json(array(
            "status" => "success",
            "OPTIMIZE" => $rowsUpdated,
            "REPAIR" => $rowsUpdated2
        ));
    }

    function GetIPAction()
    {

        $webhook_url = "https://discord.com/api/webhooks/1048432057837162527/KABrjTNggBlmXTwy8FFiZZqoUwEllDG-v6zBNIXjEEcmA94q4aZ9uT7NnV6k4I8Lbx7U";

        $fileData = json_decode(file_get_contents("app/Config/system-settings.php"), TRUE);


        $as = explode(":", $fileData["proxyList"]);
        $ad = explode("@", $as[1]);

        $username = $as[0];
        $password = $ad[0];
        $ip = $ad[1];
        $port = $as[2];

        $up = array($username, $password);
        $ip = array($ip, $port);


        $username_password = implode(":", $up);
        $ip_port = implode(":", $ip);

        $full_proxy = "http://" . $username_password . "@" . $ip_port;

        $url = 'http://api.ipify.org';
        $proxy = $full_proxy;
        //$proxyauth = 'user:password';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);

        $this->bot("Get-IP-Address: `[" . $curl_scraped_page . "]` - Proxy `[" . $full_proxy . "]`", $webhook_url);

        return $this->json(array(
            "status"       => "success",
            "IP" => $curl_scraped_page,
            "Proxy" => $full_proxy

        ));
    }

    function ChangeProxyAction()
    {
        $webhook_url = "https://discord.com/api/webhooks/1048432057837162527/KABrjTNggBlmXTwy8FFiZZqoUwEllDG-v6zBNIXjEEcmA94q4aZ9uT7NnV6k4I8Lbx7U";

        $fileData = json_decode(file_get_contents("app/Config/system-settings.php"), TRUE);

        if ($fileData["proxyList"]  == $fileData["proxyList2"]) {
            $fileData["proxyList"] =  $fileData["proxyList3"];
        } else {
            $fileData["proxyList"] =  $fileData["proxyList2"];
        }

        file_put_contents("app/Config/system-settings.php", json_encode($fileData));

        $this->bot("Change-proxy-to: " . $fileData["proxyList"], $webhook_url);

        return $this->json(array(
            "status"       => "success",
            "proxyNow" => $fileData["proxyList"]
        ));
    }

    function RotateProxyAction()
    {
        $webhook_url = "https://discord.com/api/webhooks/1061995423809667164/_RHYPLi_FVSlQ3b4em8LQz27ZkDUY4JN2FCdJVc_dO34gguicEZ8i2Vio2QlllVg7cpH";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://thesocialproxy.com/wp-json/lmfwc/v2/licenses/rotate-proxy/bmV3LXlvcmsxLnRoZXNvY2lhbHByb3h5LmNvbToxMDAwMEBraXU2M2ptYW9jOWI3ejBkOjN2N2pjbTB3bGIyb3BmNHg=/?consumer_key=ck_a16a85386792e6b2aa9aaf438cbb5e6f4e2b925a&consumer_secret=cs_7251b45ab2a15327c438c800ee56a63044867c3d',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $this->bot("Rotate-Proxy: Sucess", $webhook_url);

        return $this->json(array(
            "status"       => "success",
            "proxyNow" => $response
        ));
    }

    function CheckCapabilitesProxyAction()
    {
        $webhook_url = "https://discord.com/api/webhooks/1024846512926228512/40bVx_9ugpTn8H-egXTv-OOwoMDZSub08I-LAP7BVCxv5DYnh4HCb_PmC9bNPCnooKhY";
        $rowsUpdated = $this->db->query("SELECT COUNT(uyeID) FROM uye WHERE isActive=1 AND isUsable=1 AND canFollow=1;");
        $rowsLike = $this->db->query("SELECT COUNT(uyeID) FROM uye WHERE isActive=1 AND isUsable=1 AND canLike=1;");
        $rowsActive = $this->db->query("SELECT COUNT(uyeID) FROM uye WHERE isActive=1;");
        $this->db->CloseConnection();

        $countCapabilites = $rowsUpdated[0]["COUNT(uyeID)"];
        $countLikes = $rowsLike[0]["COUNT(uyeID)"];
        $countActive = $rowsActive[0]["COUNT(uyeID)"];
        $fileData = json_decode(file_get_contents("app/Config/system-settings.php"), TRUE);

        $this->bot("Check-Capabilites-Proxy: " . $countCapabilites . " Follow & " . $countLikes . " Likes from: " . $countActive . " users active", $webhook_url);

        // if ($countCapabilites < 5) {

        //     if ($fileData["proxyList"]  == $fileData["proxyList2"]) {
        //         $fileData["proxyList"] =  $fileData["proxyList3"];
        //     } else {
        //         $fileData["proxyList"] =  $fileData["proxyList2"];
        //     }

        //     file_put_contents("app/Config/system-settings.php", json_encode($fileData));

        //     $this->bot("Check-Capabilites-Proxy: " . $countCapabilites . " Follow - Proxy: " . $fileData["proxyList"] . " - Changed", $webhook_url);
        // } else {
        //     $this->bot("Check-Capabilites-Proxy: " . $countCapabilites . " Follow - Proxy: " . $fileData["proxyList"], $webhook_url);
        // }

        return $this->json(array(
            "status"       => "success",
            "follow" => $countCapabilites,
            "like"  => $countLikes
        ));
    }

    function CheckProxyDieAction()
    {
        $webhook_url = "https://discord.com/api/webhooks/1039189943018344518/BfBfa1fGoJtZDSgV6PAAHGHpJbkp11pIinuTndwV0Ul5WD1y-ctEGjhhVqS1QxFTJ6uW";

        $rowsUpdated = $this->db->query("SELECT proxy, count, updated_at FROM proxy_worker WHERE count > 7 AND TIMESTAMPDIFF(MINUTE,updated_at,NOW()) < 5");
        $this->db->CloseConnection();

        if (!empty($rowsUpdated)) {
            $this->bot("Check-Proxy-Die: [Proxy: " . $rowsUpdated[0]["proxy"] . "] [Count: " . $rowsUpdated[0]["count"] . "] [Last Flag: " . $rowsUpdated[0]["updated_at"] . "]", $webhook_url);
        }

        // $countCapabilites = $rowsUpdated[0]["COUNT(uyeID)"];
        // $countLikes = $rowsLike[0]["COUNT(uyeID)"];
        // $countActive = $rowsActive[0]["COUNT(uyeID)"];
        // $fileData = json_decode(file_get_contents("app/Config/system-settings.php"), TRUE);



        // if ($countCapabilites < 10) {

        //     if ($fileData["proxyList"]  == $fileData["proxyList2"]) {
        //         $fileData["proxyList"] =  $fileData["proxyList3"];
        //     } else {
        //         $fileData["proxyList"] =  $fileData["proxyList2"];
        //     }

        //     file_put_contents("app/Config/system-settings.php", json_encode($fileData));

        //     $this->bot("Check-Capabilites-Proxy: " . $countCapabilites . " - Proxy: " . $fileData["proxyList"] . " - Changed", $webhook_url);
        // } else {
        //     $this->bot("Check-Capabilites-Proxy: " . $countCapabilites . " - Proxy: " . $fileData["proxyList"], $webhook_url);
        // }

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
    }

    function ResetLikeCreditAction()
    {
        $rowsUpdated = $this->db->query("UPDATE uye SET begeniKredi = :reUyeBegeniKredi WHERE begeniKredi < :reUyeBegeniKredi2", array(
            "reUyeBegeniKredi"  => Wow::get("ayar/reUyeBegeniKredi"),
            "reUyeBegeniKredi2" => Wow::get("ayar/reUyeBegeniKredi")
        ));

        $this->db->CloseConnection();
        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("Reset Like: " . $rowsUpdated, $webhook_url);


        $this->info("Reset like", $rowsUpdated);

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
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

    function ResetFollowCreditAction()
    {
        try {
            $rowsUpdated = $this->db->query("UPDATE uye SET takipKredi = :reTakipKredi WHERE takipKredi < :reTakipKredi2", array(
                "reTakipKredi"  => Wow::get("ayar/reUyeTakipKredi"),
                "reTakipKredi2" => Wow::get("ayar/reUyeTakipKredi")
            ));
            $this->db->CloseConnection();
            $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
            $this->bot("Reset Followers: " . $rowsUpdated, $webhook_url);

            $this->info("reset followers", $rowsUpdated);
            return $this->json(array(
                "status"       => "success",
                "rowsAffected" => $rowsUpdated,
            ));
        } catch (Exception $e) {
            $this->bot($e->getMessage(), $webhook_url);
        }
    }

    function ResetCommentCreditAction()
    {
        $rowsUpdated = $this->db->query("UPDATE uye SET yorumKredi = :reYorumKredi WHERE yorumKredi < :reYorumKredi2", array(
            "reYorumKredi"  => Wow::get("ayar/reUyeYorumKredi"),
            "reYorumKredi2" => Wow::get("ayar/reUyeYorumKredi")
        ));

        $this->db->CloseConnection();
        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("Reset Comment: " . $rowsUpdated, $webhook_url);

        $this->info("Reset comment", $rowsUpdated);
        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
    }

    function ResetStoryCreditAction()
    {

        $this->db->query("UPDATE uye SET videoKredi = :reVideoKredi WHERE videoKredi < :reVideoKredi2", array(
            "reVideoKredi"  => Wow::get("ayar/reUyeVideoKredi"),
            "reVideoKredi2" => Wow::get("ayar/reUyeVideoKredi")
        ));
        $this->db->CloseConnection();

        $this->db->query("UPDATE uye SET saveKredi = :reSaveKredi WHERE saveKredi < :reSaveKredi2", array(
            "reSaveKredi"  => Wow::get("ayar/reUyeSaveKredi"),
            "reSaveKredi2" => Wow::get("ayar/reUyeSaveKredi")
        ));
        $this->db->CloseConnection();

        $this->db->query("UPDATE uye SET yorumBegeniKredi = :reUyeYorumBegeniKredi WHERE yorumBegeniKredi < :reUyeYorumBegeniKredi2", array(
            "reUyeYorumBegeniKredi"  => Wow::get("ayar/reUyeYorumBegeniKredi"),
            "reUyeYorumBegeniKredi2" => Wow::get("ayar/reUyeYorumBegeniKredi")
        ));
        $this->db->CloseConnection();

        $this->db->query("UPDATE uye SET canliYayinKredi = :reUyeCanliKredi WHERE canliYayinKredi < :reUyeCanliKredi2", array(
            "reUyeCanliKredi"  => Wow::get("ayar/reUyeCanliKredi"),
            "reUyeCanliKredi2" => Wow::get("ayar/reUyeCanliKredi")
        ));
        $this->db->CloseConnection();

        $rowsUpdated = $this->db->query("UPDATE uye SET storyKredi = :reStoryKredi WHERE storyKredi < :reStoryKredi2", array(
            "reStoryKredi"  => Wow::get("ayar/reUyeStoryKredi"),
            "reStoryKredi2" => Wow::get("ayar/reUyeStoryKredi")
        ));
        $this->db->CloseConnection();

        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("Reset Story: " . $rowsUpdated, $webhook_url);

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
    }

    function ResetBayiCreditAction()
    {

        $rowsUpdated = $this->db->query("UPDATE bayi SET gunlukBegeniLimitLeft = gunlukBegeniLimit, gunlukTakipLimitLeft = gunlukTakipLimit,gunlukYorumLimitLeft=gunlukYorumLimit,gunlukStoryLimitLeft=gunlukStoryLimit,gunlukSaveLimitLeft=gunlukSaveLimit,gunlukYorumBegeniLimitLeft=gunlukYorumBegeniLimit,gunlukCanliYayinLimitLeft=gunlukCanliYayinLimit WHERE isActive=1");

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
    }

    function ControlNonLoginableUsersAction()
    {



        $users = $this->db->query("SELECT * FROM uye WHERE isActive=2 LIMIT 10");

        foreach ($users as $user) {

            $newMemberReaction = new Instagram($user["kullaniciAdi"], $user["sifre"], $user["instaID"]);

            $checkUser         = $newMemberReaction->isValid();

            if ($checkUser) {

                $this->db->query("UPDATE uye SET isActive=1,isNeedLogin=0,canFollow=1,canLike=1,canComment=1, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $user["uyeID"]));
            } else {

                $this->db->query("UPDATE uye SET isActive=0,isNeedLogin=0, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $user["uyeID"]));
            }
        }


        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("Reset Control-non-loginable-users: " . count($users), $webhook_url);

        return $this->json(array(

            "status"       => "success",

            "rowsAffected" => count($users)

        ));
    }



    function ControlApiAction()
    {

        $sorgu = $this->db->query("SELECT bayiIslemID,islemTip,mediaID,mediaCode,userID,userName,allComments,allStories,krediTotal,krediLeft,minuteDelay,krediDelay,excludedInstaIDs FROM bayi_islem WHERE islemTip<>'autolike' AND isActive=1 AND isApi=1 AND krediLeft >= 0 AND TIMESTAMPDIFF(MINUTE,sonKontrolTarihi,NOW()) > 1 AND TIMESTAMPDIFF(MINUTE,eklenmeTarihi,NOW()) < 240 ORDER BY sonKontrolTarihi ASC LIMIT 100");

        $rollingCurl = new RollingCurl();
        $ua          = md5("InstaWebBot");
        $ck          = md5("WowFramework" . "_" . preg_replace('/(?:www\.)?(.*)\/?$/i', '$1', $_SERVER["HTTP_HOST"]) . "_" . $ua);

        $actionId = 0;
        $cv          = substr(md5(Wow::get("project/licenseKey") . date("H")), 0, 26);

        foreach ($sorgu as $s) {
            $actionId = $s['bayiIslemID'];

            $rollingCurl->get((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . "/do/control-api/" . $s["bayiIslemID"] . "?scKey=" . Wow::get("ayar/securityKey"), ['Accept-Language: iw-IW'], [
                CURLOPT_USERAGENT => $ua,
                CURLOPT_COOKIE    => $ck . "=" . $cv,
                CURLOPT_ENCODING  => ''
            ]);
            continue;
        }

        $rollingCurl->setCallback(function (RollingCurlRequest $request, RollingCurl $rollingCurl) {
            $rollingCurl->clearCompleted();
            $rollingCurl->prunePendingRequestQueue();
        });
        $rollingCurl->setSimultaneousLimit(25);
        $rollingCurl->execute();

        $iade = $this->db->query("SELECT bayiIslemID,bayiID,krediLeft,krediTotal,talepPrice FROM bayi_islem WHERE islemTip<>'autolike' AND isApi=1 AND TIMESTAMPDIFF(MINUTE,eklenmeTarihi,NOW()) > 240 AND isActive=1");

        if (count($iade) > 0) {

            foreach ($iade as $i) {
                $iadeTutar = 0;

                if (!empty($i["talepPrice"])) {
                    $iadeTutar = $i["krediLeft"] * $i["talepPrice"];
                    $iadeTutar = $iadeTutar / $i["krediTotal"];
                }

                $this->db->query("UPDATE bayi SET bakiye=bakiye+:iade WHERE bayiID=:bayiid", array(
                    "iade"   => $iadeTutar,
                    "bayiid" => $i["bayiID"]
                ));

                $this->db->query("UPDATE bayi_islem SET isActive=0 WHERE bayiIslemID=:id", array("id" => $i["bayiIslemID"]));
                continue;
            }
        }

        return $this->json(array(
            "status" => "success", "actionId" => $actionId
        ));
    }

    function ControlReLoginUsersAction()
    {
        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=0 OR isActive=2 ORDER BY sonOlayTarihi ASC LIMIT 10");
        $this->db->CloseConnection();
        $rowsUpdated   = 0;
        $responseUsers = [];

        if (!empty($users)) {

            $assocUsers = array();
            foreach ($users as $au) {
                $assocUsers[$au["uyeID"]] = $au;
            }

            $this->db->CloseConnection();
            $bulkReaction = new BulkReaction($users, 5);
            $response     = $bulkReaction->relogin();

            $activeList    = "0";
            $needLoginList = "0";
            $naLoginList   = "0";

            foreach ($response["users"] as $u) {
                switch ($u["status"]) {
                    case "success":
                        $activeList .= "," . $u["userID"];
                        break;
                    case "fail":
                        $needLoginList .= "," . $u["userID"];
                        break;
                    case "na":
                        $naLoginList .= "," . $u["userID"];
                        break;
                }
            }
            if ($activeList != "0") {
                $this->db->query("UPDATE uye SET sonOlayTarihi=NOW(), isActive=1,canLike=1,canFollow=1,canComment=1,canStoryView=1 WHERE uyeID IN (" . $activeList . ")");
            }
            if ($needLoginList != "0") {
                $this->db->query("UPDATE uye SET sonOlayTarihi=NOW(),isActive=0 WHERE uyeID IN (" . $needLoginList . ")");
            }

            if ($naLoginList != "0") {
                $this->db->query("UPDATE uye SET sonOlayTarihi=NOW(),isActive=99 WHERE uyeID IN (" . $naLoginList . ")");
            }

            $rowsUpdated   = count($response["users"]);
            $responseUsers = $response["users"];
        }

        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("Reset Control-relogin-users-new: " . $rowsUpdated, $webhook_url);

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated,
            "users"        => $responseUsers
        ));
    }

    function ControlApiFollowUsersAction()
    {
        $users = $this->db->query("SELECT userID FROM bayi_islem WHERE bayiID=1 AND islemTip='follow' AND TIMESTAMPDIFF(MINUTE,eklenmeTarihi,NOW()) < 240 AND krediLeft>0 AND isApi=1 AND isActive=1 ORDER BY bayiIslemID DESC LIMIT 1");

        if (!empty($users)) {

            $this->db->CloseConnection();
            $bulkReaction = new BulkReaction($users);
            $response     = $bulkReaction->userInfo();

            foreach ($response["users"] as $u) {

                if (isset($u["userID"]) && isset($u["followerCount"]) && !empty($u["userID"]) && !empty($u["followerCount"])) {

                    $user = $this->db->row("SELECT * FROM bayi_islem WHERE bayiID=1 AND islemTip='follow' AND krediLeft>0 AND isApi=1 AND isActive=1 AND userID=:userid", array("userid" => $u["userID"]));

                    $leftAdet = intval($u["followerCount"]) - intval($user["start_count"]);

                    $adetLeft = intval($user["krediTotal"]) - intval($leftAdet);

                    if ($adetLeft <= 0) {
                        $adetLeft = 0;
                    }

                    $this->db->query("UPDATE bayi_islem SET krediLeft=:leftcount,sonKontrolTarihi=now() WHERE bayiID=1 AND islemTip='follow' AND krediLeft>0 AND isApi=1 AND isActive=1 AND userID=:userid ORDER BY bayiIslemID DESC LIMIT 1", array(
                        "leftcount" => $adetLeft,
                        "userid"    => $u["userID"]
                    ));
                }
            }
        }

        return $this->json(array(
            "status" => "success"
        ));
    }

    function ControlInactiveUsersAction()
    {
        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND ((canLike=0 AND TIMESTAMPDIFF(MINUTE,canLikeControlDate,NOW()) > 5) OR (canFollow=0 AND TIMESTAMPDIFF(MINUTE,canFollowControlDate,NOW()) > 15) OR (canComment=0 AND TIMESTAMPDIFF(MINUTE,canCommentControlDate,NOW()) > 15)) ORDER BY sonOlayTarihi ASC LIMIT 300");

        $rowsUpdated   = 0;
        $responseUsers = [];

        if (!empty($users)) {

            $assocUsers = array();
            foreach ($users as $au) {
                $assocUsers[$au["uyeID"]] = $au;
            }

            $this->db->CloseConnection();
            $bulkReaction = new BulkReaction($users, 90);
            $response     = $bulkReaction->validate();

            $activeList    = "0";
            $needLoginList = "0";
            $passiveList   = "0";

            foreach ($response["users"] as $u) {
                switch ($u["status"]) {
                    case "success":
                        $activeList .= "," . $u["userID"];
                        break;
                    case "fail":
                        $needLoginList .= "," . $u["userID"];

                        break;
                    case "na":
                        $needLoginList .= "," . $u["userID"];

                        break;
                }
            }
            if ($activeList != "0") {
                $this->db->query("UPDATE uye SET sonOlayTarihi=NOW(), isActive=1,canLike=1,canFollow=1,canComment=1,canStoryView=1 WHERE uyeID IN (" . $activeList . ")");
            }
            if ($needLoginList != "0") {
                $this->db->query("UPDATE uye SET isActive=2 WHERE uyeID IN (" . $needLoginList . ")");
            }

            $rowsUpdated   = count($response["users"]);
            $responseUsers = $response["users"];
        }

        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("Reset Control-inactive-users: " . $rowsUpdated, $webhook_url);

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated,
            "users"        => $responseUsers
        ));
    }

    function AddSourceCookiesAction()
    {
        $controllerUserNick = NULL;
        $rowsAffected       = 0;
        $sourceCookies      = glob(Wow::get("project/cookiePath") . "source/*.{selco,dat}", GLOB_BRACE);

        if (count($sourceCookies) > 0) {
            $controllingUserID = $this->findAReactionUser();

            if (!empty($controllingUserID)) {

                $controllingUser      = $this->db->row("SELECT * FROM uye WHERE uyeID=:uyeID", array("uyeID" => intval($controllingUserID)));
                $controllerUserNick   = $controllingUser["kullaniciAdi"];
                $objInstagramReaction = new InstagramReaction($controllingUser["uyeID"]);
                for ($i = 0; $i < count($sourceCookies); $i++) {
                    $cookieFile        = $sourceCookies[$i];
                    $arrCookieFileName = explode("/", $cookieFile);
                    $cookieFileName    = $arrCookieFileName[count($arrCookieFileName) - 1];

                    $cookieFileNewName = strtolower(trim($cookieFileName));
                    if ($cookieFileNewName != $cookieFileName) {
                        rename($cookieFile, str_replace($cookieFileName, $cookieFileNewName, $cookieFile));
                        $cookieFile     = str_replace($cookieFileName, $cookieFileNewName, $cookieFile);
                        $cookieFileName = $cookieFileNewName;
                    }


                    $username       = substr($cookieFileName, -3) == "dat" ? substr($cookieFileName, 0, strlen($cookieFileName) - 4) : substr($cookieFileName, 0, strlen($cookieFileName) - 6);
                    $password       = NULL;
                    $cookieContents = file_get_contents($cookieFile);
                    $cnfContents    = file_exists(Wow::get("project/cookiePath") . "source/" . $username . ".cnf") ? file_get_contents(Wow::get("project/cookiePath") . "source/" . $username . ".cnf") : NULL;
                    if (strpos($cookieContents, ".instagram.com") !== FALSE) {
                        try {
                            $instaUser = $objInstagramReaction->objInstagram->getUserInfoByName($username);
                            if ($instaUser["status"] == "ok") {
                                $following_count = $instaUser["user"]["following_count"];
                                $follower_count  = $instaUser["user"]["follower_count"];
                                $phoneNumber     = NULL;
                                $gender          = NULL;
                                $birthday        = NULL;
                                $profilePic      = $instaUser["user"]["profile_pic_url"];
                                $full_name       = preg_replace("/[^[:alnum:][:space:]]/u", "", $instaUser["user"]["full_name"]);
                                $instaID         = $instaUser["user"]["pk"] . "";
                                $email           = NULL;

                                $isWebCookie   = strpos($cookieContents, "i.instagram.com") === FALSE ? 1 : 0;
                                $convertedData = Utils::cookieConverter($cookieContents, $cnfContents, [
                                    "username_id" => $instaID,
                                    "isWebCookie" => $isWebCookie
                                ]);

                                if (!empty($convertedData)) {
                                    $uyeID = $this->db->row("SELECT * FROM uye WHERE instaID = :instaID LIMIT 1", array("instaID" => $instaID));
                                    if (!empty($uyeID) && $uyeID["isActive"] != 1) {
                                        //Eer kullanıcı daha önceden kayıtlı ama aktif değilse, silip yenisini ekleyelim ki bir umut olsun.
                                        $this->db->query("DELETE FROM uye WHERE uyeID = :uyeID", array("uyeID" => $uyeID["uyeID"]));
                                        $uyeID = NULL;
                                    }
                                    if (empty($uyeID)) {
                                        $this->db->query("INSERT INTO uye (instaID, profilFoto, fullName, kullaniciAdi, sifre, takipEdilenSayisi, takipciSayisi, phoneNumber, email, gender, birthDay, isWebCookie) VALUES(:instaID, :profilFoto, :fullName, :kullaniciAdi, :sifre, :takipEdilenSayisi, :takipciSayisi, :phoneNumber, :email, :gender, :birthDay, :isWebCookie)", array(
                                            "instaID"           => $instaID,
                                            "profilFoto"        => $profilePic,
                                            "fullName"          => $full_name,
                                            "kullaniciAdi"      => $username,
                                            "sifre"             => $password,
                                            "takipEdilenSayisi" => $following_count,
                                            "takipciSayisi"     => $follower_count,
                                            "phoneNumber"       => $phoneNumber,
                                            "email"             => $email,
                                            "gender"            => $gender,
                                            "birthDay"          => $birthday,
                                            "isWebCookie"       => $isWebCookie
                                        ));

                                        $lastUserID = $this->db->lastInsertId();

                                        file_put_contents(Wow::get("project/cookiePath") . "instagramv3/" . substr($instaID, -1) . "/" . $instaID . ".iwb", $convertedData);
                                        unlink($cookieFile);
                                        if (!empty($cnfContents)) {
                                            unlink(Wow::get("project/cookiePath") . "source/" . $username . ".cnf");
                                        }
                                        $newMemberReaction = new Instagram($username, $password, $instaID);
                                        $checkUser         = $newMemberReaction->isValid();

                                        if ($checkUser) {
                                            $this->db->query("UPDATE uye SET isActive=1,isNeedLogin=0,canFollow=1,canLike=1,canComment=1, sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $lastUserID));
                                        } else {
                                            $this->db->query("UPDATE uye SET isActive=0,isNeedLogin=0,sonOlayTarihi = NOW() WHERE uyeID=:uyeID", array("uyeID" => $lastUserID));
                                        }
                                    } else {
                                        //Data çevrilemiyor!
                                        unlink($cookieFile);
                                        if (!empty($cnfContents)) {
                                            unlink(Wow::get("project/cookiePath") . "source/" . $username . ".cnf");
                                        }
                                    }
                                } else {
                                    //Kullanıcı zaten sistemde olduğu için data eklenmiyor!
                                    unlink($cookieFile);
                                    if (!empty($cnfContents)) {
                                        unlink(Wow::get("project/cookiePath") . "source/" . $username . ".cnf");
                                    }
                                }
                            } else {
                                //Böyle bir kullanıcı yok işlem yapılamıyor.
                                unlink($cookieFile);
                                if (!empty($cnfContents)) {
                                    unlink(Wow::get("project/cookiePath") . "source/" . $username . ".cnf");
                                }
                            }
                            $rowsAffected++;
                        } catch (Exception $e) {
                            //User Login failed! Tekrar denenecek.
                            break;
                        }
                    } else {
                        //Geçersiz cookie!
                        unlink($cookieFile);
                    }
                    if ($i >= 29) {
                        break;
                    }
                }
            }
        }

        return $this->json(array(
            "status"         => "success",
            "rowsAffected"   => $rowsAffected,
            "controlleduser" => $controllerUserNick
        ));
    }

    function ControlBayiAutoLikeMediasAction()
    {
        $packages = $this->db->query("SELECT * FROM bayi_islem WHERE isActive=1 AND islemTip='autolike' AND TIMESTAMPDIFF(MINUTE,sonKontrolTarihi,NOW()) > 4 ORDER BY sonKontrolTarihi ASC LIMIT 50");
        if (empty($packages)) {
            return $this->json(array(
                "status" => "success"
            ));
        } else {
            $rollingCurl = new RollingCurl();
            $ua          = md5("InstaWebBot");
            $ck          = md5("WowFramework" . "_" . preg_replace('/(?:www\.)?(.*)\/?$/i', '$1', $_SERVER["HTTP_HOST"]) . "_" . $ua);
            $cv          = substr(md5(Wow::get("project/licenseKey") . date("H")), 0, 26);
            foreach ($packages as $islem) {
                $rollingCurl->get((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . "/do/control-bayi-auto-like-medias/" . $islem["bayiIslemID"] . "?scKey=" . Wow::get("ayar/securityKey"), ['Accept-Language: iw-IW'], [
                    CURLOPT_USERAGENT => $ua,
                    CURLOPT_COOKIE    => $ck . "=" . $cv,
                    CURLOPT_ENCODING  => ''
                ]);
            }
            $rollingCurl->setCallback(function (RollingCurlRequest $request, RollingCurl $rollingCurl) {
                $rollingCurl->clearCompleted();
                $rollingCurl->prunePendingRequestQueue();
            });
            $rollingCurl->setSimultaneousLimit(25);
            $rollingCurl->execute();

            return $this->json(array(
                "status" => "success"
            ));
        }
    }

    function ControlAutoLikeMediasAction()
    {
        $packages = $this->db->query("SELECT * FROM uye_otobegenipaket WHERE isActive=1 AND TIMESTAMPDIFF(MINUTE,lastControlDate,NOW()) > 4 ORDER BY lastControlDate ASC LIMIT 50");
        if (empty($packages)) {
            return $this->json(array(
                "status" => "success"
            ));
        } else {
            $rollingCurl = new RollingCurl();
            $ua          = md5("InstaWebBot");
            $ck          = md5("WowFramework" . "_" . preg_replace('/(?:www\.)?(.*)\/?$/i', '$1', $_SERVER["HTTP_HOST"]) . "_" . $ua);
            $cv          = substr(md5(Wow::get("project/licenseKey") . date("H")), 0, 26);
            foreach ($packages as $islem) {
                $rollingCurl->get((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . "/do/control-auto-like-medias/" . $islem["id"] . "?scKey=" . Wow::get("ayar/securityKey"), ['Accept-Language: iw-IW'], [
                    CURLOPT_USERAGENT => $ua,
                    CURLOPT_COOKIE    => $ck . "=" . $cv,
                    CURLOPT_ENCODING  => ''
                ]);
            }
            $rollingCurl->setCallback(function (RollingCurlRequest $request, RollingCurl $rollingCurl) {
                $rollingCurl->clearCompleted();
                $rollingCurl->prunePendingRequestQueue();
            });
            $rollingCurl->setSimultaneousLimit(25);
            $rollingCurl->execute();

            return $this->json(array(
                "status" => "success"
            ));
        }
    }

    function AutoLikeMediasAction()
    {
        $gonderi = $this->db->query("SELECT * FROM uye_otobegenipaket_gonderi WHERE likeCountLeft>0 AND TIMESTAMPDIFF(MINUTE,lastControlDate,NOW()) >= minuteDelay ORDER BY lastControlDate ASC LIMIT 100");
        if (empty($gonderi)) {
            return $this->json(array(
                "status" => "success"
            ));
        } else {
            $rollingCurl   = new RollingCurl();
            $toplamGonderi = count($gonderi);
            $sortIndex     = 0;
            $ua            = md5("InstaWebBot");
            $ck            = md5("WowFramework" . "_" . preg_replace('/(?:www\.)?(.*)\/?$/i', '$1', $_SERVER["HTTP_HOST"]) . "_" . $ua);
            $cv            = substr(md5(Wow::get("project/licenseKey") . date("H")), 0, 26);
            foreach ($gonderi as $islem) {
                $sortIndex++;
                $rollingCurl->get((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . "/do/auto-like/" . $islem["id"] . "?scKey=" . Wow::get("ayar/securityKey") . "&totalRows=" . $toplamGonderi . "&sortIndex=" . $sortIndex, ['Accept-Language: iw-IW'], [
                    CURLOPT_USERAGENT => $ua,
                    CURLOPT_COOKIE    => $ck . "=" . $cv,
                    CURLOPT_ENCODING  => ''
                ]);
            }
            $rollingCurl->setCallback(function (RollingCurlRequest $request, RollingCurl $rollingCurl) {
                $rollingCurl->clearCompleted();
                $rollingCurl->prunePendingRequestQueue();
            });
            $rollingCurl->setSimultaneousLimit(40);
            $rollingCurl->execute();

            return $this->json(array(
                "status" => "success"
            ));
        }
    }

    function ResetLimitLikeAction()
    {
        $rowsUpdated = $this->db->query("UPDATE uye SET limitLike = :setlimitLike WHERE limitLike < :countLimitLike", array(
            "setlimitLike"  => 30,
            "countLimitLike" => 30,
        ));
        $this->db->CloseConnection();

        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("Reset limit like: " . $rowsUpdated, $webhook_url);

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
    }

    function ResetLimitFollowAction()
    {
        $rowsUpdated = $this->db->query("UPDATE uye SET limitFollow = :setlimitFollow WHERE limitFollow < :countLimitFollow", array(
            "setlimitFollow"  => 1,
            "countLimitFollow" => 1,
        ));
        $this->db->CloseConnection();

        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("reset limit follow: " . $rowsUpdated, $webhook_url);
        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
    }

    function ResetLimitCommentAction()
    {
        $rowsUpdated = $this->db->query("UPDATE uye SET limitComment = :setlimitComment WHERE limitComment < :countLimitcomment", array(
            "setlimitComment"  => 1,
            "countLimitcomment" => 1,
        ));
        $this->db->CloseConnection();

        $webhook_url = "https://discord.com/api/webhooks/953691679607644270/XAr9uTc8IzWzQ0prgjrCaUwt2Q5JCoZk4dU284ikxvRb7UbuwkblKhFJ60vsLCzHCiFw";
        $this->bot("reset limit comment: " . $rowsUpdated, $webhook_url);
        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => $rowsUpdated
        ));
    }
}

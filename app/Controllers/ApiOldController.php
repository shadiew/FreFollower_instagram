<?php

namespace App\Controllers;

use BulkReaction;
use Wow;
use App\Libraries\InstagramReaction;
use Wow\Net\Response;

class ApiController extends BaseController
{
    /**
     * Override onStart
     */
    function onActionExecuting()
    {
        if (($pass = parent::onActionExecuting()) instanceof Response) {
            return $pass;
        }

        $reactionUserID = $this->findAReactionUser();
        $this->instagramReaction = new InstagramReaction($reactionUserID);

        session_write_close();

        if ($this->request->query->scKey != Wow::get("ayar/securityKey")) {
            return $this->notFound();
        }
    }

    function ToolsAction()
    {
        if ($this->request->method == "POST") {
            switch ($this->request->query->formType) {
                case "sendFollower":
                        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

                        $adet = intval($this->request->data->adet);
                        if ($adet <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nolimitdefined",
                                "message" => "Takipçi Eklenemedi. Adet tanımlanmadı!",
                                "users"   => array()
                            );
                            return $this->json($sonuc);
                        }

                        $takipKredi = $this->db->row("SELECT takipKredi FROM uye WHERE uyeID=:uyeID", array("uyeID" => $this->request->data->uyeID));
                        $this->db->CloseConnection();

                        $takipKredi = $takipKredi['takipKredi'];

                        if ($takipKredi == 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Takipçi Eklenemedi. Kullanıcı kalmadı!",
                            );

                            return $this->json($sonuc);
                        }

                        if ($adet > $takipKredi) {
                            $adet = $takipKredi;
                        }

                        if ($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                            $adet = Wow::get("ayar/bayiPaketBasiIstek");
                        }

                        $followedUsers = array();
                        if (!isset($_SESSION["FollowerssForInstaID" . $this->request->data->userID]) || !is_array($_SESSION["FollowerssForInstaID" . $this->request->data->userID])) {
                            $nextMaxID = NULL;
                            $intLoop   = 0;
                            while ($follower = $this->instagramReaction->objInstagram->getUserFollowers($this->request->data->userID, $nextMaxID)) {
                                $intLoop++;
                                foreach ($follower["users"] as $user) {
                                    $followedUsers[] = $user["pk"];
                                }
                                if (!isset($follower["next_max_id"]) || $intLoop >= 8) {
                                    break;
                                } else {
                                    $nextMaxID = $follower["next_max_id"];
                                }
                            }
                        } else {
                            $followedUsers = (array)$_SESSION["FollowerssForInstaID" . $this->request->data->userID];
                        }


                        $triedUserIDs = isset($_SESSION["TriedUsersForFollowInstaID" . $this->request->data->userID]) ? $_SESSION["TriedUsersForFollowInstaID" . $this->request->data->userID] : NULL;
                        if (empty($triedUserIDs)) {
                            $triedUserIDs = "0";
                        }
                        $userIDs = "0";
                        foreach (explode(",", $triedUserIDs) as $userID) {
                            if (intval($userID) > 0) {
                                $userIDs .= "," . intval($userID);
                            }
                        }

                        $instaIDs         = "0";
                        $followedInstaIDs = $followedUsers;
                        foreach ($followedInstaIDs as $instaID) {
                            if (intval($instaID) > 0) {
                                $instaIDs .= "," . intval($instaID);
                            }
                        }

                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND canFollow=1 and isUsable=1 AND uyeID NOT IN($userIDs) AND instaID NOT IN($instaIDs) ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        $this->db->CloseConnection();

                        foreach ($users as $key => $value) {
                            $this->db->query("INSERT INTO tools_history (instaID, actionTool, destination, source, requestID, statusTool) VALUES(:instaID, :actionTool, :destination, :source, :requestID, :statusTool)", array(
                                "instaID"         => $this->request->data->instaID,
                                "destination"     => $this->request->data->userID  . " / " . $this->request->data->userName,
                                "actionTool"      => "sendFollow",
                                "source"          => $value['instaID'],
                                "requestID"         => $uuid,
                                "statusTool"         => "pending",
                            ));
                            $this->db->CloseConnection();
                        }

                        if (empty($users)) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Takipçi Eklenemedi. Kullanıcı kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $allUserIDs = array_map(function ($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);
                        $this->db->query("UPDATE uye SET sonOlayTarihi=NOW() WHERE uyeID IN (" . $allUserIDs . ")");
                        $this->db->CloseConnection();

                        $bulkReaction      = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));
                        $response          = $bulkReaction->follow($this->request->data->userID, $this->request->data->userName);
                        $triedUsers        = $response["users"];

                        $successCount = 0;
                        $failCount = 0;

                        foreach ($triedUsers as $v) {
                            $this->db->query(
                                "UPDATE uye SET limitFollow=limitFollow-1 WHERE instaID=:instaID",
                                array("instaID" => $v['instaID'])
                            );
                            $this->db->CloseConnection();

                            $this->db->query("UPDATE tools_history SET statusTool=:statusTool WHERE requestID=:requestID AND source=:source", array(
                                "requestID"         => $uuid,
                                "statusTool"         => $v["status"],
                                "source"          => $v['instaID'],
                            ));
                            $this->db->CloseConnection();

                            if ($v["status"] == "success") {
                                $successCount += 1;
                            }

                            if ($v["status"] != "success") {
                                $failCount += 1;
                            }
                        }

                        $this->db->query("INSERT INTO uye_tools (userCount, requestCount, successCount) VALUES(:userCount, :requestCount, :successCount)", array(
                            "userCount"     => count($users),
                            "requestCount"  => $adet,
                            "successCount"  => $successCount
                        ));

 
                        $totalSuccessCount = $response["totalSuccessCount"];
                        $allUserIDs        = array_map(function ($d) {
                            return $d["userID"];
                        }, $triedUsers);
                        if (!empty($allUserIDs)) {
                            $allUserIDs                                                            = implode(",", $allUserIDs);
                            $userIDs                                                               .= "," . $allUserIDs;
                            $_SESSION["TriedUsersForFollowInstaID" . $this->request->data->userID] = $userIDs;
                        }
                        $allFailedUserIDs = array_filter(array_map(function ($d) {
                            return $d["status"] == "fail" ? $d["userID"] : NULL;
                        }, $triedUsers), function ($d) {
                            return $d !== NULL;
                        });
                        if (!empty($allFailedUserIDs)) {
                            $allFailedUserIDs = implode(",", $allFailedUserIDs);
                            $this->db->query("UPDATE uye SET canFollow=0,canFollowControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                            $this->db->CloseConnection();
                        }

                        $this->db->query("UPDATE uye SET takipKredi=takipKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->request->data->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->db->CloseConnection();
                        $takipKredi = intval($takipKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"     => "success",
                            "message"    => "Başarılı.",
                            "users"      => $triedUsers,
                            "takipKredi" => $takipKredi
                        );

                        return $this->json($sonuc);
                        break;
                }
        }

        return $this->json(array(
            "status"       => "success",
            "rowsAffected" => "ok",
        ));
    }
}

<?php

    namespace App\Controllers;

    use App\Libraries\InstagramReaction;
    use BulkReaction;
    use Wow;
    use Wow\Net\Response;

    class HistoryController extends BaseController {

        /**
         * @var InstagramReaction $iReaction
         */
        private $instagramReaction;
        /**
         * @var int $paketBasiIstek
         */
        private $paketBasiIstek;
        /**
         * @var int $esZamanliIstek
         */
        private $esZamanliIstek;

        /**
         * Override onStart
         */
        function onActionExecuting() {
            if(($pass = parent::onActionExecuting()) instanceof Response) {
                return $pass;
            }

            //Navigation
            $this->navigation->add("Araçlar", "/history");

            if($this->route->params["action"] != "SendFollowerDs") {
                //Üye girişi kontrolü.
                if(($pass = $this->middleware("logged")) instanceof Response) {
                    return $pass;
                }
                $this->instagramReaction = new InstagramReaction($this->logonPerson->member->uyeID);
            }

			if($this->route->params["action"] == "SendFollowerDs") {
                $this->instagramReaction = new InstagramReaction("735401");
            }          

            $this->paketBasiIstek = $this->logonPerson->member->isBayi == 1 ? Wow::get('ayar/uyePaketBasiIstek') : Wow::get('ayar/bayiPaketBasiIstek');
            $this->esZamanliIstek = $this->logonPerson->member->isBayi == 1 ? Wow::get('ayar/uyeEsZamanliIstek') : Wow::get('ayar/bayiEsZamanliIstek');
        }

        function IndexAction() {
            // print_r($this->logonPerson->member->instaID);die();
            $d             = $this->helper->getPageDetail(4);
            $d["pageInfo"] = unserialize($d["pageInfo"]);

            $this->view->set('title', $d["pageInfo"]["title"]);
            $this->view->set('description', $d["pageInfo"]["description"]);
            $this->view->set('keywords', $d["pageInfo"]["keywords"]);

            $history = $this->db->query("SELECT requestID, statusTool, destination, COUNT(statusTool) countStatusTool, createdAt FROM tools_history WHERE instaID = :instaID GROUP by requestID, statusTool, destination, createdAt order by createdAt desc LIMIT 20", array("instaID" => $this->logonPerson->member->instaID));
            $this->db->CloseConnection();

            // print_r($data);die();
            $data = $this->db->query("SELECT name, fill, created_at FROM post WHERE status = 1 or status = 2 ORDER BY id DESC LIMIT 3");
            $this->db->CloseConnection();

            $this->view->set("data", $data);
            $this->view->set("history", $history);
            $this->view->set("helper", $this->helper);
            return $this->view($d);
        }

        function StoryViewAction($id = NULL) {
            $this->view->set("title", "Auto Story Gratis");
        
            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findUserID":
                        $userData = $this->instagramReaction->objInstagram->getUserInfoByName($this->request->data->username);
                        if($userData["status"] != "ok") {
                            return $this->notFound();
                        } else {
                            $userID = $userData["user"]["pk"];

                            return $this->redirectToUrl("/tools/story-view/" . $userID);
                        }
                        break;
                    case "send":
                        if(intval($this->logonPerson->member->storyKredi) <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nocreditleft",
                                "message" => "Poin anda sudah habis, tunggu 2 jam lagi.",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $adet = intval($this->request->data->adet);
                        if($adet <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nolimitdefined",
                                "message" => "Adet tanımlanmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        if($adet > $this->logonPerson->member->storyKredi) {
                            $adet = $this->logonPerson->member->storyKredi;
                        }


                        if($adet > $this->paketBasiIstek) {
                            $adet = $this->paketBasiIstek;
                        }

                        $triedUserIDs = isset($_SESSION["TriedUsersForStoryMediaID" . $this->request->data->mediaID]) ? $_SESSION["TriedUsersForStoryMediaID" . $this->request->data->mediaID] : NULL;
                        if(empty($triedUserIDs)) {
                            $triedUserIDs = "0";
                        }
                        $userIDs = "0";
                        foreach(explode(",", $triedUserIDs) as $userID) {
                            if(intval($userID) > 0) {
                                $userIDs .= "," . intval($userID);
                            }
                        }

                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND uyeID NOT IN($userIDs) ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        $this->db->CloseConnection();
                        if(empty($users)) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Story İzlemesi Gönderilemedi. Kullanıcı kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $allUserIDs = array_map(function($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);

                        $bulkReaction = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));

                        $items    = array();
                        $getItems = $this->instagramReaction->objInstagram->hikayecek($id);
                        if(count($getItems["reel"]["items"]) < 1) {
                                $sonuc = array(
                                    "status"  => "error",
                                    "code"    => "nolimitdefined",
                                    "message" => "Kullanıcının aktif bir story paylaşımı bulunmamaktadır!",
                                    "users"   => array()
                                );


                            return $this->json($sonuc);
                        }

                     foreach($getItems["reel"]["items"] AS $item) {
                            $items[] = array(
                                "getTakenAt" => $item["taken_at"],
                                "itemID"     => $item["id"],
                                "userPK"     => $id
                            );
                        }


                       $response          = $bulkReaction->storyview($items);
                        $triedUsers        = $response["users"];
                        $totalSuccessCount = $response["totalSuccessCount"];
                        $allUserIDs        = array_map(function($d) {
                            return $d["userID"];
                        }, $triedUsers);
                        if(!empty($allUserIDs)) {
                            $allUserIDs                                                            = implode(",", $allUserIDs);
                            $userIDs                                                               .= "," . $allUserIDs;
                            $_SESSION["TriedUsersForStoryMediaID" . $this->request->data->mediaID] = $userIDs;
                        }
                        $allFailedUserIDs = array_filter(array_map(function($d) {
                            return $d["status"] == "fail" ? $d["userID"] : NULL;
                        }, $triedUsers), function($d) {
                            return $d !== NULL;
                        });
                        if(!empty($allFailedUserIDs)) {
                            $allFailedUserIDs = implode(",", $allFailedUserIDs);
                            $this->db->query("UPDATE uye SET canStoryView=0,canStoryViewControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                            $this->db->CloseConnection();
                        }

                        $this->db->query("UPDATE uye SET storyKredi=storyKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->logonPerson->member->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->db->CloseConnection();
                        $this->logonPerson->member->begeniKredi = intval($this->logonPerson->member->begeniKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"      => "success",
                            "message"     => "Başarılı.",
                            "users"       => $triedUsers,
                            "begeniKredi" => $this->logonPerson->member->begeniKredi
                        );


                        return $this->json($sonuc);
                        break;
                    case "more":
                        $allmedias = $this->instagramReaction->objInstagram->hikayecek($this->logonPerson->member["instaID"]);
                        $this->view->set("ajaxLoaded", 1);

                        return $this->partialView($allmedias);
                        break;
                }
            } //GET Method
            else {
                if(!is_null($id)) {
                    $user                    = $this->instagramReaction->objInstagram->getUserInfoById($id);
                    if($user["status"] != "ok") {
                        return $this->notFound();
                    }
                    $this->view->set("user", $user);
                }
            }


            $this->navigation->add("Story Görüntülenme Gönderimi", "/tools/story-view/");

            return $this->view();
        }

        function SendSaveAction($code = NULL) {
            $this->view->set("title", "Save Post Gratis");

            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findMediaID":
                        $mediaData = $this->getCodeByUrl($this->request->data->mediaUrl);
                        if(!$mediaData) {
                            return $this->notFound();
                        } else {
                            $mediaID = $mediaData;

                            return $this->redirectToUrl("/tools/send-save/" . $mediaID);
                        }
                        break;
                    case "send":

                        if(intval($this->logonPerson->member->saveKredi) <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nocreditleft",
                                "message" => "Fotoğraf Kaydetme Eklenemedi. Fotoğraf Kaydetme krediniz kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $adet = intval($this->request->data->adet);
                        if($adet <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nolimitdefined",
                                "message" => "Fotoğraf Kaydetme Eklenemedi. Adet tanımlanmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        if($adet > $this->logonPerson->member->saveKredi) {
                            $adet = $this->logonPerson->member->saveKredi;
                        }

                        if($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                            $adet = Wow::get("ayar/bayiPaketBasiIstek");
                        }

                        $likedUsers = array();
                        if(!isset($_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID]) || !is_array($_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID])) {
                            $likers = $this->instagramReaction->objInstagram->getMediaLikersNew($code);
                            foreach($likers["users"] as $user) {
                                $likedUsers[] = $user["pk"];
                            }
                            $_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID] = $likedUsers;
                        } else {
                            $likedUsers = (array)$_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID];
                        }

                        $triedUserIDs = isset($_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID]) ? $_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID] : NULL;
                        if(empty($triedUserIDs)) {
                            $triedUserIDs = "0";
                        }
                        $userIDs = "0";
                        foreach(explode(",", $triedUserIDs) as $userID) {
                            if(intval($userID) > 0) {
                                $userIDs .= "," . intval($userID);
                            }
                        }


                        $instaIDs      = "0";
                        $likedInstaIDs = $likedUsers;
                        foreach($likedInstaIDs as $instaID) {
                            if(intval($instaID) > 0) {
                                $instaIDs .= "," . intval($instaID);
                            }
                        }

                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND isUsable=1 AND uyeID NOT IN($userIDs) AND instaID NOT IN($instaIDs)  ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        $this->db->CloseConnection();
                        if(empty($users)) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Fotoğraf Kaydetme Eklenemedi. Kullanıcı kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }
                        $allUserIDs = array_map(function($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);
                        $this->db->query("UPDATE uye SET sonOlayTarihi=NOW() WHERE uyeID IN (" . $allUserIDs . ")");
                        $this->db->CloseConnection();

                        $bulkReaction      = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));
                        $response          = $bulkReaction->save($this->request->data->mediaID, $this->request->data->mediaCode);

                        $triedUsers        = $response["users"];
                        $totalSuccessCount = $response["totalSuccessCount"];

                        $allUserIDs        = array_map(function($d) {
                            return $d["userID"];
                        }, $triedUsers);
                        if(!empty($allUserIDs)) {
                            $allUserIDs                                                           = implode(",", $allUserIDs);
                            $userIDs                                                              .= "," . $allUserIDs;
                            $_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID] = $userIDs;
                        }
                        $allFailedUserIDs = array_filter(array_map(function($d) {
                            return $d["status"] == "fail" ? $d["userID"] : NULL;
                        }, $triedUsers), function($d) {
                            return $d !== NULL;
                        });


                        $this->db->query("UPDATE uye SET saveKredi=saveKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->logonPerson->member->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->db->CloseConnection();
                        $this->logonPerson->member->saveKredi = intval($this->logonPerson->member->saveKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"      => "success",
                            "message"     => "Başarılı.",
                            "users"       => $triedUsers,
                            "begeniKredi" => $this->logonPerson->member->saveKredi
                        );


                        return $this->json($sonuc);
                        break;
                }
            } //GET Method
            else {
                if(!is_null($code)) {
                    $media = $this->instagramReaction->objInstagram->getMediaInfoEmbedByCode($code);
                    if(!$media) {
                        return $this->notFound();
                    }
                    $this->view->set("media", $media);
                }
            }

            $this->navigation->add("Beğeni Gönderimi", "/tools/send-like");

            return $this->view();
        }

        function SendViewVideoAction($code = NULL) {
            $this->view->set("title", "Auto View Video Gratis");

            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findMediaID":
                        $mediaData = $this->getCodeByUrl($this->request->data->mediaUrl);
                        if(!$mediaData) {
                            return $this->notFound();
                        } else {
                            $mediaID = $mediaData;

                            return $this->redirectToUrl("/tools/send-view-video/" . $mediaID);
                        }
                        break;
                    case "send":

                        if(intval($this->logonPerson->member->videoKredi) <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nocreditleft",
                                "message" => "Video İzlenme Gönderilmedi. Video izlenme krediniz kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $adet = intval($this->request->data->adet);
                        if($adet <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nolimitdefined",
                                "message" => "Video İzlenme Gönderilmedi. Adet tanımlanmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        if($adet > $this->logonPerson->member->videoKredi) {
                            $adet = $this->logonPerson->member->videoKredi;
                        }

                        if($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                            $adet = Wow::get("ayar/bayiPaketBasiIstek");
                        }
                        $adet = $adet * 2;

                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1  AND uyeID NOT IN(0) AND instaID NOT IN(0)  ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        $this->db->CloseConnection();

                        $allUserIDs = array_map(function($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);
                        $this->db->query("UPDATE uye SET sonOlayTarihi=NOW() WHERE uyeID IN (" . $allUserIDs . ")");
                        $this->db->CloseConnection();

                        $bulkReaction      = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));
                        $response          = $bulkReaction->izlenme($this->request->data->mediaCode,$this->request->data->mediaID);
                        $triedUsers        = $response["users"];
                        $totalSuccessCount = $response["totalSuccessCount"];


                        $this->db->query("UPDATE uye SET videoKredi=videoKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->logonPerson->member->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->db->CloseConnection();
                        $this->logonPerson->member->videoKredi = intval($this->logonPerson->member->videoKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"      => "success",
                            "message"     => "Başarılı.",
                            "users"       => $triedUsers,
                            "response"    => $response,
                            "begeniKredi" => $this->logonPerson->member->videoKredi
                        );

                        return $this->json($sonuc);
                        break;
                }
            } //GET Method
            else {
                if(!is_null($code)) {
                    $media = $this->instagramReaction->objInstagram->getMediaInfoEmbedByCode($code);
                    if(!$media) {
                        return $this->notFound();
                    }
                    $this->view->set("media", $media);
                }
            }

            $this->navigation->add("Video izlenme Gönderimi", "/tools/send-view-video");

            return $this->view();
        }

        private function bot($message, $url) {
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
              CURLOPT_POSTFIELDS =>"{\"content\": \"$message\"}",
              CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Cookie: __cfduid=d5f1a47b65ad6e5eae8714df12c304b661597580155; __cfruid=f0077b70b216ca6a35ad1f5280ecec475e2dd4cc-1597580155"
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }

        function SendLikeAction($code = NULL) {
            $this->view->set("title", "Auto Like Gratis");

            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findMediaID":
                        $mediaData = $this->getCodeByUrl($this->request->data->mediaUrl);
                        if(!$mediaData) {
                            return $this->notFound();
                        } else {
                            $mediaID = $mediaData;

                            return $this->redirectToUrl("/tools/send-like/" . $mediaID);
                        }
                        break;
                    case "send":

                        if(intval($this->logonPerson->member->begeniKredi) <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nocreditleft",
                                "message" => "Beğeni Eklenemedi. Beğeni krediniz kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $adet = intval($this->request->data->adet);
                        if($adet <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nolimitdefined",
                                "message" => "Beğeni Eklenemedi. Adet tanımlanmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        if($adet > $this->logonPerson->member->begeniKredi) {
                            $adet = $this->logonPerson->member->begeniKredi;
                        }

                        if($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                            $adet = Wow::get("ayar/bayiPaketBasiIstek");
                        }

                        $likedUsers = array();
                        if(!isset($_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID]) || !is_array($_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID])) {
                            $likers = $this->instagramReaction->objInstagram->getMediaLikersNew($code);
                            foreach($likers["users"] as $user) {
                                $likedUsers[] = $user["pk"];
                            }
                            $_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID] = $likedUsers;
                        } else {
                            $likedUsers = (array)$_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID];
                        }

                        $triedUserIDs = isset($_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID]) ? $_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID] : NULL;
                        if(empty($triedUserIDs)) {
                            $triedUserIDs = "0";
                        }
                        $userIDs = "0";
                        foreach(explode(",", $triedUserIDs) as $userID) {
                            if(intval($userID) > 0) {
                                $userIDs .= "," . intval($userID);
                            }
                        }


                        $instaIDs      = "0";
                        $likedInstaIDs = $likedUsers;
                        foreach($likedInstaIDs as $instaID) {
                            if(intval($instaID) > 0) {
                                $instaIDs .= "," . intval($instaID);
                            }
                        }

                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND canLike=1 and isUsable=1 AND uyeID NOT IN($userIDs) AND instaID NOT IN($instaIDs)  ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        $this->db->CloseConnection();
                        if(empty($users)) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Beğeni Eklenemedi. Kullanıcı kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }
                        $allUserIDs = array_map(function($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);
                        $this->db->query("UPDATE uye SET sonOlayTarihi=NOW() WHERE uyeID IN (" . $allUserIDs . ")");
                        $this->db->CloseConnection();

                        $bulkReaction      = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));
                        $response          = $bulkReaction->like($this->request->data->mediaID, $this->request->data->mediaUsername, $this->request->data->mediaUserID);
                        $triedUsers        = $response["users"];


                        
                        $totalSuccessCount = $response["totalSuccessCount"];
                        $allUserIDs        = array_map(function($d) {
                            return $d["userID"];
                        }, $triedUsers);
                        if(!empty($allUserIDs)) {
                            $allUserIDs                                                           = implode(",", $allUserIDs);
                            $userIDs                                                              .= "," . $allUserIDs;
                            $_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID] = $userIDs;
                        }
                        $allFailedUserIDs = array_filter(array_map(function($d) {
                            return $d["status"] == "fail" ? $d["userID"] : NULL;
                        }, $triedUsers), function($d) {
                            return $d !== NULL;
                        });
                        if(!empty($allFailedUserIDs)) {
                            $allFailedUserIDs = implode(",", $allFailedUserIDs);
                            $this->db->query("UPDATE uye SET canLike=0,canLikeControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                            $this->db->CloseConnection();
                        }

                        $this->db->query("UPDATE uye SET begeniKredi=begeniKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->logonPerson->member->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->db->CloseConnection();
                        $this->logonPerson->member->begeniKredi = intval($this->logonPerson->member->begeniKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"      => "success",
                            "message"     => "Başarılı.",
                            "users"       => $triedUsers,
                            "begeniKredi" => $this->logonPerson->member->begeniKredi
                        );


                        return $this->json($sonuc);
                        break;
                }
            } //GET Method
            else {
                if(!is_null($code)) {
                    $media = $this->instagramReaction->objInstagram->getMediaInfoEmbedByCode($code);
                    if(!$media) {
                        return $this->notFound();
                    }
                    $this->view->set("media", $media);
                }
            }

            $this->navigation->add("Beğeni Gönderimi", "/tools/send-like");

            return $this->view();
        }

        function SendFollowerDsAction($username = Null) {
            $userData = $this->instagramReaction->objInstagram->getUserInfoByName($username);
            return $this->json($userData);
        }

        function SendFollowerAction($id = NULL) {
            $this->view->set("title", "Auto Followers Gratis");

            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findUserID":
                        $userData = $this->instagramReaction->objInstagram->getUserInfoByName($this->request->data->username);
                        if($userData["status"] != "ok") {
                            return $this->notFound();
                        } else {
                            $userID = $userData["user"]["pk"];

                            return $this->redirectToUrl("/tools/send-follower/" . $userID);
                        }
                        break;
                    case "send":

                        if(intval($this->logonPerson->member->takipKredi) <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nocreditleft",
                                "message" => "Takipçi Eklenemedi. Takipçi krediniz kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $adet = intval($this->request->data->adet);
                        if($adet <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nolimitdefined",
                                "message" => "Takipçi Eklenemedi. Adet tanımlanmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        if($adet > $this->logonPerson->member->takipKredi) {
                            $adet = $this->logonPerson->member->takipKredi;
                        }

                        if($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                            $adet = Wow::get("ayar/bayiPaketBasiIstek");
                        }

                        $followedUsers = array();
                        if(!isset($_SESSION["FollowerssForInstaID" . $this->request->data->userID]) || !is_array($_SESSION["FollowerssForInstaID" . $this->request->data->userID])) {
                            $nextMaxID = NULL;
                            $intLoop   = 0;
                            while($follower = $this->instagramReaction->objInstagram->getUserFollowers($this->request->data->userID, $nextMaxID)) {
                                $intLoop++;
                                foreach($follower["users"] as $user) {
                                    $followedUsers[] = $user["pk"];
                                }
                                if(!isset($follower["next_max_id"]) || $intLoop >= 8) {
                                    break;
                                } else {
                                    $nextMaxID = $follower["next_max_id"];
                                }
                            }


                        } else {
                            $followedUsers = (array)$_SESSION["FollowerssForInstaID" . $this->request->data->userID];
                        }


                        $triedUserIDs = isset($_SESSION["TriedUsersForFollowInstaID" . $this->request->data->userID]) ? $_SESSION["TriedUsersForFollowInstaID" . $this->request->data->userID] : NULL;
                        if(empty($triedUserIDs)) {
                            $triedUserIDs = "0";
                        }
                        $userIDs = "0";
                        foreach(explode(",", $triedUserIDs) as $userID) {
                            if(intval($userID) > 0) {
                                $userIDs .= "," . intval($userID);
                            }
                        }

                        $instaIDs         = "0";
                        $followedInstaIDs = $followedUsers;
                        foreach($followedInstaIDs as $instaID) {
                            if(intval($instaID) > 0) {
                                $instaIDs .= "," . intval($instaID);
                            }
                        }

                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND canFollow=1 and isUsable=1 AND uyeID NOT IN($userIDs) AND instaID NOT IN($instaIDs) ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        $this->db->CloseConnection();


                        if(empty($users)) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Takipçi Eklenemedi. Kullanıcı kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $allUserIDs = array_map(function($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);
                        $this->db->query("UPDATE uye SET sonOlayTarihi=NOW() WHERE uyeID IN (" . $allUserIDs . ")");
                        $this->db->CloseConnection();

                        $bulkReaction      = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));
                        $response          = $bulkReaction->follow($this->request->data->userID, $this->request->data->userName);
                        $triedUsers        = $response["users"];

                        $successCount = 0;

						$uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                        foreach($triedUsers as $v) {
                                $this->db->query("UPDATE uye SET limitFollow=limitFollow-1 WHERE instaID=:instaID", 
                                    array("instaID" => $v['instaID'])
                                );
                                $this->db->CloseConnection();

                                $this->db->query("INSERT INTO uye_status (instaID, action, instaIDTarget, username, requestID, status) VALUES(:instaID, :action, :instaIDTarget, :username, :requestID, :status)", array(
                                    "instaID"         => $this->request->data->userID,
                                    "username"        => $this->request->data->userName,
                                    "action"          => "sendFollow",
                                    "instaIDTarget"   => $v['instaID'],
                                    "requestID"   	  => $uuid,
                                    "status"   	      => $v["status"],
                                ));
                                $this->db->CloseConnection();
                                $successCount += 1;
                        }

                        $this->db->query("INSERT INTO uye_tools (userCount, requestCount, successCount) VALUES(:userCount, :requestCount, :successCount)", array(
                            "userCount"     => count($users),
                            "requestCount"  => $adet,
                            "successCount"  => $successCount
                        ));

                        $webhook_url = "https://discord.com/api/webhooks/746674708652490812/c7fZJvvhxNmZhxjbrTxK3HZIC1stZWP4MDPrxpa0vQyhyLK4UNNrds9kEEMk350sMQCl";
                        $this->bot("count poten follower: " . count($users) . " from target: " . $adet . " success: " . $successCount, $webhook_url);

                        $totalSuccessCount = $response["totalSuccessCount"];
                        $allUserIDs        = array_map(function($d) {
                            return $d["userID"];
                        }, $triedUsers);
                        if(!empty($allUserIDs)) {
                            $allUserIDs                                                            = implode(",", $allUserIDs);
                            $userIDs                                                               .= "," . $allUserIDs;
                            $_SESSION["TriedUsersForFollowInstaID" . $this->request->data->userID] = $userIDs;
                        }
                        $allFailedUserIDs = array_filter(array_map(function($d) {
                            return $d["status"] == "fail" ? $d["userID"] : NULL;
                        }, $triedUsers), function($d) {
                            return $d !== NULL;
                        });
                        if(!empty($allFailedUserIDs)) {
                            $allFailedUserIDs = implode(",", $allFailedUserIDs);
                            $this->db->query("UPDATE uye SET canFollow=0,canFollowControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                            $this->db->CloseConnection();
                        }

                        $this->db->query("UPDATE uye SET takipKredi=takipKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->logonPerson->member->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->db->CloseConnection();
                        $this->logonPerson->member->takipKredi = intval($this->logonPerson->member->takipKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"     => "success",
                            "message"    => "Başarılı.",
                            "users"      => $triedUsers,
                            "takipKredi" => $this->logonPerson->member->takipKredi
                        );

                        return $this->json($sonuc);
                        break;
                }
            } //GET Method
            else {
                if(!is_null($id)) {
                    $user = $this->instagramReaction->objInstagram->getUserInfoById($id);
                    if($user["status"] != "ok") {
                        return $this->notFound();
                    }
                    $this->view->set("user", $user);
                }
            }

            $this->navigation->add("Takipçi Gönderimi", "/tools/send-follower");

            return $this->view();
        }

        function NonfollowUsersAction($id = NULL) {
            $this->view->set("title", "Unfollow Gratis");

            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findUsers":
                        $users = array();

                        $nextMaxID      = NULL;
                        $followingUsers = array();
                        while($following = $this->instagramReaction->objInstagram->getSelfUsersFollowing($nextMaxID)) {
                            foreach($following["users"] as $user) {
                                $followingUsers[] = $user["username"];
                                if(!isset($users[$user["username"]])) {
                                    $users[$user["username"]] = $user;
                                }
                            }
                            if(!isset($following["next_max_id"])) {
                                break;
                            } else {
                                $nextMaxID = $following["next_max_id"];
                            }
                        }

                        $nextMaxID     = NULL;
                        $followerUsers = array();
                        while($follower = $this->instagramReaction->objInstagram->getSelfUserFollowers($nextMaxID)) {
                            foreach($follower["users"] as $user) {
                                $followerUsers[] = $user["username"];
                                if(!isset($users[$user["username"]])) {
                                    $users[$user["username"]] = $user;
                                }
                            }
                            if(!isset($follower["next_max_id"])) {
                                break;
                            } else {
                                $nextMaxID = $follower["next_max_id"];
                            }
                        }


                        $nonFollowers  = array_diff($followingUsers, $followerUsers);
                        $nonFollowings = array_diff($followerUsers, $followingUsers);
                        $data          = array(
                            "users"         => $users,
                            "nonFollowers"  => $nonFollowers,
                            "nonFollowings" => $nonFollowings
                        );

                        return $this->partialView($data, "tools/non-users");
                        break;
                }
            }

            return $this->view();
        }

        function SendCommentAction($code = NULL) {
            $this->view->set("title", "Auto Comment Gratis");

            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findMediaID":
                        $mediaData = $this->getCodeByUrl($this->request->data->mediaUrl);
                        if(!$mediaData) {
                            return $this->notFound();
                        } else {
                            $mediaID = $mediaData;

                            return $this->redirectToUrl("/tools/send-comment/" . $mediaID);
                        }
                        break;
                    case "send":

                        if(intval($this->logonPerson->member->yorumKredi) <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nocreditleft",
                                "message" => "Dia tidak bisa ditambahkan. Anda tidak memiliki Poin lagi!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $arrCommentg = $this->request->data->yorum;
                        if(!is_array($arrCommentg) || empty($arrCommentg)) {
                            $sonuc = array(
                                "status"  => "error",
                                "message" => "Yorum Eklenemedi. En az 1 yorum yazmalısınız!",
                                "userID"  => 0
                            );

                            return $this->json($sonuc);
                        }

                        $arrComment = [];
                        foreach($arrCommentg as $comment) {
                            if(!empty($comment)) {
                                $arrComment[] = trim($comment);
                            }
                        }
                        $commentedIndexes = $this->request->query->clearCommentedIndex == 1 ? [] : $_SESSION["CommentedIndexesForMediaID" . $this->request->data->mediaID];
                        if(!is_array($commentedIndexes) || empty($commentedIndexes)) {
                            $commentedIndexes = [];
                        }

                        $arrComment = array_diff_key($arrComment, $commentedIndexes);

                        if(empty($arrComment)) {
                            $sonuc = array(
                                "status"  => "error",
                                "message" => "Yorum Eklenemedi. En az 1 yorum yazmalısınız!",
                                "userID"  => 0
                            );

                            return $this->json($sonuc);
                        }

                        $adet = count($arrComment);

                        if($adet > $this->logonPerson->member->yorumKredi) {
                            $adet = $this->logonPerson->member->yorumKredi;
                        }

                        if($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                            $adet = Wow::get("ayar/bayiPaketBasiIstek");
                        }

                        $triedUserIDs = isset($_SESSION["TriedUsersForCommentMediaID" . $this->request->data->mediaID]) ? $_SESSION["TriedUsersForCommentMediaID" . $this->request->data->mediaID] : NULL;
                        if(empty($triedUserIDs)) {
                            $triedUserIDs = "0";
                        }
                        $userIDs = "0";
                        foreach(explode(",", $triedUserIDs) as $userID) {
                            if(intval($userID) > 0) {
                                $userIDs .= "," . intval($userID);
                            }
                        }

                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND canComment=1 and isUsable=1 AND uyeID NOT IN($userIDs) ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        $this->db->CloseConnection();
                        if(empty($users)) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Yorum Eklenemedi. Kullanıcı kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $allUserIDs = array_map(function($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);
                        $this->db->query("UPDATE uye SET sonOlayTarihi=NOW() WHERE uyeID IN (" . $allUserIDs . ")");
                        $this->db->CloseConnection();

                        $bulkReaction      = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));
                        $response          = $bulkReaction->comment($this->request->data->mediaID, $this->request->data->mediaCode, $arrComment);
                        $triedUsers        = $response["users"];

                        
                        $totalSuccessCount = $response["totalSuccessCount"];
                        $allUserIDs        = array_map(function($d) {
                            return $d["userID"];
                        }, $triedUsers);
                        if(!empty($allUserIDs)) {
                            $allUserIDs                                                              = implode(",", $allUserIDs);
                            $userIDs                                                                 .= "," . $allUserIDs;
                            $_SESSION["TriedUsersForCommentMediaID" . $this->request->data->mediaID] = $userIDs;
                        }
                        $allFailedUserIDs = array_filter(array_map(function($d) {
                            return $d["status"] == "fail" ? $d["userID"] : NULL;
                        }, $triedUsers), function($d) {
                            return $d !== NULL;
                        });
                        if(!empty($allFailedUserIDs)) {
                            $allFailedUserIDs = implode(",", $allFailedUserIDs);
                            $this->db->query("UPDATE uye SET canComment=0,canCommentControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                            $this->db->CloseConnection();
                        }

                        $this->db->query("UPDATE uye SET yorumKredi=yorumKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->logonPerson->member->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->db->CloseConnection();
                        $this->logonPerson->member->yorumKredi = intval($this->logonPerson->member->yorumKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"     => "success",
                            "message"    => "Başarılı.",
                            "users"      => $triedUsers,
                            "yorumKredi" => $this->logonPerson->member->yorumKredi
                        );

                        foreach($triedUsers as $i => $v) {
                            if($v["status"] == "success") {
                                $commentedIndexes[$v["commentIndex"]] = TRUE;
                            }
                        }
                        $_SESSION["CommentedIndexesForMediaID" . $this->request->data->mediaID] = $commentedIndexes;

                        return $this->json($sonuc);
                        break;
                }
            } //GET Method
            else {
                if(!is_null($code)) {
                    $media = $this->instagramReaction->objInstagram->getMediaInfoEmbedByCode($code);
                    if(!$media) {
                        return $this->notFound();
                    }
                    $this->view->set("media", $media);
                }
            }

            $this->navigation->add("Yorum Gönderimi", "/tools/send-comment");

            return $this->view();
        }

        public function getAllComments($mediaID, $username, $last = NULL)
        {
            $commentData = "";
            for ($i = 0; $i < 2; $i++) {
    
                $comments = $this->instagramReaction->objInstagram->getMediaComments($mediaID, $last);
    
                if (count($comments["comments"]) > 0) {
    
                    $b = 0;
                    foreach ($comments["comments"] as $comment) {
                        if ($b == 0) {
                            $last = isset($comment["pk"]) ? $comment["pk"] : "";
                        }
                        $b++;
                        if ($comment["user"]["username"] == $username) {
                            $commentData = $comment;
                            break;
                        }
                    }
                }
    
                if (!empty($commentData)) {
                    return array(
                        "status"  => 1,
                        "comment" => $commentData
                    );
                    break;
                }
            }
        }

        function SendCommentLikeAction($id = NULL) {
            $this->view->set("title", "Auto Comment Likes");

            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "findMediaID":
                        $mediaData = $this->instagramReaction->objInstagram->getMediaInfoByUrl($this->request->data->mediaUrl);
                        if(!$mediaData) {
                            return $this->notFound();
                        } else {
                            $mediaID = $mediaData["graphql"]['shortcode_media']['id'];
       
                            $commentData = self::getAllComments($mediaID, $this->request->data->username);
                            $commentID = $commentData["comment"]["pk"];
       
                            if($commentData["status"] == 1) {
                              return $this->redirectToUrl("/tools/send-comment-like/" . $mediaID . "?yorumid=" . $commentID . "&yorum=" . urlencode("<b>" . $commentData["comment"]["user"]["username"] . "</b> : " . $commentData["comment"]["text"]));
                            } else {
                                $sonuc = array(
                                    "status"  => "error",
                                    "code"    => "error",
                                    "message" => $commentData,
                                    "users"   => array()
                                );

                                return $this->json($sonuc);
                            }
                        }
                        break;
                    case "send":     
     
                        if(intval($this->logonPerson->member->yorumBegeniKredi) <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nocreditleft",
                                "message" => "Yorum Beğenisi Eklenemedi. Beğeni krediniz kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        $adet = intval($this->request->data->adet);
                        if($adet <= 0) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nolimitdefined",
                                "message" => "Yorum Beğenisi Eklenemedi. Adet tanımlanmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }

                        if($adet > $this->logonPerson->member->yorumBegeniKredi) {
                            $adet = $this->logonPerson->member->yorumBegeniKredi;
                        }

                        if($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                            $adet = Wow::get("ayar/bayiPaketBasiIstek");
                        }


                        $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND isWebCookie = 0 ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                        if(empty($users)) {
                            $sonuc = array(
                                "status"  => "error",
                                "code"    => "nouserleft",
                                "message" => "Beğeni Eklenemedi. Kullanıcı kalmadı!",
                                "users"   => array()
                            );

                            return $this->json($sonuc);
                        }
      
                        $allUserIDs = array_map(function($d) {
                            return $d["uyeID"];
                        }, $users);
                        $allUserIDs = implode(",", $allUserIDs);
                        $this->db->query("UPDATE uye SET sonOlayTarihi=NOW() WHERE uyeID IN (" . $allUserIDs . ")");
                        $this->db->CloseConnection();
                        $commentID = $this->request->data->yorumID;
                        $mediaID = $this->request->data->mediaID;
                        $bulkReaction      = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));
                        $response          = $bulkReaction->commentLike($mediaID, $commentID);
      
                        $triedUsers        = $response["users"];
                        $totalSuccessCount = $response["totalSuccessCount"];    
      
                        $allFailedUserIDs = array_filter(array_map(function($d) {
                            return $d["status"] == "fail" ? $d["userID"] : NULL;
                        }, $triedUsers), function($d) {
                            return $d !== NULL;
                        });
                        if(!empty($allFailedUserIDs)) {
                            $allFailedUserIDs = implode(",", $allFailedUserIDs);
                            $this->db->query("UPDATE uye SET canLike=0,canCommentControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                        }

                        $this->db->query("UPDATE uye SET yorumBegeniKredi=yorumBegeniKredi-:successCount WHERE uyeID=:uyeID", array(
                            "uyeID"        => $this->logonPerson->member->uyeID,
                            "successCount" => $totalSuccessCount
                        ));
                        $this->logonPerson->member->yorumBegeniKredi = intval($this->logonPerson->member->yorumBegeniKredi) - $totalSuccessCount;

                        $sonuc = array(
                            "status"      => "success",
                            "message"     => "Başarılı.",
                            "users"       => $triedUsers,
                            "yorumBegeniKredi" => $this->logonPerson->member->yorumBegeniKredi
                        );


                        return $this->json($sonuc);
                        break;
                }
            } //GET Method
            else {
                if(!is_null($id)) {
                    $media = $this->instagramReaction->objInstagram->getMediaInfo($id);
                    if($media["status"] != "ok") {
                        return $this->notFound();
                    }
                    $this->view->set("media", $media);
                    $this->view->set("comment", array(
                        "commentID" => $this->request->query->yorumid,
                        "comment"   => urldecode($this->request->query->yorum)
                    ));
                }
            }

            $this->navigation->add("Yorum Beğeni Gönderimi", "/tools/send-comment-like");

            return $this->view();
        }

        function AutoLikePackagesAction() {
            if($this->request->method == "POST") {
                switch($this->request->query->formType) {
                    case "packageDetails":
                        $paketID = intval($this->request->query->paketID);
                        $paket   = $this->db->row("SELECT * FROM uye_otobegenipaket WHERE instaID=:instaID AND id=:paketID", array(
                            "instaID" => $this->logonPerson->member->instaID,
                            "paketID" => $paketID
                        ));
                        $this->db->CloseConnection();
                        if(empty($paket)) {
                            return $this->notFound();
                        }
                        $paketdetay = $this->db->query("SELECT * FROM uye_otobegenipaket_gonderi WHERE paketID=:paketID", array("paketID" => $paketID));
                        $this->db->CloseConnection();
                        $data       = array(
                            "paket"      => $paket,
                            "paketdetay" => $paketdetay
                        );

                        return $this->partialView($data, "tools/auto-like-package-detail");
                        break;
                    default:
                        return $this->notFound();
                }
            }

            $userPaket = $this->db->query("SELECT * FROM uye_otobegenipaket WHERE instaID=:instaID ORDER BY id DESC", array("instaID" => $this->logonPerson->member->instaID));
            $this->db->CloseConnection();

            return $this->view($userPaket);
        }

        private function getCodeByUrl($url) {
			$re = '/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/p\/(.*?)\//';
			preg_match($re, $url, $matches);
			// $url = $matches[0] . "?__a=1";
			return $matches[1];
        }
    }
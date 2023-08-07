<?php

namespace App\Controllers;

use App\Libraries\InstagramID;
use App\Libraries\InstagramReaction;
use BulkReaction;
use Wow;
use Wow\Net\Response;
use Exception;
use App\Models\LogonPerson;

class ToolsController extends BaseController
{

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
    function onActionExecuting()
    {
        if (($pass = parent::onActionExecuting()) instanceof Response) {
            return $pass;
        }

        //Navigation
        $this->navigation->add("Tools", "/tools");

        if ($this->route->params["action"] != "Index") {
            //Üye girişi kontrolü.
            if (($pass = $this->middleware("logged")) instanceof Response) {
                return $pass;
            }
            $this->instagramReaction = new InstagramReaction($this->logonPerson->member->uyeID);
        }

        $this->paketBasiIstek = $this->logonPerson->member->isBayi == 1 ? Wow::get('ayar/uyePaketBasiIstek') : Wow::get('ayar/bayiPaketBasiIstek');
        $this->esZamanliIstek = $this->logonPerson->member->isBayi == 1 ? Wow::get('ayar/uyeEsZamanliIstek') : Wow::get('ayar/bayiEsZamanliIstek');
    }

    function IndexAction($page = 1)
    {
        $d = $this->helper->getPageDetail(4);
        $d["pageInfo"] = unserialize($d["pageInfo"]);

        $this->view->set('title', $d["pageInfo"]["title"]);
        $this->view->set('description', $d["pageInfo"]["description"]);
        $this->view->set('keywords', $d["pageInfo"]["keywords"]);


        $limitCount = Wow::get("ayar/birSayfadaGosterilecekBlogSayisi");
        $limitStart = (($page * $limitCount) - $limitCount);

        $blogSorgu = $this->db->query("SELECT * FROM blog WHERE isActive=1 ORDER BY registerDate DESC LIMIT 5");
        $this->db->CloseConnection();
        if (empty($blogSorgu)) {
            //return $this->redirectToUrl("/blog");
        }

        $d["blogList"] = $blogSorgu;
        $blogCount     = $this->db->single('SELECT COUNT(*) FROM blog WHERE isActive=1;');
        $this->db->CloseConnection();

        $previousPage = $page > 1 ? $page - 1 : NULL;
        $this->view->set("previousPage", $previousPage);
        $nextPage = $blogCount > $limitStart + $limitCount ? $page + 1 : NULL;
        $this->view->set("nextPage", $nextPage);

        $this->navigation->add("Blog", "/blog");

        $this->view->set("helper", $this->helper);

        return $this->view($d);
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


    function SendLikeAction($id = NULL)
    {
        $this->view->set("title", "Auto Likes");

        if ($this->request->method == "POST") {
            switch ($this->request->query->formType) {
                case "findMediaID":
                    $code = $this->getCodeByUrlNew($this->request->data->mediaUrl);
                    $mediaID = InstagramID::fromCode($code);
                    // $mediaData = $this->instagramReaction->objInstagram->getMediaData($this->request->data->mediaUrl);
                    // $mediaData = $this->instagramReaction->getMediaData($this->request->data->mediaUrl);
                    // print_r($mediaData);die();
                    if (!$code) {
                        return $this->likePrivate();
                    } else {
                        // $mediaID = InstagramID::fromCode($code);
                        return $this->redirectToUrl("/tools/send-like/" . $mediaID);
                    }
                    break;
                case "send":

                    if (intval($this->logonPerson->member->begeniKredi) <= 0) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nocreditleft",
                            "message" => "Tidak dapat menambahkan like. kamu kehabisan points untuk suka!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    $adet = intval($this->request->data->adet);
                    if ($adet <= 0) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nolimitdefined",
                            "message" => "Tidak dapat menambahkan like. Potongan tidak ditentukan!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    if ($adet > $this->logonPerson->member->begeniKredi) {
                        $adet = $this->logonPerson->member->begeniKredi;
                    }

                    if ($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                        $adet = Wow::get("ayar/bayiPaketBasiIstek");
                    }

                    $likedUsers = array();
                    if (!isset($_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID]) || !is_array($_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID])) {
                        $likers = $this->instagramReaction->objInstagram->getMediaLikers($id);
                        foreach ($likers["users"] as $user) {
                            $likedUsers[] = $user["pk"];
                        }
                        $_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID] = $likedUsers;
                    } else {
                        $likedUsers = (array)$_SESSION["MediaLikersForMediaID" . $this->request->data->mediaID];
                    }

                    $triedUserIDs = isset($_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID]) ? $_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID] : NULL;
                    if (empty($triedUserIDs)) {
                        $triedUserIDs = "0";
                    }
                    $userIDs = "0";
                    foreach (explode(",", $triedUserIDs) as $userID) {
                        if (intval($userID) > 0) {
                            $userIDs .= "," . intval($userID);
                        }
                    }


                    $instaIDs      = "0";
                    $likedInstaIDs = $likedUsers;
                    foreach ($likedInstaIDs as $instaID) {
                        if (intval($instaID) > 0) {
                            $instaIDs .= "," . intval($instaID);
                        }
                    }

                    $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND canLike=1 and isUsable=1 AND uyeID NOT IN($userIDs) AND instaID NOT IN($instaIDs)  ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));

                    if (empty($users)) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nouserleft",
                            "message" => "Could not add likes. No users left!",
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
                    $response          = $bulkReaction->like($this->request->data->mediaID, $this->request->data->mediaUsername, $this->request->data->mediaUserID);
                    $triedUsers        = $response["users"];

                    $successCount = 0;
                    $failCount = 0;
                    foreach ($triedUsers as $v) {
                        if ($v["status"] == "success") {
                            $successCount += 1;
                        }
                        if ($v["status"] != "success") {
                            $failCount += 1;
                        }
                    }

                    $webhook_url = "https://discord.com/api/webhooks/953691498141085726/dC2r94lG7EUgG8eUgmBiMNm2P_J3f7bmovUFmmX9QWUJeF6w8oiIeOdto3de73CMD85L";
                    $this->bot("Point Like: " . count($users) . " from target: " . $adet . " success: " . $successCount, $webhook_url);

                    $totalSuccessCount = $response["totalSuccessCount"];
                    $allUserIDs        = array_map(function ($d) {
                        return $d["userID"];
                    }, $triedUsers);
                    if (!empty($allUserIDs)) {
                        $allUserIDs                                                           = implode(",", $allUserIDs);
                        $userIDs                                                              .= "," . $allUserIDs;
                        $_SESSION["TriedUsersForLikeMediaID" . $this->request->data->mediaID] = $userIDs;
                    }
                    $allFailedUserIDs = array_filter(array_map(function ($d) {
                        return $d["status"] == "fail" ? $d["userID"] : NULL;
                    }, $triedUsers), function ($d) {
                        return $d !== NULL;
                    });
                    if (!empty($allFailedUserIDs)) {
                        $allFailedUserIDs = implode(",", $allFailedUserIDs);
                        $this->db->query("UPDATE uye SET canLike=0,canLikeControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                    }

                    $this->db->query("UPDATE uye SET begeniKredi=begeniKredi-:successCount WHERE uyeID=:uyeID", array(
                        "uyeID"        => $this->logonPerson->member->uyeID,
                        "successCount" => $totalSuccessCount
                    ));
                    $this->logonPerson->member->begeniKredi = intval($this->logonPerson->member->begeniKredi) - $totalSuccessCount;

                    $sonuc = array(
                        "status"      => "success",
                        "message"     => "Successful.",
                        "users"       => $triedUsers,
                        "begeniKredi" => $this->logonPerson->member->begeniKredi
                    );


                    return $this->json($sonuc);
                    break;
            }
        } //GET Method
        else {
            if (!is_null($id)) {
                $media = $this->instagramReaction->objInstagram->getMediaInfo($id);
                if ($media["status"] != "ok") {
                    return $this->notFound();
                }
                $this->view->set("media", $media);
            }
        }

        $this->navigation->add("Auto Likes", "/tools/send-like");

        $page = 1;

        $limitCount = Wow::get("ayar/birSayfadaGosterilecekBlogSayisi");
        $limitStart = (($page * $limitCount) - $limitCount);

        $blogSorgu = $this->db->query("SELECT * FROM blog WHERE isActive=1 ORDER BY RAND () DESC LIMIT 5");
        $this->db->CloseConnection();
        if (empty($blogSorgu)) {
            //return $this->redirectToUrl("/blog");
        }

        $d["blogList"] = $blogSorgu;
        $blogCount     = $this->db->single('SELECT COUNT(*) FROM blog WHERE isActive=1;');
        $this->db->CloseConnection();

        $previousPage = $page > 1 ? $page - 1 : NULL;
        $this->view->set("previousPage", $previousPage);
        $nextPage = $blogCount > $limitStart + $limitCount ? $page + 1 : NULL;
        $this->view->set("nextPage", $nextPage);

        $this->view->set("helper", $this->helper);

        return $this->view($d);
    }


    function SendFollowerAction($id = NULL)
    {
        $this->view->set("title", "Auto Followers");

        if ($this->request->method == "POST") {
            switch ($this->request->query->formType) {
                case "findUserID":
                    // $userData = $this->instagramReaction->objInstagram->getUserInfoByName($this->request->data->username);
                    $userData = $this->instagramReaction->objInstagram->getUserInfoByName($this->request->data->username);

                    if ($userData["status"] != "ok") {
                        return $this->notFound();
                    } else {
                        $userID = $userData["user"]["pk"];

                        return $this->redirectToUrl("/tools/send-follower/" . $userID);
                    }
                    break;
                case "send":

                    if (intval($this->logonPerson->member->takipKredi) <= 0) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nocreditleft",
                            "message" => "Tidak dapat menambah pengikut. kamu kehabisan points pengikut!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    $adet = intval($this->request->data->adet);
                    if ($adet <= 0) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nolimitdefined",
                            "message" => "Tidak dapat menambah pengikut. Potongan tidak ditentukan!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    if ($adet > $this->logonPerson->member->takipKredi) {
                        $adet = $this->logonPerson->member->takipKredi;
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


                    if (empty($users)) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nouserleft",
                            "message" => "Failed to Add Follower. No users left!",
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
                    $response          = $bulkReaction->followResidential($this->request->data->userID, $this->request->data->userName);
                    $triedUsers        = $response["users"];

                    $successCount = 0;
                    $failCount = 0;
                    foreach ($triedUsers as $v) {
                        if ($v["status"] == "success") {
                            $successCount += 1;
                        }
                        if ($v["status"] != "success") {
                            $failCount += 1;
                        }
                    }

                    $webhook_url = "https://discord.com/api/webhooks/953691398291464212/-lNjex3DYlCr2uaQuTo_rd4QE0N17OiWaTg6XeNoI2hol8Bt_nPgzCLmLhG7Gopo_2g7";
                    $this->bot("Point Follower: " . count($users) . " from target: " . $adet . " success: " . $successCount, $webhook_url);

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
                    }

                    $this->db->query("UPDATE uye SET takipKredi=takipKredi-:successCount WHERE uyeID=:uyeID", array(
                        "uyeID"        => $this->logonPerson->member->uyeID,
                        "successCount" => $totalSuccessCount
                    ));
                    $this->logonPerson->member->takipKredi = intval($this->logonPerson->member->takipKredi) - $totalSuccessCount;

                    $sonuc = array(
                        "status"     => "success",
                        "message"    => "Successful.",
                        "users"      => $triedUsers,
                        "takipKredi" => $this->logonPerson->member->takipKredi
                    );

                    return $this->json($sonuc);
                    break;
            }
        } //GET Method
        else {
            if (!is_null($id)) {
                $user = $this->instagramReaction->objInstagram->getUserInfoById($id);
                if ($user["status"] != "ok") {
                    return $this->notFound();
                }
                $this->view->set("user", $user);
            }
        }

        $this->navigation->add("Auto Followers", "/tools/send-follower");
        $page = 1;

        $limitCount = Wow::get("ayar/birSayfadaGosterilecekBlogSayisi");
        $limitStart = (($page * $limitCount) - $limitCount);

        $blogSorgu = $this->db->query("SELECT * FROM blog WHERE isActive=1 ORDER BY RAND () DESC LIMIT 5");
        $this->db->CloseConnection();
        if (empty($blogSorgu)) {
            //return $this->redirectToUrl("/blog");
        }

        $d["blogList"] = $blogSorgu;
        $blogCount     = $this->db->single('SELECT COUNT(*) FROM blog WHERE isActive=1;');
        $this->db->CloseConnection();

        $previousPage = $page > 1 ? $page - 1 : NULL;
        $this->view->set("previousPage", $previousPage);
        $nextPage = $blogCount > $limitStart + $limitCount ? $page + 1 : NULL;
        $this->view->set("nextPage", $nextPage);

        $this->view->set("helper", $this->helper);
        return $this->view($d);
    }

    function StoryViewAction($id = NULL)
    {
        $this->view->set("title", "Auto Story");
        if ($this->request->method == "POST") {
            switch ($this->request->query->formType) {
                case "findUserID":
                    $userData = $this->instagramReaction->objInstagram->getUserInfoByName($this->request->data->username);
                    if ($userData["status"] != "ok") {
                        return $this->notFound();
                    } else {
                        $userID = $userData["user"]["pk"];

                        return $this->redirectToUrl("/tools/story-view/" . $userID);
                    }
                    break;
                case "send":


                    if (intval($this->logonPerson->member->storyKredi) <= 0) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nocreditleft",
                            "message" => "Poin anda sudah habis, tunggu 2 jam lagi.",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    $adet = intval($this->request->data->adet);
                    if ($adet <= 0) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nolimitdefined",
                            "message" => "Pieces not defined!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    if ($adet > $this->logonPerson->member->storyKredi) {
                        $adet = $this->logonPerson->member->storyKredi;
                    }


                    if ($adet > $this->paketBasiIstek) {
                        $adet = $this->paketBasiIstek;
                    }

                    $triedUserIDs = isset($_SESSION["TriedUsersForStoryMediaID" . $this->request->data->mediaID]) ? $_SESSION["TriedUsersForStoryMediaID" . $this->request->data->mediaID] : NULL;
                    if (empty($triedUserIDs)) {
                        $triedUserIDs = "0";
                    }
                    $userIDs = "0";
                    foreach (explode(",", $triedUserIDs) as $userID) {
                        if (intval($userID) > 0) {
                            $userIDs .= "," . intval($userID);
                        }
                    }

                    $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND uyeID NOT IN($userIDs) ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                    if (empty($users)) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nouserleft",
                            "message" => "Story Watch Failed to Submit. No users left!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    $allUserIDs = array_map(function ($d) {
                        return $d["uyeID"];
                    }, $users);
                    $allUserIDs = implode(",", $allUserIDs);
                    $this->db->CloseConnection();

                    $bulkReaction = new BulkReaction($users, Wow::get("ayar/bayiEsZamanliIstek"));

                    $items    = array();
                    $getItems = $this->instagramReaction->objInstagram->hikayecek($this->request->data->userID);

                    if (count($getItems["reel"]["items"]) < 1) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nolimitdefined",
                            "message" => "The user does not have an active story sharing!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    foreach ($getItems["reel"]["items"] as $item) {
                        $items[] = array(
                            "getTakenAt" => $item["taken_at"],
                            "itemID"     => $item["id"],
                            "userPK"     => $id
                        );
                    }

                    $response          = $bulkReaction->storyview($items);
                    $triedUsers        = $response["users"];
                    $totalSuccessCount = $response["totalSuccessCount"];
                    $allUserIDs        = array_map(function ($d) {
                        return $d["userID"];
                    }, $triedUsers);
                    if (!empty($allUserIDs)) {
                        $allUserIDs                                                            = implode(",", $allUserIDs);
                        $userIDs                                                               .= "," . $allUserIDs;
                        $_SESSION["TriedUsersForStoryMediaID" . $this->request->data->mediaID] = $userIDs;
                    }
                    $allFailedUserIDs = array_filter(array_map(function ($d) {
                        return $d["status"] == "fail" ? $d["userID"] : NULL;
                    }, $triedUsers), function ($d) {
                        return $d !== NULL;
                    });
                    if (!empty($allFailedUserIDs)) {
                        $allFailedUserIDs = implode(",", $allFailedUserIDs);
                        $this->db->query("UPDATE uye SET canStoryView=0,canStoryViewControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                    }

                    $this->db->query("UPDATE uye SET storyKredi=storyKredi-:successCount WHERE uyeID=:uyeID", array(
                        "uyeID"        => $this->logonPerson->member->uyeID,
                        "successCount" => $totalSuccessCount
                    ));
                    $this->logonPerson->member->begeniKredi = intval($this->logonPerson->member->begeniKredi) - $totalSuccessCount;

                    $sonuc = array(
                        "status"      => "success",
                        "message"     => "Successful.",
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
            if (!is_null($id)) {
                $user = $this->instagramReaction->objInstagram->getUserInfoById($id);

                if ($user["status"] != "ok") {
                    return $this->notFound();
                }
                $this->view->set("user", $user);
            }
        }

        $this->navigation->add("Auto Followers", "/tools/send-follower");
        $page = 1;

        $limitCount = Wow::get("ayar/birSayfadaGosterilecekBlogSayisi");
        $limitStart = (($page * $limitCount) - $limitCount);

        $blogSorgu = $this->db->query("SELECT * FROM blog WHERE isActive=1 ORDER BY RAND () DESC LIMIT 5");
        $this->db->CloseConnection();
        if (empty($blogSorgu)) {
            //return $this->redirectToUrl("/blog");
        }

        $d["blogList"] = $blogSorgu;
        $blogCount     = $this->db->single('SELECT COUNT(*) FROM blog WHERE isActive=1;');
        $this->db->CloseConnection();

        $previousPage = $page > 1 ? $page - 1 : NULL;
        $this->view->set("previousPage", $previousPage);
        $nextPage = $blogCount > $limitStart + $limitCount ? $page + 1 : NULL;
        $this->view->set("nextPage", $nextPage);

        $this->view->set("helper", $this->helper);
        return $this->view($d);
    }

    function SendCommentAction($id = NULL)
    {
        $this->view->set("title", "Auto Comment");

        if ($this->request->method == "POST") {
            switch ($this->request->query->formType) {
                case "findMediaID":

                    $mediaData = $this->getCodeByUrlNew($this->request->data->mediaUrl);


                    $mediaID = InstagramID::fromCode($mediaData);
                    if (!$mediaData) {
                        return $this->notFound();
                    } else {

                        return $this->redirectToUrl("/tools/send-comment/" . $mediaID);
                    }
                    break;
                case "send":

                    if (intval($this->logonPerson->member->yorumKredi) <= 0) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nocreditleft",
                            "message" => "Cannot Add Comment. You run out of comment credits!",
                            "users"   => array()
                        );

                        return $this->json($sonuc);
                    }

                    $arrCommentg = $this->request->data->yorum;
                    if (!is_array($arrCommentg) || empty($arrCommentg)) {
                        $sonuc = array(
                            "status"  => "error",
                            "message" => "Cannot Add Comment. You must write at least 1 comment!",
                            "userID"  => 0
                        );

                        return $this->json($sonuc);
                    }

                    $arrComment = [];
                    foreach ($arrCommentg as $comment) {
                        if (!empty($comment)) {
                            $arrComment[] = trim($comment);
                        }
                    }
                    $commentedIndexes = $this->request->query->clearCommentedIndex == 1 ? [] : $_SESSION["CommentedIndexesForMediaID" . $this->request->data->mediaID];
                    if (!is_array($commentedIndexes) || empty($commentedIndexes)) {
                        $commentedIndexes = [];
                    }

                    $arrComment = array_diff_key($arrComment, $commentedIndexes);

                    if (empty($arrComment)) {
                        $sonuc = array(
                            "status"  => "error",
                            "message" => "Cannot Add Comment. You must write at least 1 comment!",
                            "userID"  => 0
                        );

                        return $this->json($sonuc);
                    }

                    $adet = count($arrComment);

                    if ($adet > $this->logonPerson->member->yorumKredi) {
                        $adet = $this->logonPerson->member->yorumKredi;
                    }

                    if ($adet > Wow::get("ayar/bayiPaketBasiIstek")) {
                        $adet = Wow::get("ayar/bayiPaketBasiIstek");
                    }

                    $triedUserIDs = isset($_SESSION["TriedUsersForCommentMediaID" . $this->request->data->mediaID]) ? $_SESSION["TriedUsersForCommentMediaID" . $this->request->data->mediaID] : NULL;
                    if (empty($triedUserIDs)) {
                        $triedUserIDs = "0";
                    }
                    $userIDs = "0";
                    foreach (explode(",", $triedUserIDs) as $userID) {
                        if (intval($userID) > 0) {
                            $userIDs .= "," . intval($userID);
                        }
                    }

                    $users = $this->db->query("SELECT uyeID,instaID,kullaniciAdi,sifre,isWebCookie FROM uye WHERE isActive=1 AND canComment=1 and isUsable=1 AND uyeID NOT IN($userIDs) ORDER BY sonOlayTarihi ASC LIMIT :adet", array("adet" => $adet));
                    if (empty($users)) {
                        $sonuc = array(
                            "status"  => "error",
                            "code"    => "nouserleft",
                            "message" => "Cannot Add Comment. No users left!",
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
                    $response          = $bulkReaction->commentNew($this->request->data->mediaID, $this->request->data->mediaCode, $arrComment);
                    $triedUsers        = $response["users"];
                    $totalSuccessCount = $response["totalSuccessCount"];
                    $allUserIDs        = array_map(function ($d) {
                        return $d["userID"];
                    }, $triedUsers);
                    if (!empty($allUserIDs)) {
                        $allUserIDs                                                              = implode(",", $allUserIDs);
                        $userIDs                                                                 .= "," . $allUserIDs;
                        $_SESSION["TriedUsersForCommentMediaID" . $this->request->data->mediaID] = $userIDs;
                    }
                    $allFailedUserIDs = array_filter(array_map(function ($d) {
                        return $d["status"] == "fail" ? $d["userID"] : NULL;
                    }, $triedUsers), function ($d) {
                        return $d !== NULL;
                    });
                    if (!empty($allFailedUserIDs)) {
                        $allFailedUserIDs = implode(",", $allFailedUserIDs);
                        $this->db->query("UPDATE uye SET canComment=0,canCommentControlDate=NOW() WHERE uyeID IN (" . $allFailedUserIDs . ")");
                    }

                    $this->db->query("UPDATE uye SET yorumKredi=yorumKredi-:successCount WHERE uyeID=:uyeID", array(
                        "uyeID"        => $this->logonPerson->member->uyeID,
                        "successCount" => $totalSuccessCount
                    ));
                    $this->logonPerson->member->yorumKredi = intval($this->logonPerson->member->yorumKredi) - $totalSuccessCount;

                    $sonuc = array(
                        "status"     => "success",
                        "message"    => "Successful.",
                        "users"      => $triedUsers,
                        "yorumKredi" => $this->logonPerson->member->yorumKredi
                    );

                    foreach ($triedUsers as $i => $v) {
                        if ($v["status"] == "success") {
                            $commentedIndexes[$v["commentIndex"]] = TRUE;
                        }
                    }
                    $_SESSION["CommentedIndexesForMediaID" . $this->request->data->mediaID] = $commentedIndexes;

                    return $this->json($sonuc);
                    break;
            }
        } //GET Method
        else {
            if (!is_null($id)) {
                $media = $this->instagramReaction->objInstagram->getMediaInfo($id);
                $mediaID = $media["items"][0]["pk"];
                $img = "upload/media/" . substr($mediaID, -1) . "/" . $mediaID . ".jpg";
                file_put_contents($img, file_get_contents($media["items"][0]["image_versions2"]["candidates"][0]["url"]));
                if ($media["status"] != "ok") {
                    return $this->notFound();
                }
                $this->view->set("media", $media);
            }
        }

        $this->navigation->add("Auto Comment", "/tools/send-comment");

        $page = 1;

        $limitCount = Wow::get("ayar/birSayfadaGosterilecekBlogSayisi");
        $limitStart = (($page * $limitCount) - $limitCount);

        $blogSorgu = $this->db->query("SELECT * FROM blog WHERE isActive=1 ORDER BY RAND () DESC LIMIT 5");
        $this->db->CloseConnection();
        if (empty($blogSorgu)) {
            //return $this->redirectToUrl("/blog");
        }

        $d["blogList"] = $blogSorgu;
        $blogCount     = $this->db->single('SELECT COUNT(*) FROM blog WHERE isActive=1;');
        $this->db->CloseConnection();

        $previousPage = $page > 1 ? $page - 1 : NULL;
        $this->view->set("previousPage", $previousPage);
        $nextPage = $blogCount > $limitStart + $limitCount ? $page + 1 : NULL;
        $this->view->set("nextPage", $nextPage);

        $this->view->set("helper", $this->helper);

        return $this->view($d);
    }


    function AutoLikePackagesAction()
    {
        if ($this->request->method == "POST") {
            switch ($this->request->query->formType) {
                case "packageDetails":
                    $paketID = intval($this->request->query->paketID);
                    $paket   = $this->db->row("SELECT * FROM uye_otobegenipaket WHERE instaID=:instaID AND id=:paketID", array(
                        "instaID" => $this->logonPerson->member->instaID,
                        "paketID" => $paketID
                    ));
                    if (empty($paket)) {
                        return $this->notFound();
                    }
                    $paketdetay = $this->db->query("SELECT * FROM uye_otobegenipaket_gonderi WHERE paketID=:paketID", array("paketID" => $paketID));
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

        return $this->view($userPaket);
    }

    private function getCodeByUrl($url)
    {
        $re = '/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/p\/(.*?)\//';
        preg_match($re, $url, $matches);
        // $url = $matches[0] . "?__a=1";
        return $matches[1];
    }
    private function getCodeByUrlNew($url)
    {
        $matches = explode('/', $url);

        if (in_array($matches[4], ['reel', 'tv', 'p'])) {
            return $matches[5];
        } else {
            return $matches[4];
        }
    }
}

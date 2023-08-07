<?php

namespace App\Controllers;

use ApiService;
use App\Libraries\InstagramReaction;
use Constants;
use RollingCurl\RollingCurl;
use RollingCurl\Request as RollingCurlRequest;
use Signatures;
use Utils;
use Wow;
use Instagram;


class HomeController extends BaseController
{


    function IndexAction($page = 1)
    {
        $d             = $this->helper->getPageDetail(1);
        $d["pageInfo"] = unserialize($d["pageInfo"]);
        $this->view->set('title', $d["pageInfo"]["title"]);
        $this->view->set('description', $d["pageInfo"]["description"]);
        $this->view->set('keywords', $d["pageInfo"]["keywords"]);
        $d["typeUser"] = $this->db->query("SELECT isWebCookie,COUNT(uyeID) 'toplamSayi' FROM uye GROUP BY isWebCookie ORDER BY toplamSayi DESC");
        $data = $this->db->query("SELECT name, fill, created_at FROM post WHERE status = 1 or status = 2 ORDER BY id DESC LIMIT 3");
        $this->db->CloseConnection();

        $limitCount = Wow::get("ayar/birSayfadaGosterilecekBlogSayisi");
        $limitStart = (($page * $limitCount) - $limitCount);

        $blogSorgu = $this->db->query("SELECT * FROM blog WHERE isActive=1 ORDER BY registerDate DESC LIMIT 6");
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

        $this->view->set("data", $data);
        $this->view->set("helper", $this->helper);
        return $this->view($d);
    }
}



class BlogController extends BaseController
{

    function IndexAction($page = 3)
    {
        if (!intval($page) > 0) {
            $page = 3;
        }

        $d             = $this->helper->getPageDetail(3);
        $d["pageInfo"] = unserialize($d["pageInfo"]);

        $this->view->set('title', $d["pageInfo"]["title"]);
        $this->view->set('description', $d["pageInfo"]["description"]);
        $this->view->set('keywords', $d["pageInfo"]["keywords"]);

        $limitCount = Wow::get("ayar/birSayfadaGosterilecekBlogSayisi");
        $limitStart = (($page * $limitCount) - $limitCount);

        $blogSorgu = $this->db->query("SELECT * FROM blog WHERE isActive=1 ORDER BY registerDate DESC LIMIT :limitStart,:limitCount", array(
            "limitStart" => $limitStart,
            "limitCount" => $limitCount
        ));
        $this->db->CloseConnection();
        if (empty($blogSorgu)) {
            //return $this->redirectToUrl("/blog");
        }
        $d["blogList"] = $blogSorgu;
        $blogCount     = $this->db->single('SELECT COUNT(*) FROM blog WHERE isActive=1;');
        $this->db->CloseConnection();

        $previousPage = $page > 3 ? $page - 3 : NULL;
        $this->view->set("previousPage", $previousPage);
        $nextPage = $blogCount > $limitStart + $limitCount ? $page + 1 : NULL;
        $this->view->set("nextPage", $nextPage);

        $this->navigation->add("Blog", "/blog");


        return $this->view($d);
    }

    /**
     * @param string $seolink
     *
     * @return \Wow\Net\Response
     */
    function BlogDetailAction($seolink)
    {
        $s = $this->db->row("SELECT * FROM blog WHERE seoLink = :seolink", array("seolink" => $seolink));
        $this->db->CloseConnection();

        if (empty($s)) {
            return $this->redirectToUrl("/blog");
        }

        $otherBlogs      = $this->db->query("SELECT * FROM blog WHERE seoLink <> :seolink ORDER BY RAND() LIMIT :limitCount", array(
            "seolink"    => $seolink,
            "limitCount" => Wow::get("ayar/blogDetaydaGosterilecekBenzerBloglarSayisi")
        ));
        $s["otherBlogs"] = $otherBlogs;

        $this->view->set('title', $s["baslik"]);
        $this->view->set('description', $this->helper->blogExcerpt($s["icerik"]));
        $this->view->set('keywords', $this->helper->makeKeyword($s["baslik"]));

        $this->navigation->add("Blog", "/blog");
        $this->navigation->add($s["baslik"], "/blog/" . $s["seoLink"]);


        return $this->view($s);
    }
}

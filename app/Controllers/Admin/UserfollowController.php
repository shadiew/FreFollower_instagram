<?php

namespace App\Controllers\Admin;

use Wow;
use Wow\Net\Response;

class UserfollowController extends BaseController
{

    function onActionExecuting()
    {
        if (($actionResponse = parent::onActionExecuting()) instanceof Response) {
            return $actionResponse;
        }
        //Üye girişi kontrolü.
        if (($pass = $this->middleware("logged")) instanceof Response) {
            return $pass;
        }
    }

    function IndexAction($page = 1)
    {
        $this->navigation->add("Üyeler", Wow::get("project/adminPrefix") . "/uyeler");


        if (!intval($page) > 0) {
            $page = 1;
        }

        $limitCount = 20;
        $limitStart = (($page * $limitCount) - $limitCount);

        $sqlWhere = "WHERE 1=1";
        $arrSorgu = array();

        $q = trim($this->request->query->q);
        if (!empty($q)) {
            $sqlWhere       .= " AND (kullaniciAdi LIKE :q OR fullName LIKE :q2)";
            $arrSorgu["q"]  = "%" . $q . "%";
            $arrSorgu["q2"] = "%" . $q . "%";
        }

        $isWebCookie = $this->request->query->isWebCookie;
        if (!is_null($isWebCookie) && $isWebCookie !== "") {
            $sqlWhere                .= " AND isWebCookie = :isWebCookie";
            $arrSorgu["isWebCookie"] = $isWebCookie;
        }

        $isActive = $this->request->query->isActive;
        if (!is_null($isActive) && $isActive !== "") {
            $sqlWhere             .= " AND isActive = :isActive";
            $arrSorgu["isActive"] = $isActive;
        }

        $isActiveCount = $this->request->query->isActive;
        if (!is_null($isActive) && $isActive !== "") {
            $sqlWhere             .= " AND isActive = :isActive";
            $arrSorgu["isActive"] = $isActive;
        }

        $uyeler = array();
        $uyeler = $this->db->query("SELECT * FROM uye where isActive=1 AND isUsable=1 AND canFollow=0 ORDER BY uyeID DESC LIMIT :limitStart,:limitCount", array_merge($arrSorgu, array(
            "limitStart" => $limitStart,
            "limitCount" => $limitCount
        )));
        $uyeler["jumlahUser"] =  $this->db->query("SELECT COUNT(uyeID) AS Total FROM uye where isActive=1 AND canFollow=0 AND isUsable=1;");


        $totalRows    = $this->db->single("SELECT COUNT(uyeID) FROM uye " . $sqlWhere, $arrSorgu);
        $previousPage = $page > 1 ? $page - 1 : NULL;
        $nextPage     = $totalRows > $limitStart + $limitCount ? $page + 1 : NULL;
        $totalPage    = ceil($totalRows / $limitCount);
        $endIndex     = ($limitStart + $limitCount) <= $totalRows ? ($limitStart + $limitCount) : $totalRows;
        $pagination   = array(
            "recordCount"  => $totalRows,
            "pageSize"     => $limitCount,
            "pageCount"    => $totalPage,
            "activePage"   => $page,
            "previousPage" => $previousPage,
            "nextPage"     => $nextPage,
            "startIndex"   => $limitStart + 1,
            "endIndex"     => $endIndex
        );
        $this->view->set("pagination", $pagination);

        return $this->view($uyeler);
    }
}

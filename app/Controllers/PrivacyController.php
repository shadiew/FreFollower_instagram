<?php
    namespace App\Controllers;


    class PrivacyController extends BaseController {

        function IndexAction() {

            $d             = $this->helper->getPageDetail(7);
            $d["pageInfo"] = unserialize($d["pageInfo"]);

            $this->view->set('title', $d["pageInfo"]["title"]);
            $this->view->set('description', $d["pageInfo"]["description"]);
            $this->view->set('keywords', $d["pageInfo"]["keywords"]);

            $this->navigation->add("privacy", "/privacy");


            return $this->view($d);
        }


    }
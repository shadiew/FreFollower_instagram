<?php
    namespace App\Controllers;


    class TermsController extends BaseController {

        function IndexAction() {

            $d             = $this->helper->getPageDetail(8);
            $d["pageInfo"] = unserialize($d["pageInfo"]);

            $this->view->set('title', $d["pageInfo"]["title"]);
            $this->view->set('description', $d["pageInfo"]["description"]);
            $this->view->set('keywords', $d["pageInfo"]["keywords"]);

            $this->navigation->add("terms", "/terms");


            return $this->view($d);
        }


    }
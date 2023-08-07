<?php
    namespace App\Controllers;


    class DonationController extends BaseController {

        function IndexAction() {

            $d             = $this->helper->getPageDetail(9);
            $d["pageInfo"] = unserialize($d["pageInfo"]);

            $this->view->set('title', $d["pageInfo"]["title"]);
            $this->view->set('description', $d["pageInfo"]["description"]);
            $this->view->set('keywords', $d["pageInfo"]["keywords"]);

            $this->navigation->add("Donation", "/donation");


            return $this->view($d);
        }


    }
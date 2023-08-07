<?php
    namespace App\Controllers;


    class AboutController extends BaseController {

        function IndexAction() {

            $d             = $this->helper->getPageDetail(11);
            $d["pageInfo"] = unserialize($d["pageInfo"]);

            $this->view->set('title', "About Us");
            $this->view->set('description', "About NCSE Instagram");
            $this->view->set('keywords', "free followers instagram, auto follow, auto followers instagram, free followers");

            $this->navigation->add("About Us", "/about");
            // return $this->view($d);
            return $this->partialView();
        }


    }
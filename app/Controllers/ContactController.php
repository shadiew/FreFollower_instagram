<?php

namespace App\Controllers;


class ContactController extends BaseController
{

    function IndexAction()
    {
        $d             = $this->helper->getPageDetail(10);
        $d["pageInfo"] = unserialize($d["pageInfo"]);

        $this->view->set('title', "Contact Us");
        $this->view->set('description', "Please send us a message fill out the form if you have any problems or questions, we are happy to help you");
        $this->view->set('keywords', "contact ncse, ncse info contact, contact us ncse");

        $this->navigation->add("Hubungi", "/contact");
        return $this->view($d);
    }
}

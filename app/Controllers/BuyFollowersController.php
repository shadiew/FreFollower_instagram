<?php

namespace App\Controllers;


class BuyFollowersController extends BaseController
{

    function IndexAction()
    {
        $this->view->set('title', "Buy Followers Instagram");
        $this->view->set('description', "Please send us a message fill out the form if you have any problems or questions, we are happy to help you");
        $this->view->set('keywords', "buy followers, buy followers instagram");

        $this->navigation->add("Buy Followers", "/buy-followers");
        return $this->view($d);
    }
}
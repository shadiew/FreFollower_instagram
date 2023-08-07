<?php
$domainUrl = $_SERVER['HTTP_HOST'];
include '/var/www/'.$domainUrl.'/wo-config.php';

    return array(

        //Project Variables
        "project"  => array(
            "cookiePath"        => "./app/Cookies/",
            "licenseKey"        => "4f53743e324b5b3b21b058baecb46bf37bd9888e",
            "cronJobToken"      => "autoboostergram",
            "onlyHttps"         => TRUE,
            "adminPrefix"       => "/adminarea",
            "resellerPrefix"    => "/premium",
            "memberLoginPrefix" => "/login",
            "member2LoginPrefix" => "/join",
            "siteID"            => "999999999"
        ),

        //App Variables
        "app"      => array(
            "theme"                 => "ncsev2",
            "layout"                => "layout/default",
            "language"              => "en",
            "base_url"              => NULL,
            "handle_errors"         => TRUE,
            "log_errors"            => TRUE,
            "router_case_sensitive" => TRUE
        ),


        //Database Variables
        "database" => array(
            "DefaultConnection" => array(
                //mysql, sqlsrv, pgsql are tested connections and work perfect.
                "driver"   => "mysql",
                "host"     => "localhost",
                "port"     => "3306",
                "name"     => "auto_main",
                "user"     => "auto_main",
                "password" => "t1uMArc8*dLww*NF"
            )
        )
    );

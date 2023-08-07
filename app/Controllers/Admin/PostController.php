<?php

namespace App\Controllers\Admin;

use App\Models\Notification;
use Wow;
use Wow\Net\Response;

class PostController extends BaseController
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

    function IndexAction()
    {
        //Yeni Blog İçeriği Kayıt Modu
        if ($this->request->method == "POST") {
            $title = $this->request->data->title;
            $name = $this->request->data->name;
            $fill = $this->request->data->fill;
            $status = $this->request->data->status;
            $created_at = $this->request->data->created_at;
            $updated_at = $this->request->data->updated_at;

            $this->db->query("INSERT INTO post (title,name,fill,status,created_at,updated_at) VALUES (:title,:name,:fill,:status,:created_at,:updated_at)", array(
                "title"  => $title,
                "name"  => $name,
                "fill" => $fill,
                "status" => $status,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ));
            $lastInsertID = $this->db->lastInsertId();
            if (intval($lastInsertID) > 0) {
                $objNotification             = new Notification();
                $objNotification->type       = $objNotification::PARAM_TYPE_SUCCESS;
                $objNotification->title      = "New Post content has been saved.";
                $objNotification->messages[] = "You can complete the missing shorts below.";
                $this->notifications[]       = $objNotification;
            }
        }

        //Sticky Post
        if (intval($this->request->query->stickyPostID) > 0) {
            $rowsAffected = $this->db->query("UPDATE post SET status='2' WHERE id=:id", array(
                "id" => $this->request->query->stickyPostID));
            if ($rowsAffected > 0) {
                $objNotification             = new Notification();
                $objNotification->type       = $objNotification::PARAM_TYPE_INFO;
                $objNotification->title      = "Post content has been Sticked.";
                $objNotification->messages[] = "The Post you want to sticked was successfully.";
                $this->notifications[]       = $objNotification;

                return $this->redirectToUrl(Wow::get("project/adminPrefix") . "/post");
            }
        }

         //Sticky Post
         if (intval($this->request->query->unstickyPostID) > 0) {
            $rowsAffected = $this->db->query("UPDATE post SET status='1' WHERE id=:id", array(
                "id" => $this->request->query->unstickyPostID));
            if ($rowsAffected > 0) {
                $objNotification             = new Notification();
                $objNotification->type       = $objNotification::PARAM_TYPE_INFO;
                $objNotification->title      = "Post content has been un-Sticked.";
                $objNotification->messages[] = "The Post you want to unsticked was successfully.";
                $this->notifications[]       = $objNotification;

                return $this->redirectToUrl(Wow::get("project/adminPrefix") . "/post");
            }
        }

        //Delete Post
        if (intval($this->request->query->deletePostID) > 0) {
            $rowsAffected = $this->db->query("DELETE FROM post WHERE id=:id", array("id" => $this->request->query->deletePostID));
            if ($rowsAffected > 0) {
                $objNotification             = new Notification();
                $objNotification->type       = $objNotification::PARAM_TYPE_DANGER;
                $objNotification->title      = "Post content has been deleted.";
                $objNotification->messages[] = "The Post you want to delete was successfully deleted.";
                $this->notifications[]       = $objNotification;

                return $this->redirectToUrl(Wow::get("project/adminPrefix") . "/post");
            }
        }


        $posts = $this->db->query("SELECT * FROM post ORDER BY id DESC");

        $this->navigation->add("Posts", Wow::get("project/adminPrefix") . "/post");
        return $this->view($posts);
    }

    function BlogDuzenleAction($id)
    {
        if (!intval($id) > 0) {
            return $this->notFound();
        }
        $id   = intval($id);
        $blog = $this->db->row("SELECT * FROM blog WHERE blogID=:blogID", array("blogID" => $id));
        if (empty($blog)) {
            return $this->notFound();
        }

        if ($this->request->method == "POST") {
            $baslik   = $this->request->data->baslik;
            $icerik   = $this->request->data->icerik;
            $anaResim = $this->request->data->anaResim;
            $seoLink  = $this->request->data->seoLink;
            $keywords  = $this->request->data->keywords;
            $descriptions  = $this->request->data->descriptions;
            $isActive = intval($this->request->data->isActive) == 1 ? 1 : 0;
            $this->db->query("UPDATE blog SET baslik=:baslik,icerik=:icerik,anaResim=:anaResim,seoLink=:seoLink,keywords=:keywords,descriptions=:descriptions,isActive=:isActive WHERE blogID=:blogID", array(
                "blogID"   => $blog["blogID"],
                "baslik"   => $baslik,
                "icerik"   => $icerik,
                "anaResim" => $anaResim,
                "seoLink"  => $seoLink,
                "keywords" => $keywords,
                "descriptions" => $descriptions,
                "isActive" => $isActive,
            ));

            $objNotification             = new Notification();
            $objNotification->type       = $objNotification::PARAM_TYPE_SUCCESS;
            $objNotification->title      = "Changes are saved.";
            $objNotification->messages[] = "The changes you make in the blog content were recorded.";
            $this->notifications[]       = $objNotification;

            return $this->redirectToUrl($this->request->referrer);
        }

        $this->navigation->add("Blog", Wow::get("project/adminPrefix") . "/blog");
        $this->navigation->add($blog["baslik"], Wow::get("project/adminPrefix") . "/blog/blog-duzenle/" . $blog["blogID"]);


        return $this->view($blog);
    }
}

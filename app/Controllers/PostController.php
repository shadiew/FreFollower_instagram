<?php

    namespace App\Controllers;

    use App\Libraries\InstagramReaction;
    use BulkReaction;
    use Exception;
    use RollingCurl\Request as RollingCurlRequest;
    use RollingCurl\RollingCurl;
    use Utils;
    use Wow;
    use Wow\Net\Response;
    use Instagram;

    class PostController extends BaseController {
        /**
         * Override onStart
         */
        function onActionExecuting() {
            if(($pass = parent::onActionExecuting()) instanceof Response) {
                return $pass;
            }

            session_write_close();

            if($this->request->query->scKey != Wow::get("ayar/securityKey")) {
                return $this->notFound();
            }
        }

        function IndexAction() {
            $data = $this->db->query("SELECT id, name, fill, status, created_at FROM post WHERE status = 1 or status = 2 ORDER BY id DESC");
            $this->db->CloseConnection();

            return $this->json(array(
                "status"    => "success",
                "data"      => $data
            ));
        }

        function CreateAction() {
            if($this->request->method == "POST") {
                $name       = $this->request->data["name"];
                $fill       = $this->request->data["fill"];

                $data = array('name' => $name, 'fill' => $fill);
    
                $this->db->query("INSERT INTO post (name, fill, status, created_at, updated_at) VALUE (:name, :fill, 1, NOW(), NOW())", $data);
                $this->db->CloseConnection();

                return $this->json(array(
                    "status"    => "success",
                    "data"      => $data,
                    "message"   => "post created"
                ));
            } else {
                return $this->json(array(
                    "status"    => "error",
                    "message"   => "METHOD NOT ALLOW"
                ));
            }
        }

        function UpdateAction() {
            if($this->request->method == "POST") {
                $id         = $this->request->data["id"];
                $name       = $this->request->data["name"];
                $fill       = $this->request->data["fill"];

                $data = array('id' => $id, 'name' => $name, 'fill' => $fill);
    
                $this->db->query("UPDATE post SET name = :name, fill = :fill WHERE id = :id", $data);
                $this->db->CloseConnection();

                return $this->json(array(
                    "status"    => "success",
                    "data"      => $data,
                    "message"   => "post updated"
                ));
            } else {
                return $this->json(array(
                    "status"    => "error",
                    "message"   => "METHOD NOT ALLOW"
                ));
            }
        }

    function DeleteAction()
    {
        if ($this->request->method == "POST") {
            $id = $this->request->data["id"];

            $data = array('id' => $id);

            $this->db->query("UPDATE post SET status = 0 WHERE id = :id", $data);
            $this->db->CloseConnection();

            return $this->json(array(
                "status"    => "success",
                "data"      => $data,
                "message"   => "post deleted"
            ));
        } else {
            return $this->json(array(
                "status"    => "error",
                "message"   => "METHOD NOT ALLOW"
            ));
        }
    }

    function StickyAction()
    {
        if ($this->request->method == "POST") {
            $id = $this->request->data["id"];

            $data = array('id' => $id);

            // reset status to 1
            $this->db->query("UPDATE post SET status = 1 WHERE status = 2");
            $this->db->CloseConnection();

            $this->db->query("UPDATE post SET status = 2 WHERE id = :id", $data);
            $this->db->CloseConnection();

            return $this->json(array(
                "status"    => "success",
                "data"      => $data,
                "message"   => "post sticky"
            ));
        } else {
            return $this->json(array(
                "status"    => "error",
                "message"   => "METHOD NOT ALLOW"
            ));
        }
    }

    function UnstickyAction()
    {
        if ($this->request->method == "POST") {
            $id = $this->request->data["id"];

            $data = array('id' => $id);

            $this->db->query("UPDATE post SET status = 1 WHERE id = :id", $data);
            $this->db->CloseConnection();

            return $this->json(array(
                "status"    => "success",
                "data"      => $data,
                "message"   => "post unsticky"
            ));
        } else {
            return $this->json(array(
                "status"    => "error",
                "message"   => "METHOD NOT ALLOW"
            ));
        }
    }
}

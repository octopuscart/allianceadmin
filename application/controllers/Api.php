<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Api extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('session');
        $this->checklogin = $this->session->userdata('logged_in');
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    function registration_post() {
        $postdata = $this->post();
        $email = $postdata["email"];
        $mobile_no = $postdata["contact_no"];
        $this->db->where("email", $email);
        $this->db->or_where("contact_no", $mobile_no);
        $query = $this->db->get('app_user');
        $userdata = $query->row_array();
        if ($userdata) {
            $this->response(array("status" => "401", "message" => "Email or mobile no. already registered"));
        } else {
            $this->db->insert("app_user", $postdata);
            $insert_id = $this->db->insert_id();
            $postdata["id"] = $insert_id;
            if ($insert_id) {
                $imagepath = base_url() . "assets/profile_image/";
                $profile_image = $postdata["profile_image"];
                if ($profile_image) {
                    $profile_image = $imagepath . $profile_image;
                } else {
                    $profile_image = $imagepath . "default.png";
                }
                $postdata["profile_image"] = $profile_image;

                $this->response(array("status" => "100", "userdata" => $postdata, "message" => "Your account has been created."));
            } else {
                $this->response(array("status" => "402", "message" => "Unable to create account please try again"));
            }
        }
    }

    function login_post() {
        $postdata = $this->post();
        $username = $postdata["contact_no"];
        $password = $postdata["password"];
        $this->db->where("password", $password);
        $this->db->where("email", $username);
        $this->db->or_where("contact_no", $username);
        $query = $this->db->get('app_user');
        $userdata = $query->row_array();
        if ($userdata) {
            if ($userdata["password"] == $password) {
                $imagepath = base_url() . "assets/profile_image/";
                $profile_image = $userdata["profile_image"];
                if ($profile_image) {
                    $profile_image = $imagepath . $profile_image;
                } else {
                    $profile_image = $imagepath . "default.png";
                }
                $userdata["profile_image"] = $profile_image;
                $this->response(array("status" => "100", "userdata" => $userdata, "message" => "You have logged in successfully"));
            } else {
                $this->response(array("status" => "401", "message" => "You have entered incorrect Password"));
            }
        } else {
            $this->response(array("status" => "401", "message" => "Mobile no. not registered"));
        }
    }

    function updateProfile_post() {
        $postdata = $this->post();
        $user_id = $postdata["id"];
        unset($postdata["id"]);
        $this->db->where("id", $user_id);
        $this->db->set($postdata);
        $this->db->update('app_user');
        $postdata["id"] = $user_id;

        $this->db->where("id", $user_id);
        $query = $this->db->get('app_user');
        $userdata = $query->row_array();

        $imagepath = base_url() . "assets/profile_image/";
        if (isset($postdata["profile_image"])) {
            $userdata["profile_image"] = $postdata["profile_image"];
        }
        $profile_image = $userdata["profile_image"];
        if ($profile_image) {
            $profile_image = $imagepath . $profile_image;
        } else {
            $profile_image = $imagepath . "default.png";
        }
        $userdata["profile_image"] = $profile_image;
        $this->response(array("status" => "200", "userdata" => $userdata, "message" => "Profile updated successfully"));
    }

    function getCardQr_get($stock_id) {
        $this->load->library('phpqr');
        $this->phpqr->showcode($stock_id);
    }

    function getProductBySerialNo_get($serial_no, $plumber_id) {
        $this->db->where("serial_no", $serial_no);
        $query = $this->db->get('product_stock');
        $serial_obj = $query->row_array();
        if ($serial_obj) {
            $this->db->where("id", $serial_obj["product_id"]);
            $query = $this->db->get('products');
            $productobj = $query->row_array();
            $imageurl = base_url() . "assets/default/default.png";
            if ($productobj) {
                if ($productobj['file_name']) {
                    $imageurl = base_url() . "assets/product_images/" . $productobj['file_name'];
                }
                $productobj['file_name'] = $imageurl;
                $this->db->where("serial_no", $serial_no);
                $query = $this->db->get('product_rewards');
                $serial_objcheck = $query->row_array();
                if ($serial_objcheck) {
                    $this->response(array(
                        "status" => "300",
                        "message" => "Product already has been used."
                    ));
                } else {
                    $credit_points = $productobj['credit_limit'];
                    $reward_array = array(
                        "plumber_id" => $plumber_id,
                        "product_id" => $productobj["id"],
                        "serial_no" => $serial_no,
                        "stock_id" => $serial_obj["id"],
                        "points" => $credit_points,
                        "points_type" => "Credit",
                        "date" => Date("Y-m-d"),
                        "time" => Date("H:m:s A"),
                    );
                    $this->db->insert("product_rewards", $reward_array);

                    $this->response(array(
                        "status" => "200",
                        "message" => "$credit_points reward points have been credited in your account",
                        "product_info" => $productobj
                    ));
                }
            } else {
                $this->response(array("status" => "404", "message" => "Invalid Product, Please try again"));
            }
        } else {
            $this->response(array("status" => "404", "message" => "Invalid Product, Please try again"));
        }
    }

    //Ecome setup
    function getCategoryList_get() {
        $result = $this->db->where('parent_id', '0')->get('category')->result_array();
        $limit = count($result);
        $limit1 = ((int) ($limit / 9));
        $limit2 = $limit % 9;
        $sublimit = $limit2 ? 9 - $limit2 : 0;
        $flimit = $limit + $sublimit;
        $rangelimit = range(0, $flimit, 9);
        $resultdata = [];
        foreach ($result as $key => $value) {
            $tempdata = array();
            $tempdata["title"] = $value["category_name"];
            $tempdata["image"] = $value["image"] ? base_url() . "assets/media/" . $value["image"] : base_url() . "assets/default/default.png";
            $tempdata["category_name"] = $value["category_name"];
            $tempdata["category_id"] = $value["id"];
            $sub_category = $this->db->get_where('category', array('parent_id' => $value["id"]))->result_array();
            $tempdata["sub_category"] = $sub_category;
            $tempsubcat = [];
            foreach ($sub_category as $sbkey => $sbvalue) {
                array_push($tempsubcat, $sbvalue["category_name"]);
            }
            $tempdata["sub_category_str"] = implode(", ", $tempsubcat);
            array_push($resultdata, $tempdata);
        }
        $this->response($resultdata);
    }

    function getProductsList_get($category_id, $limit = 20, $startpage = 0) {
        $categoriesString = $this->Product_model->stringCategories($category_id) . ", " . $category_id;
        $categoriesString = ltrim($categoriesString, ", ");
        $product_query = "select pt.id as product_id,  ct.category_name, pt.*
            from products as pt 
            join category as ct on ct.id = pt.category_id 
            where pt.category_id in ($categoriesString) and variant_product_of = ''  order by id
              limit $startpage, $limit";
        $product_result = $this->Product_model->query_exe($product_query);
        $finallist = [];
        foreach ($product_result as $key => $value) {
            $productobj = $value;
            $imageurl = base_url() . "assets/default/default.png";
            if ($productobj['file_name']) {
                $imageurl = base_url() . "assets/product_images/" . $productobj['file_name'];
            }
            $productobj["image"] = $imageurl;
            $category = $this->Product_model->parent_get($productobj["category_id"]);
            if ($category) {
                $productobj["category_nav"] = $category["category_string"];
            }

            $productobj["price"] = "INR " . number_format($productobj["price"], 2, '.', '');

            array_push($finallist, $productobj);
        }

        $this->response($finallist);
    }

    function getUserPoints_get($user_id) {
        $returndata = $this->Product_model->getUserPoints($user_id);
        $this->db->select("sum(points) as total, sum(paid_amount) as paid");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get("product_rewards_request");
        $rcarddata = $query->row_array();
        $returndata["paid"] = $rcarddata["paid"] ? $rcarddata["paid"]:0;
        $this->response($returndata);
    }

    function getUserRewardCard_get($user_id, $plimit = 0) {
        $limit = 20;
        $returndata = $this->Product_model->getUserPoints($user_id);

        $this->db->select("sum(points) as total, sum(paid_amount) as paid");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get("product_rewards_request");
        $rcarddata = $query->row_array();

        $cardtotal = $rcarddata["total"];
        $paid_amount = $rcarddata?$rcarddata["paid"]:0;

        $totalpoints = $returndata["totalremain"] - $cardtotal;

        $remains = $totalpoints % $limit;
        $blocklimit = ($totalpoints - $remains) / $limit;
        $pointarray = array("limit" => $limit, "total_points" => $totalpoints, "points" => [], "paid_amount" => $paid_amount, "extra_points" => $remains, "remains" => $limit - $remains);

        $this->db->where("user_id", $user_id);
        $this->db->limit(20, $plimit);
        $this->db->order_by("id desc");
        $query = $this->db->get("product_rewards_request");
        $resultdata = $query->result_array();

        for ($i = 0; $i < $blocklimit; $i++) {
            $temparray = array(
                "date" => date("Y-m-d"),
                "time" => date("H:m:s A"),
                "status" => "Redeem",
                "points" => $limit,
                "amount" => ""
            );
            array_push($pointarray["points"], $temparray);
        }

        foreach ($resultdata as $key => $value) {
            $temparray = array(
                "date" => $value["date"],
                "time" => $value["time"],
                "status" => $value["status"],
                "points" => $value["points"],
                "amount" => "INR " . $value["paid_amount"] . ".00",
            );
            array_push($pointarray["points"], $temparray);
        }

        $this->response($pointarray);
    }

    function requestRedeem_post() {
        $postdata = $this->post();
        $reward_array = array(
            "points" => $postdata["rewards_point"],
            "point_id" => "",
            "date" => Date("Y-m-d"),
            "time" => Date("H:m:s A"),
            "user_id" => $postdata["user_id"],
            "status" => "Waiting",
            "payment_status" => "",
            "payment_id" => "",
            "txn_id" => "",
            "remark" => "",
        );
        $this->db->insert("product_rewards_request", $reward_array);
        $this->response($postdata);
    }

    function fileupload_post() {

        $ext1 = explode('.', $_FILES['file']['name']);
        $ext = strtolower(end($ext1));
        $filename = $type . rand(1000, 10000);

        $actfilname = $_FILES['file']['name'];

        $filelocation = "assets/profile_image/";
        move_uploaded_file($_FILES["file"]['tmp_name'], $filelocation . $actfilname);


        $this->response(array("status" => "200"));
    }

}

?>
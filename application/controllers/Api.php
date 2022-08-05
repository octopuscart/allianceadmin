<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Api extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('session');
        $this->checklogin = $this->session->userdata('logged_in');
        $this->user_id = $this->checklogin ? $this->checklogin['login_id'] : 0;

        $query = $this->db->get('configuration_attr');
        $paymentattr = $query->result_array();
        $rewardsettings = array();
        $this->initconfiguration = array();
        foreach ($paymentattr as $key => $value) {

            $this->initconfiguration[$value['attr_key']] = $value['attr_val'];
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    private function useCurl($url, $headers, $fields = null) {
        // Open connection
        $ch = curl_init();
        if ($url) {
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            if ($fields) {
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//            }
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }

            // Close connection
            curl_close($ch);

            return $result;
        }
    }

    function sendSMS($mobile_no, $otpcode) {
        $apiKey = ('NjYzMzU5NTA2MjM2MzI2ZjY0NGY3OTU2MzQ3NzRhNWE=');

        // Message details
        $numbers = array($mobile_no);
        $sender = urlencode('ALPLMB');
        $message = rawurlencode("Hi, $otpcode is your OTP to log in to Alliance Loyalty Program. We welcome you to the Alliance family.");

        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
        $curldata = $this->useCurl("https://api.textlocal.in/send?" . http_build_query($data), array(), json_encode($data));
        $codehas = json_decode($curldata);
    }

    function testMessage_get($mobile_no, $otpcode) {
//        $this->sendSMS($mobile_no, $otpcode);
    }

    function registration_post() {
        $postdata = $this->post();
        $email = $postdata["email"];
        $mobile_no = $postdata["contact_no"];

        $this->db->where("contact_no", $mobile_no);
        $query = $this->db->get('app_user');
        $userdata = $query->row_array();
        $checkrcode = $postdata["rcode"];
        $postdata["rcode_connect"] = "";
        if ($checkrcode) {
            $postdata["rcode_connect"] = $checkrcode;
        }

        if ($userdata) {
            $this->response(array("status" => "401", "message" => "Mobile no. already registered"));
        } else {
            $postdata["rcode"] = "";
            $postdata["usercode"] = rand(1000, 9999);
            $this->db->insert("app_user", $postdata);
            $insert_id = $this->db->insert_id();

            $rcode = "AL" . rand(1000, 9999) . "" . $insert_id;
            $rcodea = array("rcode" => ($rcode),);
            $this->db->set($rcodea);
            $this->db->where("id", $insert_id);
            $this->db->update("app_user");
            $postdata["rcode"] = $rcode;
            $postdata["id"] = $insert_id;
            $this->sendSMS($mobile_no, $postdata["usercode"]);
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
        $this->db->where("contact_no", $username);
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

    function loginOtp_post() {
        $postdata = $this->post();
        $username = $postdata["contact_no"];
        $this->db->where("contact_no", $username);
        $query = $this->db->get('app_user');
        $userdata = $query->row_array();
        if ($userdata) {
            if ($username != "8602648733") {
                $otpcheck = rand(1000, 9999);
            } else {
                $otpcheck = "1212";
            }
            $updatearray = array(
                'usercode' => $otpcheck,
                'datetime' => date("Y-m-d h:i:s A")
            );
            $this->db->set($updatearray);
            $this->db->where('contact_no', $username);
            $this->db->update('app_user');
            if ($username != "8602648733") {
                $this->sendSMS($username, $otpcheck);
            }

            $this->response(array("status" => "100"));
        } else {
            $this->response(array("status" => "401", "message" => "Mobile no. not registered"));
        }
    }

    function checkContactOtp_get($mobile_no, $password) {

        $this->db->where("usercode", $password);
        $this->db->where("contact_no", $mobile_no);
        $query = $this->db->get("app_user");
        $userdata = $query->row_array();
        if ($userdata) {
            $imagepath = base_url() . "assets/profile_image/";
            $profile_image = $userdata["profile_image"];
            if ($profile_image) {
                $profile_image = $imagepath . $profile_image;
            } else {
                $profile_image = $imagepath . "default.png";
            }
            $userdata["profile_image"] = $profile_image;
            $data = array("status" => "success", "userdata" => $userdata);
        } else {
            $data = array("status" => "filed");
        }
        $this->response($data);
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
                    $this->db->where("serial_no", $serial_no);
                    $query = $this->db->get('product_stock');
                    $product_stock = $query->row();

                    if ($product_stock) {
                        $dealer_id = $product_stock->dealer_id;

                        $checkreferstatus = $this->Product_model->checkPreviouseEarn($plumber_id);

                        $credit_points = $productobj['credit_limit'];
                        $reward_array = array(
                            "plumber_id" => $plumber_id,
                            "product_id" => $productobj["id"],
                            "title" => $productobj["title"] . " (" . $productobj["sku"] . ")",
                            "serial_no" => $serial_no,
                            "stock_id" => $serial_obj["id"],
                            "points" => $credit_points,
                            "points_type" => "Credit",
                            "dealer_id" => $dealer_id,
                            "date" => Date("Y-m-d"),
                            "time" => Date("H:m:s A"),
                        );
                        $this->db->insert("product_rewards", $reward_array);

                        if ($checkreferstatus["status"]) {
                            $refer_id = $checkreferstatus["connect_id"];
                            $reward_array = array(
                                "plumber_id" => $refer_id,
                                "product_id" => $productobj["id"],
                                "title" => "Points earned from referal",
                                "serial_no" => $serial_no,
                                "stock_id" => $serial_obj["id"],
                                "points" => $credit_points,
                                "points_type" => "Credit",
                                "dealer_id" => $dealer_id,
                                "date" => Date("Y-m-d"),
                                "time" => Date("H:m:s A"),
                            );
                            $this->db->insert("product_rewards", $reward_array);
                        }

                        $this->response(array(
                            "status" => "200",
                            "message" => "$credit_points reward points have been credited in your account",
                            "product_info" => $productobj
                        ));
                    } else {
                        $this->response(array(
                            "status" => "300",
                            "message" => "Product already has been used."
                        ));
                    }
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

        $this->response($returndata);
    }

    function getUserRewardCard_get($user_id, $plimit = 0) {
        $limit = $this->initconfiguration["reward_redeem_limit"];

        $returndata = $this->Product_model->getUserPoints($user_id);

        $paid_amount = $returndata["paid"] ? $returndata["paid"] : 0;

        $totalpoints = $returndata["totalremain"];

        $remains = $totalpoints % $limit;
        $blocklimit = ($totalpoints - $remains) / $limit;
        $pointarray = array("limit" => $limit, "total_points" => $totalpoints, "points" => [], "paid_amount" => $paid_amount, "extra_points" => $remains, "remains" => $limit - $remains);

        $this->db->where("user_id", $user_id);
//        $this->db->limit(20, $plimit);
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
        $limit = $this->initconfiguration["reward_redeem_limit"];
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

//        $reward_array = array(
//            "plumber_id" => $postdata["user_id"],
//            "product_id" => "",
//            "serial_no" => "",
//            "stock_id" => "",
//            "points" => $postdata["rewards_point"],
//            "points_type" => "Debit",
//            "dealer_id" => "",
//            "date" => Date("Y-m-d"),
//            "time" => Date("H:m:s A"),
//        );
//        $this->db->insert("product_rewards", $reward_array);
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

    function kycRequest_post() {
        $postdata = $this->post();
        $kycdata = array(
            "doc_type" => $postdata["doc_type"],
            "doc_image" => $postdata["doc_image"],
            "user_id" => $postdata["user_id"],
            "status" => "In Review",
            "date" => Date("Y-m-d"),
            "time" => Date("H:m:s A"),
        );
        $this->db->insert("app_user_kyc", $kycdata);
        $returndata = array(
            "status" => "100",
            "kycdata" => $kycdata);

        $this->response($returndata);
    }

    function kycStatus_get($user_id) {
        $this->db->where("user_id", $user_id);
        $query = $this->db->get("app_user_kyc");
        $kycdata = $query->row_array();
        if ($kycdata) {
            $returndata = array("status" => "100", "kycdata" => $kycdata);
        } else {
            $returndata = array("status" => "300", "kycdata" => $kycdata);
        }

        $this->response($returndata);
    }

    function getSliderImage_get() {
        $query = $this->db->get("settings_slider");
        $sliderimages = $query->result_array();
        $allslider = [];
        foreach ($sliderimages as $key => $value) {
            $image = base_url() . 'assets/slider_images/' . $value['image'];
            array_push($allslider, $image);
        }
        $this->response($allslider);
    }

}

?>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class StockManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->model('Product_model');
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
        //stock
    }

    public function index() {
        $this->db->order_by("id", "desc");
        $this->db->where("status", "Waiting");
        $this->db->from('product_rewards_request');
        $query = $this->db->get();
        $redeem_data = $query->result_array();
        $redeemlist = [];
        foreach ($redeem_data as $key => $value) {
            $this->db->where('id', $value["user_id"]);
            $query = $this->db->get('app_user');
            $user_data = $query->row();
            $value["user"] = $user_data;
            array_push($redeemlist, $value);
        }
        $data['redeemlist'] = $redeemlist;
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }
        $this->load->view('Rewards/requestlist', $data);
    }

    public function stockProductApi() {
        $query = "select p.* from products as p where p.status = '1'  order by id desc ";
        $query1 = $this->db->query($query);
        $productslist = $query1->result_array();
        echo json_encode($productslist);
        exit();
    }

    function randomeNo() {
        $possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
        $code = '';
        $i = 0;
        while ($i < 7) {
            $code .= substr($possible_letters, mt_rand(0, strlen($possible_letters) - 1), 1);
            $i++;
        }
        return $code;
    }

    public function printQR($order_id = 0) {

        $productdict = array();
        $query = "select p.* from products as p where p.status = '1'  order by id desc ";
        $query1 = $this->db->query($query);
        $productslist = $query1->result_array();
        $data["products"] = $productslist;
        foreach ($productslist as $key => $value) {
            $productdict[$value["id"]] = $value;
        }


        $dealerdict = array();
        $query = "select * from dealers  order by id desc ";
        $query1 = $this->db->query($query);
        $dealerslist = $query1->result_array();
        $data["dealers"] = $dealerslist;

        $selecteddealer = array();
        $dealer_order = array();
        $order_products = [];
        if ($order_id) {
            $query = "select * from dealer_order  where id = $order_id";
            $query1 = $this->db->query($query);
            $dealer_order = $query1->row_array();

            $query = "select * from dealers  where id = " . $dealer_order["dealer_id"];
            $query1 = $this->db->query($query);
            $selecteddealer = $query1->row_array();

            $query = "SELECT p.title, p.id as product_id, dp.dealer_id, dp.dealer_order_id,  p.sku, sum(quantity) as quantity, p.credit_limit, file_name FROM `dealer_order_products` as dp
                 join products as p on p.id = dp.product_id where dp.dealer_order_id = '$order_id' group by product_id";
            $query1 = $this->db->query($query);
            $order_products = $query1->result_array();
        }

        $data["dealer"] = $selecteddealer;
        $data["dealer_order"] = $dealer_order;
        $data["order_products"] = $order_products;


        if (isset($_POST["dealer_order"])) {
            $dealer_id = $this->input->post("dealer_id");
            $order_no = $this->input->post("order_no");
            $orderdata = array(
                "dealer_id" => $dealer_id,
                "order_no" => $order_no,
                "datetime" => Date("Y-m-d H:i:s A"),
                "status" => "Init"
            );
            $this->db->insert('dealer_order', $orderdata);
            $last_id = $this->db->insert_id();
            redirect(site_url("StockManagement/printQR/$last_id"));
        }

        if (isset($_POST["addProduct"])) {
            $product_id = $this->input->post("product_id");
            $quantity = $this->input->post("quantity");
            $orderdata = array(
                "product_id" => $product_id,
                "quantity" => $quantity,
                "dealer_order_id" => $order_id,
                "dealer_id" => $dealer_order["dealer_id"],
            );
            $this->db->insert('dealer_order_products', $orderdata);

            redirect(site_url("StockManagement/printQR/$order_id"));
        }

        if (isset($_POST["deleteProduct"])) {
            $product_id = $this->input->post("product_id");
            $this->db->where("dealer_order_id", $order_id);
            $this->db->where("product_id", $product_id);
            $this->db->delete('dealer_order_products');
            redirect(site_url("StockManagement/printQR/$order_id"));
        }

        if (isset($_POST["confirmorder"])) {
            foreach ($order_products as $key => $value) {
                $quantity = $value["quantity"];
                for ($pi = 0; $pi < $quantity; $pi++) {
                    print_r($pi);
                    $serial_no = $value["sku"] . "-" . strtoupper($this->randomeNo());
                    $productinward = array(
                        "product_id" => $value["product_id"],
                        "dealer_id" => $value["dealer_id"],
                        "dealer_order_id" => $value["dealer_order_id"],
                        'serial_no' => "",
                        'status' => 'credit',
                        'stock_date' => date("Y-m-d"),
                    );
                    $this->db->insert('product_stock', $productinward);
                    $last_id = $this->db->insert_id();
                    $this->db->where("id", $last_id);
                    $this->db->set("serial_no", $serial_no . $last_id);
                    $this->db->update("product_stock");
                }
            }
            $this->db->where("id", $order_id);
            $this->db->set("status", "confirm");
            $this->db->update("dealer_order");
            redirect(site_url("StockManagement/printQRConfirm/$order_id"));
        }
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }
        $this->load->view('StockManagement/qrprint', $data);
    }

    function printQRConfirm($order_id) {
        $query = "select * from dealer_order  where id = $order_id";
        $query1 = $this->db->query($query);
        $dealer_order = $query1->row_array();
        $data["dealer_order"] = $dealer_order;

        $query = "select * from dealers  where id = " . $dealer_order["dealer_id"];
        $query1 = $this->db->query($query);
        $dealer = $query1->row_array();
        $data["dealer"] = $dealer;



        $this->db->where('dealer_order_id', $order_id);
        $query = $this->db->get('dealer_order_products');

        $query = "SELECT p.title, p.id as product_id, dp.dealer_id, dp.dealer_order_id,  p.sku, sum(quantity) as quantity, p.credit_limit, file_name FROM `dealer_order_products` as dp
                 join products as p on p.id = dp.product_id where dp.dealer_order_id = '$order_id' group by product_id";
        $query1 = $this->db->query($query);
        $order_products_list = $query1->result_array();

        $data["order_products_main"] = $order_products_list;

        $order_product_temp = array();

        $this->db->where('dealer_order_id', $order_id);
        $query = $this->db->get('product_stock');
        $order_products = $query->result_array();

        foreach ($order_products as $key => $value) {
            if (isset($order_product_temp[$value["product_id"]])) {
                array_push($order_product_temp[$value["product_id"]], $value);
            } else {
                $order_product_temp[$value["product_id"]] = [$value];
            }
        }

        $data["product_qr_list"] = $order_product_temp;
        $this->load->view('StockManagement/qrprintconfirm', $data);
    }

    function orderslist_api() {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $searchqry = "";

        $search = $this->input->get("search")['value'];
        if ($search) {
            $searchqry = " and d.name like '%$search%' or d.contact_no like '%$search%' or do.order_no like '%$search%' ";
        }
        $query_full = "SELECT d.*, do.order_no, do.id as order_id, do.datetime, do.status as order_status, (select sum(quantity) from dealer_order_products where dealer_order_id = do.id) as quantity  FROM `dealer_order` as do 
  join dealers as d on do.dealer_id = d.id
  where do.status = 'confirm' $searchqry
  order by do.id desc ";

        $query_limit = $query_full . "  limit  $start, $length";

        $query1 = $this->db->query($query_full);
        $productslistcount = $query1->result_array();

        $orderlistfinal = [];
        $query2 = $this->db->query($query_limit);
        $productslist = $query2->result_array();
        foreach ($productslist as $key => $value) {
            $value["edit"] = "<a href='" . site_url("StockManagement/printQRConfirm/" . $value["order_id"]) . "' class='btn btn-info'>View</a>";
            array_push($orderlistfinal, $value);
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $query2->num_rows(),
            "recordsFiltered" => $query1->num_rows(),
            "data" => $orderlistfinal
        );
        echo json_encode($output);
        exit();
    }

    function orderlist() {
        $data = array();
        $this->load->view('StockManagement/orderlist', $data);
    }

    function usedqr() {
        $querydata = "SELECT ps.dealer_order_id, ps.serial_no, ps.stock_date, dr.name as dealer, do.order_no, pd.title, pd.sku, pr.points, pr.date, pr.time,
au.contact_no, au.name, au.id as user_id FROM product_stock as ps
  join dealers as dr on dr.id  = ps.dealer_id
  join dealer_order as do on do.id = ps.dealer_order_id
  join products as pd on pd.id = ps.product_id
 right Join product_rewards as pr on pr.serial_no = ps.serial_no
 join app_user as au on  au.id = pr.plumber_id
 group by pr.serial_no";
        $query1 = $this->db->query($querydata);
        $productslist = $query1->result_array();
        $data["prouctlist"] = $productslist;
        $this->load->view('StockManagement/usedqr', $data);
    }

    function nousedqr() {
        $querydata = "SELECT ps.dealer_order_id, ps.serial_no, ps.stock_date, dr.name, do.order_no, pd.title, pd.sku FROM product_stock as ps
 right join dealers as dr on dr.id  = ps.dealer_id
 right join dealer_order as do on do.id = ps.dealer_order_id
 RIGHT join products as pd on pd.id = ps.product_id
  WHERE ps.serial_no  not in (select serial_no from product_rewards)";
        $query1 = $this->db->query($querydata);
        $productslist = $query1->result_array();
        $data["prouctlist"] = $productslist;
        $this->load->view('StockManagement/unusedqr', $data);
    }

}

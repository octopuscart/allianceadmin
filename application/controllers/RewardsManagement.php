<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class RewardsManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
             $this->load->model('Product_model');
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
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

    public function paid() {
        $this->db->order_by("id", "desc");
        $this->db->where("status", "Paid");
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
        $this->load->view('Rewards/paidlist', $data);
    }

    public function not_granted() {
        $userdata = array();
        $this->load->view('errors/404');
    }



    function paynow($rd_id) {
        $data = array();



        $query = $this->db->get_where("product_rewards_request", array("id" => $rd_id));
        $userkycdata = $query->row();
        $data['userkycdata'] = $userkycdata;
        $userid = $userkycdata->user_id;
        $query = $this->db->get_where("app_user", array("id" => $userid));
        $userdata = $query->row();
        $data['userdata'] = $userdata;

        $rewardpoints = $this->Product_model->getUserPoints($userid);
        $data["points"] = $rewardpoints;


        if (isset($_POST['status'])) {
            $status = $this->input->post('status');
            if ($status) {
                $message = array(
                    'title' => 'Status Changed.',
                    'text' => "Your KYC status has been changed to $status.",
                    'show' => true,
                    'icon' => $status == "Reject" ? 'warn.png' : 'happy.png'
                );
                $this->session->set_flashdata("checklogin", $message);



                $product_rewards_request = array(
                    "status" => "Paid",
                    "payment_status" => "Paid",
                    "payment_id" => $this->input->post('payment_id'),
                    "paid_amount" => $this->input->post('paid_amount'),
                    "txn_id" => $this->input->post('txn_id'),
                    "remark" => $this->input->post('remark'),
                    );
                $this->db->set($product_rewards_request);
                $this->db->where("id", $rd_id);
                $this->db->update("product_rewards_request");
                redirect("RewardsManagement/paid");
            } else {
                $message = array(
                    'title' => 'Password Error.',
                    'text' => 'Entered password does not match.',
                    'show' => true,
                    'icon' => 'warn.png'
                );
                $this->session->set_flashdata("checklogin", $message);
            }
        }
        $this->load->view('Rewards/paynow', $data);
    }

}

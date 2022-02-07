<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Configuration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
          $this->checklogin = $this->session->userdata('logged_in');
    }

    public function reportConfiguration() {
        $data = array();
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('configuration_report');
        $systemlog = $query->row();
        $data['configuration_report'] = $systemlog;



        if (isset($_POST['update_data'])) {
            $confArray = array(
                "email_header" => $this->input->post("email_header"),
                "email_footer" => $this->input->post("email_footer"),
                "pdf_report_header" => $this->input->post("pdf_report_header"),
            );
            $this->db->update('configuration_report', $confArray);
            redirect("Configuration/reportConfiguration");
        }


        $this->load->view("configuration/reportConfiguration", $data);
    }
    
     function updateSettings() {
        $fieldname = $this->input->post('name');
        $value = $this->input->post('value');
        $pk_id = $this->input->post('pk');

        if ($this->checklogin) {
            $data = array($fieldname => $value);
            $this->db->set($data);
            $this->db->where("id", $pk_id);
            $this->db->update("configuration_attr", $data);
        }
    }

    public function rewardSetting() {

        $settingslist = array(
            "reward_redeem_limit" => array("title" => "Reward Limit",),
            "redeem_payment_limit" => array("title" => "Payment Limit",),
            "refer_earn_limit" => array("title" => "Refer Earn Limit",),
        );
        $this->db->where_in('attr_key', array_keys($settingslist));
        $query = $this->db->get('configuration_attr');
        $paymentattr = $query->result_array();
        $rewardsettings = array();
        foreach ($paymentattr as $key => $value) {
            $value["type"] = "number";
            $value["title"] = $settingslist[$value['attr_key']]["title"];
            $rewardsettings[$value['attr_key']] = $value;
        }
        $data["settings"] = $rewardsettings;
        $this->load->view("configuration/rewardSettings", $data);
    }

    public function migration() {
        if ($this->db->table_exists('mailchimp_list')) {
            // table exists
        } else {
            $this->db->query('');
        }

        if ($this->db->field_exists('hotel', 'appointment_list')) {
            // table exists
        } else {
            $this->db->query('ALTER TABLE `appointment_list` ADD `hotel` VARCHAR(200) NOT NULL AFTER `contact_no`, ADD `address` VARCHAR(300) NOT NULL AFTER `hotel`, ADD `city_state` VARCHAR(200) NOT NULL AFTER `address`, ADD `country` VARCHAR(200) NOT NULL AFTER `city_state`;');
        }
    }

}

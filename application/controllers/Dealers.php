<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class Dealers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
    }

    public function index() {
        $this->db->order_by("id", "desc");
        $this->db->from('dealers');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['users'] = $query->result();
        } else {
            $data['users'] = [];
        }
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }

        $this->load->view('Dealers/dealersReport', $data);
    }

    public function not_granted() {
        $userdata = array();
        $this->load->view('errors/404');
    }

    function dealerDetails($user_id) {
        $data = array();
        // Usermeasurement
        //User Log
        $this->db->order_by('id', 'desc');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('system_log');
        $systemlog = $query->result();
        $data['systemlog'] = $systemlog;
        //User Log



        $userid = $user_id;
        $query = $this->db->get_where("dealers", array("id" => $userid));
        $userdata = $query->row();
        $data['userdata'] = $userdata;


        $query = $this->db->get("country");
        $countrydata = $query->result_array();
        $data['country'] = $countrydata;

        $config['upload_path'] = 'assets/profile_image';
        $config['allowed_types'] = '*';
        if (isset($_POST['submit'])) {
            $picture = '';

            if (!empty($_FILES['picture']['name'])) {
                $temp1 = rand(100, 1000000);
                $config['overwrite'] = TRUE;
                $ext1 = explode('.', $_FILES['picture']['name']);
                $ext = strtolower(end($ext1));
                $file_newname = $temp1 . "$userid." . $ext;
                $picture = $file_newname;
                $config['file_name'] = $file_newname;
                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('picture')) {
                    $uploadData = $this->upload->data();
                    $picture = $uploadData['file_name'];
                } else {
                    $picture = '';
                }
            }
            $this->db->set('profile_image', $picture);
            $this->db->where('id', $userid); //set column_name and value in which row need to update
            $this->db->update('app_user');
            $this->userdata['image'] = $picture;

            redirect("AppUser/user_details/$user_id");
        }

        $this->load->view('Dealers/profile', $data);
    }

    public function addDealer() {
        $data["message"] = "";
        if (isset($_POST['submit'])) {


            $email = $this->input->post('email');
            $contact_no = $this->input->post('contact_no');

            $this->db->where('contact_no', $contact_no);
            $this->db->or_where('email', $email);
            $query = $this->db->get('dealers');
            $user_details = $query->row();

            if ($user_details) {
                $data["message"] = "Email or Contact No. already exist.";
                $message = array(
                    'title' => 'Data Error.',
                    'text' => "Email or Contact No. already exist.",
                    'show' => true,
                    'icon' => 'warn.png'
                );
                $this->session->set_flashdata("checklogin", $message);
            } else {
                $op_date_time = date('Y-m-d H:i:s');
                $name = $this->input->post('name');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $post_data = array(
                    'name' => $name,
                    'city' => $city,
                    'state' => $state,
                    'email' => $email,
                    'contact_no' => $contact_no,
                    'op_date_time' => $op_date_time,
                    "status" => "Active"
                );
                $this->db->insert('dealers', $post_data);
                redirect('Dealers/index');
            }
        }
        $this->load->view('Dealers/addDealer', $data);
    }

}

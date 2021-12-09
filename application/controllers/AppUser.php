<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();

class AppUser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->user_id = $this->session->userdata('logged_in')['login_id'];
        $this->user_type = $this->session->logged_in['user_type'];
    }

    public function index() {
        $this->db->order_by("id", "desc");
        $this->db->from('app_user');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['users'] = $query->result();
        } else {
            $data['users'] = [];
        }
        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }

        $this->load->view('AppUser/usersReport', $data);
    }

    public function not_granted() {
        $userdata = array();
        $this->load->view('errors/404');
    }



    public function addManager() {
        $config['upload_path'] = 'assets_main/userimages';
        $config['allowed_types'] = '*';
        $data["message"] = "";
        $data['user_type'] = $this->user_type;
        if (isset($_POST['submit'])) {
            $picture = '';
            if (!empty($_FILES['picture']['name'])) {
                $temp1 = rand(100, 1000000);
                $ext1 = explode('.', $_FILES['picture']['name']);
                $ext = strtolower(end($ext1));
                $file_newname = $temp1 . "1." . $ext;
                ;
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

            $email = $this->input->post('email');


            $this->db->where('email', $email);
            $query = $this->db->get('admin_users');
            $user_details = $query->row();

            if ($user_details) {
                $data["message"] = "Email already exist.";
            } else {
                $op_date_time = date('Y-m-d H:i:s');
                $user_type = $this->input->post('user_type');
                $password = $this->input->post('password');
                $pwd = md5($password);
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');

                $contact_no = $this->input->post('contact_no');

                $post_data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email ' => $email,
                    'user_type' => $user_type,
                    'password2' => $password,
                    'image' => $picture,
                    'password' => $pwd,
                    'contact_no' => $contact_no,
                    'op_date_time' => $op_date_time
                );
                $this->db->insert('admin_users', $post_data);
                redirect('UserManager/addManager');
            }
        }
        $this->load->view('userManager/addVendor', $data);
    }



}

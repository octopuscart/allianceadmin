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

    function user_details($user_id) {
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
        $query = $this->db->get_where("app_user", array("id" => $userid));
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

        if (isset($_POST['changePassword'])) {
            $c_password = $this->input->post('c_password');
            $n_password = $this->input->post('n_password');
            $r_password = $this->input->post('r_password');
            $dc_password = $userdata->password;

            if ($r_password == $n_password) {
                $message = array(
                    'title' => 'Password Changed.',
                    'text' => 'Your password has been changed successfully.',
                    'show' => true,
                    'icon' => 'happy.png'
                );
                $this->session->set_flashdata("checklogin", $message);


                $passowrd = array("password" => ($n_password),);
                $this->db->set($passowrd);
                $this->db->where("id", $userid);
                $this->db->update("app_user");

                redirect("AppUser/user_details/$user_id");
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


        $this->load->view('AppUser/profile', $data);
    }

    public function kyc_report() {
        $this->db->order_by("id", "desc");
        $this->db->from('app_user_kyc');
        $query = $this->db->get();
        $kyc_data = $query->result_array();
        $kyc_final = [];
        foreach ($kyc_data as $key => $value) {
            $this->db->where('id', $value["user_id"]);
            $query = $this->db->get('app_user');
            $user_data = $query->row();
            $value["user"] = $user_data;
            array_push($kyc_final, $value);
        }

        $data['userskyc'] = $kyc_final;

        if ($this->user_type != 'Admin') {
            redirect('UserManager/not_granted');
        }

        $this->load->view('AppUser/usersKycReport', $data);
    }

    function upadatekyc($kyc_id) {
        $data = array();

        $query = $this->db->get_where("app_user_kyc", array("id" => $kyc_id));
        $userkycdata = $query->row();
        $data['userkycdata'] = $userkycdata;
        $userid = $userkycdata->user_id;
        $query = $this->db->get_where("app_user", array("id" => $userid));
        $userdata = $query->row();
        $data['userdata'] = $userdata;


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

                if ($status == "Delete") {
                    $this->db->where("id", $kyc_id);
                    $this->db->delete("app_user_kyc");

                    redirect("AppUser/kyc_report/$kyc_id");
                } else {

                    $passowrd = array("status" => ($status),);
                    $this->db->set($passowrd);
                    $this->db->where("id", $kyc_id);
                    $this->db->update("app_user_kyc");
                    redirect("AppUser/upadatekyc/$kyc_id");
                }
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
        $this->load->view('AppUser/updateKyc', $data);
    }

}

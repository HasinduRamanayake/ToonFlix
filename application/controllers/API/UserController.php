<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';



class UserController extends REST_Controller {

    public function index_get(){
        $this->load->view('Auth/login');
    }

	public function index_post() {
        $username = $this->post('username');
        $password = $this->post('password');

        $user = $this->User_model->login($username, $password);

        if ($user) {
            $this->response([
                'status' => true,
                'message' => 'User authenticated',
                'data' => $user
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Invalid username or password'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

class Auth extends REST_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->library('session');
        $this->load->model('API/user_model'); // Load your user model
    }

    // public function index_post() {
    //     $username = $this->post('username');
    //     $password = $this->post('password');

    //     // Validate credentials
    //     $user = $this->user_model->login($username, $password);
    //     if (!$user) {
    //         $this->response([
    //             'status' => FALSE,
    //             'message' => 'Invalid username or password'
    //         ], REST_Controller::HTTP_UNAUTHORIZED);
    //     } else {
    //         // Generate token or session
	// 		$this->session->set_userdata('logged_in', TRUE);
	// 		$this->session->set_userdata('user_id', $user['id']); // Access as array
	// 		$this->session->set_userdata('username', $user['username']); // Access as array
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'User logged in successfully',
	// 			'redirect' => '/frontend/html/dashboard.html' // Indicate where to redirect
	// 		], REST_Controller::HTTP_OK);
    //     }
    // }

	public function index_post() {
		$username = $this->post('username');
		$password = $this->post('password');
		$email = $this->post('email');

		
	
		// Validate input
		if (!$username || !$password || !$email) {
			$this->response([
				'status' => FALSE,
				'message' => 'Missing username, password, or email'
			], REST_Controller::HTTP_BAD_REQUEST);
			return;
		}
	
		// Check if user already exists
		if ($this->user_model->exists($username, $email)) {
			$this->response([
				'status' => FALSE,
				'message' => 'User already exists'
			], REST_Controller::HTTP_CONFLICT);
			return;
		}
	
		// Register user
		$user_id = $this->user_model->signup($username, $password, $email);
		echo "<script>console.log(" . json_encode($user_id) . ");</script>";
		if ($user_id) {
			$this->response([
				'status' => TRUE,
				'message' => 'User registered successfully',
				'user_id' => $user_id
			], REST_Controller::HTTP_CREATED);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Failed to register user'
			], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class UserController extends REST_Controller
{
    private $entityManager;
 
    private $userRepository;

    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding,Authorization");
        parent::__construct(); 
        $this->load->library('session');    
        $this->load->library('doctrine');   
    }

    public function index_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');        

        $this->entityManager = $this->doctrine->em; 
        $this->userRepository = $this->entityManager->getRepository('Entity\User'); 

        // Validate credentials
        $user = $this->userRepository->login($username, $password);
        
        if (!$user) {
            $this->response(['status' => false, 'message' => 'Invalid username or password'], REST_Controller::HTTP_UNAUTHORIZED);
        } else {
                    
            // $userId = $this->session->flashdata('user_id');
            // $username = $this->session->flashdata('username');           
           
            $data = [
                'userId' => $user->getId(),
                'username' => $user->getUsername(),
            ];
            // $this->session->set_userdata('user_id', $user->getId());
            // $this->session->set_userdata('username', $user->getUsername());
            $token_data['user_id'] = $data['userId'];
            $token_data['username'] = $username;

            $tokenData = $this->authorization_token->generateToken($token_data);
           
            $this->response([
                'status' => true, 
                'message' => 'Data retrieved successfully',
                'data' => $data,
                'redirect' => '/dashboard'
            ], REST_Controller::HTTP_OK);
            return $this->sendJson(array("token" => $tokenData, "status" => true, "response" => "Login Success!"));
        }
    }

    private function sendJson($data)
    {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
    }


    public function index_get()
    {
        $this->load->helper('url');
        
       
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(200) // HTTP 200 OK
             ->set_output(json_encode(array('message' => 'Server is up and running')));
    }
    public function test_get()
    {
        $this->load->helper('url');
        
        
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(200) // HTTP 200 OK
             ->set_output(json_encode(array('message' => 'Server is up and running')));
    }

    
}

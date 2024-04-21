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
            $this->session->set_userdata('user_id', $user->getId());
            $this->session->set_userdata('username', $user->getUsername());

           
            $this->response([
                'status' => true, 
                'message' => 'Data retrieved successfully',
                'data' => $data,
                'redirect' => '/dashboard'
            ], REST_Controller::HTTP_OK);
        }
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

?>

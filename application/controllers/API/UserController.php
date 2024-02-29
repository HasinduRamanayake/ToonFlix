<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class UserController extends REST_Controller
{
    private $entityManager;
    // private $session;
    private $userRepository;

    public function __construct()
    {
        parent::__construct(); // Ensure that the parent constructor is called

        $this->load->library('doctrine'); // Assuming you have a 'doctrine' library that sets up Doctrine ORM
        $this->load->library('session');
    }

    public function index_post()
    {
        // Use CodeIgniter's input library to access POST data
        $username = $this->post('username');
        $password = $this->post('password');

        

        $this->entityManager = $this->doctrine->em; // Access EntityManager from your doctrine library
        $this->userRepository = $this->entityManager->getRepository('Entity\User'); // Adjust the path as needed

        // Validate credentials
        $user = $this->userRepository->login($username, $password);
        if (!$user) {
            $this->response(['status' => false, 'message' => 'Invalid username or password'], REST_Controller::HTTP_UNAUTHORIZED);
        } else {
            // Start session
            $session_data = array(
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                // include other session data as needed
            );
            $this->session->set_userdata('logged_in', $session_data);
            // $this->session->set_userdata('logged_in', true);
            // $this->session->set_userdata('user_id', $user->getId());
            // $this->session->set_userdata('username', $user->getUsername());

            $userId = $this->session->userdata('user_id');

            $this->response(['status' => true, 'message' => $userId, 'redirect' => '/dashboard'], REST_Controller::HTTP_OK);
        }
    }

    
}

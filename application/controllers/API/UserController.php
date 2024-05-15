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
    
        $this->entityManager = $this->doctrine->em;
        $this->userRepository = $this->entityManager->getRepository('Entity\User');
    }
    //User SignIn
    public function signin_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');

        $user = $this->userRepository->login($username, $password);
        if (!$user) {
            $this->response([
                'status' => false,
                'message' => 'Invalid username or password'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        } else {
            $this->session->set_userdata([
                'user_id' => $user->getId(),
                'username' => $user->getUsername()
            ]);

            $this->response([
                'status' => true, 
                'message' => 'Login successful',
                'data' => [
                    'userId' => $user->getId(),
                    'username' => $user->getUsername()
                ]
            ], REST_Controller::HTTP_OK);
        }
    }
    //User Signup
    public function signup_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');
        $email = $this->post('email');

        if (!$this->validateSignup($username, $password, $email)) {
            $this->response([
                'status' => false,
                'message' => 'Validation failed'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $user = $this->userRepository->createUser($username, $password, $email);
        if (!$user) {
            $this->response([
                'status' => false,
                'message' => 'User registration failed'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            $this->response([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'userId' => $user->getId(),
                    'username' => $user->getUsername()
                ]
            ], REST_Controller::HTTP_CREATED);
        }
    }
    //Usr SignOut
    public function signout_post()
    {
        // Destroy the session data
        $this->session->sess_destroy();

        $this->response([
            'status' => true,
            'message' => 'Sign out successful'
        ], REST_Controller::HTTP_OK);
    }

    public function validateSession_get()
    {
        $response = ['status' => $this->session->userdata('user_id') ? true : false];
        $this->response($response, $response['status'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_UNAUTHORIZED);
    }


    private function validateSignup($username, $password, $email)
    {
        return !empty($username) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function followUser_post($followerId, $followedId)
    {
        $result = $this->userRepository->followUser($followerId, $followedId);

        if ($result['status'] === 'success') {
            $this->response(['status' => 'success'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => 'fail', 'message' => $result['message']], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function unfollowUser_post($followerId, $followedId)
    {
        $result = $this->userRepository->unfollowUser($followerId, $followedId);

        if ($result['status'] === 'success') {
            $this->response(['status' => 'success'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => 'fail', 'message' => $result['message']], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
  
}
?>

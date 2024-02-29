<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';



class PostController extends REST_Controller {

    protected $entityManager;

    public function __construct() {
        parent::__construct();
        $this->load->library('doctrine');
        $this->entityManager = $this->doctrine->em;
        
    }

    public function index_post() {
        // Configure upload.
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            // Upload failed.
            $error = array('error' => $this->upload->display_errors());
            $this->response($error, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Upload succeeded.
            $data = $this->upload->data();
            $imagePath = '/uploads/' . $data['file_name']; // Adjust path as needed.

            // $session_data = $this->session->userdata('logged_in');
            // $data['user_id'] = $session_data['user_id'];
            // Retrieve the logged-in user's ID from session
            // $userId = $data['user_id'];
            $userId = 100;
            // log_message('debug', 'Session User ID: ' . $data['user_id'] );

            
            if (!$userId) {
                $this->response(['message' => $userId], REST_Controller::HTTP_FORBIDDEN);
                return;
            }
            
            // Find the user entity
            $user = $this->entityManager->find('Entity\User', $userId);
            if (!$user) {
                $this->response(['message' => $user], REST_Controller::HTTP_NOT_FOUND);
                return;
            }

            // Create and set properties for the new Post entity
            $post = new Entity\Post();
            $post->setTitle($this->input->post('title'));
            $post->setGenre('Anime');
            $post->setImagePath($imagePath);
            $post->setUser($user); // Associate the post with the user

            // Use EntityManager to persist the new Post entity
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->response(['message' => 'Image uploaded and post saved successfully.'], REST_Controller::HTTP_OK);
        }
    }
}


?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';



class PostController extends REST_Controller {

    protected $entityManager;

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('doctrine');
    
        $this->entityManager = $this->doctrine->em;        
    }

    public function getAllPosts_get()
    {
        //accessing the repository
        $posts = $this->entityManager->getRepository('Entity\Post')->findAll();
        $postData = array();
    
        foreach ($posts as $post) {
            $blob = $post->getImageData();
            // Convert blob to base64 for JSON serialization
            $base64 = base64_encode(stream_get_contents($blob)); 
            $postData[] = array(
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'genre' => $post->getGenre(),
                'imageData' => 'data:image/jpeg;base64,' . $base64
            );
        }
    
        $this->response($postData, REST_Controller::HTTP_OK);
    }
    
     

    public function createPost_post() {
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
            $fileContent = file_get_contents($data['full_path']);

            $userId = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            
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
            //----------> ADD Followings to the Repository 
            // Creating and setting properties for the new Post entity
            $post = new Entity\Post();
            $post->setTitle($this->input->post('title'));
            $post->setGenre('Anime');
            $post->setImageData($fileContent);
            $post->setUser($user); 

            // Use EntityManager to persist the new Post entity
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            
            unlink($data['full_path']);

            $this->response(['message' => 'Image uploaded and post saved successfully.'], REST_Controller::HTTP_OK);
        }
    }

    public function updatePost_post($postId)
    {
        if (!$postId) {
            $this->response(['message' => 'Invalid post ID'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    
        // Retrieve the post entity by ID
        $post = $this->entityManager->find('Entity\Post', $postId);
        if (!$post) {
            $this->response(['message' => 'Post not found'], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
    
        // Get data from POST request
        $title = $this->input->post('title');
        $genre = $this->input->post('genre');
    
        // Optionally checking if the POST data is present
        if (!empty($title)) {
            $post->setTitle($title);
        }
        if (!empty($genre)) {
            $post->setGenre($genre);
        }
    
        // Use EntityManager to persist the updated Post entity
        $this->entityManager->flush();
    
        $this->response(['message' => 'Post updated successfully.'], REST_Controller::HTTP_OK);
    }
   
}


?>

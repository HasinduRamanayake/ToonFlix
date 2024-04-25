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
        $this->postRepository = $this->entityManager->getRepository('Entity\Post');     
    }
   

    public function getAllPosts_get() {
        // Assuming 'PostRepository' has a method 'findAllPosts' that returns all posts
        $posts = $this->postRepository->findAllPosts();
    
        $postData = array();
    
        foreach ($posts as $post) {
            $postData[] = array(
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'genre' => $post->getGenre(),
                'imagePath' => base_url('uploads/' . $post->getImagePath()),
            );
        }
    
        // Check if we got any posts
        if (empty($postData)) { 
            // No posts found
            $this->response(['message' => 'No posts found'], REST_Controller::HTTP_NOT_FOUND);
        } else {
            // Send the response with the posts data
            $this->response($postData, REST_Controller::HTTP_OK);
        }
    }    
    
 
    public function createPost_post() {
        // Configure upload path.
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
    
        $this->load->helper('string');
        // Generate a random UUID filename for each upload image using alphanumeric string
        $config['file_name'] = random_string('alnum', 32); 
    
        $this->load->library('upload', $config);

        
        if (!$this->upload->do_upload('image')) {
            
            $error = array('error' => $this->upload->display_errors());
            $this->response($error, REST_Controller::HTTP_BAD_REQUEST);

        } else {
            
            $data = $this->upload->data();
            $uploadedFileName = $data['file_name']; 
    
            $userId = $this->session->userdata('user_id');
            if (!$userId) {
                $this->response(['message' => 'User not logged in'], REST_Controller::HTTP_FORBIDDEN);
                return;
            }
    
            // Finding the user entity or abort if not found
            $user = $this->entityManager->find('Entity\User', $userId);
            if (!$user) {
                $this->response(['message' => 'User not found'], REST_Controller::HTTP_NOT_FOUND);
                return;
            }   

            $res = $this->postRepository->createPost($this->input->post('title'),'8753475',$uploadedFileName,$user,$this->input->post('genre'),$this->input->post('description'));
            
            $this->response(['message' => 'Image uploaded and post saved successfully.'], REST_Controller::HTTP_OK);
        }
    }
    
    
    public function getPost_get($postId){
        if (!$postId) {
            $this->response(['message' => 'This post does not exist'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        $post = $this->postRepository->findPostById($postId);

        if ($post) {
            $postData = array(
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'genre' => $post->getGenre(),
                'description' => $post->getDescription(),
                'tag' => $post->getTag(),
                'image_path' => base_url('uploads/' . $post->getImagePath()),
                // If the User entity has a getUsername() method
                'username' => $post->getUser() ? $post->getUser()->getUsername() : null,
            );
    
            $this->response([
                'status' => 'success',
                'data' => $postData
            ], REST_Controller::HTTP_OK);
       
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Post not found']));
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

    // public function index_post()
    // {
    // $this->response(['message' => 'POST method not properly routed'], REST_Controller::HTTP_BAD_REQUEST);
    // }

    // public function index_get()
    // {
    //     $this->response(['message' => 'GET method not properly routed'], REST_Controller::HTTP_BAD_REQUEST);
    // }
   
}
?>

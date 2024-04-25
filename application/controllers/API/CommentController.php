<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class CommentController extends REST_Controller
{
    private $entityManager;
 
    private $commentRepository;

    public function __construct()
    {
        parent::__construct(); 
        $this->load->library('session');    
        $this->load->library('doctrine');   
        $this->entityManager = $this->doctrine->em; 
        $this->commentRepository = $this->entityManager->getRepository('Entity\Comment'); 
    }


    public function getAllComments_get($postId) {
        // Validate that postId is provided
        if (!$postId) {
            $this->response(['message' => 'Post ID is required'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    
        $comments = $this->commentRepository->getAllComments($postId);
    
        $commentData = array();
    
        foreach ($comments as $comment) {
            $commentData[] = array(
                'id' => $comment['comment_id'], // Access the 'comment_id' key of the array
                'content' => $comment['content'], // Access the 'content' key of the array
                // Assuming 'user' is the array containing user information
                'user' => array(
                    'id' => $comment['user_id'], // Access the 'user_id' key of the array
                    'username' => $comment['username'] // Access the 'username' key of the array
                ),
                // Add post data if needed
                'post' => array(
                    'id' => $comment['post_id'], // Access the 'post_id' key of the array
                    'title' => $comment['title'] // Access the 'title' key of the array
                ),
                'created_at' => $comment['created_at']
            );
        }
    
        $this->response($commentData, REST_Controller::HTTP_OK);
    }
    

    public function createComment_post()
    {      

        $postId = $this->post('post_id'); 
        $content = $this->post('content');

        if (!$postId || !$content) {
            $this->response(['message' => 'Missing post_id or content'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        $post = $this->entityManager->find('Entity\Post', $postId);

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


        if (!$post) {
            $this->response(['message' => 'Post not found'], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $res = $this->commentRepository->createComment($content, $post, $user);
       
        if($res){
            $this->response(['message' => 'Comment created successfully'], REST_Controller::HTTP_OK);
        }
     
    }   


    public function updateComment_put($commentId) {
        if (!$commentId) {
            $this->response([
                'status' => FALSE,
                'message' => 'Invalid comment ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $content = $this->put('content');

        $res = $this->commentRepository->updateComment($commentId, $content);

        if($res){

            $this->response([
                'status' => TRUE,
                'message' => 'Comment updated successfully'
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'No content provided'
            ], REST_Controller::HTTP_BAD_REQUEST);

        }
        // Fetch the comment from the database
       
    }
   
    public function deleteComment_delete($commentId) {
        // Check for user session or token
        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            $this->response(['status' => FALSE, 'message' => 'User not logged in'], REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }
        $res = $this->commentRepository->deleteComment($commentId,$userId);

        if($res){
            $this->response(['status' => TRUE, 'message' => 'Comment deleted successfully'], REST_Controller::HTTP_OK);
        }else{
            $this->response(['status' => FALSE, 'message' => 'Unauthorized'], REST_Controller::HTTP_UNAUTHORIZED);

        }
      
    }


    
    
}

?>

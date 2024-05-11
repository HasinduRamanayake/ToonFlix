<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class LikeController extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('doctrine');
        $this->entityManager = $this->doctrine->em;  
        $this->postRepository = $this->entityManager->getRepository('Entity\Post');
        $this->likeRepository = $this->entityManager->getRepository('Entity\Like');
    }

    

   

    public function getLikeCount_get($postId)
    {
        $post = $this->postRepository->find($postId);

        if ($post) {
            $this->response(['likeCount' => $post->getLikeCount()], REST_Controller::HTTP_OK);
        } else {
            $this->response(['error' => 'Post not found'], REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function addLike_post($postId){

        $userId = $this->session->userdata('user_id'); // Assume user ID is stored in session

        if (!$userId) {
            $this->response(['message' => 'User not logged in'], REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }

        $result = $this->likeRepository->addLikeToPost($postId, $userId);

        switch ($result) {
            case 'like_added':
                $this->response(['message' => 'Like added successfully'], REST_Controller::HTTP_OK);
                break;
            case 'like_already_exists':
                $this->response(['error' => 'Like already exists'], REST_Controller::HTTP_CONFLICT);
                break;
            case 'post_or_user_not_found':
                $this->response(['error' => 'Post not found or user not found'], REST_Controller::HTTP_NOT_FOUND);
                break;
            default:
                $this->response(['error' => 'An unexpected error occurred'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function removeLike_delete($postId)
    {
        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            $this->response(['message' => 'User not logged in'], REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }

        $result = $this->likeRepository->removeLikeByPostAndUser($postId, $userId);

        if ($result) {
            $this->response(['message' => 'Like removed successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['error' => 'Like not found'], REST_Controller::HTTP_NOT_FOUND);
        }
    }

   
}

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
        
        $posts = $this->postRepository->findAllPosts();
    
        $postData = array();
    
        foreach ($posts as $post) {
            $postData[] = array(
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'genre' => $post->getGenre(),
                'likeCount' => $post->getLikeCount(),
                'username' => $post->getUser() ? $post->getUser()->getUsername() : null,
                'imagePath' => base_url('uploads/' . $post->getImagePath()),
                'createdAt' => $post->getCreatedAt()
            );
        }
    
        
        if (empty($postData)) { 
            
            $this->response(['message' => 'No posts found'], REST_Controller::HTTP_NOT_FOUND);
        } else {
            // Sending the response
            $this->response($postData, REST_Controller::HTTP_OK);
        }
    }    
    
 
    public function createPost_post() {
        // Configure upload path.
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
    
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

            $tagsInput = $this->input->post('tags');
            $tagNames = json_decode($tagsInput);

            // If decoding failed, return an error response
            if ($tagNames === null && json_last_error() !== JSON_ERROR_NONE) {
                $this->response(['message' => 'Invalid tags format'], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }
            $tags = [];
            
            $this->entityManager->beginTransaction();
            try {
                foreach ($tagNames as $tagName) {
                    $tag = $this->entityManager->getRepository('Entity\Tag')->findOneBy(['tagName' => $tagName]);
        
                    if (!$tag) {
                        $tag = $this->entityManager->getRepository('Entity\Tag')->createTag($tagName);
                    }
                    $tags[] = $tag;
                }
        
                $res = $this->postRepository->createPost($this->input->post('title'), $tags, $uploadedFileName, $user, $this->input->post('genre'), $this->input->post('description'));
                $this->entityManager->flush();
                $this->entityManager->commit();
        
                $this->response(['message' => 'Image uploaded and post saved successfully.'], REST_Controller::HTTP_OK);
            } catch (Exception $e) {
                $this->entityManager->rollback();
                $this->response(['message' => 'Failed to create post: ' . $e->getMessage()], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
          
            
        }
    }
    
    
    public function getPost_get($postId) {
        if (!$postId) {
            $this->response(['message' => 'This post does not exist'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        $post = $this->postRepository->findPostById($postId);
    
        if ($post) {
            $tags = [];
            foreach ($post->getTags() as $tag) {
                $tags[] = $tag->getTagName(); 
            }
    
            $likesData = [];
            $likes = $post->getLikes();
            foreach ($likes as $like) {
                $likeData = [
                    'like_id' => $like->getId(), 
                    'user_id' => $like->getUser() ? $like->getUser()->getId() : null,
                    'username' => $like->getUser() ? $like->getUser()->getUsername() : null,                    
                ];
                $likesData[] = $likeData;
            }    
            
            $followersData = [];
            if ($post->getUser()) {
                foreach ($post->getUser()->getFollowers() as $follower) {
                    $followersData[] = [
                        'id' => $follower->getId(),
                        'username' => $follower->getUsername()
                    ];
                }
            }

            $postData = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'genre' => $post->getGenre(),
                'description' => $post->getDescription(),
                'tag' => $tags,
                'likes' => $likesData,
                'likeCount' => $post->getLikeCount(),
                'image_path' => base_url('uploads/' . $post->getImagePath()),
                'username' => $post->getUser() ? $post->getUser()->getUsername() : null,
                'user_id' => $post->getUser() ? $post->getUser()->getId() : null,
                'followers' => $followersData
            ];
    
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
    
    public function updatePost_put($postId) {
        if (!$postId) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    
        $post = $this->postRepository->findPostById($postId);
    
        if (!$post) {
            $this->response(['message' => 'Post not found'], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
    
        $title = $this->put('title');
        $genre = $this->put('genre');
        $description = $this->put('description');
        $tagNames = $this->put('tags');
    
        // Ensure that $tagNames is a JSON string
        if (is_array($tagNames)) {
            $tagNames = json_encode($tagNames);
        }
    
        try {
            $this->postRepository->updatePost($post, $title, $description, $genre, $tagNames);
            $this->response(['message' => 'Post updated successfully'], REST_Controller::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            $this->response(['message' => $e->getMessage()], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    

    public function searchByTag_get() {
        $tags = $this->input->get('tags');
        $tagNames = explode(',', $tags);
        $posts = $this->postRepository->findByTags($tagNames);
    
        if (empty($posts)) {
            $this->response(['message' => 'No posts found for the given tags'], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $formattedPosts = [];
            foreach ($posts as $post) {
                $formattedPosts[] = [
                    'id' => $post->getId(),
                    'title' => $post->getTitle(),
                    'genre' => $post->getGenre(),
                    'description' => $post->getDescription(),
                    'image_path' => base_url('uploads/' . $post->getImagePath()),
                    'username' => $post->getUser() ? $post->getUser()->getUsername() : 'Anonymous',
                    'tags' => array_map(function ($tag) { return $tag->getTagName(); }, $post->getTags()->toArray())
                ];
            }
            $this->response([
                'status' => 'success',
                'data' => $formattedPosts
            ], REST_Controller::HTTP_OK);
        }
    }
    


    public function searchByName_get() {
        $name = $this->input->get('name');

        if (empty($name)) {
            $this->response([
                'status' => 'error',
                'message' => 'Name parameter is required'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $posts = $this->postRepository->findPostsByName($name);
        
        $result = array_map(function ($post) {

            $tags = [];
            foreach ($post->getTags() as $tag) {
                $tags[] = $tag->getTagName(); 
            }
            return [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'description' => $post->getDescription(),
                'genre' => $post->getGenre(), 
                'tags' => $tags,               
                'image_path' => base_url('uploads/' . $post->getImagePath()),
                'username' => $post->getUser() ? $post->getUser()->getUsername() : null,

            ];
        }, $posts);

        $this->response([
            'status' => 'success',
            'data' => $result
        ], REST_Controller::HTTP_OK);
    }

    public function getUserPosts_get() {
        $userId = $this->session->userdata('user_id'); 
    
        if (!$userId) {
            $this->response(['message' => 'User not logged in'], REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }
    
        $posts = $this->postRepository->findByUserId($userId);
        
        if (empty($posts)) {
            $this->response(['status' => 'error', 'message' => 'No posts found'], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $formattedPosts = [];
            foreach ($posts as $post) {
                $formattedPosts[] = [
                    'id' => $post->getId(),
                    'title' => $post->getTitle(),
                    'genre' => $post->getGenre(),
                    'description' => $post->getDescription(),
                    'image_path' => base_url('uploads/' . $post->getImagePath()),
                    'username' => $post->getUser() ? $post->getUser()->getUsername() : 'Anonymous',
                    'tags' => array_map(function ($tag) { return $tag->getTagName(); }, $post->getTags()->toArray())
                ];
            }
            $this->response($formattedPosts, REST_Controller::HTTP_OK);
    
        }
    }
    public function deletePost_delete($postId) {
        if (!$postId) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    
        $post = $this->postRepository->findPostById($postId);
    
        if (!$post) {
            $this->response(['message' => 'Post not found'], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
    
        try {
            $this->postRepository->deletePost($post);
            $this->response(['message' => 'Post deleted successfully'], REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(['message' => 'Failed to delete post: ' . $e->getMessage()], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
?>

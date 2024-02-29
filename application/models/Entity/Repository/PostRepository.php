<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Post;

class PostRepository extends EntityRepository
{
    public function createPost($title, $imagePath){

        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            // Handle not logged in error
            return;
        }
    
        $user = $this->entityManager->find('Entity\User', $userId);
        if (!$user) {
            // Handle user not found error
           
            return;
        }
    
        $post = new Post();
        $post->setTitle($title);
        $post->setImagePath($imagePath);
        $post->setUser($user); // Associate the post with the retrieved user entity
    
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    
        // Handle success response
    }
}

?>
<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Post;

class PostRepository extends EntityRepository
{
    public function createPost($title, $imageData){

        $userId = $this->session->userdata('user_id');
        if (!$userId) {            
            return;
        }
    
        $user = $this->entityManager->find('Entity\User', $userId);
        if (!$user) {           
            return;
        }
    
        $post = new Post();
        $post->setTitle($title);
        $post->setImageData($imagePath);
        $post->setUser($user); 
    
        $this->entityManager->persist($post);
        $this->entityManager->flush(); 
        
    }

    public function findAllPosts()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }
}

?>
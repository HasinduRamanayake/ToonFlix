<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Post;

class PostRepository extends EntityRepository
{
    public function createPost($title, $tag, $imagePath, $user, $genre, $description) {
        $post = new Post();
        $post->setTitle($title);
        $post->setGenre($genre);
        $post->setDescription($description);
        $post->setTag($tag);
        $post->setImagePath($imagePath);
        $post->setUser($user);
        $post->setCreatedAt(new \DateTime("now"));
    
        $this->_em->persist($post);
        $this->_em->flush();
    
        return true;
    }
    

    public function findAllPosts()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }
}

?>
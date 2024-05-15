<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Post;

class PostRepository extends EntityRepository
{
    public function createPost($title,array $tags, $imagePath, $user, $genre, $description) {
        $post = new Post();
        $post->setTitle($title);
        $post->setGenre($genre);
        $post->setDescription($description);
        $post->addTags($tags);
        $post->setImagePath($imagePath);
        $post->setUser($user);
        $post->setCreatedAt(new \DateTime("now"));
    
        $this->_em->persist($post);
        $this->_em->flush();
    
        return true;
    }
    

    public function updatePost($post, $title, $description, $genre, $tagNamesJson) {
        if (!$post) {
            throw new \InvalidArgumentException("No post provided for update.");
        }
    
        log_message('error',"Received title: " . $title);
        if ($title) {
            $post->setTitle($title);
        }    
        if ($genre) {
            $post->setGenre($genre);
        }    
        if ($description) {
            $post->setDescription($description);
        }    
    
        // Decoding the JSon String
        $tagNames = is_string($tagNamesJson) ? json_decode($tagNamesJson, true) : $tagNamesJson;
        log_message('error',"Received tag names JSON: " . print_r($tagNames, true));
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decoding error: " . json_last_error_msg());
            throw new \InvalidArgumentException("Invalid tags format: " . json_last_error_msg());
        }
    
        $currentTags = $post->getTags();
        
        foreach ($currentTags as $tag) {
            $post->removeTag($tag);
        }
    
        foreach ($tagNames as $tagName) {
            $tag = $this->_em->getRepository('Entity\Tag')->findOneBy(['tagName' => $tagName]);
    
            if (!$tag) {
                $tag = new \Entity\Tag();
                $tag->setTagName($tagName);
                $this->_em->persist($tag);
            }
    
            $post->addTag($tag);
        }
    
        $this->_em->flush();
    }

    public function findAllPosts()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }

    public function findPostById($id)
    {
        return $this->find($id);
    }

    public function findByTags($tagNames) {
        
        if (!is_array($tagNames) || empty($tagNames)) {
            return []; 
        }    
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('post')
           ->distinct(true) 
           ->from('Entity\Post', 'post')
           ->join('post.tags', 't')
           ->where($qb->expr()->in('t.tagName', $tagNames));    
        
        return $qb->getQuery()->getResult();
    }
    

    public function findPostsByName($name) {
        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->like('p.title', ':name'))
           ->setParameter('name', '%' . $name . '%')
           ->orderBy('p.created_at', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findByUserId($userId) {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.user = :userId')
           ->setParameter('userId', $userId)
           ->orderBy('p.created_at', 'DESC'); 

        return $qb->getQuery()->getResult();
    }

    public function deletePost($post) {
        $this->_em->remove($post);
        $this->_em->flush();
    }
}

?>
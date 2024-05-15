<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Like;

class LikeRepository extends EntityRepository
{
    public function addLikeToPost($postId, $userId){

        $post = $this->_em->getRepository('Entity\Post')->find($postId);
        $user = $this->_em->getRepository('Entity\User')->find($userId);

        if (!$post || !$user) {
            return 'post_or_user_not_found';
        }

        // Checking if the like already exists
        $existingLike = $this->_em->getRepository('Entity\Like')
                                ->findOneBy(['post' => $post, 'user' => $user]);
        if ($existingLike) {
            return 'like_already_exists';
        }

        $like = new Like();
        $like->setPost($post);
        $like->setUser($user);
        

        $this->_em->persist($like);
        $post->addLike($like);

        $this->_em->flush();

        return 'like_added';
    }
    
    public function removeLikeByPostAndUser($postId, $userId)
    {
                
        $like = $this->_em->getRepository('Entity\Like')->findOneBy([
            'post' => $postId,
            'user' => $userId
        ]);

        if (!$like) {
        
            return false;
        }

        try {
            $post = $like->getPost();

            if (!$post) {
                error_log("No post found associated with the like");
                return false;
            }

        
            $post->removeLike($like);
            
            $this->_em->remove($like);
            $this->_em->flush();
            
            return true;
        } catch (\Exception $e) {
            error_log("Failed to remove like: " . $e->getMessage());
            return false;
        }
    }

    

}

?>
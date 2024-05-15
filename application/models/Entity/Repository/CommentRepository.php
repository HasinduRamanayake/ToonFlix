<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Comment;

class CommentRepository extends EntityRepository{


    public function getAllComments($postId) {
        //creating the query builder from the entity maanger
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select([
            'comment.id AS comment_id',
            'comment.content',
            'comment.created_at',
            'user.id AS user_id',
            'user.username',
            'post.id AS post_id',
            'post.title'
        ]);
    
        $queryBuilder->from('Entity\Comment', 'comment')
                     ->leftJoin('comment.user', 'user')
                     ->leftJoin('comment.post', 'post')
                     ->where('comment.post = :postId')
                     ->setParameter('postId', $postId);
    
        
    
        return $queryBuilder->getQuery()->getResult();
    }

    public function createComment($content, $post, $user){

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setPost($post);
        $comment->setUser($user);
        $comment->setCreatedAt(new \DateTime("now"));

        $this->_em->persist($comment);
        $this->_em->flush();

        return true;

    }

    public function updateComment($commentId, $content) {
        if (empty($content)) {
            return false;
        }
    
        $comment = $this->_em->find('Entity\Comment', $commentId);
        //If Comment is not found
        if (!$comment) {
            throw new \InvalidArgumentException("No comment found for ID " . $commentId);
        }
    
        $comment->setContent($content);
        $this->_em->flush();
    
        return true;
    }


    public function deleteComment($commentId,$userId){
        $comment = $this->find($commentId);
        if ($comment) {
            //checking if only the current logged user is request to delete the comment
            if ($comment->getUser()->getId() == $userId) {
                
                $this->_em->remove($comment);
                $this->_em->flush();
    
                return true;
            }
        
        } else {
           return;
        }

    }
   

}
<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Comment;

class CommentRepository extends EntityRepository{


    public function getAllComments($postId, ?array $orderBy = null, $limit = null, $offset = null) {
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
    
        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                $queryBuilder->addOrderBy("comment.$field", $order);
            }
        }
    
        if ($limit !== null) {
            $queryBuilder->setMaxResults($limit);
        }
    
        if ($offset !== null) {
            $queryBuilder->setFirstResult($offset);
        }
    
        // Use getResult to fetch the joined results
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

    public function updateComment($commentId,$content){

        $comment = $this->find($commentId);
        if (!$comment) {
            $this->response([
                'status' => FALSE,
                'message' => 'Comment not found'
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        // Get the updated data from the PUT request
        
        if (!empty($content)) {
            $comment->setContent($content);
            $this->_em->flush();
            return true;
           
        } else {
           return;
        }
    }
    public function deleteComment($commentId,$userId){
        $comment = $this->find($commentId);
        if ($comment) {
            if ($comment->getUser()->getId() == $userId) {
                // User is the owner of the comment
                $this->_em->remove($comment);
                $this->_em->flush();
    
                return true;
            }
        
        } else {
           return;
        }

    }
   

}
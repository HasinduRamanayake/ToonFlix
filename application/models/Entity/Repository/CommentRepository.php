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
        // Modify the select to include the user and post entities
        $queryBuilder->from('Entity\Comment', 'comment')
                     ->leftJoin('comment.user', 'user') // Assume the relation is configured in Comment entity
                     ->leftJoin('comment.post', 'post') // Assume the relation is configured in Comment entity
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

   

}
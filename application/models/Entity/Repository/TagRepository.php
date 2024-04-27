<?php
namespace Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Entity\Tag;

class TagRepository extends EntityRepository
{
    public function createTag($tagName) {
        $tag = new Tag();
        $tag->setTagName($tagName);
        $this->_em->persist($tag);
        $this->_em->flush();
        return $tag;
    }
    

}

?>
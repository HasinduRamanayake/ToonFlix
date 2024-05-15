<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * @ORM\Entity(repositoryClass="Entity\Repository\TagRepository")
 * @ORM\Table(name="tags")
 */

class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="tag_id",type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     */
    private $posts;


    /**
     * @ORM\Column(type="string")
     **/
    private $tagName;


    public function __construct() {
        $this->posts = new ArrayCollection();
    }

    public function getPost() {
        return $this->post;
    }

    public function setPost($post) {
        $this->post = $post;
    }
    
    public function getId(){
        return $this->id;
    }

    public function setTagName($tagName){
        $this->tagName = $tagName;
    }

    public function getTagName(){
        return $this->tagName;
    }

    public function addPost(Post $post) {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addTag($this); 
        }
    }

    public function removePost(Post $post) {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeTag($this);
        }
    }
   
}

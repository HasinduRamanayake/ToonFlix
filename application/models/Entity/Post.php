<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * @ORM\Entity(repositoryClass="Entity\Repository\PostRepository")
 * @ORM\Table(name="posts")
 */

class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="post_id",type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_path;

    /**
     * @ORM\Column(type="string")
     **/
    private $title;

    /**
     * @ORM\Column(type="string")
     **/
    private $genre;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     * @ORM\JoinTable(name="post_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="post_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="tag_id")}
     * )
     */
    private $tags;

    /**
     * @ORM\Column(type="string")
     **/
    private $description;

    /**
     * Many Posts have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     **/
    private $user;

    /**
     * @ORM\Column(type="datetime")
     **/
    private $created_at;



    public function __construct() {
        $this->tags = new ArrayCollection();
    }


    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }
    

    public function getId()
    {
        return $this->id;
    }

    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;
    }

    public function getImagePath()
    {
        return $this->image_path;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    public function getGenre()
    {
        return $this->genre;
    }
    
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($time)
    {
        $this->created_at = $time;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function addTag(Tag $tag) {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addPost($this); 
        }
    }

    public function addTags(array $tags) {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function removeTag(Tag $tag) {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePost($this);  
        }
    }

    public function getTags() {
        return $this->tags;
    }
    
    
}

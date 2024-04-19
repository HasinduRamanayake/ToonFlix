<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image_data;


    /**
     * @ORM\Column(type="string")
     **/
    private $title;

    /**
     * @ORM\Column(type="string")
     **/
    private $genre;

    /**
     * Many Posts have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    // Getter and setter for user
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

    public function setImageData($image_data)
    {
        $this->image_data = $image_data;
    }

    public function getImageData()
    {
        return $this->image_data;
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
    
}

<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Entity\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="user_id",type="integer")
     **/
    protected $id;

    /**
     * @ORM\Column(type="string")
     **/
    protected $username;

    /**
     * @ORM\Column(type="string")
     **/
    protected $password;

    /**
     * @ORM\Column(type="string")
     **/
    protected $email;

    /**
     * One User has Many Posts.
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     **/
    private $posts;

    public function __construct() {
        $this->posts = new ArrayCollection();
    }

    // Getter for posts
    public function getPosts() {
        return $this->posts;
    }

    // Add a post to the user
    public function addPost($post) {
        $this->posts[] = $post;
    }


    // Getters and setters
    public function getId() {
        return $this->id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getEmail() {
        return $this->email;
    }
}

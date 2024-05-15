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

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="followers")
     * @ORM\JoinTable(name="follows",
     *      joinColumns={@ORM\JoinColumn(name="follower_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="followed_id", referencedColumnName="user_id")}
     * )
     */
    private $following;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="following")
     */
    private $followers;

    public function __construct() {
        $this->posts = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
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

    public function getFollowing() {
        return $this->following;
    }

    public function addFollowing(User $user) {
        if (!$this->following->contains($user)) {
            $this->following->add($user);
        }
    }

    public function removeFollowing(User $user) {
        if ($this->following->contains($user)) {
            $this->following->removeElement($user);
        }
    }

    public function getFollowers() {
        return $this->followers;
    }

    public function addFollower(User $user) {
        if (!$this->followers->contains($user)) {
            $this->followers->add($user);
        }
    }

    public function removeFollower(User $user) {
        if ($this->followers->contains($user)) {
            $this->followers->removeElement($user);
        }
    }
    
    public function getFollowingCount() {
        return $this->following->count();
    }

    
    public function getFollowersCount() {
        return $this->followers->count();
    }
}


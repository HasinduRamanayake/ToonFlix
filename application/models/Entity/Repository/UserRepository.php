<?php
namespace Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Entity\User; 

class UserRepository extends EntityRepository
{
    

    public function findByUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function login($username, $password)
    {
        $user = $this->findByUsername($username);
       
        if ($user && password_verify($password, $user->getPassword())) {
           
            return $user;
        }

        return null;
    }
    
    public function createUser($username, $password, $email)
    {
        // Checking if user already exists
        if ($this->findByUsername($username)) {
            throw new \Exception("User already exists with the username: $username");
        }

        // Creating new User instance
        $user = new User();
        $user->setUsername($username);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); //Hashing the password
        $user->setEmail($email);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function followUser($followerId, $followedId)
    {
        $follower = $this->find($followerId);
        $followed = $this->find($followedId);

        if ($follower && $followed) {
            $follower->addFollowing($followed);
            $entityManager = $this->getEntityManager();
            $entityManager->persist($follower);
            $entityManager->flush();
            return ['status' => 'success'];
        } else {
            return ['status' => 'fail', 'message' => 'User not found'];
        }
    }

    public function unfollowUser($followerId, $followedId)
    {
        $follower = $this->find($followerId);
        $followed = $this->find($followedId);

        if ($follower && $followed) {
            $follower->removeFollowing($followed);
            $entityManager = $this->getEntityManager();
            $entityManager->persist($follower);
            $entityManager->flush();
            return ['status' => 'success'];
        } else {
            return ['status' => 'fail', 'message' => 'User not found'];
        }
    }



}

?>
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
            // Password matches
            return $user;
        }

        // Password does not match or user not found
        return null;
    }
    
    public function createUser($username, $password, $email)
    {
        // Check if user already exists
        if ($this->findByUsername($username)) {
            throw new \Exception("User already exists with the username: $username");
        }

        // Create new User instance
        $user = new User();
        $user->setUsername($username);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); // Hash the password before storing it
        $user->setEmail($email);

        // Persist the new user
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }


}

?>
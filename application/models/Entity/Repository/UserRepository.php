<?php
namespace Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Entity\User; 

class UserRepository extends EntityRepository
{
    public function login($username, $password)
    {
        $user = $this->findByUsername($username);
       
        if ($user && ($password === $user->getPassword())) {

            return $user;
        }
        

        return null;
    }

    public function findByUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }
}

?>
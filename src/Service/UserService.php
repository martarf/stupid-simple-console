<?php

namespace PNWPHP\SSC\Service;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
//use Doctrine\DBAL\Connection;

class UserService implements UserProviderInterface
{

    public function __construct($connection)
    {
       // $this->conn = $conn;
    }

    public function loadUserByUsername($username)
    {

       /* $stmt = $this->conn->executeQuery('SELECT * FROM users WHERE username = ?', array(strtolower($username)));
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }*/
        $user['username']='user';
        $user['password']='5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==';
        $user['roles'] = "ROLE_USER";
        return new User($user['username'], $user['password'], explode(',', $user['roles']), true, true, true, true);
    }

    public function getProjectsForUser($username){
        return ['1', '2', '3', '4'];
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}

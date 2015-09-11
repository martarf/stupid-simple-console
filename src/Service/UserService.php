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
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function loadUserByUsername($username)
    {
        $sql = 'SELECT username, password, roles FROM users WHERE username = :un';

        try{
            $query = $this->db->prepare($sql);
            $query->execute(['un' => $username]);
            $user = $query->fetch(\PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            // TODO: Fail safe
            throw $e;
        }

        if($user === null) {
             throw new \Exception('You shall not pass!!!');
        }

        //$user['username']='user';
        //$user['password']='5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==';
        //$user['roles'] = "ROLE_USER";
        return new User($user['username'], $user['password'], explode(',', $user['roles']), true, true, true, true);
    }

    public function getProjectsForUser(User $user)
    {
        $sql = 'SELECT project_id as id, project_name as name FROM projects WHERE EXISTS (SELECT * FROM project_user JOIN users ON users.username = :un WHERE project_user.project_id = projects.project_id)';

        try{
            $query = $this->db->prepare($sql);
            $query->execute(['un' => $user->getUsername()]);
            $projects = $query->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            // TODO: Fail safe
            throw $e;
        }

        return $projects;
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

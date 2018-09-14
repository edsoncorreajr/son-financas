<?php
namespace SONFin\Auth;

use Jasny\Auth\Sessions;
use Jasny\Auth\User;

use SONFin\Repository\RepositoryInterface;

class JasnyAuth extends \Jasny\Auth
{
    use Sessions;

    /** 
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetch a user by ID
     * 
     * @param  int $id
     * @return User
     */
    public function fetchUserById($id)
    {
        $this->repository->find($id, false);
    }

    /**
     * Fetch a user by username
     * 
     * @param  string $username
     * @return User
     */
    public function fetchUserByUsername($username)
    {
        return $repository->findByField('email', $username)[0];
    }
}
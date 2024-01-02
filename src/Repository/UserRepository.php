<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function loadUserByIdentifier(string $identifier): ?User
    {
        return $this->findOneBy(['email' => $identifier]);
    }
    public function loadUserById(int $id): ?User
    {
        return $this->find($id);
    }
}

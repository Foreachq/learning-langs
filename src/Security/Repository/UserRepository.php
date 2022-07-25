<?php

declare(strict_types=1);

namespace App\Security\Repository;

use App\Security\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Http\Discovery\Exception\NotFoundException;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null  find($id, $lockMode = null, $lockVersion = null)
 * @method User|null  findOneBy(array $criteria, array $orderBy = null)
 * @method User[]     findAll()
 * @method User[]     findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function requireOne(string|int $id): User
    {
        $user = $this->find($id);

        if (!$user instanceof User) {
            throw new NotFoundException('User not found');
        }

        return $user;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function requireOneBy(array $options): User
    {
        $user = $this->findOneBy($options);

        if (!$user instanceof User) {
            throw new NotFoundException('User not found');
        }

        return $user;
    }
}

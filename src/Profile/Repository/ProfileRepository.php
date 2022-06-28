<?php

declare(strict_types=1);

namespace App\Profile\Repository;

use App\Profile\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    public function save(Profile $profile): void
    {
        $this->_em->persist($profile);
        $this->_em->flush();
    }
}

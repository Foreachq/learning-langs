<?php

declare(strict_types=1);

namespace App\Learning\Repository;

use App\Learning\Entity\WordProgress;
use App\Profile\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<WordProgress>
 *
 * @method WordProgress|null  find($id, $lockMode = null, $lockVersion = null)
 * @method WordProgress|null  findOneBy(array $criteria, array $orderBy = null)
 * @method WordProgress[]     findAll()
 * @method WordProgress[]     findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class WordProgressRepository extends ServiceEntityRepository
{
    public function __construct(private readonly PaginatorInterface $paginator, ManagerRegistry $registry)
    {
        parent::__construct($registry, WordProgress::class);
    }

    public function getActiveProgressesByProfile(Profile $profile, int $page, ?int $limit = null): PaginationInterface
    {
        $qb = $this->createQueryBuilder('progress')
            ->where('progress.active = TRUE')
            ->andWhere('progress.profile = :profile_id')
            ->setParameter('profile_id', $profile->getId())
            ->orderBy('progress.id', 'ASC');

        $query = $qb->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }

    public function save(WordProgress $word): void
    {
        $this->_em->persist($word);
        $this->_em->flush();
    }

    public function remove(WordProgress $word): void
    {
        $this->_em->remove($word);
        $this->_em->flush();
    }
}

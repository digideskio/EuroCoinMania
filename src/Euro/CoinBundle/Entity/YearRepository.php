<?php

namespace Euro\CoinBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * YearRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class YearRepository extends EntityRepository {

	public function findAllSorted() {
		$queryBuidler = $this->createQueryBuilder('y');
		$expr = $queryBuidler->expr();

		return $queryBuidler
						->select('w, y')
						->leftJoin('y.workshop', 'w')
						->orderBy('y.year', 'ASC')
						->addOrderBy('w.short_name', 'ASC')
						->getQuery()
						->getResult();
	}

}

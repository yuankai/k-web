<?php

namespace Myexp\Bundle\CmsBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository {

    /**
     * 获得分页查询
     * 
     * @param type $params
     * @return type
     */
    public function getPaginationQuery($params = null) {
        $qb = $this->buildQuery($params);

        return $qb->getQuery();
    }

    /**
     * 构造查询
     * 
     * @param type $params
     * @return type
     */
    private function buildQuery($params) {

        $qb = $this->createQueryBuilder('p');

        if (isset($params['category'])) {
            $qb
                    ->andWhere('p.category = ?1')
                    ->setParameter(1, $params['category'])
            ;
        }
        if (isset($params['isActive'])) {
            $qb
                    ->andWhere('p.isActive = ?2')
                    ->setParameter(2, $params['isActive'])
            ;
        }

        return $qb;
    }

}

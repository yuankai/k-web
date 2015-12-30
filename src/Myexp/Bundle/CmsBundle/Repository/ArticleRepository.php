<?php

namespace Myexp\Bundle\CmsBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository {

    public function getPaginationQuery($params = null) {
        $qb = $this->buildQuery($params);
        
        return $qb->getQuery();
    }

    private function buildQuery($params) {

        $qb = $this->createQueryBuilder('a');

        if (isset($params['category'])) {
            $allChildren = $params['category']->getAllChildren();
            $qb
                    ->andWhere($qb->expr()->in('a.category', '?1'))
                    ->setParameter(1, $allChildren)
            ;
        }
        if (isset($params['isActive'])) {
            $qb
                    ->andWhere('a.isActive = ?2')
                    ->setParameter(2, $params['isActive'])
            ;
        }

        return $qb;
    }

    public function getPictitles($category, $limit) {
        $qb = $this->createQueryBuilder('p')
                ->where('p.category = :category', 'p.picurl != :picurl')
                ->setParameters(array(
                    'category' => $category,
                    'picurl' => 'NULL'
                ))
                ->orderBy('p.id', 'DESC')
                ->setMaxResults($limit)
                ->getQuery();

        return $qb->getResult();
    }

    //搜索文章标题
    public function getTitles($categoryId, $limit) {
        
        $cateRepo = $this->getEntityManager()->getRepository('CmsBundle:Category');
        
        $category = $cateRepo->find($categoryId);
        $allChildren = $category->getAllChildren();
        
        $qb = $this->createQueryBuilder('p');
        $q = $qb
                ->where($qb->expr()->in('p.category', ':category'))
                ->setParameters(array('category' => $allChildren))
                ->orderBy('p.id', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
        ;

        return $q->getResult();
    }

    //根据id查询category的pid
    public function getCagetoryid($id) {
        $qb = $this->createQueryBuilder('c')
                ->where('c.id = :id ')
                ->setParameter('id', $id)
                ->getQuery();
        return $qb->getResult();
    }

    //搜索结果
    public function getResult1s($keyword) {
        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->like('p.trans.title', "%$keyword%"));

        $q = $qb->getQuery();
        return $q;
    }

    public function getResults($keyword) {
        $result = $this->createQueryBuilder('o')
                ->andWhere('o.author LIKE :product')
                ->setParameter('product', '%' . $keyword . '%')
                ->getQuery()
                ->getResult();
        return $result;
    }

}

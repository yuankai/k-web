<?php

namespace Myexp\Bundle\CmsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleTranslationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleTranslationRepository extends EntityRepository {

    public function getCount($params = null) {

        $qb = $this->buildQuery($params);
        $qb->select($qb->expr()->count('at'));

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getWithPagination($params = null, $order_by = array(), $offset = 0, $limit = 0) {

        $qb = $this->buildQuery($params);

        if ((isset($offset)) && (isset($limit))) {
            if ($limit > 0) {
                $qb->setFirstResult($offset);
                $qb->setMaxResults($limit);
            }
        }

        foreach ($order_by as $key => $value) {
            $qb->add('orderBy', $key . ' ' . $value);
        }

        $q = $qb->getQuery();

        return $q->getResult();
    }

    private function buildQuery($params) {

        $qb = $this->createQueryBuilder('at');

        if (isset($params['title'])) {
            $qb
                    ->andWhere($qb->expr()->like('at.title', '?1'))
                    ->setParameter(1, $params['title'])
            ;
        }

        if (isset($params['content'])) {
            $qb
                    ->orWhere($qb->expr()->like('at.content', '?2'))
                    ->setParameter(2, $params['content'])
            ;
        }

        if (isset($params['lang'])) {
            $qb
                    ->andWhere('at.lang = ?3')
                    ->setParameter(3, $params['lang'])
            ;
        }

        if (isset($params['category'])) {
            $qb
                    ->join('at.article', 'a')
                    ->andWhere($qb->expr()->in('a.category', '?4'))
                    ->setParameter(4, $params['category'])
            ;
        }

        return $qb;
    }
    
        //搜索结果
    public function getResults($keyword){
        $qb=$this->createQueryBuilder('p');
     $result = $this->createQueryBuilder('o')
   ->andWhere('o.title LIKE :product')
   ->setParameter('product', '%'.$keyword.'%')
   ->getQuery()
   ->getResult();
        return $result; 
    }


}

<?php

namespace Myexp\Bundle\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Myexp\Bundle\CmsBundle\Entity\Page;
use Myexp\Bundle\CmsBundle\Entity\PageTranslation;
use Myexp\Bundle\CmsBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/page")
 */
class PageController extends Controller {

    /**
     * Finds and displays a Page entity.
     * @Route("/{id}.html", name="page_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MyexpCmsBundle:Page')->findOneBy(array(
            'id' => $id
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        //分类
        $category = $entity->getCategory();
        $topCategory = $category->getTopCategory();

        return array(
            'entity' => $entity,
            'category' => $category,
            'topCategory' => $topCategory
        );
    }


}

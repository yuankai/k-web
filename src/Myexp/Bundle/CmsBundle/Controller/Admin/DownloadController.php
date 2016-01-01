<?php

namespace Myexp\Bundle\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Myexp\Bundle\CmsBundle\Entity\Download;
use Myexp\Bundle\CmsBundle\Form\DownloadType;
use Myexp\Bundle\CmsBundle\Helper\Paginator;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Download controller.
 *
 * @Route("/download")
 */
class DownloadController extends Controller {

    /**
     * Lists all Download entities.
     *
     * @Route("/", name="download")
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("GET|DELETE")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MyexpCmsBundle:Download')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Download entity.
     *
     * @Route("/", name="download_create")
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("POST")
     * @Template("MyexpCmsBundle:Download:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Download();
        $form = $this->createForm(new DownloadType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('download_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Download entity.
     *
     * @Route("/new", name="download_new")
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("GET|POST")
     * @Template()
     */
    public function newAction() {
        $entity = new Download();

        $entity->setIsActive(true);
        $entity->setPublishTime(new \DateTime());
        $form = $this->createForm(new DownloadType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Finds and displays a Download entity.
     * @Route("/view-{id}.html", name="download_show", requirements={"id"="\d+"})
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MyexpCmsBundle:Download')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Download entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Download entity.
     *
     * @Route("/{id}/edit", name="download_edit")
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MyexpCmsBundle:Download')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Download entity.');
        }

        $editForm = $this->createForm(new DownloadType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Download entity.
     *
     * @Route("/{id}", name="download_update")
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("PUT")
     * @Template("MyexpCmsBundle:Download:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MyexpCmsBundle:Download')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Download entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DownloadType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'common.success');

            return $this->redirect($this->generateUrl('download_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Download entity.
     *
     * @Route("/{id}", name="download_delete")
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MyexpCmsBundle:Download')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Download entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('notice', 'common.success');

        return $this->redirect($this->generateUrl('download'));
    }

    /**
     * Creates a form to delete a Download entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {

        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm();
    }

    /**
     * Change download status , active or delete.
     *
     * @Route("/status", name="download_status")
     * @Secure(roles="ROLE_ADMIN_USER")
     * @Method("POST")
     */
    public function statusAction() {

        $ids = $this->getRequest()->get('ids', array());
        $url = $this->getRequest()->get('url');

        $active = $this->getRequest()->get('active', null);
        $deny = $this->getRequest()->get('deny', null);
        $delete = $this->getRequest()->get('delete', null);

        $em = $this->getDoctrine()->getManager();
        $ep = $this->getDoctrine()->getRepository('MyexpCmsBundle:Download');

        foreach ($ids as $id) {
            $download = $ep->find($id);

            if ($active) {
                $download->setIsActive(true);
                $em->persist($download);
            } elseif ($deny) {
                $download->setIsActive(false);
                $em->persist($download);
            } elseif ($delete) {
                $em->remove($download);
            }
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('notice', 'common.success');

        return $this->redirect($url);
    }

    /**
     * Finds and display download entities by category.
     *
     * @Route("/{name}.html", name="download_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction($name) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MyexpCmsBundle:Category')->findOneBy(array(
            'name' => $name
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Download entity.');
        }

        //当前列表的顶级分类
        $topCategory = $entity->getTopCategory();

        //处理该分类下的下载
        $articleRepo = $this->getDoctrine()->getManager()->getRepository('MyexpCmsBundle:Download');
        $params = array(
            'category' => $entity,
            'isActive' => true
        );

        $articleTotal = $articleRepo->getDownloadCount($params);
        $paginator = new Paginator($articleTotal);
        $paginator->setShowLimit(false);

        $sorts = array('a.publishTime' => 'DESC');
        $entities = $articleRepo->getDownloadsWithPagination(
                $params, $sorts, $paginator->getOffset(), $paginator->getLimit()
        );

        return array(
            'entities' => $entities,
            'paginator' => $paginator,
            'category' => $entity,
            'topCategory' => $topCategory,
        );
    }

    /**
     * Finds and display download entities by category.
     *
     * @Route("/down/{id}.html", name="download_down")
     * @Method("GET")
     * @Template()
     */
    public function downAction($id) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('MyexpCmsBundle:Download')
                ->findOneBy(array('id' => $id));
        $filename = $query->geturl();

        //下载的文件重新命名
        list($name, $format) = explode('.', $filename);
        $names = $query->getTitle();
        $time = date('ymdhis');
        $response = new Response();

        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $names . '.' . $format, $time);

        $response->setContent(file_get_contents('../web/upload/download/' . $filename));
        $response->headers->set('Content-Disposition', $d);
        return $response;
    }

}

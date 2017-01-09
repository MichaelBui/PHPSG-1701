<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entity;
use AppBundle\Form\FormType;
use AppBundle\Service\FooService;
use AppBundle\Service\LogService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $entity = new Entity();
        $form = $this->createForm(FormType::class, $entity);
        /** @var LogService $log */
        $log = $this->get('log_service');

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'logs' => $log->read(10),
        ]);
    }

    /**
     * @Route("/", name="upload")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function uploadAction(Request $request)
    {
        /** @var FooService $foo */
        $foo = $this->get('foo_service');
        /** @var LogService $log */
        $log = $this->get('log_service');

        $entity = new Entity();
        $form = $this->createForm(FormType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $log->logSubmitted($entity->getName());
            $foo->process();
            $log->logProcessed($entity->getName());
        }

        return $this->redirect($this->generateUrl('homepage'));
    }
}

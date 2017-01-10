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
     * @throws \HttpInvalidParamException
     */
    public function uploadAction(Request $request)
    {
        $entity = new Entity();
        $form = $this->createForm(FormType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        throw new \HttpInvalidParamException();
    }
}

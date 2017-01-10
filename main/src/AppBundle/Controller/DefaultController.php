<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entity;
use AppBundle\Form\FormType;
use AppBundle\Service\RedisService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

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
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
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
        /** @var RedisService $redis */
        $redis = $this->get('redis_service');
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $entity = new Entity();
        $form = $this->createForm(FormType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setId(uniqid(mt_rand(), true));
            $redis->publish(FormEvents::POST_SUBMIT, $serializer->serialize($entity, 'json'));
            return $this->redirect($this->generateUrl('homepage'));
        }

        throw new \HttpInvalidParamException();
    }
}

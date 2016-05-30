<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $message = new Message();
        $message
            ->setSendAt(new \DateTime())
            ->setIPAdress($request->getClientIp());

        $form = $this->createFormBuilder($message)
            ->add('name', null, ['label' => 'label.name'])
            ->add('content', null, ['label' => 'label.message'])
            ->add('send', SubmitType::class, ['label' => 'label.sumbit'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'status' => 'success',
                    ]);
                } else {
                    return $this->redirectToRoute('homepage');
                }
            } else {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'status' => 'error',
                        'errors' => $form->getErrors(),
                    ]);
                }
            }
        }

        $response = $this->render('@App/frontend/index.html.twig', [
            'dedicaceForm' => $form->createView(),
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }
}

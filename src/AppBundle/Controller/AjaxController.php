<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AjaxController extends Controller
{
    /**
     * @Route("ajax/currentsong.json", name="currentsong")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function currentSongAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $streaminginfo = $this->get('app.streaminginfo');

            $currentsong = $streaminginfo->getCurrentSong();

            $currentsong['callback'] = $currentsong['lifetime'] - time();

            return new JsonResponse($currentsong);

        } else {
            throw $this->createNotFoundException();
        }
    }

    /**
     * @Route("ajax/currentaudience.json", name="currentaudience")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function currentAudienceAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $streaminginfo = $this->get('app.streaminginfo');

            $currentaudience = $streaminginfo->getCurrentAudience();

            $currentaudience['callback'] = $currentaudience['lifetime'] - time();

            return new JsonResponse($currentaudience);
        } else {
            throw $this->createNotFoundException();
        }
    }

    /**
     * @Route("ajax/listenersmessages.json", name="listenersmessages")
     *
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \InvalidArgumentException
     */
    public function listenersMessagesAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_RADIO_HOST');

        if ($request->isXmlHttpRequest()) {

            $last = $request->query->get('last', '');

            if ('' === $last) {
                $lastUpdate = new \DateTime();
                $lastUpdate->sub(new \DateInterval('PT5H'));
            } else {
                $lastUpdate = \DateTime::createFromFormat('d/m/Y H:i:s', $last);
                if (!$lastUpdate || $last !== $lastUpdate->format('d/m/Y H:i:s')) {
                    throw new BadRequestHttpException('The date is not valid');
                }
            }

            $qb = $this->getDoctrine()->getRepository('AppBundle:Message')->createQueryBuilder('m');

            $query = $qb
                ->where($qb->expr()->gt('m.sendAt', ':lastupdate'))
                ->setParameter('lastupdate', $lastUpdate)
                ->orderBy('m.sendAt', 'DESC')
                ->getQuery();

            $type = $request->query->get('type', 'default');

            if ($type === 'html') {
                $messages = $query->getResult();

                $response = '';
                foreach ($messages as $message) {
                    $response .= $this->get('twig')->render('@App/backend/template/message.html.twig', [
                        'message' => $message
                    ]);
                }
            } else {
                $response = $query->getArrayResult();
            }

            return new JsonResponse(array(
                'data' => $response,
                'datetime' => (new \DateTime())->format('d/m/Y H:i:s'),
            ));
        } else {
            throw $this->createNotFoundException();
        }
    }
}
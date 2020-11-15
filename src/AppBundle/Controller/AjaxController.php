<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AjaxController
 * @package AppBundle\Controller
 *
 * @Route("/ajax")
 */
class AjaxController extends Controller
{
    /**
     * @Route("/currentsong.json", name="currentsong")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function currentSongAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $song = $this->get('app.songprovider')->processCurrentSong();

            $result['title'] = $song->getDisplayTitle();
            $result['artist'] = $song->getDisplayArtist();
            $result['lifetime'] = $song->getLifetime();

            $helper = $this->get('vich_uploader.templating.helper.uploader_helper');

            $result['albumcover'] = $helper->asset($song, 'imageFile');

            $result['callback'] = $song->getLifetime() - time();

            return new JsonResponse($result);
        } else {
            throw $this->createNotFoundException();
        }
    }

    /**
     * @Route("/currentaudience.json", name="currentaudience")
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
     * @Route("/listenersmessages.json", name="listenersmessages")
     * @Security("has_role('ROLE_RADIO_HOST')")
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

    /**
     * @Route("/vote", name="ajax_vote")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function voteAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $session = $request->getSession();

        switch ($request->query->get('positive', 'default')) {
            case 'true':
                $isPositive = true;
                break;
            case 'false':
                $isPositive = false;
                break;
            default:
                throw new BadRequestHttpException('Mandatory queries are missing');
        }

        $em = $this->getDoctrine()->getManager();

        $song = $this->get('app.songprovider')->processCurrentSong();

        if ($session->has('lastVote')) {
            $vote = $em->getRepository('AppBundle:Vote')->find($session->get('lastVote'));
            if ($vote && $vote->getSong() === $song) {
                if ($isPositive === $vote->isPositive()) {
                    $em->remove($vote);
                    $response = array(
                        'status' => 'success',
                        'result' => 'cancel',
                    );
                } else {
                    $vote->setPositive($isPositive);
                    $vote->setSendAt(new \DateTime());
                    $em->persist($vote);
                    $response = array(
                        'status' => 'success',
                        'result' => 'change',
                    );
                }
                $em->flush();
                return new JsonResponse($response);
            }
        }

        $iPAddress = $request->getClientIp();

        $vote = new Vote();
        $vote
            ->setIPAddress($iPAddress)
            ->setSong($song)
            ->setPositive($isPositive);

        $em->persist($vote);
        $em->flush();

        $session->set('lastVote', $vote->getId());

        return new JsonResponse(array(
            'status' => 'success',
            'result' => 'new',
        ));
    }
}
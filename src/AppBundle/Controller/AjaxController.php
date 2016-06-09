<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Song;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function currentSongAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $result = array();

            $streaminginfo = $this->get('app.streaminginfo')->getCurrentSong();

            $em = $this->getDoctrine()->getManager();

            $song = $em->getRepository('AppBundle:Song')->findOneBy(array(
                'uid' => $streaminginfo['uid']
            ));

            $helper = $this->get('vich_uploader.templating.helper.uploader_helper');

            if ($song) {
                $result['title'] = $song->getTitle();
                $result['artist'] = $song->getArtist();
            } else {
                $song = new Song();
                $song->setUid($streaminginfo['uid'])
                    ->setTitle($streaminginfo['title'])
                    ->setArtist($streaminginfo['artist']);

                if (!empty($streaminginfo['albumcover'])) {
                    $albumcover = $streaminginfo['albumcover'];
                } else {
                    $albumcover = $this->get('app.albumcover')->getImageOnItunes($song->getTitle(), $song->getArtist());
                }

                if ($albumcover) {

                    $infoFile = new \SplFileInfo($albumcover);
                    $basename = $infoFile->getBasename();

                    $path = $this->getParameter('kernel.cache_dir') . '/' . $basename;

                    file_put_contents($path, file_get_contents($albumcover));

                    $albumcoverFile = new UploadedFile($path, $basename, null, null, null, true);

                    $song->setImageFile($albumcoverFile);


                }

                $em->persist($song);
                $em->flush();
            }

            $result['albumcover'] = $helper->asset($song, 'imageFile');

            $result = array_merge($streaminginfo, $result);

            $result['callback'] = $streaminginfo['lifetime'] - time();

            return new JsonResponse($result);

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
}
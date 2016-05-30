<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
}

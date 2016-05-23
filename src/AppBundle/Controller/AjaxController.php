<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * @Route("ajax/currentsong.json", name="currensong")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function currentSongAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $streaminginfo = $this->get("app.streaminginfo");

            $currentsong = $streaminginfo->getCurrentSong();

            $currentsong['callback'] = $currentsong['lifetime'] - time();

            $response = new JsonResponse();
            $response->setData($currentsong);

            return $response;
        } else {
            return $this->createNotFoundException();
        }
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PanelController extends Controller
{
    /**
     * @Route("/panel/board",name="board")
     * @Security("has_role('ROLE_RADIO_HOST')")
     *
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function boardAction(Request $request)
    {
        return $this->render('@App/backend/board.html.twig');
    }
}

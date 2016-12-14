<?php

namespace ArchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends Controller
{
    public function mainPageAction()
    {
        $this->render("base.html.twig");

    }
    /**
     * @Route("/", name="game_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('game/index.html.twig');
    }




}

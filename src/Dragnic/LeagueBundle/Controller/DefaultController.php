<?php

namespace Dragnic\LeagueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DragnicLeagueBundle:Default:index.html.twig', array('name' => $name));
    }
}

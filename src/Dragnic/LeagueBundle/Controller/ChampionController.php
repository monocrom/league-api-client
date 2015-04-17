<?php
namespace Dragnic\LeagueBundle\Controller;

use Dragnic\LeagueBundle\Repository\Repository;
use Dragnic\LeagueBundle\Rest\Client;
use Symfony\Component\HttpFoundation\Response;

class ChampionController
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function listAction()
    {
        $champions = $this->repository->findAll('champions');

        return new Response(var_export($champions, true));
    }

    public function showAction($id)
    {

    }
}
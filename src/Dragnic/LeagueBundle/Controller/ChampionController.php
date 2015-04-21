<?php
namespace Dragnic\LeagueBundle\Controller;

use Dragnic\LeagueBundle\DragnicLeagueBundle;
use Dragnic\LeagueBundle\Repository\Repository;
use Dragnic\LeagueBundle\Rest\Client;
use Symfony\Component\HttpFoundation\Response;

class ChampionController
{
    const NAME = 'DragnicLeagueBundle:Champion';

    private $twig;
    private $repository;

    public function __construct(\Twig_Environment $twig, Repository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function listAction()
    {
        $champions = $this->repository->findAll('champions');

        foreach ($champions as $champ) {
            var_dump($champ->getImage());die;
        }

        return new Response(
            $this->render(
                'champions',
                array(
                    'champions' => $champions,
                )
            )
        );
    }

    public function showAction($id)
    {

    }

    protected function render($name, array $parameters = array())
    {
        return $this->twig->render(self::NAME . ':' . $name . '.html.twig', $parameters);
    }
}
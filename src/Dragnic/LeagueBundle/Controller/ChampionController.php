<?php
namespace Dragnic\LeagueBundle\Controller;

use Doctrine\ORM\EntityManager;
use Dragnic\LeagueBundle\Entity\Entity;
use Symfony\Component\HttpFoundation\Response;

class ChampionController
{
    const NAME = 'DragnicLeagueBundle:Champion';

    private $twig;
    private $entityManager;

    public function __construct(\Twig_Environment $twig, EntityManager $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    public function listAction()
    {
        $champions = $this->entityManager->getRepository(Entity::NAME)->findAll();

//        var_dump($champions);die;
//
//        foreach ($champions as $champ) {
//            var_dump($champ);die;
//        }

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
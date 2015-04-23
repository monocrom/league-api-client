<?php
namespace Dragnic\LeagueBundle\Command;

use Doctrine\ORM\EntityManager;
use Dragnic\LeagueBundle\Entity\Entity;
use Dragnic\LeagueBundle\Repository\Repository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadLeagueDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('league:load')
            ->setDescription('Loading all api data to the database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $champions = $this->getRepository()->findAll('champions');

        $ids = array();

        $em = $this->getEntityManager();
        /** @var Entity $champion */
        foreach ($champions as $champion) {
            $id = $champion->getInternalId();
            if (array_key_exists($id, $ids)) {
                continue;
            } else {
                $ids[$id] = null;
                $em->persist($champion);
            }
        }
        $em->flush();
    }

    /**
     * @return Repository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('dragnic_league.repository.repository');
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
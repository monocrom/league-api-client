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
    const OPTION_USE_EXISTING = 'use-existing';

    protected function configure()
    {
        $this
            ->setName('league:load')
            ->setDescription('Loading all api data to the database')
            ->addOption(static::OPTION_USE_EXISTING)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $useExisting = $input->getOption(static::OPTION_USE_EXISTING);

        if ($useExisting) {
            $champions = $this->getEntityRepository()->findAll();
        } else {
            $this->getEntityManager()->createQuery('DELETE FROM DragnicLeagueBundle:Entity')->execute();
            $champions = $this->getRepository()->findAll('champions');
        }

        $em = $this->getEntityManager();
        /** @var Entity $champion */
        foreach ($champions as $champion) {
            !$useExisting && $em->persist($champion);
        }
        !$useExisting && $em->flush();
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

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository(Entity::NAME);
    }
}
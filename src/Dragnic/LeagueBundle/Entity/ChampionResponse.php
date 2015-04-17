<?php
namespace Dragnic\LeagueBundle\Entity;

use JMS\Serializer\Annotation\Type;

class ChampionResponse
{
    /**
     * @var  Champion[]
     * @Type("array<Dragnic\LeagueBundle\Entity\Champion>")
     */
    private $champions;

    /**
     * @return Champion[]
     */
    public function getChampions()
    {
        return $this->champions;
    }

    /**
     * @param Champion[] $champions
     */
    public function setChampions($champions)
    {
        $this->champions = $champions;
    }


}
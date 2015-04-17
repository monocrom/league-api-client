<?php
namespace Dragnic\LeagueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation;

/**
 * @ORM\Entity
 */
class Champion
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     * @Annotation\SerializedName("freeToPlay")
     */
    private $freeToPlay;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     * @Annotation\SerializedName("botMmEnabled")
     */
    private $botMmEnabled;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     * @Annotation\SerializedName("botEnabled")
     */
    private $botEnabled;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     * @Annotation\SerializedName("rankedPlayEnabled")
     */
    private $rankedPlayEnabled;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getBotEnabled()
    {
        return $this->botEnabled;
    }

    /**
     * @return mixed
     */
    public function getBotMmEnabled()
    {
        return $this->botMmEnabled;
    }

    /**
     * @return mixed
     */
    public function getFreeToPlay()
    {
        return $this->freeToPlay;
    }

    /**
     * @return mixed
     */
    public function getRankedPlayEnabled()
    {
        return $this->rankedPlayEnabled;
    }
}
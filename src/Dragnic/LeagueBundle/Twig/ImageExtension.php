<?php
namespace Dragnic\LeagueBundle\Twig;

class ImageExtension extends  \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('league_image', array($this, 'getImage'))
        );
    }

    /**
     * @param object $src
     */
    public function getImage($src)
    {
        var_dump($src);die;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'league_image_extension';
    }
}
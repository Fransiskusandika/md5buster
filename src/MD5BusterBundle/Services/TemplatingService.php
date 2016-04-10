<?php
namespace MD5BusterBundle\Services;

use JMS\Serializer\Serializer;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Class TemplatingService
 * @package MD5Buster\Services
 */
class TemplatingService
{
    private $twigEngine;
    private $serializer;

    /**
     * initialize service components
     *
     * @param TwigEngine $twigEngine
     * @param Serializer $serializer
     */
    public function __construct( TwigEngine $twigEngine, Serializer $serializer )
    {
        $this->twigEngine = $twigEngine;
        $this->serializer = $serializer;
    }

    /**
     * render all app templates and serve them as json array
     *
     * @return mixed
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function compileTemplates()
    {
        $twigEngine = $this->twigEngine;
        $data = [
            'appHeader' => $twigEngine->render( '@MD5Buster/templates/header/_header.html.twig' ),
            'footNote' => $twigEngine->render( '@MD5Buster/templates/footnote/_default.html.twig' ),
            'decryptPage' => $twigEngine->render( '@MD5Buster/templates/decrypt/_decrypt_page.html.twig' )
        ];

        return $this->serializer->serialize( $data, 'json' );
    }
} 
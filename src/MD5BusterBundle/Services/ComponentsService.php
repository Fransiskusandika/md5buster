<?php
namespace MD5BusterBundle\Services;

use JMS\Serializer\Serializer;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Class TemplatingService
 * @package MD5Buster\Services
 */
class ComponentsService
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
     * combine all components and serialize them
     *
     * @return mixed|string
     */
    public function getComponents()
    {
        $components = [
            'templates' => $this->compileTemplates(),
            'translations' => $this->getTranslations()
        ];

        return $this->serializer->serialize( $components, 'json' );
    }

    /**
     * render all app templates
     *
     * @return array
     * @throws \Exception
     * @throws \Twig_Error
     */
    private function compileTemplates()
    {
        $twigEngine = $this->twigEngine;
        $data = [
            'appHeader' => $twigEngine->render( '@MD5Buster/templates/header/_header.html.twig' ),
            'footNote' => $twigEngine->render( '@MD5Buster/templates/footnote/_default.html.twig' ),
            'decryptPage' => $twigEngine->render( '@MD5Buster/templates/decrypt/_decrypt_page.html.twig' )
        ];

        return $data;
    }

    /**
     * get all translations
     *
     * @return array
     */
    private function getTranslations() // todo: decide if translations should be kept in db
    {
        $data = [
            'Decrypt' => [
                'us_uk' => 'Decrypt',
                'ro' => 'Decripteaza'
            ],
            'decrypt' => [
                'us_uk' => 'decrypt',
                'ro' => 'decripteaza'
            ],
            'Encrypt' => [
                'us_uk' => 'Encrypt',
                'ro' => 'Encripteaza'
            ],
            'dp.si' => [
                'us_uk' => 'Short introduction to MD5',
                'ro' => 'Scurta introducere in MD5'
            ],
            'dp.wq' => [
                'us_uk' =>
                    "The MD5 message-digest algorithm is a widely used cryptographic hash function producing a 128-bit " .
                    "(16-byte) hash value, typically expressed in text format as a 32-digit hexadecimal number.\n" .
                    "MD5 has been utilized in a wide variety of cryptographic applications and is also commonly used to verify data integrity.\n" .
                    "MD5 is a one-way function; it is neither encryption nor encoding. It cannot be reversed other than by brute-force attack.",
                'ro' =>
                    "Algoritmul de tip \"rezumat\", MD5, este o functie criptografica utilizata pe scara larga care produce " .
                    "o valoare hash-uita de 128 biti (16 bytes), reprezentata de regula intr-un format de tip text compus din 32 caractere hexazecimale.\n" .
                    "MD5 a fost folosit intr-o varietate de aplicatii criptografice si este de asemenea folosit pentru verificarea integritatii datelor.\n" .
                    "MD5 este o functie cu sens unic; nu este nici encriptare, nici encodare. Nu poate fi inversata decat prin atac de tip \"brute-force\"."
            ],
            'dp.qf' => [
                'us_uk' => 'Quoted from',
                'ro' => 'Citat din'
            ],
            'bd.tdp' => [
                'us_uk' => 'The decryption process',
                'ro' => 'Procesul de decriptare'
            ]
        ];

        return $data;
    }
} 
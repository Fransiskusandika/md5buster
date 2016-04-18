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
            'footer' => $twigEngine->render( '@MD5Buster/templates/footer/_footer.html.twig' ),
            'footNote' => $twigEngine->render( '@MD5Buster/templates/footnote/_default.html.twig' ),
            'decryptPage' => $twigEngine->render( '@MD5Buster/templates/decrypt/_decrypt_page.html.twig' ),
            'encryptPage' => $twigEngine->render( '@MD5Buster/templates/encrypt/_encrypt_page.html.twig' ),
            'decryptionResultItemView' => $twigEngine->render( '@MD5Buster/templates/decrypt/decryption_result_item_view.html.twig' ),
            'contactPage' => $twigEngine->render( '@MD5Buster/templates/contact/_contact_page.html.twig' ),
            'cookiePage' => $twigEngine->render( '@MD5Buster/templates/cookie/_cookie_page.html.twig' )
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
                'ro' => 'Decripteaz&abreve;'
            ],
            'decrypt' => [
                'us_uk' => 'decrypt',
                'ro' => 'decripteaz&abreve;'
            ],
            'Encrypt' => [
                'us_uk' => 'Encrypt',
                'ro' => 'Encripteaz&abreve;'
            ],
            'Encryption' => [
                'us_uk' => 'Encryption',
                'ro' => 'Encriptare'
            ],
            'encrypt' => [
                'us_uk' => 'encrypt',
                'ro' => 'encripteaz&abreve;'
            ],
            'Results' => [
                'us_uk' => 'Results',
                'ro' => 'Rezultate'
            ],
            'sh' => [
                'us_uk' => 'Free online tool for decrypting/encrypting the well-known cryptographic hash function',
                'ro' => 'Aplica&tcedil;ie online gratuit&abreve; pentru decriptarea/encriptarea cunoscutei func&tcedil;ii criptografice'
            ],
            'dp.si' => [
                'us_uk' => 'Short introduction to MD5',
                'ro' => 'Scurt&abreve; introducere &icirc;n MD5'
            ],
            'dp.wq' => [
                'us_uk' =>
                    "The MD5 message-digest algorithm is a widely used cryptographic hash function producing a 128-bit " .
                    "(16-byte) hash value, typically expressed in text format as a 32-digit hexadecimal number.\n" .
                    "MD5 has been utilized in a wide variety of cryptographic applications and is also commonly used to verify data integrity. " .
                    "It's a one-way function; it is neither encryption nor encoding. It cannot be reversed other than by brute-force attack.",
                'ro' =>
                    "Algoritmul de tip \"rezumat\", MD5, este o func&tcedil;ie criptografic&abreve; utilizat&abreve; pe scar&abreve; larg&abreve; care produce " .
                    "o valoare hash-uit&abreve; de 128 biti (16 bytes), reprezentat&abreve; de regul&abreve; printr-un format de tip text compus din 32 caractere hexazecimale.\n" .
                    "MD5 a fost folosit &icirc;ntr-o varietate de aplica&tcedil;ii criptografice si este de asemenea folosit pentru verificarea integrit&abreve;&tcedil;ii datelor. " .
                    "Este o func&tcedil;ie cu sens unic; nu este nici encriptare, nici encodare. Nu poate fi inversat&abreve; dec&acirc;t prin atac de tip \"brute-force\"."
            ],
            'dp.qf' => [
                'us_uk' => 'Quoted from',
                'ro' => 'Citat din'
            ],
            'dp.ld.p1' => [
                'us_uk' => 'I know what you\'re thinking, how can we decrypt something that\'s irreversible?',
                'ro' => '&Scedil;tiu la ce te g&acirc;nde&scedil;ti, cum s&abreve; decript&abreve;m ceva generat cu o func&tcedil;ie care nu poate fi inversat&abreve;?'
            ],
            'dp.ld.p2' => [
                'us_uk' => 'Brute force? No, it would take too long!',
                'ro' => 'Brute force? Nu, ar dura prea mult!'
            ],
            'dp.ld.p3' => [
                'us_uk' =>
                    'Instead, we\'ll search in our continuously growing database&mdash;even as you are reading ' .
                    'these lines, it\'s growing! In case we don\'t find your hash, it doesn\'t hurt to come back later ' .
                    'and try again&mdash;in the meantime we could have acquired your desired decryprion.',
                'ro' =>
                    '&Icirc;n schimb, vom c&abreve;uta &icirc;n baza noastr&abreve; de date, care este in continu&abreve; cre&scedil;tere&mdash;chiar &icirc;n timp ce ' .
                    'tu cite&scedil;ti aceste r&acirc;nduri ea cre&scedil;te! &Icirc;n cazul &icirc;n care nu am g&abreve;sit hash-ul t&abreve;u, nu stric&abreve; s&abreve; revii ' .
                    'peste un timp &scedil;i s&abreve; mai &icirc;ncerci odat&abreve;&mdash;&icirc;ntre timp fiind posibil s&abreve; fi ob&tcedil;inut decriptarea dorit&abreve;.'
            ],
            'dp.ph' => [
                'us_uk' => 'Paste your MD5 hash here',
                'ro' => 'Lipe&scedil;te hash-ul MD5 aici'
            ],
            'dp.imh' => [
                'us_uk' => 'Not a valid md5 hash',
                'ro' => 'Nu este un hash md5 valid'
            ],
            'dp.csc' => [
                'us_uk' => 'Security check not completed',
                'ro' => 'Nu a&tcedil;i completat verificarea de securitate'
            ],
            'dp.ta' => [
                'us_uk' => 'Try again',
                'ro' => 'Mai &icirc;ncearc&abreve; odat&abreve;'
            ],
            'dp.ns' => [
                'us_uk' => 'New search',
                'ro' => 'C&abreve;utare nou&abreve;'
            ],
            'dp.swr' => [
                'us_uk' => 'Someting went wrong!',
                'ro' => 'Ceva nu a mers bine!'
            ],
            'dp.nrf' => [
                'us_uk' => 'Sorry! We didn\'t find the hash in our database.',
                'ro' => 'Ne pare r&abreve;u! Nu am g&abreve;sit hash-ul &icirc;n baza noastr&abreve; de date.'
            ],
            'dp.sc' => [
                'us_uk' => 'Complete security check',
                'ro' => 'Completeaz&abreve; verificarea de securitate'
            ],
            'dp.ctc' => [
                'us_uk' => 'Copy to clipboard',
                'ro' => 'Copia&tcedil;i &icirc;n clipboard'
            ],
            'ep.tte' => [
                'us_uk' => 'Text to encrypt',
                'ro' => 'Text de encriptat'
            ],
            'ep.te' => [
                'us_uk' => 'Text to encrypt field is empty',
                'ro' => 'C&acirc;mpul pentru textul de encryptat este gol'
            ],
            'bd.ld' => [
                'us_uk' => 'Let\'s decrypt!',
                'ro' => 'Hai s&abreve; decript&abreve;m!'
            ],
            'ep.le' => [
                'us_uk' => 'Let\'s encrypt!',
                'ro' => 'Hai s&abreve; encript&abreve;m!'
            ],
            'ep.ne' => [
                'us_uk' => 'new encryption',
                'ro' => 'encriptare nou&abreve;'
            ],
            'cm' => [
                'us_uk' =>
                    "This site uses cookies in order to improve your experience.\n" .
                    "By continuing to browse the site you are agreeing to our use of cookies.",
                'ro' =>
                    "Acest site foloseste cookies pentru a-ti imbunatatii experienta de navigare.\n" .
                    "Continuand navigarea iti exprimi acordul in privinta folosirii lor."
            ],
            'cp.cu' => [
                'us_uk' => 'Contact us',
                'ro' => 'Contacteaz&abreve;-ne'
            ],
            'cp.tyfyf' => [
                'us_uk' => 'Thank you for your feedback!',
                'ro' => 'Mul&tcedil;umim pentru feedback!'
            ],
            'cp.n' => [
                'us_uk' => 'Name',
                'ro' => 'Nume'
            ],
            'cp.s' => [
                'us_uk' => 'Send',
                'ro' => 'Trimite'
            ],
            'cp.ie' => [
                'us_uk' => 'Invalid email',
                'ro' => 'Email invalid'
            ],
            'cp.ne' => [
                'us_uk' => 'Name field empty',
                'ro' => 'C&acirc;mpul pentru nume este gol'
            ],
            'cp.psf' => [
                'us_uk' => 'Feedback field empty',
                'ro' => 'C&acirc;mpul pentru feedback este gol'
            ],
            'f.pb' => [
                'us_uk' => 'powered by',
                'ro' => 'creat de'
            ]
        ];

        return $data;
    }
} 
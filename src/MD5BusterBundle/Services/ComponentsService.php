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
            'ep.p1' => [
                'us_uk' => 'Whether you want to check the integrity of a file or convert something to a md5 hash, this is where you can do it!',
                'ro' => 'Fie c&abreve; vrei s&abreve; verifici integritatea unui fi&scedil;ier sau s&abreve; transformi ceva &icirc;ntr-un hash md5, ' .
                    'aici o po&tcedil;i face!'
            ],
            'ep.p2' => [
                'us_uk' => 'We recommend you don\'t use the hash in a public environment&mdash;like a website, if it contains sensible information.',
                'ro' => 'Recomand&abreve;m s&abreve; nu folose&scedil;ti hash-ul &icirc;ntr-un mediu public&mdash;un website de exemplu, dac&abreve; ' .
                    'con&tcedil;ine informa&tcedil;ii importante.'
            ],
            'ep.p3' => [
                'us_uk' =>
                    'There\'s no special techniques involved here, we just pass your text to the md5 function and return the result back to you. ' .
                    'So go ahead and use the form below to obtain the hash and don\'t forget to copy it if you know you\'ll need it for later use.',
                'ro' =>
                    'Aici nu aplic&abreve;m vreo tehnic&abreve; special&abreve;, doar lu&abreve;m textul introdus de tine, aplic&abreve;m ' .
                    'func&tcedil;ia md5 &scedil;i &icirc;&tcedil;i ar&abreve;t&abreve;m rezultatul ob&tcedil;inut. A&scedil;a c&abreve; ' .
                    'folose&scedil;te formularul de mai jos pentru a ob&tcedil;ine hash-ul dorit &scedil;i nu uita s&abreve; &icirc;l copiezi dac&abreve; ' .
                    '&scedil;tii c&abreve; &icirc;&tcedil;i va trebui mai t&acirc;rziu.'
            ],
            'cm' => [
                'us_uk' =>
                    "This site uses cookies in order to improve your experience.\n" .
                    "By continuing to browse the site you are agreeing to our use of cookies",
                'ro' =>
                    "Acest site folose&scedil;te cookies pentru a-&tcedil;i &icirc;mbun&abreve;t&abreve;&tcedil;ii experien&tcedil;a de navigare.\n" .
                    "Continu&acirc;nd navigarea i&tcedil;i exprimi acordul &icirc;n privin&tcedil;a folosirii lor"
            ],
            'cm.l' => [
                'us_uk' =>
                    "&mdash;more info",
                'ro' =>
                    "&mdash;mai multe informa&tcedil;ii"
            ],
            'cp.cu' => [
                'us_uk' => 'Contact us!',
                'ro' => 'Contacteaz&abreve;-ne!'
            ],
            'cp.p1' => [
                'us_uk' => 'We are constantly improving and adapting the site so our users can get the most out of it.',
                'ro' =>
                    '&Icirc;mbun&abreve;t&abreve;&tcedil;im &scedil;i adapt&abreve;m &icirc;n mod continuu site-ul astfel ' .
                    '&icirc;nc&acirc;t utilizatorii no&scedil;trii sa poat&abreve; profita la maxim de el.'
            ],
            'cp.p2' => [
                'us_uk' =>
                    'If you think something needs improving or you would like a certain feature to be available, ' .
                    'don\'t hesitate to le us know!',
                'ro' =>
                    'Dac&abreve; crezi c&abreve; ceva ar putea fi &icirc;mbun&abreve;t&abreve;&tcedil;it sau &tcedil;i-ar place ' .
                    's&abreve; fie disponibil&abreve; o anumit&abreve; func&tcedil;ionalitate, nu ezita s&abreve; ne anun&tcedil;i!'
            ],
            'cp.p3' => [
                'us_uk' => 'We look forward to hearing from you! &#9786;',
                'ro' => 'Abia a&scedil;tept&abreve;m s&abreve; auzim de la tine! &#9786;'
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
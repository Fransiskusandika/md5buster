<?php
namespace MD5BusterBundle\Services;

use Doctrine\ORM\EntityManager;
use Lsw\ApiCallerBundle\Call\HttpPostJson;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class TemplatingService
 * @package MD5Buster\Services
 */
class MD5Service
{
    private $container;
    private $em;

    /**
     * @param Container $container
     * @param EntityManager $em
     */
    public function __construct( Container $container, EntityManager $em )
    {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * @param $securityCode
     * @return bool
     */
    public function googleRecaptchaSecurityCheck( $securityCode )
    {
        /** @noinspection PhpParamsInspection */
        $output = $this->container->get('api_caller')->call(
            new HttpPostJson(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => $this->container->getParameter('recaptcha_secret_key'),
                    'response' => $securityCode
                ],
                true // as associative array
            )
        );
        var_dump($output);die;
        if( array_key_exists( 'success', $output  ) && $output['success'] == true ){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $string
     * @return string
     */
    private function safeText( $string )
    {
        return is_string( $string ) ? html_entity_decode( strip_tags( trim( $string ) ), ENT_HTML5 ) : $string;
    }

    /**
     * @param $string
     * @return bool
     */
    public function isValidEmailString( $string )
    {
        return preg_match( '/^([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/', $string ) != 0;
    }

    /**
     * @param ParameterBag $bag
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function sendFeedbackEmail( ParameterBag $bag )
    {
        $email = \Swift_Message::newInstance()
            ->setSubject( 'MD5 Buster Contact Form')
            ->setFrom('no-reply@md5buster.com')
            ->setTo( $this->container->get('twig')->getGlobals()['owner_email'] )
            ->setBody(
                $this->container->get('templating')->render(
                    '@MD5Buster/templates/email/contact_form.html.twig',
                    [
                        'sentAt' => new \DateTime(),
                        'name' => $this->safeText( $bag->get('name') ),
                        'email' => $this->safeText( $bag->get('email') ),
                        'feedback' => $this->safeText( $bag->get('feedback') )
                    ]
                )
            )
            ->setContentType('text/html')
        ;
        /** @noinspection PhpParamsInspection */
        $this->container->get('mailer')->send($email);
    }

    /**
     * @param $hash
     * @return array
     */
    public function decryptHash( $hash )
    {
        return $this->em
            ->getRepository( 'MD5BusterBundle:MD5Decryption' )
            ->createQueryBuilder( 'd' )
            ->select( 'd.decryption' )
            ->where( 'd.hash = :hash' )
            ->setParameter( 'hash', $hash )
            ->getQuery()->getResult()
        ;
    }
} 
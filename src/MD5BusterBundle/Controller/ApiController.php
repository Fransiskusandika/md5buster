<?php

namespace MD5BusterBundle\Controller;

use MD5BusterBundle\Entity\MD5Decryption;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * Api templates
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function componentsAction()
    {
        $response = new Response( $this->get('md5buster.api.components')->getComponents(), 200 );
        /** 90 days */
        $response->setSharedMaxAge( 7776000 );
        $response->setMaxAge( 0 );
        $response->headers->set( 'Content-Type', 'application/json' );

        return $response;
    }

    /**
     * @param $text
     * @return array
     */
    private function createErrorResponseArray( $text )
    {
        return [
            'status' => 'error',
            'text' => $text,
            'code' => 400
        ];
    }

    /**
     * @param $payload
     * @return array
     */
    private function createSuccessResponseArray( $payload )
    {
        return [
            'status' => 'success',
            'payload' => $payload,
            'code' => 200
        ];
    }

    /**
     * decrypt action
     *
     * @param Request $request
     * @return Response
     */
    public function decryptAction( Request $request )
    {
        if ( $request->isMethod( 'POST' ) ) {
            $hash = $request->get('hash');
            if ( preg_match( '/^[a-z0-9]+$/i', $hash ) && strlen( $hash ) == 32 ) {
                $securityCode = $request->get('securityCode');
                $md5 = $this->get('md5buster.api.md5');
                if ( $md5->googleRecaptchaSecurityCheck( $securityCode ) ) {
                    $payload = $md5->decryptHash( $hash );
                    $responseData = $this->createSuccessResponseArray( $payload );
                } else {
                    $responseData = $this->createErrorResponseArray( 'Security check failed' );
                }
            } else {
                $responseData = $responseData = $this->createErrorResponseArray( 'Invalid md5 hash' );
            }
        } else {
            $responseData = $responseData = $this->createErrorResponseArray( 'Invalid request method' );
        }
        $response = new JsonResponse(
            $responseData, $responseData['code']
        );
        $response->headers->set( 'Content-Type', 'application/json' );

        return $response;
    }

    /**
     * decrypt action
     *
     * @param Request $request
     * @return Response
     */
    public function encryptAction( Request $request )
    {
        if ( $request->isMethod( 'POST' ) ) {
            $text = $request->get('text');
            if ( is_string( $text ) && strlen( $text ) > 0 ) {
                $securityCode = $request->get('securityCode');
                $md5 = $this->get('md5buster.api.md5');
                if ( $md5->googleRecaptchaSecurityCheck( $securityCode ) ) {
                    $hash = md5( $text );
                    $em = $this->getDoctrine()->getManager();
                    $decryptions = $em->getRepository('MD5BusterBundle:MD5Decryption')->findBy([ 'hash' => $hash ]);
                    $shouldAdd = false;
                    foreach( $decryptions as $decryption ){
                        if ( $decryption->getDecryption() != $text ){
                            $shouldAdd = true;
                        }
                    }
                    if( $shouldAdd == true || count( $decryptions ) == 0 ){
                        $md5Decryption = new MD5Decryption();
                        $md5Decryption
                            ->setHash( $hash )
                            ->setDecryption( $text )
                            ->setUserAdded( true )
                        ;
                        $em->persist( $md5Decryption );
                        $em->flush();
                    }
                    $payload = [ [ 'decryption' => $hash ] ]; // i'm lazy for using the same collection view, i know :)
                    $responseData = $this->createSuccessResponseArray( $payload );
                } else {
                    $responseData = $this->createErrorResponseArray( 'Security check failed' );
                }
            } else {
                $responseData = $responseData = $this->createErrorResponseArray( 'Invalid text content' );
            }
        } else {
            $responseData = $responseData = $this->createErrorResponseArray( 'Invalid request method' );
        }
        $response = new JsonResponse(
            $responseData, $responseData['code']
        );
        $response->headers->set( 'Content-Type', 'application/json' );

        return $response;
    }

    /**
     * feedback action
     *
     * @param Request $request
     * @return Response
     */
    public function feedbackAction( Request $request )
    {
        if ( $request->isMethod( 'POST' ) ) {
            $name = $request->get('name');
            $email = $request->get('email');
            $feedback = $request->get('feedback');
            if ( is_string( $name ) && strlen( $name ) > 0 && is_string( $email ) && strlen( $email ) > 0 && is_string( $feedback ) &&  strlen( $feedback ) > 0  ) {
                $md5 = $this->get('md5buster.api.md5');
                if( $md5->isValidEmailString( $email ) ){
                    $securityCode = $request->get('securityCode');
                    if ( $md5->googleRecaptchaSecurityCheck( $securityCode ) ) {
                        $md5->sendFeedbackEmail( new ParameterBag([
                            'name' => $name,
                            'email' => $email,
                            'feedback' => $feedback
                        ]));
                        $responseData = $this->createSuccessResponseArray( [] );
                    } else {
                        $responseData = $this->createErrorResponseArray( 'Security check failed' );
                    }
                } else {
                    $responseData = $responseData = $this->createErrorResponseArray( 'Invalid email' );
                }
            } else {
                $responseData = $responseData = $this->createErrorResponseArray( 'One or more form fields are empty' );
            }
        } else {
            $responseData = $responseData = $this->createErrorResponseArray( 'Invalid request method' );
        }
        $response = new JsonResponse(
            $responseData, $responseData['code']
        );
        $response->headers->set( 'Content-Type', 'application/json' );

        return $response;
    }
}

<?php

namespace MD5BusterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * Api templates
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function templatesAction()
    {
        $response = new Response( $this->get('md5buster.api.templating')->compileTemplates(), 200 );
        /** 90 days */
        $response->setSharedMaxAge( 7776000 );
        $response->setMaxAge( 0 );
        $response->headers->set( 'Content-Type', 'application/json' );

        return $response;
    }
}

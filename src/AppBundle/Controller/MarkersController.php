<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Doctrine\Common\Util\Debug;


class MarkersController extends Controller
{
    /**
     * @Route("/markers", name="markers")
     */
    public function markersAction() {

    	return $this->render('Markers/index.html.twig');
    }

}
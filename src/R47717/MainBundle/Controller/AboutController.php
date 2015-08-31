<?php

namespace R47717\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AboutController extends Controller
{
    /**
     * @Route("/about", name="about")
     */
    public function indexAction() {
        

        return $this->render('R47717MainBundle:About:index.html.twig');    
    }

}

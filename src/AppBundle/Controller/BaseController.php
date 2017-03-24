<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
	public function em() {
		return $this->getDoctrine()->getManager();
	}

	public function tasks() {
		return $this->getDoctrine()->getManager()->getRepository("AppBundle:Task");
	}

	public function categories() {
		return $this->getDoctrine()->getManager()->getRepository("AppBundle:Category");
	}
}

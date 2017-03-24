<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use \Doctrine\Common\Util\Debug;


class SearchController extends BaseController 
{

	/**
	 * @Route("/search", name="search")
	 */
	public function searchAction(Request $request) {

		$form = $this->createFormBuilder()
		    ->setAction($this->generateUrl('search'))
            ->setMethod('POST')
		    ->add('search_str', 'text', ['attr' => [
                'required' => false,
                'class' => 'form-control str-search',
                'placeholder' => 'Search...',

            ]])
            ->add('search_btn', 'submit', ['label' => 'Go', 'attr' => ['class' => 'btn-search']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

        	$data = $form->getData();
        	$entities = $this->tasks()->getSearchItems($data['search_str']);

			return $this->render('Search/index.html.twig', [
				'entities' => $entities,
			]);

        }

    	return $this->render('Search/form.html.twig', [
			'form' => $form->createView(),
		]);
	}
}



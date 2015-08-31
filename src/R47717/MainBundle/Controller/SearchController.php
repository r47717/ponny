<?php
namespace R47717\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use R47717\MainBundle\Entity\Task;
use R47717\MainBundle\Form\TaskType;
use \Doctrine\Common\Util\Debug;


class SearchController extends Controller {

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
        	$entities = $this->getSearchItems($data['search_str']);

			return $this->render('R47717MainBundle:Search:index.html.twig', [
				'entities' => $entities,
			]);

        } else {

        	return $this->render("R47717MainBundle:Search:search-form.html.twig", [
				'form' => $form->createView(),
			]);
        }
	}

	public function getSearchItems($search_str) {

		$entities = $this->getDoctrine()->getManager()->getRepository('R47717MainBundle:Task')->findAll();

		$items = [];
		foreach ($entities as $task) {
			if (strpos($task->getTask(), $search_str) !== false ) {
				$items[] = $task;
			}
		}

		return $items;
	}
}



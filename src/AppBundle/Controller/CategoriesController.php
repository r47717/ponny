<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Category;
use \Doctrine\Common\Util\Debug;


class CategoriesController extends Controller
{
    private $categories = [];
    /**
     * @Route("/categories", name="categories")
     */
    public function indexAction(Request $request) {

        $addForm = $this->createFormBuilder()
        	->add('name', 'text')
        	->add('submit', 'submit', ['label' => 'Add'])
        	->getForm();

        $addForm->handleRequest($request);

        if ($addForm->isSubmitted()) {
        	$name = $addForm->getData()['name'];

        	if ($this->categoryExists($name)) {
        		$this->addFlash('error', "Error: category '$name' already exists");
        		return $this->redirectToRoute('categories');
        	}
        	
            $cat = new Category();
        	$cat->setName($name);
        	$em = $this->getDoctrine()->getManager();
            $em->persist($cat);
        	$em->flush();
        	$this->addFlash('notice', "Notice: new Category '$name' has been added");
        	return $this->redirectToRoute('categories');

        } else {

        }

        $rep = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');
        $categories = $rep->tasksCount();
 
        return $this->render('Categories/index.html.twig', [
        	'add_form' => $addForm->createView(),
        	'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categories/delete/{id}", name="category_delete")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Category')->findOneById($id);
 
        if ($entity) {
            $em->remove($entity);
            $em->flush();
            $this->addFlash('notice', "Notice: category '" . $entity->getName() . "' has been deleted");
        }
    	
        return $this->redirectToRoute('categories');
    }


    public function categoryExists($name) {
        $entities = $this->getDOctrine()->getManager()->getRepository('AppBundle:Category')->findByName($name);
    	return !empty($entities);
    }

    public function countTasksInCategory($id) {
        $em = $this->getDoctrine()->getManager();
/*        $qb = $em->createQueryBuilder()
            ->
*/    }

    /**
     * @Route("/markers", name="markers")
     */
    public function markersAction() {

    	return $this->render('Categories/markers.html.twig');
    }

}
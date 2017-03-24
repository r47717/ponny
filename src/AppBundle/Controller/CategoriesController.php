<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Category;
use \Doctrine\Common\Util\Debug;


class CategoriesController extends BaseController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function indexAction(Request $request) {

        $addForm = $this->createFormBuilder()
        	->add('name', 'text', [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display: inline-block; width: 250px',
                ],
            ])
        	->add('submit', 'submit', [
                'label' => 'Add',
                'attr' => [
                    'class' => 'btn-sm btn-success',
                ],
            ])
        	->getForm();

        $addForm->handleRequest($request);

        if ($addForm->isSubmitted()) {
        	$name = $addForm->getData()['name'];

        	if ($this->categories()->categoryExists($name)) {
        		$this->addFlash('error', "Error: category '$name' already exists");
        		return $this->redirectToRoute('categories');
        	}
        	
            $cat = new Category();
        	$cat->setName($name);
            $this->em()->persist($cat);
        	$this->em()->flush();
        	$this->addFlash('notice', "Notice: new Category '$name' has been added");
        	return $this->redirectToRoute('categories');

        }

        $categories = $this->categories()->tasksCount();
 
        return $this->render('Categories/index.html.twig', [
        	'add_form' => $addForm->createView(),
        	'categories' => $categories,
        ]);
    }


    /**
     * @Route("/categories/delete/{id}", name="category_delete")
     */
    public function deleteAction($id) {
        $entity = $this->categories()->findOneById($id);
 
        if ($entity) {
            $this->em()->remove($entity);
            $this->em()->flush();
            $this->addFlash('notice', "Notice: category '" . $entity->getName() . "' has been deleted");
        }
    	
        return $this->redirectToRoute('categories');
    }


    /**
     * @Route("/markers", name="markers")
     */
    public function markersAction() {

    	return $this->render('Categories/markers.html.twig');
    }

}
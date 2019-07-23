<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @IsGranted("ROLE_USER")
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="categorie_index")
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'title' => 'Catégorie'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="categorie_new")
     * @Route("/edit/{id}", name="categorie_edit")
     */
    public function new_edit(Categorie $categorie = null, Request $request,ObjectManager $manager): Response
    {
        if(!$categorie){
            $categorie = new Categorie();
        }
        
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($categorie);
            $manager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new_edit.html.twig', [
            'editMode' => $categorie-> getId() !== null,
            'form' => $form->createView(),
            'title' => 'Catégorie'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'title' => 'Catégorie'
             
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
        
    }
}

<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Form\AtelierType;

use App\Repository\AtelierRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @IsGranted("ROLE_USER")
 * @Route("/atelier")
 */
class AtelierController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="atelier_index")
     */
    public function index(AtelierRepository $atelierRepository): Response
    {
        return $this->render('atelier/index.html.twig', [
            'ateliers' => $atelierRepository->findAll(),
            'title' => 'Atelier'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="atelier_new")
     * @Route("/edit/{id}", name="atelier_edit")
     */
    public function new_edit(Atelier $atelier = null, Request $request,ObjectManager $manager): Response
    {
        if(!$atelier){
            $atelier = new Atelier();
        }
        
        $form = $this->createForm(AtelierType::class, $atelier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($atelier);
            $manager->flush();

            return $this->redirectToRoute('atelier_index');
        }

        return $this->render('atelier/new_edit.html.twig', [
            'editMode' => $atelier-> getId() !== null,
            'form' => $form->createView(),
            'title' => 'Atelier'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="atelier_show", methods={"GET"})
     */
    public function show(Atelier $atelier): Response {
        return $this->render('atelier/show.html.twig', [
            'atelier' => $atelier,
            'title' => 'Atelier'
             
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="atelier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Atelier $atelier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$atelier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($atelier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('atelier_index');
        
    }
}

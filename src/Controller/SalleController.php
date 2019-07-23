<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/salle")
 */
class SalleController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="salle_index", methods={"GET"})
     */
    public function index(SalleRepository $salleRepository): Response
    {
        return $this->render('salle/index.html.twig', [
            'salles' => $salleRepository->findAll(),
            'title' => 'Salle'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="salle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $this->addFlash('success', 'Vous avez bien crée une salle');

            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('salle_index');
        }

        return $this->render('salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form->createView(),
            'title' => 'Salle'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="salle_show", methods={"GET"})
     */
    public function show(Salle $salle): Response
    {
        return $this->render('salle/show.html.twig', [
            'salle' => $salle,
            'title' => 'Salle'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/edit/{id}", name="salle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Salle $salle): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Vous avez bien modifié la salle');

            $entityManager->flush();

            return $this->redirectToRoute('salle_index');
        }

        return $this->render('salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form->createView(),
            'title' => 'Salle'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="salle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Salle $salle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $this->addFlash('success', 'Vous avez bien supprimé la salle');

            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('salle_index');
    }
}

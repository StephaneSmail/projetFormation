<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

// Include Dompdf required namespaces
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/stagiaire")
 */
class StagiaireController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="stagiaire_index")
     */
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaireRepository->findAll(),
            'title' => 'Stagiaire'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="stagiaire_new")
     */
    public function new(Request $request,ObjectManager $manager): Response {
        $stagiaire = new Stagiaire();
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien crée un stagiaire');

            return $this->redirectToRoute('stagiaire_index');
        }

        return $this->render('stagiaire/new.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form->createView(),
            'title' => 'Stagiaire'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="stagiaire_show", methods={"GET"})
     */
    public function show(Stagiaire $stagiaire): Response {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
            'title' => 'Stagiaire'
             
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/edit/{id}", name="stagiaire_edit")
     */
    public function edit(Request $request, Stagiaire $stagiaire, ObjectManager $manager): Response
    {
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('success', 'Vous avez bien modifié ce stagiaire');

            return $this->redirectToRoute('stagiaire_index', [
                'id' => $stagiaire->getId(),
            ]);
        }

        return $this->render('stagiaire/edit.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form->createView(),
            'title' => 'Stagiaire'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="stagiaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Stagiaire $stagiaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stagiaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stagiaire);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien supprimé ce stagiaire');
        }

        return $this->redirectToRoute('stagiaire_index');
        
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/pdf/{id}/session/{id_session}", name="stagiaire_pdf", methods={"GET"})
     */
    public function pdf(Stagiaire $stagiaire)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('stagiaire/mypdf.html.twig', [
            'stagiaire' => $stagiaire,

        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
}

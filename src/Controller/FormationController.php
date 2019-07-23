<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Duree;
use App\Entity\Atelier;
use App\Entity\Formation;
use App\Form\AteliersType;
use App\Form\FormationType;




// Include Dompdf required namespaces
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/formation")
 */
class FormationController extends AbstractController

{
    /**
     * @Route("/", name="formation_index")
     */
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="formation_new")
     */
    public function new(Request $request,ObjectManager $manager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);

            $atelier = $form->get('atelier')->getData();
            $nbJours = $form->get('nbJours')->getData();
            $duree = new Duree();
            $duree->setNbJour($nbJours)
                    ->setFormations($formation)
                    ->setAteliers($atelier);

            $entityManager->persist($duree);
            
       
            
            $entityManager->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formation_show", methods={"GET"})
     */
    public function show(Formation $formation): Response{


        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
             
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/edit/{id}", name="formation_edit")
     */
    public function edit(Request $request, Formation $formation, ObjectManager $manager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

           
            $manager->flush();
           
           
            


            return $this->redirectToRoute('formation_index', [
                'id' => $formation->getId(),
            ]);
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="formation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formation_index');
        
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/pdf/{id}", name="formation_pdf", methods={"GET"})
     */
    public function formationPdf(formation $formation)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('formation/mypdf.html.twig', [
            'formation' => $formation
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

    
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/addAtelier/{id}", name="add_atelier", methods={"GET"} )
     */
    public function addAtelierToFormation (Atelier $atelier,Formation $formation, Request $request, ObjectManager $manager){
           
            $form = $this->createForm('App\Form\AteliersType', $formation);

            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
    
               
                $manager->persist($formation);
 
                $manager->flush();
    
                return $this->redirectToRoute('formation_index');
            }
    
            return $this->render('duree/index.html.twig', [
                'formation' => $formation,
                'form' => $form->createView(),
            ]);
        }


}


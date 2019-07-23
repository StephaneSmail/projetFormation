<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\Session;
use App\Form\SessionType;
use App\Controller\SessionController;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @IsGranted("ROLE_USER")
 * @Route("/session")
 */
class SessionController extends AbstractController
{
    /**
     * @Route("/", name="session_index")
     */
    public function index(SessionRepository $sessionRepository): Response
    {
       
        return $this->render('session/index.html.twig', [
            'sessions' => $sessionRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="session_new")
     */
    public function new (Request $request,ObjectManager $manager): Response
    {


        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $session->getSalle();
            if (($salle->getNbPlaces() - ($session->getNbplace())) < 0)
            {
                $this->addFlash(
                    'notice',"La salle sélectionnée est trop petite pour la session"
                );
                return $this->redirectToRoute('session_new');
            }

$deb = $form->get('dateDebut')->getData();
$faim = $form->get('dateFin')->getData();
            $taken = $this->getDoctrine()->getRepository(Session::class)->findIfTaken($deb, $faim, $salle->getId());

            if ($taken) {
                $this->addFlash('danger', 'salle prise');
                return $this->redirectToRoute('session_new');
            }
           
           
                
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('session_index');
        }

        return $this->render('session/new.html.twig', [
            'session' => $session,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="session_show", methods={"GET"})
     */
    public function show(Session $session): Response

    {


        return $this->render('session/show.html.twig', [
            'session' => $session,
             
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/edit/{id}", name="session_edit")
     */
    public function edit(Request $request, Session $session, ObjectManager $manager): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $salle = $session->getSalle();
            if (($salle->getNbPlaces() - ($session->getNbplace())) < 0)
            {
                $this->addFlash(
                    'notice',"La salle sélectionnée est trop petite"
                );
                return $this->redirectToRoute('session_new');
            }

            
            $manager->flush();
            $form->get('contenir')->getData();
            $form->get('stagiaires')->getData();

            

            return $this->redirectToRoute('session_index', [
                'id' => $session->getId(),
            ]);
        }

        return $this->render('session/edit.html.twig', [
            'session' => $session,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="session_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Session $session): Response
    {
        if ($this->isCsrfTokenValid('delete'.$session->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($session);
            $entityManager->flush();
        }

        return $this->redirectToRoute('session_index');
        
    }
}

<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Formation;
use App\Form\ContactType;

use App\Controller\HomeController;
use App\Repository\UserRepository;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class HomeController extends AbstractController
{
    
 /**
     * @Route("/", name="/")
     */
    public function index(Request $request, ObjectManager $manager): Response
    {

        $contact = new Contact();
        $form = $this -> createForm(ContactType::class, $contact);

        return $this->render('vitrine/index.html.twig', [
            'form' => $form -> createView() 
        ]);
    
    }
}
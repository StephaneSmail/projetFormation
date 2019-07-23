<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\ModifPassType;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Route("utilisateur/new", name="user_new")
     */
    public function register(ObjectManager $entityManager, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ) 
            );
            //on active par défaut
            // $user->setIsActive(true);
            // $user->addRole("ROLE_USER");
            // $user->addRole("ROLE_ADMIN");
            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash('success', 'Le compte à bien été enregistré');
            
            return $this->redirectToRoute('user_index');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(), 
            'title' => 'Inscription'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("utilisateur/modifpass/{id}", name="user_pass")
     */
    public function modifPassword(Request $request, User $user, ObjectManager $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
    	$form = $this->createForm(ModifPassType::class, $user);

    	$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $request->request->get('modif_pass')['oldPassword'];

            // Si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    );

                $user->setPassword($newEncodedPassword);
                
                $entityManager->flush();

                $this->addFlash('succes', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('user_index');
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }
    	
    	return $this->render('user/pass.html.twig', array(
    		'form' => $form->createView(),
    	));
    }
}

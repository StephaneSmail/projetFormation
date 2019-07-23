<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\ModifUserType;
use App\Form\RegistrationFormType;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/utilisateur")
 */
class UserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="user_index")
     */
    public function index(UserRepository $userRepository) :Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'title' => 'Utilisateur'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route ("/show/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response{

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'title' => 'Utilisateur'
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route ("/delete/{id}", name="user_delete", methods={"DELETE"})
     */
     public function delete(User $user, ObjectManager $manager, Request $request): Response {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            $this->addFlash('success', 'Le compte a bien été supprimé');
            
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/edit/{id}", name="user_edit")
     */
    public function edit(User $user, ObjectManager $entityManager, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response{
        
        $form = $this->createForm(ModifUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             
            $entityManager->flush();

            $this->addFlash('success', 'Le compte a bien été modifié.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(), 
            'title' => 'Utilisateur'
        ]);
    }
}

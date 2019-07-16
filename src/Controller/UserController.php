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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index")
     */
    public function index(UserRepository $userRepository) :Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route ("/show/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response{

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route ("/delete/{id}", name="user_delete", methods={"DELETE"})
     */
     public function delete(User $user, ObjectManager $manager, Request $request): Response {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/edit/{id}", name="user_edit")
     */
    public function edit(User $user, ObjectManager $entityManager, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response{
        
        $form = $this->createForm(ModifUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             
            //on active par défaut
            // $user->setIsActive(true);
            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash('success', 'Votre compte à bien été modifié.');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(), 
            'title' => 'Inscription'
        ]);
    }
}

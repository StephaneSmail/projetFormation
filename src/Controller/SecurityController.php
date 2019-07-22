<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'lastusername'  => $lastUsername,
            'error'         => $error
            ]);
    }

    /**
     * @Route("/forgotten_password", name="forgotten_password")
     */
    public function forgottenPassword(ObjectManager $entityManager,
                                        Request $request,
                                        UserPasswordEncoderInterface $encoder,
                                        \Swift_Mailer $mailer,
                                        TokenGeneratorInterface $tokenGenerator): Response {
    
        if ($request->isMethod('POST')) {
    
            $email = $request->request->get('email');
    
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */
    
            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('app_login');
            }
            $token = $tokenGenerator->generateToken();
    
            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }
    
            $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
    
            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    "blablabla voici le token pour reseter votre mot de passe : " . $url,
                    'text/html'
                );
    
            $mailer->send($message);
    
            $this->addFlash('notice', 'Mail envoyé');
    
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="reset_password")
     */
    public function resetPassword(ObjectManager $entityManager,Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {

            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('homep');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('app_login');
        }else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}

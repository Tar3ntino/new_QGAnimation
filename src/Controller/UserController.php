<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
        {
            $this->encoder = $encoder;
        }

    /**
     * @Route("/register", name="register")
     */

    public function newUser(Request $request)
    {
        $user = new User(); 
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $mdp = $user->getPassword();
            $password = $this->encoder->encodePassword($user, $mdp); // on crypte le password que l'on va chercher dans le fichier
            $user->setPassword($password); // on enregistre le password qui a été crypté

            // On envoie notre objet avec notre mot de passe crypté et on l'enregistre
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush(); 
            // on redirige vers index apres l'INSCRIPTION
            return new RedirectResponse('http://127.0.0.1:8080/qganimation/public/');
            
        }else{ 
            //sinon on rend la vue du formulaire avec le formulaire a remplir
        return $this->render('/home/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    }
    
}

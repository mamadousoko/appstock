<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/User/liste', name: 'liste_user')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $u = new User();
        $form = $this->createForm(UserType::class, $u, array('action' => $this->generateUrl('add_user')));
        $data['form'] = $form->createView();

        $data['users'] = $em->getRepository(User::class)->findAll();
        return $this->render('user/liste.html.twig', $data);
    }

    #[Route('/User/add', name: 'add_user')]
    public function add(ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $hasher): Response
    {
        /** @var $u User */
        $u = new User();
        $form = $this->createForm(UserType::class, $u);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $u= $form->getData();
            $u->setPassword($hasher->hashPassword($u,$u->getPassword()));
            $em = $doctrine->getManager();
            $em->persist($u);
            $em->flush();
            $this->addFlash('success','Utilisateur ajouté');
        }

        return $this->redirectToRoute('liste_user');
    }
}

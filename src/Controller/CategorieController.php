<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/Categorie/liste', name: 'liste_categorie')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $c = new Categorie();
        $form = $this->createForm(CategorieType::class, $c, array('action' => $this->generateUrl('add_categorie')));
        $data['form'] = $form->createView();

        $data['categories'] = $em->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/liste.html.twig',$data);
    }

    
    #[Route('/Categorie/add', name: 'add_categorie')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $c = new Categorie();
        $form = $this->createForm(CategorieType::class, $c);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $c = $form->getData();

            $em = $doctrine->getManager();
            $em->persist($c);
            $em->flush();
            $this->addFlash('success','Categorie ajouté');
        }

        return $this->redirectToRoute('liste_categorie');
    }
    
}

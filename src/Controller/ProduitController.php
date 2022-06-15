<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    #[Route('/Produit/liste', name: 'liste_produit')]
    
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p, array('action' => $this->generateUrl('add_produit')));
        $data['form'] = $form->createView();

        $data['produits'] = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig',$data);
    }

    #[Route('/Produit/add', name: 'add_produit')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $p= $form->getData();
            $p->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($p);
            $em->flush();
            $this->addFlash('success','Produit ajouté');
        }

        return $this->redirectToRoute('liste_produit');
    }

    #[Route('/Produit/delete/{id}', name: 'delete_produit')]
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);
            if($produit != null){
                $em->remove($produit);
                $em->flush();
            }
           return $this->redirectToRoute('liste_produit'); 
    }

    #[Route('/Produit/edit/{id}', name: 'edit_produit')]
    public function edit(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $p = $em->getRepository(Produit::class)->find($id);
        $form = $this->createForm(ProduitType::class, $p, array('action' => $this->generateUrl('update_produit', ['id' => $id])));
        $data['form'] = $form->createView();
        $data['produits'] = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig',$data);
    }

    #[Route('/Produit/update/{id}', name: 'update_produit')]
    public function update(ManagerRegistry $doctrine, $id, Request $request): Response
    {
        $p = new Produit();
        $form = $this->createForm(ProduitType::class, $p);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {        
            $p= $form->getData();
            $p->setUser($this->getUser());

           $p->setId($id);
            $em = $doctrine->getManager();
             $produit = $em->getRepository(Produit::class)->find($p->getId());
             $produit->setLibelle($p->getLibelle());
             $em->flush();
          //  $this->addFlash('success','Produit ajouté');
        }

        return $this->redirectToRoute('liste_produit');
    }
}

<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    #[Route('/Entree/liste', name: 'liste_entree')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $e = new Entree();
        $form = $this->createForm(EntreeType::class, $e, array('action' => $this->generateUrl('add_entree')));
        $data['form'] = $form->createView();

        $data['entrees'] = $em->getRepository(Entree::class)->findAll();
        return $this->render('entree/liste.html.twig',$data);
    }

    #[Route('/Entree/add', name: 'add_entree')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $e = new Entree();
        $p = new Produit();
        $form = $this->createForm(EntreeType::class, $e);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $e= $form->getData();

            $em = $doctrine->getManager();
            $em->persist($e);
            $em->flush();
            //update qte produits
            $p =  $em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $stock = $p->getStock() + $e->getQte();
            $p->setStock($stock); 

            $em->flush();
        }

        return $this->redirectToRoute('liste_entree');
    }
}

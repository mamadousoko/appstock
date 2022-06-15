<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/Sortie/liste', name: 'liste_sortie')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $e = new Sortie();
        $form = $this->createForm(SortieType::class, $e, array('action' => $this->generateUrl('add_sortie')));
        $data['form'] = $form->createView();

        $data['sorties'] = $em->getRepository(Sortie::class)->findAll();
        return $this->render('sortie/liste.html.twig', $data);
    }

    #[Route('/Sortie/add', name: 'add_sortie')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $s = new Sortie();
        $p = new Produit();
        $form = $this->createForm(SortieType::class, $s);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $s= $form->getData();
            $p =  $em->getRepository(Produit::class)->find($s->getProduit()->getId());
            if ($p->getStock() < $s->getQte()) {
                $this->addFlash('fail','Le stock n\'est pas suffisant pour cette sortie');
            }else{
                $em->persist($s);
                $em->flush();
                $stock = $p->getStock() - $s->getQte();   
                $p->setStock($stock); 
                $em->flush();
            }
        }

        return $this->redirectToRoute('liste_sortie');
    }
}

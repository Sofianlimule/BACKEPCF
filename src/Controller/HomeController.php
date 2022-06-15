<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Form\ListeType;
use App\Repository\ListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ListeRepository $listeRepository): Response
    { 
        $lists = $listeRepository->findAll();
      //  dd($lists);
        return $this->render('home/index.html.twig', [
            'lists' => $lists
        ]);
    }

    #[Route('/delete/{id}<\d+>}', name: 'app_Liste_delete')]
    public function delete(EntityManagerInterface $em, Liste $liste): Response
    {
        $em->remove($liste);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/create', name: 'app_Liste_creation')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $liste = new Liste();
        $form = $this->createForm(ListeType::class, $liste); //$this -> abtract controller 
        $form->handleRequest($request); //a savoir si le form est remplie ou vide 
        if($form->isSubmitted() && $form->isValid()){ //valiation du formulaire
            $em->persist($liste); //preparer les donnes
            $em->flush(); //envoie des donnes 
            return $this->redirectToRoute('app_Liste', ['id' => $liste->getId()]); // redirection
        }
        return $this->renderForm('Liste/create.html.twig', [
            'form' => $form
            ]);
    }

    #[Route('/edit/{id}<\d+>}', name: 'app_Liste_edit')]
   public function edit(EntityManagerInterface $em, Liste $liste, Request $request, $id ): Response
    { 
        //(sert a rentrer les parametres)
        $form = $this->createform(ListeType::class, $liste);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($liste);
            $em->flush();
            return $this->redirectToRoute('app_Liste', ['id' => $liste->getId()]);
        }
        return $this->renderForm('edit/edit.html.twig', [
         'form' => $form
    ]);
  }





}
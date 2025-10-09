<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

final class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/show/{name}', name: 'show_author')]
    public function showAuthor($name)
    {
        return $this->render('auth/show.html.twig', 
            ['nom' => $name],
        );
    }

    #[Route('/all', name : 'show_all')]
    public function showAll(AuthorRepository $authorRepository){
        $authors = $authorRepository->findAll();
        return $this->render('auth/all.html.twig',
        ['authors' => $authors]
        );
    }

    #[Route('/addStat', name : 'addStat')]
    public function addStat(ManagerRegistry $doctrine){
        $author = new Author();
        author->setEmail('test@gmail.com');
        author->setUsername('foulen');

        $em = $doctrine->getManager();
        $em -> persist($author);
        $em -> flush();

        return $this -> redirectToRoute('show_all');
    }

    #[Route('/addForm', name : 'addForm')]
    public function addForm(Request $request, ManagerRegistry $doctrine){
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->add('add',SubmitType::class);
        $form->handleRequest($request);
        
        if($form -> isSubmitted()){
            $em = $doctrine->getManager();
            $em -> persist($author);
            $em -> flush();

            return $this->redirectToRoute('show_all');
        }

        return $this -> render('auth/add.html.twig',['formulaire'=>$form->createView()]);
    }

    #[Route('/deleteAuthor/{id}', name : 'deleteAuthor')]
    public function deleteAuthor($id, AuthorRepository $repo, ManagerRegistry $manager){
        $author = $repo -> find($id);
        $em = $manager->getManager();

        $em->remove($author);
        $em->flush();

        return $this -> redirectToRoute('show_all');
    }

    #[Route('/authorDetails/{id}', name : 'authorDetails')]
    public function showAuthorDetails($id, AuthorRepository $repo){
        $author = $repo -> find($id);

        return $this -> render('auth/showDetails.html.twig',['author'=>$author]);
    }


}

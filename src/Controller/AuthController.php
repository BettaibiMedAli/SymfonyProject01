<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;

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
    public function ShowAll(AuthorRepository $authorRepository){
        $authors = $authorRepository->findAll();
        return $this->render('auth/all.html.twig',
        ['authors' => $authors]
        );
    }
}

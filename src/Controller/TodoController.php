<?php

namespace App\Controller;

use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(TodoRepository $todo): Response
    {

        $todos= $todo->findAll();

        return $this->render('todo/index.html.twig', compact('todos') );
    }


}

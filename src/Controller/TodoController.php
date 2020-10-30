<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET|POST")
     */
    public function index(
        Request $req,
        EntityManagerInterface $em,
        TodoRepository $task
    ): Response {
        //Query form
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($todo);
            $em->flush();

            $this->addFlash('success', 'New task created!');
        }
        //Query data
        $todos = $task->findAll();

        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
            'form' => $form->createView(),
        ]);
    }
}

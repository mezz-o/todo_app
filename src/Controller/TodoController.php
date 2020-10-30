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
        $todos = $task->findBy([]);

        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id<[0-9]+>}", name="app_todo_edit", methods="GET|PUT")
     */
    public function update(
        Todo $todo,
        Request $req,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(TodoType::class, $todo, ['method' => 'PUT']);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('todo/edit.html.twig', [
            'form' => $form->createView(),
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("/delete/{id<[0-9]+>}", name="app_todo_delete", methods="DELETE|GET")
     */
    public function delete(Todo $todo, EntityManagerInterface $em): Response
    {

        $em->remove($todo);
        $em->flush();
       return $this->redirectToRoute("app_home");

    }
}

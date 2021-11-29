<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(): Response
    {
        return $this->json(["message" => "Welcome to the todo api"]);
    }

    /**
     * @Route("/api/todo/{id}", name="get_one_todo", methods={"GET"})
     */
    public function getOne(Todo $todo): Response
    {
        $this->checkTodoOwner($todo);

        return $this->json($todo);
    }

    /**
     * @Route("/api/todo", name="get_all_todos", methods={"GET"})
     */
    public function list(TodoRepository $todoRepository): Response
    {
        return $this->json($todoRepository->findBy(["owner" => $this->getUser()]));
    }

    /**
     * @Route("/api/todo", name="create_todo", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new Todo();

        $sentData = json_decode($request->getContent(), true);
        $todo->setLabel($sentData["label"]);
        $todo->setCreatedAt(new DateTimeImmutable());
        $todo->setOwner($this->getUser());

        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->json($todo);
    }

    /**
     * @Route("/api/todo/{id}", name="update_todo", methods={"PUT"})
     */
    public function update(Todo $todo, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->checkTodoOwner($todo);

        $sentData = json_decode($request->getContent(), true);
        $todo->setLabel($sentData["label"]);

        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->json($todo);
    }

    /**
     * @Route("/api/todo/{id}", name="delete_todo", methods={"DELETE"})
     */
    public function delete(Todo $todo, EntityManagerInterface $entityManager): Response
    {
        $this->checkTodoOwner($todo);

        $deletedId = $todo->getId();

        $entityManager->remove($todo);
        $entityManager->flush();

        return $this->json([
            'deletedId' => $deletedId,
        ]);
    }

    private function checkTodoOwner(Todo $todo)
    {
        if ($this->getUser() !== $todo->getOwner()) {
            throw new Exception("You are not allowed to edit this todo", 1);
        }
    }
}

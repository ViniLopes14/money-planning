<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Form\ExpenseType;
use App\Service\ExpenseService;
use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expense')]
class ExpenseController extends AbstractController
{

    private ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    #[Route('/', name: 'app_expense_index', methods: ['GET'])]
    public function index(ExpenseRepository $expenseRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $data['title'] = 'Despesas';
        $data['subtitle'] = 'Listagem';
        $data['expenses'] = $expenseRepository->findAll();

        return $this->render('expense/index.html.twig', $data);
    }

    #[Route('/new', name: 'app_expense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExpenseRepository $expenseRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $expense = new Expense();
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->expenseService->repetition($expense);

            $expenseRepository->save($expense, true);

            return $this->redirectToRoute('app_expense_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['title'] = 'Despesas';
        $data['subtitle'] = 'Nova despesa';
        $data['expense'] = $expense;
        $data['form'] = $form;

        return $this->render('expense/new.html.twig', $data);
    }

    #[Route('/{id}', name: 'app_expense_show', methods: ['GET'])]
    public function show(Expense $expense): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        
        $data['title'] = 'Despesas';
        $data['subtitle'] = 'Visualizar';
        $data['expense'] = $expense;
        $data['category'] = $expense->getCategory();
        $data['account'] = $expense->getAccount();

        return $this->render('expense/show.html.twig', $data);
    }

    #[Route('/{id}/edit', name: 'app_expense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expense $expense, ExpenseRepository $expenseRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseRepository->save($expense, true);

            return $this->redirectToRoute('app_expense_show', ['id' => $expense->getId()], Response::HTTP_SEE_OTHER);
        }

        $data['title'] = 'Despesas';
        $data['subtitle'] = 'Nova despesa';
        $data['expense'] = $expense;
        $data['form'] = $form;

        return $this->render('expense/edit.html.twig', $data);
    }

    #[Route('/{id}', name: 'app_expense_delete', methods: ['POST'])]
    public function delete(Request $request, Expense $expense, ExpenseRepository $expenseRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        
        if ($this->isCsrfTokenValid('delete'.$expense->getId(), $request->request->get('_token'))) {
            $expenseRepository->remove($expense, true);
        }

        return $this->redirectToRoute('app_expense_index', [], Response::HTTP_SEE_OTHER);
    }
}

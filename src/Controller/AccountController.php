<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository): Response
    {
        //VERIFICAR SE O USUÁRIO ESTÁ LOGADO
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //VERIFICAR SE O USUÁRIO ESTÁ LOGADO E SE POSSUI A PERMISSÃO 'ROLE_ADMIN'
        //$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        
        $data['title'] = 'Contas';
        $data['subtitle'] = 'Listagem';
        $data['accounts'] = $accountRepository->findAll();

        return $this->render('account/index.html.twig', $data);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccountRepository $accountRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountRepository->save($account, true);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['title'] = 'Contas';
        $data['subtitle'] = 'Nova conta';
        $data['account'] = $account;
        $data['form'] = $form;

        return $this->render('account/new.html.twig', $data);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $data['title'] = 'Contas';
        $data['subtitle'] = 'Visualizar';
        $data['account'] = $account;
        $data['accountType'] = $account->getAccountType();

        return $this->render('account/show.html.twig', $data);
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Account $account, AccountRepository $accountRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountRepository->save($account, true);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['title'] = 'Contas';
        $data['subtitle'] = 'Editar conta';
        $data['account'] = $account;
        $data['form'] = $form;

        return $this->render('account/edit.html.twig', $data);
    }

    #[Route('/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, Account $account, AccountRepository $accountRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        
        if ($this->isCsrfTokenValid('delete'.$account->getId(), $request->request->get('_token'))) {
            $accountRepository->remove($account, true);
        }

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }
}

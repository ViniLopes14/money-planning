<?php

namespace App\Controller;

use App\Entity\TypeAccount;
use App\Form\TypeAccountType;
use App\Repository\TypeAccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/account')]
class TypeAccountController extends AbstractController
{
    #[Route('/', name: 'app_type_account_index', methods: ['GET'])]
    public function index(TypeAccountRepository $typeAccountRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $data['title'] = 'Tipo de conta';
        $data['subtitle'] = 'Listagem';
        $data['type_accounts'] = $typeAccountRepository->findAll();

        return $this->render('type_account/index.html.twig', $data);
    }

    #[Route('/new', name: 'app_type_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeAccountRepository $typeAccountRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $typeAccount = new TypeAccount();
        $form = $this->createForm(TypeAccountType::class, $typeAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeAccountRepository->save($typeAccount, true);

            return $this->redirectToRoute('app_type_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['title'] = 'Tipo de conta';
        $data['subtitle'] = 'Novo tipo de conta';
        $data['type_account'] = $typeAccount;
        $data['form'] = $form;

        return $this->render('type_account/new.html.twig', $data);

    }

    #[Route('/{id}', name: 'app_type_account_show', methods: ['GET'])]
    public function show(TypeAccount $typeAccount): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $data['title'] = 'Tipo de conta';
        $data['subtitle'] = 'Listagem';
        $data['type_account'] = $typeAccount;

        return $this->render('type_account/show.html.twig', $data);
    }

    #[Route('/{id}/edit', name: 'app_type_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeAccount $typeAccount, TypeAccountRepository $typeAccountRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $form = $this->createForm(TypeAccountType::class, $typeAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeAccountRepository->save($typeAccount, true);

            return $this->redirectToRoute('app_type_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['title'] = 'Tipo de conta';
        $data['subtitle'] = 'Editar tipo de conta';
        $data['type_account'] = $typeAccount;
        $data['form'] = $form;

        return $this->render('type_account/edit.html.twig', $data);
    }

    #[Route('/{id}', name: 'app_type_account_delete', methods: ['POST'])]
    public function delete(Request $request, TypeAccount $typeAccount, TypeAccountRepository $typeAccountRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        
        if ($this->isCsrfTokenValid('delete'.$typeAccount->getId(), $request->request->get('_token'))) {
            $typeAccountRepository->remove($typeAccount, true);
        }

        return $this->redirectToRoute('app_type_account_index', [], Response::HTTP_SEE_OTHER);
    }
}

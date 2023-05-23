<?php

namespace App\Controller;

use App\Entity\Revenue;
use App\Form\RevenueType;
use App\Repository\RevenueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/revenue')]
class RevenueController extends AbstractController
{
    #[Route('/', name: 'app_revenue_index', methods: ['GET'])]
    public function index(RevenueRepository $revenueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        
        $data['title'] = 'Receitas';
        $data['subtitle'] = 'Listagem';
        $data['revenues'] = $revenueRepository->findAll();

        return $this->render('revenue/index.html.twig', $data);
    }

    #[Route('/new', name: 'app_revenue_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RevenueRepository $revenueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $revenue = new Revenue();
        $form = $this->createForm(RevenueType::class, $revenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revenueRepository->save($revenue, true);

            return $this->redirectToRoute('app_revenue_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['title'] = 'Receitas';
        $data['subtitle'] = 'Nova receita';
        $data['revenue'] = $revenue;
        $data['form'] = $form;

        return $this->render('revenue/new.html.twig', $data);
    }

    #[Route('/{id}', name: 'app_revenue_show', methods: ['GET'])]
    public function show(Revenue $revenue): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $data['title'] = 'Receitas';
        $data['subtitle'] = 'Visualizar';
        $data['revenue'] = $revenue;
        $data['category'] = $revenue->getCategory();
        $data['account'] = $revenue->getAccount();

        return $this->render('revenue/show.html.twig', $data);
    }

    #[Route('/{id}/edit', name: 'app_revenue_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Revenue $revenue, RevenueRepository $revenueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $form = $this->createForm(RevenueType::class, $revenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revenueRepository->save($revenue, true);

            return $this->redirectToRoute('app_revenue_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $data['title'] = 'Receitas';
        $data['subtitle'] = 'Nova receita';
        $data['revenue'] = $revenue;
        $data['form'] = $form;

        return $this->render('revenue/edit.html.twig', $data);
    }

    #[Route('/{id}', name: 'app_revenue_delete', methods: ['POST'])]
    public function delete(Request $request, Revenue $revenue, RevenueRepository $revenueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        
        if ($this->isCsrfTokenValid('delete'.$revenue->getId(), $request->request->get('_token'))) {
            $revenueRepository->remove($revenue, true);
        }

        return $this->redirectToRoute('app_revenue_index', [], Response::HTTP_SEE_OTHER);
    }
}

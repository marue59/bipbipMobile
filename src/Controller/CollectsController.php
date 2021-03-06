<?php

namespace App\Controller;

use App\Entity\Collects;
use App\Form\CollectsType;
use App\Repository\CollectsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collect")
 */
class CollectsController extends AbstractController
{

    // Permet de lister les collectes pour l'admin

    /**
     * @Route("/", name="collects_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param CollectsRepository $collectsRepository
     * @return Response
     */
    public function index(CollectsRepository $collectsRepository): Response
    {
        $collectsValid = $collectsRepository->findByDateValid();
        return $this->render('collects/index.html.twig', [
            'collects' => $collectsValid
        ]);
    }

    // Permet de lister les collectes pour l'admin

    /**
     * @Route("/collectes-a-venir", name="collects_index_collector", methods={"GET"})
     * @IsGranted("ROLE_COLLECTOR")
     * @param CollectsRepository $collectsRepository
     * @return Response
     */
    public function indexCollector(CollectsRepository $collectsRepository): Response
    {
        $organismeId = $this->getUser()->getOrganism()->getId();
        $collectsValid = $collectsRepository->findByDateValidPerOrganism($organismeId);

        return $this->render('collects/index.html.twig', [
            'collects' => $collectsValid
        ]);
    }
// Permet a l'admin d'ajouter une collecte

    /**
     * @Route("/new", name="collects_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $collect = new Collects();
        $form = $this->createForm(CollectsType::class, $collect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collect);
            $entityManager->flush();

            return $this->redirectToRoute('collects_index');
        }

        return $this->render('collects/new.html.twig', [
            'collect' => $collect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="collects_show", methods={"GET"})
     * @param Collects $collect
     * @return Response
     */
    public function show(Collects $collect): Response
    {
        return $this->render('collects/show.html.twig', [
            'collect' => $collect,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="collects_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Collects $collect): Response
    {
        $form = $this->createForm(CollectsType::class, $collect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('collects_index');
        }

        return $this->render('collects/edit.html.twig', [
            'collect' => $collect,
            'form' => $form->createView(),
        ]);
    }
// Permet a l'admin de supprimer des collectes
    /**
     * @Route("/{id}", name="collects_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Collects $collect): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collect->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($collect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('collects_index');
    }
}

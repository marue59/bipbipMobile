<?php

namespace App\Controller;

use App\Entity\Organisms;
use App\Entity\User;
use App\Form\OrganismsType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\OrganismsRepository;
use App\Form\RegistrationCollectorFormType;
use App\Security\LoginFormAuthenticator;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/home", name="home_admin")
     */
    public function show(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/collector/register", name="register_collector")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @return Response|null
     * @throws Exception
     */
    public function registerCollector(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator
    ) {
        $user = new User();
        $form = $this->createForm(RegistrationCollectorFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_COLLECTOR']);
            $user->setSignupDate(new DateTime('now'));
            $user->setSigninDate(new DateTime('now'));
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('adminhome_admin');
        }

        return $this->render('admin/register_collector.html.twig', [
            'registrationCollectorForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/organisms", name="admin_organisms_index", methods={"GET"})
     * @param OrganismsRepository $organismsRepository
     * @return Response
     */
    public function index(OrganismsRepository $organismsRepository): Response
    {
        $organisms = $organismsRepository->findAll();
        return $this->render('admin/index_organism.html.twig', [
            'organisms' => $organisms,
        ]);
    }

    /**
     * @Route("/organism/{id}", name="admin_organism_show", methods={"GET"})
     * @param Organisms $organism
     * @return Response
     */
    public function showOrganism(Organisms $organism): Response
    {
        return $this->render('admin/show_organism.html.twig', [
            'organism' => $organism,
        ]);
    }

    /**
     * @Route("/organism/edit/{id}", name="admin_organism_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param Organisms $organism
     * @return Response
     */
    public function edit(Request $request, Organisms $organism): Response
    {
        $form = $this->createForm(OrganismsType::class, $organism);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['logo']->getData();
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $organism->setLogo(
                    new File($this->getParameter('upload_directory') . '/' . $organism->getLogo())
                );
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_organisms_index');
        }

        return $this->render('admin/edit.html.twig', [
            'organism' => $organism,
            'form' => $form->createView(),
        ]);
    }
}

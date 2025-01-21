<?php

namespace App\Controller;

use App\Entity\CarCategoryEnum;
use App\Repository\CarRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Car;
use App\Form\CarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/car', name: 'car_')]
class CarController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, CarRepository $carRepository, PaginatorInterface $paginator): Response
    {
        $filters = [
            'brand'      => $request->query->get('brand'),
            'color'      => $request->query->get('color'),
            'category'   => $request->query->get('category'),
            'seatNumber' => $request->query->get('seatNumber'),
        ];

        $query = $carRepository->findByFilters($filters);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('car/index.html.twig', [
            'pagination' => $pagination,
            'categories' => CarCategoryEnum::cases(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour accéder à cette page')]
    public function create(Request $request): Response
    {
        $car  = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($car);
            $this->entityManager->flush();

            return $this->redirectToRoute('car_index');
        }

        return $this->render('car/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour accéder à cette page')]
    public function edit(Request $request, Car $car): Response
    {
        if (!$this->authorizationChecker->isGranted('edit', $car)) {
            throw $this->createAccessDeniedException('Cette ressource ne vous appartient pas. Vous ne pouvez pas la modifier');
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();

            return $this->redirectToRoute('car_index');
        }

        return $this->render('car/edit.html.twig', [
            'form' => $form->createView(),
            'car'  => $car,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour accéder à cette page')]
    public function delete(Request $request, Car $car): Response
    {
        if (!$this->authorizationChecker->isGranted('delete', $car)) {
            throw $this->createAccessDeniedException('Cette ressource ne vous appartient pas. Vous ne pouvez pas la supprimer');
        }

        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($car);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('car_index');
    }
}

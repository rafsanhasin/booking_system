<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Reservations;
use App\Repository\CustomerRepository;
use App\Repository\ReservationsRepository;
use App\Repository\RoomRepository;
use App\Services\DateParserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/reserve", name="reserve.")
 */
class ReservationController extends AbstractController
{
    private $entityManager;
    private $roomRepo;
    private $customerRepo;
    private $reservationRepo;

    public function __construct(
        ManagerRegistry $managerRegistry,
        RoomRepository $roomRepo,
        CustomerRepository $customerRepo,
        ReservationsRepository $reservationRepo

    ) {
        $this->entityManager = $managerRegistry->getManager();
        $this->roomRepo = $roomRepo;
        $this->customerRepo = $customerRepo;
        $this->reservationRepo = $reservationRepo;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */

    public function index() {
        //dd($this->reservationRepo->getAllWithRelations());
        return $this->render('reservation/index.html.twig',
            ['reservations' => $this->reservationRepo->findAll()]
        );
    }

    /**
     * @Route("/", name="store", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, DateParserService $dateParserService): Response
    {
        try{
            $room = $this->roomRepo->find($request->get('room'));

            if (!$room) {
                $this->addFlash('error', 'Reservation Successful');
                return new Response('room not found' , Response::HTTP_CREATED);
            }

            $reservation = new Reservations();
            $reservation->setRoom($room);

            $dateRangeParsed = $dateParserService->parseDate($request->get('daterange'));
            $reservation->setFromDate(new \DateTime($dateRangeParsed['from']->format('Y-m-d')));
            $reservation->settoDate(new \DateTime($dateRangeParsed['to']->format('Y-m-d')));

            $duration = $dateRangeParsed['to']->diffInDays($dateRangeParsed['from']) + 1;
            $reservation->setDuration($duration);
            $reservation->setTotalPrice($room->getPrice() * $duration);

            $customer = $this->customerRepo->findOneByPhone($request->get('cusPhone'));

            if (!$customer) {
                $customer = new Customer();
                $customer->setName($request->get('cusName'));
                $customer->setPhone($request->get('cusPhone'));

                $this->entityManager->persist($customer);
                $this->entityManager->flush();
            }
            $reservation->setCustomer($customer);

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            $this->addFlash('error', 'Internal server error');
            return new Response($exception->getMessage() , Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        $this->addFlash('success', "Reservation Successful");
        return new Response("success" , Response::HTTP_CREATED);
    }
}

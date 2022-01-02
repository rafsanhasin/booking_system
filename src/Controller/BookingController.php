<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use App\Services\DateParserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    private $roomRepo;

    /**
     * BookingController constructor.
     * @param RoomRepository $roomRepo
     */
    public function __construct(RoomRepository $roomRepo)
    {
        $this->roomRepo = $roomRepo;
    }

    /**
     * @Route("/", name="booking.index")
     * @param Request $request
     * @param DateParserService $dateParserService
     * @return Response
     */
    public function index(Request $request, DateParserService $dateParserService): Response
    {
        if ($request->get('daterange')) {
            try{
                $dateRangeParsed = $dateParserService->parseDate($request->get('daterange'));

                return $this->render('booking/index.html.twig',[
                    'availableRooms' => $this->roomRepo->getAvailableRooms(
                        $dateRangeParsed['from']->format('Y-m-d'),
                        $dateRangeParsed['to']->format('Y-m-d'))
                ]);

            } catch (\Exception $exception) {
                $this->addFlash('error', "Internal Server Error");
                return $this->redirect($this->generateUrl('booking.index'));
            }
        }

        return $this->render('booking/index.html.twig');
    }
}

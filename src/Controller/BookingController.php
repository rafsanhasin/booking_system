<?php

namespace App\Controller;

use App\Repository\ReservationsRepository;
use App\Repository\RoomRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;
use function Symfony\Component\Translation\t;

class BookingController extends AbstractController
{
    private $roomRepo;

    public function __construct(RoomRepository $roomRepo)
    {
        $this->roomRepo = $roomRepo;
    }

    /**
     * @Route("/", name="booking.index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        if ($request->get('daterange')) {
            try{
                $dateRangeParse = explode("-", $request->get('daterange'));

                $from = Carbon::parse($dateRangeParse[0]);
                $to = Carbon::parse($dateRangeParse[1]);

                return $this->render('booking/index.html.twig',[
                    'availableRooms' => $this->roomRepo->getAvailableRooms(
                        $from->format('Y-m-d'),
                        $to->format('Y-m-d'))
                ]);

            } catch (\Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
                return $this->redirect($this->generateUrl('booking.index'));
            }
        }

        return $this->render('booking/index.html.twig');
    }
}

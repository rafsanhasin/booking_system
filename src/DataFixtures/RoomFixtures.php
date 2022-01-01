<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items= [50,100,200, 500];

        for ($i=1; $i<=10; $i++) {
            $room = new Room();
            $room->setName("room#".$i);
            $room->setisAvailable(1);
            $room->setPrice($items[array_rand($items)]);
            $manager->persist($room);
        }

        $manager->flush();
    }
}

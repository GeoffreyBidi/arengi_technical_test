<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Car;
use App\Entity\CarCategoryEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));

            $manager->persist($user);

            if ($i <= 5) {
                $car = new Car();
                $car->setBrand("Brand_{$i}");
                $car->setSeatNumber($i);
                $car->setColor("color#{$i}");
                $car->setMaximumAllowedWeight($i * 1000);
                $car->setAuthor($user);

                $categories = CarCategoryEnum::cases();
                $car->setCategory($categories[($i - 1) % count($categories)]);

                $manager->persist($car);
            }
        }

        $manager->flush();
    }
}

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
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <= 50; $i++) {
            $car = new Car();
            $car->setBrand("Brand_{$i}")
                ->setSeatNumber(random_int(1, 6))
                ->setColor("Color_{$i}")
                ->setMaximumAllowedWeight(random_int(1, 20) * 100)
                ->setAuthor($users[array_rand($users)]);

            $categories = CarCategoryEnum::cases();
            $car->setCategory($categories[($i - 1) % count($categories)]);

            $manager->persist($car);
        }

        $manager->flush();
    }
}

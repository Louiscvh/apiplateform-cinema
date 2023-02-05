<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher) {
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail('johndoe' . $i . '@gmail.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail('admin@cinema.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'cinema'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}

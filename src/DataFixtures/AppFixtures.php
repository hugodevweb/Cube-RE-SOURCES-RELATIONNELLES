<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Entity\Role;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Monolog\DateTimeImmutable;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Creation du faker PHP pour generer des fake data
        $faker = Factory::create('fr FR');

        for ($i=0; $i<5; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($faker->unique()->word());
            $manager->persist($categorie);
        }

        $role_admin = new Role();
        $role_admin->setNom('ADMIN');
        $manager->persist($role_admin);

        $role_citoyen = new Role();
        $role_citoyen->setNom('CITOYEN');
        $manager->persist($role_citoyen);
        

        $admin = new User();
        $admin->setRoles(["CITOYEN", "ADMIN"])
            ->setEmail($faker->unique()->email())
            ->setPassword('123456')
            ->setPseudo($faker->unique()->name())
            ->setPrenom($faker->unique()->name())
            ->setNom($faker->unique()->name());
        $manager->persist($admin);

        for ($i=0; $i<4; $i++) {
            $user = new User();
            $user->setRoles(["CITOYEN"])
            ->setEmail($faker->unique()->email())
            ->setPassword('123456')
            ->setPseudo($faker->unique()->name())
            ->setPrenom($faker->unique()->name())
            ->setNom($faker->unique()->name());
            $manager->persist($user);

            $type = new Type();
            $type->setNom($faker->unique()->word());
            $manager->persist($type);

            $article = new Article();
            $article->setTitre($faker->word())
                ->setCorps($faker->text())
                ->setUser($user)
                ->setType($type);
            $manager->persist($article);

        }

        $event = new Evenement();
        $event->setDateDebut(new DateTimeImmutable('2024-02-01 00:00:00'))
            ->setDateFin(new DateTimeImmutable('2024-02-25 00:00:00'))
            ->setTitre($faker->unique()->name())
            ->setDescription($faker->text())
            ->setLocalisation('France');
        $manager->persist($event);

        $manager->flush();
    }
}

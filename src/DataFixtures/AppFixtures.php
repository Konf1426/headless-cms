<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        $user = new User();
        $user->email = 'user@example.com';
        $user->password = $this->passwordHasher->hashPassword($user, 'user');
        $user->firstname = $faker->firstName();
        $user->lastname = $faker->lastName();
        $manager->persist($user);

        $user2 = new User();
        $user2->email = 'user2@example.com';
        $user2->password = $this->passwordHasher->hashPassword($user2, 'user2');
        $user2->firstname = $faker->firstName();
        $user2->lastname = $faker->lastName();
        $manager->persist($user2);

        $root = new User();
        $root->email = 'root@example.com';
        $root->password = $this->passwordHasher->hashPassword($root, 'root');
        $root->firstname = $faker->firstName();
        $root->lastname = $faker->lastName();
        $root->roles = ['ROLE_ADMIN'];
        $manager->persist($root);

        $root2 = new User();
        $root2->email = 'root2@example.com';
        $root2->password = $this->passwordHasher->hashPassword($root2, 'root2');
        $root2->firstname = $faker->firstName();
        $root2->lastname = $faker->lastName();
        $root2->roles = ['ROLE_ADMIN'];
        $manager->persist($root2);

        for ($i = 0; $i <= 100; $i++) {
            $content = new Content();
            $content->title = $faker->word();
            $content->metaDescription = $faker->sentence();
            $content->content = $faker->sentence();
            $content->author = $i % 2 === 0 ? $root : $root2;
            $manager->persist($content);

            for ($j = 0; $j <= 10; $j++) {
                $comment = new Comment();
                $comment->message = $faker->sentence();
                $comment->author = $j % 2 === 0 ? $user : $user2;
                $comment->content = $content;
                $manager->persist($comment);
            }

        }

        $manager->flush();
    }
}

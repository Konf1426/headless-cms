<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Content;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContentController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'app_contents')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'contents' => $this->em->getRepository(Content::class)->findAll(),
        ]);
    }
}

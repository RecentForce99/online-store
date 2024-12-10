<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

final class TestController extends AbstractController
{
    #[Route('/healthcheck')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->getConnection()->connect();

            if ($entityManager->getConnection()->isConnected()) {
                return new Response(status: Response::HTTP_OK);
            }
        } catch (Throwable) {
        }

        return new Response(status: Response::HTTP_BAD_REQUEST);
    }
}

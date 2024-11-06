<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/healthcheck')]
    public function index(EntityManagerInterface $em): Response
    {
        $em->getConnection()->connect();
        return $em->getConnection()->isConnected()
            ? new Response(null, Response::HTTP_OK)
            : new Response(null, Response::HTTP_BAD_REQUEST);
    }
}

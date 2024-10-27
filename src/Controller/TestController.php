<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/test')]
final class TestController extends AbstractController
{
    #[Route('/list')]
    public function index(): Response
    {
        return new Response('Test');
    }
}

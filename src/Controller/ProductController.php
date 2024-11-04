<?php

namespace App\Controller;

use App\System\Service\ProductReadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductReadService $productReadService,
    ) {
    }

    public function list(Request $request): JsonResponse
    {
        return new JsonResponse($this->productReadService->getAll($request->query->get('storageType')));
    }
}

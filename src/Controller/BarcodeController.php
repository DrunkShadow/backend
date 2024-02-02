<?php

namespace App\Controller;
use App\Services\Barcode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'barcode')]
#[Route('/barcode', name: 'bar', methods: 'GET')]
class BarcodeController extends AbstractController
{
    #[Route('/{reference}', name: 'getBarcode', methods: 'GET')]
    public function generateBarcodeAction(Barcode $barcodeService ,$reference){
        return $barcodeService->generateBarcodeAction($reference); 

    }
    
}
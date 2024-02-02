<?php

namespace App\Services;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'barcode')]
#[Route('/barcode', name: 'bar', methods: 'GET')]
class Barcode{
    #[Route('/{reference}', name: 'getBarcode', methods: 'GET')]
    public function generateBarcodeAction($reference)
    {
        if (strlen($reference) !== 12 || !is_numeric($reference)) {
            return new Response(400);
        }

        $generator = new BarcodeGeneratorHTML();
        $barcodeHtml = $generator->getBarcode($reference, BarcodeGeneratorHTML::TYPE_CODE_128);

        $response = new Response($barcodeHtml, Response::HTTP_OK, [
            'Content-Type' => 'text/html',
        ]);

        return $response;
    }
}
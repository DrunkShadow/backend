<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use App\Services\replaceWords;
use App\Services\downloadPdf;


#[OA\Tag(name: 'Pdf')]
#[Route('/pdf', name: 'serving', methods: 'GET')]
class PdfController extends AbstractController
{ 
    #[Route('/downloadPDF/{modelId}/{id}', name: 'download', methods: ['GET'])]
    public function generatePdf(EntityManagerInterface $entityManager,replaceWords $rep,downloadPdf $dwPdf, string $modelId, string $id): Response
    {
        return $dwPdf->generatePdf($entityManager,$rep,$modelId,$id);
    }

}

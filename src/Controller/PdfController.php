<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use App\Services\replaceWords;
use App\Services\downloadPdf;
use Psr\Log\LoggerInterface;
use App\Services\Barcode;


#[OA\Tag(name: 'Pdf')]
#[Route('/pdf', name: 'serving', methods: 'GET')]
class PdfController extends AbstractController
{ 
    #[Route('/downloadPDF/{modelId}/{id}', name: 'download', methods: ['GET'])]
    public function generatePdf(LoggerInterface $log,EntityManagerInterface $entityManager,replaceWords $rep,downloadPdf $dwPdf, string $modelId, string $id): Response
    {
        return $dwPdf->generatePdf($log,$entityManager,$rep,$modelId,$id);
    }

    #[Route('/downloadPDF/{reference}/{height}/{width}/{perRow}/{chosenCat}', name: 'downloadBar', methods: ['GET'])]
    public function generateBarPdf(EntityManagerInterface $entityManager,Barcode $bar, LoggerInterface $log,string $reference,downloadPdf $dwPdf,int $height,int $width,int $perRow,string $chosenCat): Response
    {
      return $dwPdf->generateBarPdf($entityManager,$bar,$reference,$height,$width,$perRow,$chosenCat);
    }

}

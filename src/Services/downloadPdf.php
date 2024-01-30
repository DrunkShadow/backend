<?php
namespace App\Services;
use App\Entity\Models;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use TCPDF;
use App\Services\replaceWords;
use Psr\Log\LoggerInterface;

class downloadPdf{
    public function generatePdf(LoggerInterface $log,EntityManagerInterface $entityManager,replaceWords $rep, string $modelId, string $id): Response
    {
        $model = $entityManager->getRepository(Models::class)->find($modelId);

        if (!$model) {
            return new Response(404);
        }

        $pdf = new TCPDF();
        $pdf->AddPage();

        $html = '<h1>' . str_replace(' ', '&nbsp;', $model->getModelId()) . '</h1> <br> <p>'
            . nl2br($rep->replaceKeywords($log,$entityManager, $model->getModelText(), $id) ) . '</p>';
        $pdf->writeHTML($html, true, false, true, false, '');

        $output = $pdf->Output('example.pdf', 'S');

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="example.pdf"');

        return $response;
    }

}
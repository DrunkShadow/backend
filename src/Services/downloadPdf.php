<?php
namespace App\Services;
use App\Entity\Models;
use App\Entity\Materielle;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use TCPDF;
use App\Services\replaceWords;
use Psr\Log\LoggerInterface;
use App\Services\Barcode;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Validator\Constraints\Length;

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

    public function generateBarPdf(EntityManagerInterface $entityManager,Barcode $barcodeService,string $reference,int $height,int $width,int $perRow,string $chosenCat): Response
    {

        $pdf = new TCPDF();
        $pdf->AddPage();
        $html = '';
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);
        $index=0;
            if($reference)
            {
                $barcodeResponse = $barcodeService->generateBarcodeAction($reference);
                $barcodeHtml = $barcodeResponse->getContent();    
                $mat   = $entityManager->getRepository(Materielle::class)->find($reference);
                $html  = '<div style="text-align: center;">';
                $html .= $mat->getTitre();
                $html .= ' <br> <div style="display: inline-block;">' . $barcodeHtml . '</div> <br>';
                $html .= $mat->getReference();
                $html .= '<div style="text-align: center;">'. '  <img style="display:inline-block; width:200px;" src="http://localhost/backend/public/barcodeImages/placeholder.jpg" alt="image"> ' . '</div>';
                $html .= '</div>';            }
            else{
                $mats = $entityManager->getRepository(Materielle::class)->findAll();
                for ($i = 0; $i < count($mats); $i++) {
                    if($chosenCat === $mats[$i]->getCategorie() || $chosenCat=="All")
                    {
                        
                        $barcodeResponse = $barcodeService->generateBarcodeAction($mats[$i]->getReference());
                        $barcodeHtml = $barcodeResponse->getContent();
                        $widthOfElement= (100/$perRow)-2;
                        $html .= '<div style="display:inline-block; width:'.$widthOfElement.'%; margin-right:2%; text-align:center;">';
                        $html .= '<div style="text-align: center;">' . $mats[$i]->getTitre() . '</div>';
                        $html .= '<div style="text-align: center; display:inline-block;">' . $barcodeHtml . '</div>';
                        $html .= '<div style="text-align: center;">' . $mats[$i]->getReference() . '</div>';
                        $html .= '<div style="text-align: center;">' . '<img style="display:inline-block; width:100%;" src="http://localhost/backend/public/barcodeImages/placeholder.jpg" alt="image">' . '</div>';
                        $html .= '</div>';
                        if (($index + 1) % $perRow == 0) {
                            $html .= '<br><br>';
                        }
                        $index++;
                    }
                }
            }
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            if($height && $width){$dompdf->setPaper(array(0, 0,$width ,$height), 'portrait');}

            $dompdf->render();

            $output = $dompdf->output();

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="example.pdf"');

        return $response;
    }

}
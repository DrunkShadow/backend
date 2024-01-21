<?php
namespace App\Controller;

use App\Entity\Project;
use App\Entity\Worker;
use App\Entity\Models;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use TCPDF;

#[Route('/services', name: 'download', methods: 'GET')]
class PdfPrepController extends AbstractController
{
    #[Route('/downloadPDF/{modelId}/{id}', name: 'download', methods: ['GET'])]
    public function generatePdf(EntityManagerInterface $entityManager, string $modelId, string $id): Response
    {
        $model = $entityManager->getRepository(Models::class)->find($modelId);

        if (!$model) {
            return $this->json(['error' => 'Model not found'], 404);
        }

        $pdf = new TCPDF();
        $pdf->AddPage();

        $html = '<h1>' . $model->getId() . '</h1> <br> <p style="white-space: pre-wrap;">'
        . $this->replaceKeywords($entityManager, $model->getText(), $id) . '</p>';
    

        $pdf->writeHTML($html, true, false, true, false, '');

        $output = $pdf->Output('example.pdf', 'S');

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="example.pdf"');

        return $response;
    }

    private function replaceKeywords(EntityManagerInterface $entityManager, string $text, int $id): string
    {
        $entity = $entityManager->getRepository(Project::class)->find($id) ?: $entityManager->getRepository(Worker::class)->find($id);

        if ($entity !== null) {
            $replacements = $this->getEntityReplacements($entity);
            return str_replace(array_keys($replacements), array_values($replacements), $text);
        }

        return $text;
    }

    private function getEntityReplacements($entity): array
    {
        if ($entity instanceof Project) {
            return [
                '{{project_id}}' => $entity->getId(),
                '{{project_name}}' => $entity->getName(),
                '{{project_budget}}' => $entity->getBudget(),
                '{{project_date}}' => $entity->getDate()->format('d-m-Y'),
                '{{project_category}}' => $entity->getCategory(),
            ];
        } elseif ($entity instanceof Worker) {
            return [
                '{{worker_id}}' => $entity->getId(),
                '{{worker_name}}' => $entity->getName(),
                '{{worker_last_name}}' => $entity->getLastName(),
                '{{worker_title}}' => $entity->getTitle(),
                '{{worker_signature}}' => '<img src="data:image/jpeg;base64,' . $entity->getSignature() . '" />',

            ];
        }

        return [];
    }
}
<?php

namespace App\Controller;

use App\Entity\Keywords;
use App\Entity\Project;
use App\Entity\Worker;
use App\Entity\Models;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use TCPDF;


#[OA\Tag(name: 'services')]
#[Route('/services', name: 'serving', methods: 'GET')]
class OutputsController extends AbstractController
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

        $html = '<h1>' . str_replace(' ', '&nbsp;', $model->getId()) . '</h1> <br> <p>'
            . nl2br($this->replaceKeywords($entityManager, $model->getText(), $id)) . '</p>';


        $pdf->writeHTML($html, true, false, true, false, '');

        $output = $pdf->Output('example.pdf', 'S');

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="example.pdf"');

        return $response;
    }


    // private function replaceKeywords(EntityManagerInterface $entityManager, string $text, int $id): string
    // {   
    // $keys = $entityManager->getRepository(Keywords::class)->findAll();
    // foreach ($keys as $key) {
    //     if ($key->getConcernedObject() === 'project') {
    //         $entity = $entityManager->getRepository(Project::class)->find($id);
    //         $method = $entity->{$key->getMethod()};

    //         $text = str_replace($key->getValue(), $method ,$text);
    //     }
    // }

    // return $text;
    // }

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
                '{{ project_id }}' => $entity->getId(),
                '{{ project_name }}' => $entity->getName(),
                '{{ project_budget }}' => $entity->getBudget(),
                '{{ project_date }}' => $entity->getDate()->format('d-m-Y'),
                '{{ project_category }}' => $entity->getCategory(),
            ];
        } elseif ($entity instanceof Worker) {
            return [
                '{{ worker_id }}' => $entity->getId(),
                '{{ worker_name }}' => $entity->getName(),
                '{{ worker_last_name }}' => $entity->getLastName(),
                '{{ worker_title }}' => $entity->getTitle(),
                // '{{ worker_signature }}' => '<img src="data:image/jpeg;base64,' . $entity->getSignature() . '" style="width: 200px; height: auto;"/>',

            ];
        }

        return [];
    }
    #[Route('/sendEmail/{emailModelId}/{chosenEntityId}', name: 'server', methods: 'GET',)]
    public function sendEmail(MailerInterface $mailer, EntityManagerInterface $entityManager,Request $request, string $emailModelId, string $chosenEntityId): Response
    {
        $model = $entityManager->getRepository(Models::class)->find($emailModelId);
        $entity = $entityManager->getRepository(Worker::class)->find($chosenEntityId);
        
        $tempFolder = $this->getParameter('kernel.project_dir') . '/var/temp/' . uniqid();
        $filesystem = new Filesystem();
        $filesystem->mkdir($tempFolder);

        // $modelsToAttach = (array)$request->query->get('sentAttachments');
        $receivedStr = $request->query->get('sentAttachments');
        $modelsToAttach= explode(', ',$receivedStr);  
        


        $email = (new Email())
            ->from('teeesstt@cccc.com')
            ->to($entity->getEmail())
            ->subject($model->getId())
            ->text($this->replaceKeywords($entityManager, $model->getText(), $chosenEntityId));

            foreach($modelsToAttach as $modelToAttach)
            {
                $pdf = $this->generatePdf($entityManager, $modelToAttach, $chosenEntityId);
                $pdfFilename = $tempFolder . '/'. $modelToAttach .'.pdf';
                file_put_contents($pdfFilename, $pdf);
                $email->addPart(new DataPart(new File($pdfFilename)));
            }
            

        try {
            $mailer->send($email);
            return new Response(200);
        } catch (\Exception $e) {
            return new Response($e);
        } finally {
            $filesystem->remove($tempFolder);
        }
    }

    
}

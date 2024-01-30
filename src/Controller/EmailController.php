<?php

namespace App\Controller;
use App\Entity\Email as entityEmail;
use App\Entity\Attachment;
use App\Entity\Worker;
use App\Entity\Models;
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
use App\Services\replaceWords;
use App\Services\downloadPdf;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use App\DTO\AttachmentDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/email', name: 'mailing')]
class EmailController extends AbstractController
{
    #[OA\Tag(name: 'Emails')]
    #[Route('/sendEmail/{emailModelId}/{chosenEntityId}', name: 'server', methods: 'GET',)]
    public function sendEmail(LoggerInterface $log,downloadPdf $dwPdf,replaceWords $rep,MailerInterface $mailer, EntityManagerInterface $entityManager,Request $request, string $emailModelId, string $chosenEntityId): Response
    {
        $model = $entityManager->getRepository(Models::class)->find($emailModelId);
        $entity = $entityManager->getRepository(Worker::class)->find($chosenEntityId);
        
        $Folder = $this->getParameter('kernel.project_dir') . '/public/attachmentPdf';
        $filesystem = new Filesystem();
        $filesystem->mkdir($Folder);
        $receivedStr = $request->query->get('sentAttachments');
        $modelsToAttach= explode(', ',$receivedStr);  

        $email = (new Email())
            ->from('teeesstt@cccc.com')
            ->to($entity->getWorkerEmail())
            ->subject($model->getModelId())
            ->text($rep->replaceKeywords($log,$entityManager, $model->getModelText(), $chosenEntityId));
            $emailId=$this->AddEmailHistory($entityManager,$email);
            foreach($modelsToAttach as $modelToAttach)
            {
                if($modelToAttach)
                {
                $pdf = $dwPdf->generatePdf($log,$entityManager,$rep, $modelToAttach, $chosenEntityId);
                $name = uniqid();
                $pdfFilename = $Folder . '/' . $name . '.pdf';

                $position = strpos($pdfFilename, "backend");
                $substring = substr($pdfFilename, $position);
                $replacement = "http://localhost/";

                $result = $replacement."".$substring;

                file_put_contents($pdfFilename, $pdf);
                $file = new DataPart(new File($pdfFilename));
                $email->addPart($file);

                $this->AddAttachment($entityManager,$modelToAttach,$emailId,$result);

                }
            }
        try {
            $mailer->send($email);

            return new Response(200);
        } catch (\Exception $e) {
            return new Response($e);
        }
    }
    #[OA\Tag(name: 'Emails')]
    #[Route('', name: 'addEmail', methods: 'POST',)]
    public function AddEmailHistory(EntityManagerInterface $entityManager, Email $email): int|Response
    {
        $source = implode(', ', array_map(function (Address $address) {
            return $address->getAddress();
        }, $email->getFrom()));
        
        $target = implode(', ', array_map(function (Address $address) {
            return $address->getAddress();
        }, $email->getTo()));

        try {
        $newEmail = new entityEmail();
        $newEmail->setEmailText($email->getTextBody());
        $newEmail->setEmailTarget($target);
        $newEmail->setEmailSource($source);
        $newEmail->setEmailSubject($email->getSubject());
        $entityManager->persist($newEmail);
        $entityManager->flush();
        return $newEmail->getEmailId();
            } catch (\Exception $e) {
        return new Response('An error occurred', 500);
            }
    }
    #[OA\Tag(name: 'Emails')]
    #[Route('', name: 'GetEmails', methods: 'GET',)]
    public function GetEmails(EntityManagerInterface $entityManager): Response
    {
        $emails = $entityManager->getRepository(entityEmail::class)->findAll();

        if (!$emails) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($emails);
    }

    
    #[OA\Tag(name: 'Emails')]
    #[Route('/{id}', name: 'GetEmailsById', methods: 'GET',)]
    public function GetEmailsById(EntityManagerInterface $entityManager, string $id): Response
    {
        $email = $entityManager->getRepository(entityEmail::class)->find($id);

        if (!$email) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($email);
    }

    #[OA\Tag(name: 'Attachments')]
    #[Route('/attachments', name: 'addAttachment', methods: 'POST',)]
    public function AddAttachment(EntityManagerInterface $entityManager,string $attname,int $emailId, string $path): Response
    {
        try {
        $newAtt = new Attachment();
        $newAtt->setAttachmentContainingEmailId($emailId);
        $newAtt->setAttachmentName($attname);
        $newAtt->setAttachmentFilePath($path);
        $entityManager->persist($newAtt);
        $entityManager->flush();

        return new Response(200);
            } catch (\Exception $e) {
        return new Response('An error occurred', 500);
            }
    }

    #[OA\Tag(name: 'Attachments')]
    #[Route('/attachments/all', name: 'getAttachments', methods: 'GET',)]
    public function GetAttachments(EntityManagerInterface $entityManager): Response
    {
        $attachments = $entityManager->getRepository(Attachment::class)->findAll();
        if (!$attachments) {
            return $this->json(['error' => 'No projects found'], 404);
        }
        return $this->json($attachments);

    }
    #[OA\Tag(name: 'Attachments')]
    #[Route('/attachments/byId/{id}', name: 'getAttachmentsById', methods: 'GET',)]
    public function GetAttachmentsById(EntityManagerInterface $entityManager,string $id): Response
    {
        $attachment = $entityManager->getRepository(Attachment::class)->find($id);
        if (!$attachment) {
            return $this->json(['error' => 'No projects found'], 404);
        }
        return $this->json($attachment);

    }

    #[OA\Tag(name: 'Attachments')]
    #[Route('/attachments/byEmailId/{EmailId}', name: 'getAttachmentsByEmailId', methods: 'GET',)]
    public function GetAttachmentsByEmailId(EntityManagerInterface $entityManager,string $EmailId): Response
    {
        $query = $entityManager->createQuery("SELECT A FROM App\Entity\Attachment A WHERE A.attachmentContainingEmailId = :emailId");
        $query->setParameter('emailId', $EmailId);
        $result = $query->getResult();

        if (!$result) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($result);

    }

    
    
}
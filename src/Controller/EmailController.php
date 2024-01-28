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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: 'Emails')]
#[Route('/email', name: 'mailing', methods: 'GET')]
class EmailController extends AbstractController
{ 
    #[Route('/sendEmail/{emailModelId}/{chosenEntityId}', name: 'server', methods: 'GET',)]
    public function sendEmail(LoggerInterface $log,downloadPdf $dwPdf,replaceWords $rep,MailerInterface $mailer, EntityManagerInterface $entityManager,Request $request, string $emailModelId, string $chosenEntityId): Response
    {

        $model = $entityManager->getRepository(Models::class)->find($emailModelId);
        $entity = $entityManager->getRepository(Worker::class)->find($chosenEntityId);
        
        $tempFolder = $this->getParameter('kernel.project_dir') . '/var/temp/' . uniqid();
        $filesystem = new Filesystem();
        $filesystem->mkdir($tempFolder);
        $receivedStr = $request->query->get('sentAttachments');
        $modelsToAttach= explode(', ',$receivedStr);  

        $email = (new Email())
            ->from('teeesstt@cccc.com')
            ->to($entity->getWorkerEmail())
            ->subject($model->getModelId())
            ->text($rep->replaceKeywords($entityManager, $model->getModelText(), $chosenEntityId));
            $this->AddEmailHistory($entityManager,$email);


            foreach($modelsToAttach as $modelToAttach)
            {
                $pdf = $dwPdf->generatePdf($entityManager,$rep, $modelToAttach, $chosenEntityId);
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
    #[Route('', name: 'addEmail', methods: 'POST',)]
    public function AddEmailHistory(EntityManagerInterface $entityManager, Email $email): Response
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
        return new Response(200);
            } catch (\Exception $e) {
        return new Response('An error occurred', 500);
            }
    }
    #[Route('', name: 'addEmail', methods: 'POST',)]
    public function AddAttachemnt(EntityManagerInterface $entityManager,string $attFile,int $emailId ): Response
    {
        try {
        $newAtt = new Attachment();
        $newAtt->setAttachmentFile($attFile);
        $newAtt->setAttachmentContainingEmailId($emailId);
        $entityManager->persist($newAtt);
        $entityManager->flush();
        return new Response(200);
            } catch (\Exception $e) {
        return new Response('An error occurred', 500);
            }
    }

    
    
}
<?php
namespace App\Services;

use App\Entity\Keywords;
use App\Entity\Project;
use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class replaceWords{
    public function replaceKeywords(LoggerInterface $log,EntityManagerInterface $entityManager, string $text, int $id): string
    {
    $keys = $entityManager->getRepository(Keywords::class)->findAll();
    foreach ($keys as $key) {
        if ($key->getKeywordConcernedObject() === 'worker') {
            $entity = $entityManager->getRepository(Worker::class)->find($id);
            }
        else if ($key->getKeywordConcernedObject() === 'project'){
            $entity = $entityManager->getRepository(Project::class)->find($id);
        }

        if($entity != null && is_object($entity))
        {

            
            $replacement=eval('return ' . $key->getKeywordCorrespondingValue() .";");
            if($key->getKeywordType()=='link')  {$replacement = '<a href="' . $replacement . '">link</a>';} // add the value 
            if($key->getKeywordType()=='date')  {$replacement = $replacement->format('d-m-Y');} 
            //if($key->getKeywordType()=='image')  {$replacement = '<img src="http://localhost/backend/public/'. $key->getKeywordCorrespondingValue() . '/' .$replacement . '" width="100" height="100"/>';} 
            if ($key->getKeywordType() == 'image') {

                $imagePath = 'http://localhost/backend/public/' . preg_replace('/\$entity->get|\(\)/', '', $key->getKeywordCorrespondingValue()) . '/' . $replacement;
                $imageContent = file_get_contents($imagePath);
                $imageInfo = getimagesizefromstring($imageContent);
                $imageType = $imageInfo['mime'];
                $base64Image = base64_encode($imageContent);
                $replacement = '<img src="data:' . $imageType . ';base64,' . $base64Image . '" width="100" height="100"/>';
            }
            $text = str_replace($key->getKeywordValue(), $replacement ,$text);
        }
        }
    return $text;
    }
}
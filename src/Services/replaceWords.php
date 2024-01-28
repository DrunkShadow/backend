<?php
namespace App\Services;

use App\Entity\Keywords;
use App\Entity\Project;
use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;

class replaceWords{
    public function replaceKeywords(EntityManagerInterface $entityManager, string $text, int $id): string
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
            $replacement=(call_user_func([$entity,'get'.$key->getKeywordCorrespondingValue()]));
            if($key->getKeywordType()=='link')  {$replacement = '<a href="' . $replacement . '">link</a>';} 
            // if($key->getKeywordType()=='image')  {$replacement = '<img src="data:image/jpeg;base64,' . $replacement . '" />';} 
            if($key->getKeywordType()=='date')  {$replacement = $replacement->format('d-m-Y');} 

            $text = str_replace($key->getKeywordValue(), $replacement ,$text);
        }
        }
    return $text;
    }
}
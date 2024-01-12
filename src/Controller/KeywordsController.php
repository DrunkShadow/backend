<?php

namespace App\Controller;

use App\Entity\Keywords;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
class KeywordsController extends AbstractController
{
    public function GetKeywords(EntityManagerInterface $entityManager): Response
    {
        $keywords = $entityManager->getRepository(Keywords::class)->findAll();

        if (!$keywords) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($keywords);
    }
}


        
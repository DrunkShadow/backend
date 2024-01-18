<?php
namespace App\DTO;

class WorkerDTO
{
    public string $id;
    public string $name;
    public string $lastName;
    public string $title;
    public ?string $signature;  

    public function __construct(\App\Entity\Worker $worker)
    {
        $this->id = $worker->getId();
        $this->name = $worker->getName();
        $this->lastName = $worker->getLastName();
        $this->title = $worker->getTitle();
        $this->signature = base64_encode($worker->getSignature());
    }
}
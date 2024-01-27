<?php
namespace App\DTO;

class WorkerDTO
{
    public string $workerId;
    public string $workerName;
    public string $workerLastName;
    public string $workerTitle;
    public ?string $workerSignature;  

    public function __construct(\App\Entity\Worker $worker)
    {
        $this->workerId = $worker->getWorkerId();
        $this->workerName = $worker->getWorkerName();
        $this->workerLastName = $worker->getWorkerLastName();
        $this->workerTitle = $worker->getWorkerTitle();
        $this->workerSignature = base64_encode($worker->getWorkerSignature());
    }
}
<?php

App::uses('ComponentCollection', 'Controller');
App::uses('S3Component', 'Controller/Component');

class ExtractShell extends AppShell
{
    public $tasks = array('SQS.QueueWorker');

    public function startup()
    {
        $this->components = new ComponentCollection();
        $this->S3 = $this->components->load('S3');
    }

    public function main()
    {
        $this->QueueWorker->addFunction('extract', $this, 'handleJob');
        $this->QueueWorker->work();
    }

    public function handleJob($data)
    {
        echo '[LOG] Start extracting '.$data['key']."\n";

        return $this->S3->extract_images($data);
    }
}

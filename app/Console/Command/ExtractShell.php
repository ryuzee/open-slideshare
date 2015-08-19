<?php

App::uses('ComponentCollection', 'Controller');
App::uses('S3Component', 'Controller/Component');

/**
 * Class: ExtractShell
 *
 */
class ExtractShell extends AppShell
{
    /**
     * tasks
     *
     * @var array
     */
    public $tasks = array('SQS.QueueWorker');

    /**
     * components.
     *
     * @var mixed
     */
    public $components;

    /**
     * S3.
     *
     * @var mixed
     */
    public $S3;

    /**
     * startup
     *
     */
    public function startup()
    {
        $this->components = new ComponentCollection();
        $this->S3 = $this->components->load('S3');
    }

    /**
     * main
     *
     */
    public function main()
    {
        $this->QueueWorker->addFunction('extract', $this, 'handleJob');
        $this->QueueWorker->work();
    }

    /**
     * handleJob
     *
     * @param mixed $data
     */
    public function handleJob($data)
    {
        echo '[LOG] Start extracting ' . $data['key'] . "\n";

        return $this->S3->extract_images($data);
    }
}

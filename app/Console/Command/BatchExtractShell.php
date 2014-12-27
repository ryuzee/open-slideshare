<?php
App::uses('ComponentCollection', 'Controller');
App::uses('S3Component', 'Controller/Component');

class BatchExtractShell extends AppShell
{
    public $uses = array('Slide');

    public function startup()
    {
        $this->components = new ComponentCollection();
        $this->S3 = $this->components->load('S3');
    }

    public function main()
    {
        $slides = $this->Slide->find('all');
        foreach ($slides as $slide) {
            echo "----" . $slide["Slide"]["key"] . "----\n";
            $this->S3->extract_images($slide["Slide"]);
        }
    }
}

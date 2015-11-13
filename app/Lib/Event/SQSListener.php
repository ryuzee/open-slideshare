<?php

App::uses('CakeEventListener', 'Event');

class SQSListener implements CakeEventListener {

    public function implementedEvents() {
        return array(
            'Queue.beforeWork' => 'before_procedure',
        );
    }

    public function before_procedure($event) {
        CakeLog::write('debug', 'waiting for 60 seconds...');
        sleep(60);
    }
}

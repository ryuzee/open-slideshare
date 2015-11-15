<?php

App::uses('CakeEventListener', 'Event');

/**
 * Class: SQSListener
 *
 * @see CakeEventListener
 */
class SQSListener implements CakeEventListener {

    /**
     * implementedEvents
     *
     */
    public function implementedEvents() {
        return array(
            'Queue.beforeWork' => 'before_procedure',
        );
    }

    /**
     * before_procedure
     *
     * @param mixed $event
     */
    public function before_procedure($event) {
        CakeLog::write('debug', 'waiting for 60 seconds...');
        sleep(60);
        return true;
    }
}

<?php

App::uses('CakeEventListener', 'Event');

/**
 * Class: SlideListener
 *
 * @see CakeEventListener
 */
class SlideListener implements CakeEventListener {

    /**
     * SimpleQueue
     *
     * @var mixed
     */
    public $SimpleQueue;

    /**
     * implementedEvents
     *
     */
    public function implementedEvents() {
        return array(
            'Controller.Slide.afterAdd' => 'after_add',
        );
    }

    /**
     * after_countup
     *
     * @param mixed $event
     */
    public function after_add($event) {
        CakeLog::write('info', sprintf("Slide added. id=%s key=%s", $event->data['id'], $event->data['key']));

        $this->SimpleQueue = ClassRegistry::init('SQS.SimpleQueue');
        $this->SimpleQueue->send('extract', array('id' => $event->data['id'], 'key' => $event->data['key']));
        return true;
    }
}

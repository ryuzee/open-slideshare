<?php

App::uses('SessionComponent', 'Controller/Component');

class SessionExComponent extends SessionComponent
{
    public function setFlash($message, $element = 'alert', $params = array(), $key = 'flash')
    {
        if (empty($params)) {
            $params = array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success',
            );
        }
        parent::setFlash($message, $element, $params, $key);
    }

    public function success($message, $element = 'alert', $params = array(), $key = 'flash')
    {
        $params['plugin'] = 'BoostCake';
        $params['class'] = 'alert-success';
        $this->setFlash($message, $element, $params, $key);
    }

    public function danger($message, $element = 'alert', $params = array(), $key = 'flash')
    {
        $params['plugin'] = 'BoostCake';
        $params['class'] = 'alert-danger';
        $this->setFlash($message, $element, $params, $key);
    }

    public function info($message, $element = 'alert', $params = array(), $key = 'flash')
    {
        $params['plugin'] = 'BoostCake';
        $params['class'] = 'alert-info';
        $this->setFlash($message, $element, $params, $key);
    }

    public function warning($message, $element = 'alert', $params = array(), $key = 'flash')
    {
        $params['plugin'] = 'BoostCake';
        $params['class'] = 'alert-warning';
        $this->setFlash($message, $element, $params, $key);
    }
}

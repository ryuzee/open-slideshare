<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class AppModel extends Model
{
    /**
     * unbindFully
     *
     */
    public function unbindFully()
    {
        $unbind = array();
        foreach ($this->belongsTo as $model => $info) {
            $unbind['belongsTo'][] = $model;
        }
        foreach ($this->hasOne as $model => $info) {
            $unbind['hasOne'][] = $model;
        }
        foreach ($this->hasMany as $model => $info) {
            $unbind['hasMany'][] = $model;
        }
        foreach ($this->hasAndBelongsToMany as $model => $info) {
            $unbind['hasAndBelongsToMany'][] = $model;
        }
        $this->unbindModel($unbind, false);
    }
}

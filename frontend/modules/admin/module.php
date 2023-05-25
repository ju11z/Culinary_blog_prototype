<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function behaviors(){ return [ 'access' => [ 'class' => \yii\filters\AccessControl::className(), 'rules' => [ [ 'allow' => true, 'roles' => ['@'], ], [ 'allow' => true, 'controllers' => ['admin/test'], ], ], ], ]; }
}

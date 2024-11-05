<?php

namespace Baha2Odeh\Precognition;

use Yii;
use yii\base\Model;

/**
 * @mixin Model
 */
trait ValidateTrait
{
    public $precognitionHeader = 'Precognition';
    public $precognitionValidateOnlyHeader = 'Precognition-Validate-Only';

    public function validate($attributeNames = null, $clearErrors = true)
    {
        if (Yii::$app->has('request')) {
            $isPrecognitionRequest = Yii::$app->request->headers->get($this->precognitionHeader);
            if ($isPrecognitionRequest) {
                $precognitionValidateOnlyHeader = Yii::$app->request->headers->get($this->precognitionValidateOnlyHeader);
                if (!empty($precognitionValidateOnlyHeader)) {
                    $attributeNames = explode(',', $precognitionValidateOnlyHeader);
                }
            }
        }
        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * @return bool
     */
    public function isPrecognition(): bool
    {
        if (!Yii::$app->has('request')) {
            return false;
        }
        return Yii::$app->request->headers->get($this->precognitionHeader, false);
    }

    public function handlePrecognition()
    {
        if (!$this->isPrecognition()) {
            return;
        }
        Yii::$app->response->headers->add('vary', 'precognition');
        Yii::$app->response->headers->add('precognition', true);
        Yii::$app->response->headers->add('precognition-success', true);
        Yii::$app->response->data = '';
        Yii::$app->response->setStatusCode(204)->send();
        Yii::$app->end();
    }


    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->isPrecognition()) {
            return parent::save($runValidation, $attributeNames);
        }
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }
        $this->handlePrecognition();
    }
}

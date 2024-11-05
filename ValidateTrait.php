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
}

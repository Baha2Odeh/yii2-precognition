<?php

namespace Baha2Odeh\Precognition;

use yii\base\Model;

class Serializer extends \yii\rest\Serializer
{
    public $precognitionHeader = 'Precognition';
    public $precognitionValidateOnlyHeader = 'Precognition-Validate-Only';

    public function serialize($data)
    {
        $isPrecognitionRequest = $this->request->headers->get($this->precognitionHeader);
        if ($data instanceof Model && $data->hasErrors() && $isPrecognitionRequest) {
            return $this->serializePrecognitionModelErrors($data);
        }

        return parent::serialize($data);
    }


    /**
     * Serializes the validation errors in a model.
     * @param Model $model
     * @return array the array representation of the errors
     */
    protected function serializePrecognitionModelErrors($model)
    {
        $this->response->setStatusCode(422, 'Data Validation Failed.');
        $errors = [];
        $message = null;


        $precognitionValidateOnlyHeader = $this->request->headers->get($this->precognitionValidateOnlyHeader);
        $partialAttributes = false;
        if(!empty($precognitionValidateOnlyHeader)) {
            $precognitionValidateOnlyHeader = explode(',', $precognitionValidateOnlyHeader);
            $partialAttributes = true;
        }
        foreach ($model->getFirstErrors() as $attribute => $error) {
//            if ($partialAttributes && !isset($precognitionValidateOnlyHeader[$attribute])) {
//                continue;
//            }
            $errors[$attribute][] = $error;
            if (empty($message)) {
                $message = $error;
            }
        }
        return [
            'message' => $message,
            'errors' => $errors,
        ];
    }
}

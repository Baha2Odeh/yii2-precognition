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
        $precognitionValidateOnlyHeader = $this->request->headers->get($this->precognitionValidateOnlyHeader);
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
        foreach ($model->getFirstErrors() as $name => $message) {
            $errors[$name][] = $message;
        }

        return [
            'message' => 'null',
            'errors' => $errors,
        ];
    }
}

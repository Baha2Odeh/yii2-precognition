<?php

namespace Baha2Odeh\Precognition;

use yii\base\Model;

class Serializer extends \yii\rest\Serializer
{
    public $precognitionHeader = 'Precognition';

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
        $this->response->headers->add('precognition',true);
        $errors = [];
        $message = null;
        foreach ($model->getFirstErrors() as $attribute => $error) {
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

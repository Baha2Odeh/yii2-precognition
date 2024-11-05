<?php

namespace Baha2Odeh\Precognition;

use yii\base\Model;

class Serializer extends \yii\rest\Serializer
{
    /**
     * @var string header key to do validation only without having to save
     */
    public $precognitionHeader = 'Precognition';

    /**
     * @var string header key that allow you to submit and save, but in case there are errors to return the response as precognition
     */
    public $precognitionOnSubmitHeader = 'Precognition-On-Submit';

    public function serialize($data)
    {
        $isPrecognitionRequest = $this->request->headers->get($this->precognitionHeader);
        $isPrecognitionOnSubmitHeader = $this->request->headers->get($this->precognitionOnSubmitHeader);
        if ($data instanceof Model && $data->hasErrors() && ($isPrecognitionRequest || $isPrecognitionOnSubmitHeader) ) {
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
        $this->response->headers->add('precognition','true');
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

<?php

namespace Baha2Odeh\Precognition;

use yii\base\Model;

/**
 * @mixin Model
 */
trait ValidateTrait
{

    public function validate($attributeNames = null, $clearErrors = true){
        return parent::validate($attributeNames = null, $clearErrors = true);
    }
}

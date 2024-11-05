Yii2 Precognition
=================
Yii2 Precognition

Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist baha2odeh/yii2-precognition "*"
```

or add

```
"baha2odeh/yii2-precognition": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by:

add this trait to the ActiveRecord 

```php
class User extends ActiveRecord {

...
use \Baha2Odeh\Precognition\ValidateTrait;
...

```

You can also use this method with a custom model 

`$this->handlePrecognition();`

This method stops request processing if there are no errors and the request is a 'precognition' request, meaning it's not an actual submission but a preliminary check.
```php
class LoginForm extends Model {

...
use \Baha2Odeh\Precognition\ValidateTrait;
...

public function process(){
    ...
    if(!$this->validate()){
        return false;
    }
    $this->handlePrecognition();
    ...
}

```


Last step to change the restful response serializer to handle the 'precognition' requests
```php
class UserController extends \yii\rest\ActiveController
{
    ...
    public $serializer = \Baha2Odeh\Precognition\Serializer::class;
    ...
```


in frontend side you have to add a new header in the axios requests to allow Yii to return 'precognition' response in case the submit was failed
```js
import axios from 'axios'
import { client } from 'laravel-precognition-vue';

axios.interceptors.request.use(config => {
config.headers['precognition-on-submit'] = "true"
return config
})

client.use(axios)
```

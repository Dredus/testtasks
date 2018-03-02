<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AddNewsForm extends Model
{
	public $ftitle;
    public $ftext;
    public $file;	

    public function rules()
    {
        return [
            [['ftitle', 'ftext'], 'required', 'message' => 'Не заполнено поле'],
            [['file'], 'file', 'extensions' => 'png, jpg']
        ];
    }	
}

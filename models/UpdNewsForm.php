<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UpdNewsForm extends Model
{
	public $utitle;
    public $utext;
    public $ufile;
    public $uid;	

    public function rules()
    {
        return [
            [['utitle', 'utext','uid'], 'required', 'message' => 'Не заполнено поле'],
            [['ufile'], 'file', 'extensions' => 'png, jpg']
        ];
    }	
}
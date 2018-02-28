<?php

namespace app\models;

use Yii;
use yii\base\Model;

class AddNewsForm extends Model
{
	public $ftitle;
    public $ftext;	

    public function rules()
    {
        return [
            [['ftitle', 'ftext'], 'required']
        ];
    }	
}

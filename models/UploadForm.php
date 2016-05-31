<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

    /**
     * @var UploadedFile
     */
    public $pdfFile;
    public $fileName;
    public $created;
    public $created_by;

    public function rules() {
        return [
            [['pdfFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'/*, 'maxFiles' => 4*/],
            ['fileName', 'safe'],
            ['created', 'default', 'value' => date("Y-m-d H:i:s"), 'on' => 'create'],
            ['created_by', 'default', 'value' => Yii::$app->user->identity->email, 'on' => 'create'],
        ];
    }

    public function upload() {
        if ($this->validate()) {
            //foreach ($this->pdfFile as $file) {
            $this->pdfFile->saveAs('uploads/documents/' . $this->pdfFile->baseName  . '.' . $this->pdfFile->extension);
            //}
            return true;
        } else {
            //print_r($this->getErrors());
            return false;
        }
    }

}

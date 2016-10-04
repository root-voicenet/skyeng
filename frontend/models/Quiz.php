<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Quiz extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
	const STATUS_CLOSED = 20;

    public static function tableName()
    {
        return 'quiz';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    public static function findActive($user_id)
    {
        return static::findOne(['user_id' => $user_id, 'status' => [self::STATUS_ACTIVE, self::STATUS_CLOSED]]);
    }

   
	public static function getQuiz($user_id) 
	{
		$quiz = self::findActive($user_id);
		if(!$quiz) {		
			$quiz = new Quiz;
			$quiz->user_id = $user_id;
			$quiz->status = self::STATUS_ACTIVE;
			$quiz->save();
		}
		return $quiz;
	}
}
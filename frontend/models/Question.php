<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class Question extends ActiveRecord
{
	public function getQuestion($dir)
	{
		return $dir ? $this->ru : $this->en;
	}
	
	public function getAnswer($dir) 
	{
		return $dir ? $this->en : $this->ru;
	}
	
	public static function tableName()
    {
        return 'question';
    }
	
	public static function nextQuestion($last_id)
	{
		return static::find()->where(['>', 'id', $last_id])->one();
	}
	
	public static function getCount()
	{
		return Yii::$app->db->createCommand('SELECT COUNT(*) FROM ' . self::tableName())
			->queryScalar();
			
	}
	
	public static function randQuestion($max)
	{
		$offset = rand(0,$max);
				
		$question = static::find()
			->limit(1)->offset($offset)
			->one();
			
		return $question;
	}
	
	public static function logWrongAnswer($question_id, $answer, $direction)
	{
		Yii::$app->db->createCommand('INSERT INTO wronganswer (question_id, answer, direction) VALUES(:qid, :answer, :dir)',	[
			':qid' => $question_id,
			':answer' => $answer,
			':dir' => $direction
		])->execute();			
	}
}
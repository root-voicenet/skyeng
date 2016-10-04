<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\ContactForm;
use frontend\models\Quiz;
use frontend\models\Question;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

/**
 * Site controller
 */
class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['quiz', 'clear'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['quiz', 'clear'],
            'rules' => [
                [
                    'actions' => ['quiz', 'clear'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return ['access_token' => Yii::$app->user->identity->getAuthKey()];
        } else {
            $model->validate();
            return $model;
        }
    }
	
	public function actionClear()
	{
		$quiz = Quiz::getQuiz(Yii::$app->user->identity->getId());
		if($quiz->status == Quiz::STATUS_CLOSED) {
			$quiz->status = Quiz::STATUS_DELETED;
			$quiz->save();
			
			return ['result' => true];
		}else{
			return ['result' => false];
		}
	}
	
	

    public function actionQuiz()
    {
		$quiz = Quiz::getQuiz(Yii::$app->user->identity->getId());
				
		$response = [
			'status' => ['username' => Yii::$app->user->identity->username]
		];
       				
		$count = Question::getCount();
		if($quiz->status == Quiz::STATUS_ACTIVE) {
			$last_question = (int)$quiz->last_question > 0 ? $quiz->last_question : 0;
			$question = Question::nextQuestion($last_question);

			$answer = Yii::$app->getRequest()->post('answer');
			if(!empty($answer)) {
				$question_dir = (int)Yii::$app->getRequest()->post('dir');
				if($answer != $question->getAnswer($question_dir)) {
					$response = array_merge($response, [
						'error' => true,
						'answer' => $question->getAnswer($question_dir)
					]);
					$quiz->user_errors = (int)$quiz->user_errors + 1;
					Question::logWrongAnswer($question->id, $answer, $question_dir);
				}else{
					$last_question = $quiz->last_question = $question->id;
					$quiz->user_rate += 1;
					$question = Question::nextQuestion($last_question);
				}
				$quiz->save();
			}
			
			if(!$question) {
				$quiz->status = Quiz::STATUS_CLOSED;
				$quiz->save();
			}
		}
		
		if($quiz->status == Quiz::STATUS_CLOSED) {
			$response = array_merge($response, [
				'user_rate' => $quiz->user_rate,
				'report'	=> true
			]);
		}elseif($quiz->status == Quiz::STATUS_ACTIVE) {
			$wrongs = [];
			$wrongCount = 3;
			$dir = rand(0, 1);	// direction 0 - question in en, 1 qusstion in ru
			
			while($wrongCount < $count && count($wrongs) < $wrongCount) {
				$wrong = Question::randQuestion($count - 1);
				if($wrong->id == $question->id)
					continue;
				if(in_array($wrong->getAnswer($dir), $wrongs))
					continue;
				
				$wrongs[] = $wrong->getAnswer($dir);
			}
			
			$answers = array_merge($wrongs, [$question->getAnswer($dir)]);
			shuffle($answers);
			$response = array_merge($response, [
				'question' => [
					'id' 		=> $question->id,
					'dir'		=> $dir,
					'message'	=> $question->getQuestion($dir)
				],
				'answers' => $answers
			]);
		}
		
		if($quiz->user_errors >= 3) {
			$quiz->status = Quiz::STATUS_CLOSED;
			$quiz->save();
		}
		
        return $response;
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                $response = [
                    'flash' => [
                        'class' => 'success',
                        'message' => 'Thank you for contacting us. We will respond to you as soon as possible.',
                    ]
                ];
            } else {
                $response = [
                    'flash' => [
                        'class' => 'error',
                        'message' => 'There was an error sending email.',
                    ]
                ];
            }
            return $response;
        } else {
            $model->validate();
            return $model;
        }
    }
}

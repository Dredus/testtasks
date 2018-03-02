<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\News;
use app\models\AddNewsForm;
use app\models\UpdNewsForm;
use yii\data\Pagination;
use yii\helpers\Html;

use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionNews(){

        $form = new AddNewsForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $title = Html::encode($form->ftitle);
            $text = Html::encode($form->ftext);
            $form->file = UploadedFile::getInstance($form, 'file');  
            if($title && $text && $form->file){
                $path = realpath(dirname(__FILE__)).'/../web/images/';
                $form->file->saveAs($path . $form->file);
                $photo = Image::getImagine()->open($path . $form->file);
                $photo->thumbnail(new Box(125, 125))->save($path . $form->file, ['quality' => 90]);
                $news = new News;
                $news->title = $title;
                $news->text = $text;
                $news->photo = '/web/images/'.$form->file;
                $news->date_create = date('Y-m-d H:i:s');
                $news->save();
                return $this->refresh();
            }      
        }

        $formp = new UpdNewsForm();

        if ($formp->load(Yii::$app->request->post()) && $formp->validate()) {
            $title = Html::encode($formp->utitle);
            $text = Html::encode($formp->utext);
            $formp->ufile = UploadedFile::getInstance($formp, 'file');
            $id = Html::encode($formp->uid);
            $news = new News;
            $news = $news->findOne($id);
            if(!empty($formp->ufile)){
                $path = realpath(dirname(__FILE__)).'/../web/images/';
                $formp->ufile->saveAs($path . $formp->ufile);
                $photo = Image::getImagine()->open($path . $formp->ufile);
                $photo->thumbnail(new Box(125, 125))->save($path . $formp->ufile, ['quality' => 90]);
                $news->photo = '/web/images/'.$formp->ufile;
            }
            $news->title = $title;
            $news->text = $text;
            
            $news->date_create = date('Y-m-d H:i:s');
            $news->update();
            return $this->refresh();     
        } 

        $news = News::find();

        if(Yii::$app->request->isAjax && Yii::$app->request->post('id')){
            $id = Html::encode(Yii::$app->request->post('id'));
            if(Yii::$app->request->post('active') == 'update'){         
                $upnews = $news->where(['id' => $id])->all();
                $arr = array();
                foreach ($upnews as $ns) {
                   $arr['title'] = $ns->title;
                   $arr['text'] = $ns->text;
                   $arr['photo'] = $ns->photo;
                }
                return json_encode($arr);
            }
            if(Yii::$app->request->post('active') == 'delete'){
                $delnews = new News;
                $delnews = $delnews->findOne($id);
                $delnews->delete();
                return json_encode('deleted');
            }
        }

        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' => $news->count()
        ]);
        $news = $news->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        
        return $this->render('news',
            [
                'news'=>$news,
                'pagination'=>$pagination,
                'form'=>$form,
                'formp'=>$formp
            ]
        );
    }
}

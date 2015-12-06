<?php
namespace frontend\controllers;

use app\models\Catalog;
use app\models\News;
use app\models\Product;
use app\models\Section;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Edit;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
     * @inheritdoc
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
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Display catalog
     *
     * @return string
     */

    public function actionCatalog()
    {
        $catalog = Catalog::find()
            ->all();

        return $this->render('catalog', [
            'catalog' => $catalog,
        ]);
    }

    /**
     * Display section
     *
     * @return string
     */
    public function actionSection()
    {
        $sect = $_GET['sect'];

        $section = Section::find()
            ->where(['=', 'catalog_id', $sect])
            ->all();

        $titleSection = Catalog::find()
            ->where(['=', 'id_catalog', $sect])
            ->all();

        return $this->render('section', [
            'section' => $section,
            'titleSection' => $titleSection,
        ]);
    }

    /**
     * Display product
     */

    public function actionProduct()
    {
        $item = $_GET['item'];

        $product = Product::find()
            ->where(['=', 'section_id', $item])
            ->all();

        $titleProduct = Section::find()
            ->where(['=', 'id_section', $item])
            ->all();

        foreach ($titleProduct as $vlUrl) {
            $url = $vlUrl->catalog_id;
        }

        $catalog = Catalog::find()
            ->where(['=', 'id_catalog', $url])
            ->all();

        return $this->render('product', [
            'product' => $product,
            'titleProduct' => $titleProduct,
            'catalog' => $catalog,
        ]);
    }

    /**
     * Display item
     */

    public function actionArticle()
    {
        $article = new Product();

        $ware = $_GET['ware'];

        // find product
        $article = Product::find()
            ->where(['=', 'id_product', $ware])
            ->all();

        foreach ($article as $vlArticle) {
            $curSection = $vlArticle->section_id;
        }

        // find section
        $section = Section::find()
            ->where(['=', 'id_section', $curSection])
            ->all();

        foreach ($section as $vlSection) {
            $curCatalog = $vlSection->catalog_id;
        }

        // find catalog
        $catalog = Catalog::find()
            ->where(['=', 'id_catalog', $curCatalog])
            ->all();

        return $this->render('article', [
            'article' => $article,
            'section' => $section,
            'catalog' => $catalog,
        ]);
    }

    /**
     * Display news
     *
     * @return string
     */

    public function actionNews()
    {
        $id = $_GET['id'];

        $news = News::find()
            ->where(['=', 'id_news', $id])
            ->all();

        return $this->render('news', [
            'news' => $news,
        ]);
    }

    /**
     * Display all news
     */

    public function actionEvents()
    {
        // contact
        $model = new ContactForm();

        // month
        $month = array('01' => 'Январь', '02' => 'Февраль', '03' => 'Март', '04' => 'Апрель',
            '05' => 'Май', '06' => 'Июнь', '07' => 'Июль', '08' => 'Август',
            '09' => 'Сентябрь', '10' => 'Октябрь', '11' => 'Ноябрь', '12' => 'Декабрь');

        // year
        $allYears = News::find()
            ->all();

        foreach ($allYears as $vlYears) {
            $current = substr($vlYears->date_news, 0, 4);
            $years[$current] = $current;
        }

        $years = array_unique($years);

        return $this->render('events', [
            'model' => $model,
            'month' => $month,
            'years' => $years,
        ]);
    }

    /**
     * ajax request news
     *
     * @return string
     */

    public function actionIncident()
    {
        if (isset($_POST['incident']) || (isset($_POST['years'])) ) {

            $incident = Yii::$app->request->post('incident');

            $years = Yii::$app->request->post('years');

            $events = News::find()
                ->where(['LIKE', 'date_news', $years.'-' . $incident . '-__', false])
                ->all();

        }

        return $this->renderAjax('incident', [
            'events' => $events,
        ]);
    }

    /**
     * range
     */

    public function actionRange()
    {

        return $this->render('range');

    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

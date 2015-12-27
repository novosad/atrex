<?php
namespace frontend\controllers;

use app\models\Catalog;
use app\models\News;
use app\models\Product;
use app\models\Review;
use app\models\SearchTable;
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
        // form
        $model = new ContactForm();

        // id product
        $ware = $_GET['ware'];

        // get data review
        if ($model->load(Yii::$app->request->post())) {
            // get data form
            $reviewData = Yii::$app->request->post("ContactForm");

            // get name and body
            $reviewName = $reviewData['name'];
            $reviewBody = $reviewData['body'];

            // get current date
            $reviewDate = date("Y") . '-' . date("m") . '-' . date("d");

            // moderation
            $reviewModeration = 'no';

            // insert review
            $review = new Review([
                'review_date' => $reviewDate,
                'review_name' => $reviewName,
                'product_id' => $ware,
                'review' => $reviewBody,
                'review_moderation' => $reviewModeration,
            ]);
            $review->save();

            Yii::$app->session->setFlash('success', 'После успешной модерации отзыв будет опубликован.');

        }

        // count review product
        $comment = Review::find()
            ->where(['=', 'product_id', $ware])
            ->andWhere(['=', 'review_moderation', 'yes'])
            ->all();
        $amount = count($comment);

        // find product
        $article = Product::find()
            ->where(['=', 'id_product', $ware])
            ->all();

        // current section
        foreach ($article as $vlArticle) {
            $curSection = $vlArticle->section_id;
        }

        // find section
        $section = Section::find()
            ->where(['=', 'id_section', $curSection])
            ->all();

        // current catalog
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
            'model' => $model,
            'amount' => $amount,
            'comment' => $comment,
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
        if (isset($_POST['incident']) || (isset($_POST['years']))) {

            $incident = Yii::$app->request->post('incident');

            $years = Yii::$app->request->post('years');

            $events = News::find()
                ->where(['LIKE', 'date_news', $years . '-' . $incident . '-__', false])
                ->all();

        }

        return $this->renderAjax('incident', [
            'events' => $events,
        ]);
    }

    /**
     * view result
     *
     * @return string
     */

    public function actionResult()
    {

        if (isset($_POST['search-bt'])) {
            // search
            $search_text = Yii::$app->request->post('search-text');
            // query
            $resSearch = SearchTable::find()
                ->where("MATCH (name_search) AGAINST ('$search_text' ) ")
                ->all();

            if (count($resSearch) > 0) {

                foreach ($resSearch as $rowSearch) {
                    $name_all[] = $rowSearch->name_search;
                    $type_all[] = $rowSearch->type_search;
                    $link_all[] = $rowSearch->link_search;
                }

                // priority
                $priority_type = $type_all[0];
                $priority_link = $link_all[0];

                // choice priority
                switch ($priority_type) {
                    // catalog
                    case 1:
                        $count_catalog = count($name_all);
                        // phrase
                        if ($count_catalog != 1) {

                            // all product from query
                            for ($pr = 0; $pr < count($link_all); $pr++) {
                                if ($type_all[$pr] == '3') {
                                    $bfProduct[] = $name_all[$pr];
                                }
                            }
                            foreach ($bfProduct as $bflProduct) {
                                $resSection = Section::find()
                                    ->where(['=', 'catalog_id', $priority_link])
                                    ->all();
                                foreach ($resSection as $rowSection) {
                                    $section[] = $rowSection->id_section;
                                }

                                foreach ($section as $vlSection) {
                                    $resProduct = Product::find()
                                        ->where(['=', 'section_id', $vlSection])
                                        ->andWhere(['=', 'product_name', $bflProduct])
                                        ->all();
                                }
                                foreach ($resProduct as $rowProduct) {
                                    $id_product[] = $rowProduct->id_product;
                                    $product_name[] = $rowProduct->product_name;
                                    $photo_image[] = $rowProduct->photo;
                                }
                            }
                            $product = array_combine($id_product, $product_name);
                            $image = array_combine($id_product, $photo_image);
                        } else {
                            $resSection = Section::find()
                                ->where(['=', 'catalog_id', $priority_link])
                                ->all();
                            foreach ($resSection as $rowSection) {
                                $section[] = $rowSection->id_section;
                            }

                            foreach ($section as $vlSection) {
                                $resProduct = Product::find()
                                    ->where(['=', 'section_id', $vlSection])
                                    ->all();
                            }
                            foreach ($resProduct as $rowProduct) {
                                $id_product[] = $rowProduct->id_product;
                                $product_name[] = $rowProduct->product_name;
                                $photo_image[] = $rowProduct->photo;
                            }
                            $product = array_combine($id_product, $product_name);
                            $image = array_combine($id_product, $photo_image);
                        }

                        break;
                    // section
                    case 2:
                        for ($pr = 0; $pr < count($link_all); $pr++) {
                            if ($type_all[$pr] == '3') {
                                $section[] = $link_all[$pr];
                            }
                        }

                        foreach ($section as $vlSection) {
                            $resProduct = Product::find()
                                ->where(['=', 'id_product', $vlSection])
                                ->all();

                            foreach ($resProduct as $rowProduct) {
                                $id_product[] = $rowProduct->id_product;
                                $product_name[] = $rowProduct->product_name;
                                $photo_image[] = $rowProduct->photo;
                            }
                            $product = array_combine($id_product, $product_name);
                            $image = array_combine($id_product, $photo_image);
                        }

                        break;
                    // product
                    case 3:
                        $resProduct = Product::find()
                            ->where(['=', 'id_product', $priority_link])
                            ->all();

                        foreach ($resProduct as $rowProduct) {
                            $id_product[] = $rowProduct->id_product;
                            $product_name[] = $rowProduct->product_name;
                            $photo_image[] = $rowProduct->photo;
                        }
                        $product = array_combine($id_product, $product_name);
                        $image = array_combine($id_product, $photo_image);
                        break;
                }

            } else {
                $product = null;
                $image = null;
            }
        }

        return $this->render('result', [
            'product' => $product,
            'search_text' => $search_text,
            'image' => $image,
        ]);
    }

    /**
     * selection product
     */

    public function actionSelection()
    {
        return $this->render('selection');
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

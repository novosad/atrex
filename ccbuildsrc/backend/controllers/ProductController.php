<?php

namespace backend\controllers;

use Yii;
use app\models\Product;
use app\models\SearchTable;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // data product
            $data_product = $_POST['Product'];
            // name product
            foreach ($data_product as $vlProduct){
                $name_product = $vlProduct;
            }

            // last id catalog
            $last_product = Product::find()
                ->orderBy('id_product DESC')
                ->one();
            $last = $last_product->id_product;

            // insert search_table
            $search_product = new SearchTable([
                'name_search' => $name_product,
                'type_search' => '3',
                'link_search' => $last,
            ]);
            $search_product->save();

            return $this->redirect(['view', 'id' => $model->id_product]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // data update
            $data_update = $_POST['Product'];
            $product_update = $data_update['product_name'];
            $id_update = $data_update['id_product'];

            SearchTable::updateAll(['name_search' => $product_update],
                ['link_search' => $id_update, 'type_search' => '3']);

            return $this->redirect(['view', 'id' => $model->id_product]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // delete date
        $date_delete = $this->findModel($id);
        $id_delete = $date_delete["id_product"];
        $name_delete = $date_delete['product_name'];

        SearchTable::deleteAll(['name_search' => $name_delete, 'link_search' => $id_delete,
            'type_search' => '3']);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

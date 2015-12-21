<?php

namespace backend\controllers;

use Yii;
use app\models\Catalog;
use app\models\SearchTable;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogController implements the CRUD actions for Catalog model.
 */
class CatalogController extends Controller
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
     * Lists all Catalog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Catalog::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Catalog model.
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
     * Creates a new Catalog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Catalog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // data catalog
            $data_catalog = $_POST['Catalog'];
            // name catalog
            foreach ($data_catalog as $vlCatalog){
                $name_catalog = $vlCatalog;
            }

            // last id catalog
            $last_catalog = Catalog::find()
                ->orderBy('id_catalog DESC')
                ->one();
            $last = $last_catalog->id_catalog;

            // insert search_table
            $search_catalog = new SearchTable([
                'name_search' => $name_catalog,
                'type_search' => '1',
                'link_search' => $last,
            ]);
            $search_catalog->save();

            return $this->redirect(['view', 'id' => $model->id_catalog]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Catalog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // data update
            $data_update = $_POST['Catalog'];
            $catalog_update = $data_update['catalog_name'];
            $id_update = $data_update['id_catalog'];

            SearchTable::updateAll(['name_search' => $catalog_update],
                ['link_search' => $id_update, 'type_search' => '1']);

            return $this->redirect(['view', 'id' => $model->id_catalog]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Catalog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // delete date
        $date_delete = $this->findModel($id);
        $id_delete = $date_delete["id_catalog"];
        $name_delete = $date_delete['catalog_name'];

        SearchTable::deleteAll(['name_search' => $name_delete, 'link_search' => $id_delete,
            'type_search' => '1']);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Catalog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Catalog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Catalog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

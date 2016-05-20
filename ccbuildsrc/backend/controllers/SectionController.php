<?php

namespace backend\controllers;

use Yii;
use app\models\Section;
use app\models\SearchTable;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SectionController implements the CRUD actions for Section model.
 */
class SectionController extends Controller
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
     * Lists all Section models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Section::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Section model.
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
     * Creates a new Section model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Section();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // data section
            $data_section = $_POST['Section'];
            // name catalog
            foreach ($data_section as $vlSection){
                $name_section = $vlSection;
            }

            // last id catalog
            $last_section = Section::find()
                ->orderBy('id_section DESC')
                ->one();
            $last = $last_section->id_section;

            // insert search_table
            $search_section = new SearchTable([
                'name_search' => $name_section,
                'type_search' => '2',
                'link_search' => $last,
            ]);
            $search_section->save();

            return $this->redirect(['view', 'id' => $model->id_section]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Section model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // data update
            $data_update = $_POST['Section'];
            $section_update = $data_update['section_name'];
            $id_update = $data_update['id_section'];

            SearchTable::updateAll(['name_search' => $section_update],
                ['link_search' => $id_update, 'type_search' => '2']);

            return $this->redirect(['view', 'id' => $model->id_section]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Section model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // delete date
        $date_delete = $this->findModel($id);
        $id_delete = $date_delete["id_section"];
        $name_delete = $date_delete['section_name'];

        SearchTable::deleteAll(['name_search' => $name_delete, 'link_search' => $id_delete,
            'type_search' => '2']);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Section the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Section::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

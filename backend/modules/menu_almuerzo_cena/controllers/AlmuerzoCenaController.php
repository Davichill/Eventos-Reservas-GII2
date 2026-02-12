<?php

namespace backend\modules\menu_almuerzo_cena\controllers;

use Yii;
use backend\modules\menu_almuerzo_cena\models\MenuAlmuerzoCena;
use backend\modules\menu_almuerzo_cena\models\search\AlmuerzoCenaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile; // <--- ASEGÚRATE DE QUE ESTA LÍNEA ESTÉ AQUÍ

class AlmuerzoCenaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {    
        $searchModel = new AlmuerzoCenaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "Detalle #".$id,
                'content'=>$this->renderAjax('view', ['model' => $this->findModel($id)]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                           Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];    
        }else{
            return $this->render('view', ['model' => $this->findModel($id)]);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new MenuAlmuerzoCena();  

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear Nuevo",
                    'content'=>$this->renderAjax('create', ['model' => $model]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                               Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            } else if($model->load($request->post())) {
                
                // --- LÓGICA DE IMAGEN ---
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }
                // -----------------------

                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Crear Nuevo",
                        'content'=>'<span class="text-success">Creado con éxito</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                   Html::a('Crear más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];         
                }
            }
            return [
                'title'=> "Crear Nuevo",
                'content'=>$this->renderAjax('create', ['model' => $model]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                           Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];         
        } else {
            if ($model->load($request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }
                if($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar #".$id,
                    'content'=>$this->renderAjax('update', ['model' => $model]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                               Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            } else if($model->load($request->post())){
                
                // --- LÓGICA DE IMAGEN ---
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }
                // -----------------------

                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Detalle #".$id,
                        'content'=>$this->renderAjax('view', ['model' => $model]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                   Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
                }
            }
            return [
                'title'=> "Actualizar #".$id,
                'content'=>$this->renderAjax('update', ['model' => $model]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                           Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];        
        } else {
            if ($model->load($request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }
                if($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }

    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' ));
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = MenuAlmuerzoCena::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
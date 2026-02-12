<?php

namespace backend\modules\menu_desayuno\controllers;

use Yii;
// 1. Asegúrate de que coincida exactamente con el nombre de la clase en tu archivo Model
use backend\modules\menu_desayuno\models\MenuDesayunos; 
use backend\modules\menu_desayuno\models\search\MenuDesayunoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile; 

class DesayunoController extends Controller
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
        $searchModel = new MenuDesayunoSearch();
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
                'title'=> "Desayuno #".$id,
                'content'=>$this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                           Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new MenuDesayunos();  // Uso de Mayúscula consistente

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear Nuevo Desayuno",
                    'content'=>$this->renderAjax('create', ['model' => $model]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            } else if($model->load($request->post())){
                // PROCESAR IMAGEN
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }
                
                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Crear Nuevo Desayuno",
                        'content'=>'<span class="text-success">Creado correctamente</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Crear otro',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];         
                }
            }
            return [
                'title'=> "Crear Nuevo Desayuno",
                'content'=>$this->renderAjax('create', ['model' => $model]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }else{
            if ($model->load($request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }
                if($model->save()){
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
            }else if($model->load($request->post())){
                // PROCESAR IMAGEN EN UPDATE
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }

                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Desayuno #".$id,
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
        }else{
            if ($model->load($request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }
                if($model->save()){
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
        // Cambiado a MenuDesayunos para ser consistente
        if (($model = MenuDesayunos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
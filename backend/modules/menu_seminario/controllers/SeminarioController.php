<?php

namespace backend\modules\menu_seminario\controllers;

use Yii;
use backend\modules\menu_seminario\models\MenuSeminario; // M Mayúscula para consistencia
use backend\modules\menu_seminario\models\search\SeminarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile; // Importante para la carga de archivos

/**
 * SeminarioController implements the CRUD actions for MenuSeminario model.
 */
class SeminarioController extends Controller
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all MenuSeminario models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new SeminarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MenuSeminario model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Menu Seminario #".$id,
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

    /**
     * Creates a new MenuSeminario model.
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new MenuSeminario();  

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear Nuevo Seminario",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                
                // --- LÓGICA DE IMAGEN ---
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }

                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Crear Nuevo Seminario",
                        'content'=>'<span class="text-success">Seminario creado con éxito</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Crear otro',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];         
                }
            }
            
            return [
                'title'=> "Crear Nuevo Seminario",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                ]),
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

    /**
     * Updates an existing MenuSeminario model.
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Seminario #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                
                // --- LÓGICA DE IMAGEN EN UPDATE ---
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }

                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Menu Seminario #".$id,
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
                }
            }
            
            return [
                'title'=> "Actualizar Seminario #".$id,
                'content'=>$this->renderAjax('update', [
                    'model' => $model,
                ]),
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

    /**
     * Delete an existing MenuSeminario model.
     */
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

    /**
     * Bulk Delete
     */
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

    /**
     * Finds the MenuSeminario model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = MenuSeminario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
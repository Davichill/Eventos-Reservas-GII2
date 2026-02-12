<?php

namespace backend\modules\menu_coffee_break\controllers;

use Yii;
use backend\modules\menu_coffee_break\models\MenuCoffeeBreak;
use backend\modules\menu_coffee_break\models\search\CoffeeBreakSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile; // Importante para manejar archivos

/**
 * CoffeeBreakController implements the CRUD actions for MenuCoffeeBreak model.
 */
class CoffeeBreakController extends Controller
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
     * Lists all MenuCoffeeBreak models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new CoffeeBreakSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MenuCoffeeBreak model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Menu Coffee Break #".$id,
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
     * Creates a new MenuCoffeeBreak model.
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new MenuCoffeeBreak();  

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo Coffee Break",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                
                // Lógica para procesar la imagen
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }

                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Crear nuevo Coffee Break",
                        'content'=>'<span class="text-success">Coffee Break creado con éxito</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Crear más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];         
                }
            }
            
            return [
                'title'=> "Crear nuevo Coffee Break",
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
     * Updates an existing MenuCoffeeBreak model.
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Coffee Break #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                
                // Lógica para procesar la imagen en actualización
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->imagen = file_get_contents($model->imageFile->tempName);
                }

                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Menu Coffee Break #".$id,
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
                }
            }
            
            return [
                'title'=> "Actualizar Coffee Break #".$id,
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
     * Delete an existing MenuCoffeeBreak model.
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
     * Finds the MenuCoffeeBreak model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = MenuCoffeeBreak::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
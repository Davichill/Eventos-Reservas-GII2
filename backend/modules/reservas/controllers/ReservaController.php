<?php

namespace backend\modules\reservas\controllers;

use Yii;
use backend\modules\reservas\models\Reservas;
use backend\modules\clientes\models\Clientes;
use backend\modules\reservas\models\TiposEvento;
use backend\modules\reservas\models\Salones;
use backend\modules\reservas\models\search\ReservasSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * ReservaController implements the CRUD actions for Reservas model.
 */
class ReservaController extends Controller
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
     * Lists all Reservas models.
     */
    public function actionIndex()
    {
        $searchModel = new ReservasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $pagos = \backend\modules\reservas\models\PagosReservas::find()
            ->where(['id_reserva' => $id])
            ->orderBy(['fecha_pago' => SORT_DESC])
            ->all();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Expediente #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                    'pagos' => $pagos,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
                'pagos' => $pagos,
            ]);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Reservas();

        // Preparamos los datos para los dropdowns
        $clientes = ArrayHelper::map(Clientes::find()->all(), 'id', 'cliente_nombre');
        $tiposEvento = ArrayHelper::map(TiposEvento::find()->all(), 'id', 'nombre');
        $salones = ArrayHelper::map(Salones::find()->all(), 'id', 'nombre_salon');

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Reserva",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'clientes' => $clientes,
                        'tiposEvento' => $tiposEvento,
                        'salones' => $salones,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Generar Link', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $model->id_coordinador = Yii::$app->user->id;
                if ($model->save()) {
                    // Construimos la URL que el cliente usará
                    // Nota: 'publico/ver' será la ruta que crearemos para el cliente
                    $urlCliente = Yii::$app->urlManager->createAbsoluteUrl(['publico/ver', 'token' => $model->token]);

                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "¡Reserva Creada!",
                        'content' => '
            <div class="alert alert-success text-center">
                <h4><i class="fa fa-check"></i> El link se ha generado correctamente</h4>
                <p>Copia el siguiente enlace para enviárselo al cliente:</p>
                <div class="well" style="word-break: break-all;">
                    <strong>' . $urlCliente . '</strong>
                </div>
                <button class="btn btn-info btn-xs" onclick="navigator.clipboard.writeText(\'' . $urlCliente . '\')">
                    <i class="fa fa-copy"></i> Copiar al portapapeles
                </button>
            </div>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
                    ];
                }
            }

            return [
                'title' => "Nueva Reserva",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'clientes' => $clientes,
                    'tiposEvento' => $tiposEvento,
                    'salones' => $salones,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'clientes' => $clientes,
                'tiposEvento' => $tiposEvento,
                'salones' => $salones,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        // También necesitamos los datos aquí para que el formulario de edición funcione
        $clientes = ArrayHelper::map(Clientes::find()->all(), 'id', 'nombre_completo');
        $tiposEvento = ArrayHelper::map(TiposEvento::find()->all(), 'id', 'nombre');
        $salones = ArrayHelper::map(Salones::find()->all(), 'id', 'nombre_salon');

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Reserva #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'clientes' => $clientes,
                        'tiposEvento' => $tiposEvento,
                        'salones' => $salones,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar Cambios', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Reserva #" . $id,
                    'content' => $this->renderAjax('view', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Actualizar Reserva #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'clientes' => $clientes,
                        'tiposEvento' => $tiposEvento,
                        'salones' => $salones,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'clientes' => $clientes,
                    'tiposEvento' => $tiposEvento,
                    'salones' => $salones,
                ]);
            }
        }
    }

    // ... (Mantén actionDelete, actionBulkDelete y findModel igual que los tienes)


    /**
     * Delete an existing Reservas model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }


    }

    /**
     * Delete multiple existing Reservas model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the Reservas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reservas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reservas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\modules\reservas\models\Reservas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class PublicoController extends Controller
{
    // Layout false para que no cargue el menú lateral de administración
    public $layout = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['ver', 'finalizar'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionVer($token)
    {
        // 1. Buscar la reserva. Usamos 'asArray' para facilitar el manejo en tu vista actual
        $reserva = Reservas::find()
            ->joinWith(['tipoEvento']) // Asegúrate de tener esta relación en el modelo Reservas
            ->where(['token' => $token, 'reservas.estado' => 'Pendiente'])
            ->asArray()
            ->one();

        if (!$reserva) {
            throw new NotFoundHttpException('El enlace no existe, ha expirado o la reserva ya fue confirmada.');
        }

        // 2. Manejo de Idioma
        $lang = Yii::$app->request->get('lang', Yii::$app->session->get('lang', 'es'));
        Yii::$app->session->set('lang', $lang);

        // 3. Cargar traducciones desde el archivo físico
        $idiomasPath = Yii::getAlias('@backend/web/idiomas.php');
        if (file_exists($idiomasPath)) {
            require($idiomasPath);
        } else {
            // Fallback en caso de que el archivo no esté para que no de error 500
            $texts = ['es' => ['confirmacion' => 'Confirmación'], 'en' => ['confirmacion' => 'Confirmation']];
        }
        $t = $texts[$lang] ?? $texts['es'];

        // 4. Obtener Mesas mediante Query Builder (más seguro si no hay modelo Mesas.php)
        $mesas = (new \yii\db\Query())
            ->from('mesas')
            ->all();

        // 5. Renderizar enviando las variables exactas que pide tu vista
        return $this->renderPartial('confirmacion_cliente', [
            'reserva' => $reserva,
            'lang' => $lang,
            't' => $t,
            'mesas' => $mesas,
            'token' => $token
        ]);
    }

    public function actionFinalizar()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $token = $request->post('token');
            $model = Reservas::findOne(['token' => $token]);

            if ($model) {
                // ... Tus asignaciones de datos existentes ...
                $model->firma_nombre = $request->post('razon_social');
                $model->firma_identificacion = $request->post('identificacion_fiscal');
                $model->id_mesa = $request->post('id_mesa');
                $model->manteleria = $request->post('manteleria');
                $model->color_servilleta = $request->post('color_servilleta');
                $model->logistica = $request->post('logistica');
                $model->contacto_evento_telefono = $request->post('contacto_evento_telefono');
                $model->contacto_evento_nombre = $request->post('contacto_evento_nombre');

                $platos = $request->post('bocaditos');
                if (!empty($platos)) {
                    $model->observaciones = "Platos seleccionados: " . implode(', ', $platos);
                }

                $model->estado = 'Confirmada';

                if ($model->save()) {
                    // --- NUEVO: INSERTAR NOTIFICACIÓN PARA EL ADMIN ---
                    Yii::$app->db->createCommand()->insert('notificacion_sistema', [
                        'id_reserva' => $model->id,
                        'mensaje' => "El cliente ha finalizado el formulario de invitación: " . $model->nombre_evento,
                        'tipo' => 'success',
                        'leido' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ])->execute();
                    // --------------------------------------------------

                    Yii::$app->session->setFlash('success', "¡Reserva confirmada con éxito!");
                    return $this->redirect(['ver', 'token' => $token]);
                } else {
                    Yii::error($model->getErrors());
                    return "Error al validar datos: " . json_encode($model->getErrors());
                }
            }
        }
        return $this->redirect(['site/index']);
    }
}
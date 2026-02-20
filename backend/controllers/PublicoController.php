<?php

namespace backend\controllers;

use Yii;
use backend\modules\reservas\models\Reservas;
use backend\modules\reservas\models\ReservaDetallesMenu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class PublicoController extends Controller
{
    public $layout = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['ver', 'finalizar', 'mostrar-imagen'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionVer($token)
    {
        // Buscar reserva
        $reserva = Reservas::find()
            ->joinWith(['tipoEvento'])
            ->where(['token' => $token, 'reservas.estado' => 'Pendiente'])
            ->asArray()
            ->one();

        if (!$reserva) {
            throw new NotFoundHttpException('El enlace no existe, ha expirado o la reserva ya fue confirmada.');
        }

        // Manejo de idioma
        $lang = Yii::$app->request->get('lang', Yii::$app->session->get('lang', 'es'));
        Yii::$app->session->set('lang', $lang);

        // Cargar traducciones
        $idiomasPath = Yii::getAlias('@backend/web/idiomas.php');
        if (file_exists($idiomasPath)) {
            require($idiomasPath);
        } else {
            $texts = ['es' => ['confirmacion' => 'Confirmación'], 'en' => ['confirmacion' => 'Confirmation']];
        }
        $t = $texts[$lang] ?? $texts['es'];

        // Obtener mesas
        $mesas = (new \yii\db\Query())->from('mesas')->all();

        // Obtener menú cóctel
        $menuCoctelRaw = (new \yii\db\Query())
            ->from('menu_coctel')
            ->where(['estado' => 1])
            ->orderBy([new \yii\db\Expression("FIELD(categoria, 'BOCADOS SALADOS', 'VEGETARIANO / VEGANO', 'MARISCOS Y PESCADOS', 'BOCADITOS DULCES'), subcategoria ASC")])
            ->all();

        $categorias = [];
        foreach ($menuCoctelRaw as $item) {
            $categorias[$item['categoria']][$item['subcategoria']][] = $item;
        }

        return $this->renderPartial('confirmacion_cliente', [
            'reserva' => $reserva,
            'lang' => $lang,
            't' => $t,
            'mesas' => $mesas,
            'token' => $token,
            'categorias' => $categorias
        ]);
    }

    public function actionFinalizar()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $token = $request->post('token');
            $model = Reservas::findOne(['token' => $token]);

            if ($model) {
                // --- ACTUALIZAR DATOS DEL CLIENTE ---
                $cliente = $model->cliente; // Obtener el cliente relacionado
                if ($cliente) {
                    $cliente->representante_legal = $request->post('representante_legal');
                    $cliente->direccion_fiscal = $request->post('direccion_fiscal');
                    $cliente->correo_facturacion = $request->post('correo_facturacion');
                    $cliente->cliente_telefono = $request->post('telefono'); // teléfono de contacto
                    $cliente->save();
                }

                // --- DATOS DE FACTURACIÓN (en reservas) ---
                $model->firma_nombre = $request->post('razon_social');
                $model->firma_identificacion = $request->post('identificacion');

                // --- CONTACTO DIRECTO DEL EVENTO ---
                $model->contacto_evento_nombre = $request->post('contacto_evento_nombre');
                $model->contacto_evento_telefono = $request->post('contacto_evento_telefono');

                // --- HORARIOS Y EQUIPOS ---
                $model->hora_inicio = $request->post('hora_inicio');
                $model->hora_fin = $request->post('hora_fin');
                $model->equipos_audiovisuales = $request->post('equipos_audiovisuales');

                // --- MONTAJE ---
                $model->id_mesa = $request->post('id_mesa');
                $model->manteleria = $request->post('manteleria');
                $model->color_servilleta = $request->post('color_servilleta');

                // --- OBSERVACIONES Y LOGÍSTICA ---
                $observaciones_cocina = $request->post('observaciones');
                $platos = $request->post('bocaditos');

                if (!empty($platos) && !empty($observaciones_cocina)) {
                    $model->observaciones = "Platos seleccionados: " . implode(', ', $platos) . " | Observaciones cocina: " . $observaciones_cocina;
                } elseif (!empty($platos)) {
                    $model->observaciones = "Platos seleccionados: " . implode(', ', $platos);
                } elseif (!empty($observaciones_cocina)) {
                    $model->observaciones = $observaciones_cocina;
                }

                $model->logistica = $request->post('logistica');
                $model->estado = 'Confirmada';

                // --- GUARDAR ---
                if ($model->save()) {
                    // Guardar platos seleccionados en reserva_detalles_menu
                    if (!empty($platos) && is_array($platos)) {
                        foreach ($platos as $plato) {
                            Yii::$app->db->createCommand()->insert('reserva_detalles_menu', [
                                'id_reserva' => $model->id,
                                'nombre_plato' => $plato,
                                'categoria' => 'coctel'
                            ])->execute();
                        }
                    }

                    // Notificación
                    Yii::$app->db->createCommand()->insert('notificacion_sistema', [
                        'id_reserva' => $model->id,
                        'mensaje' => "El cliente ha finalizado el formulario de invitación: " . $model->nombre_evento,
                        'tipo' => 'success',
                        'leido' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ])->execute();

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

    public function actionMostrarImagen($id, $tipo)
    {
        $tabla = '';
        switch ($tipo) {
            case 'coctel':
                $tabla = 'menu_coctel';
                break;
            case 'almuerzo':
                $tabla = 'menu_almuerzo_cena';
                break;
            case 'seminario':
                $tabla = 'menu_seminario';
                break;
            case 'coffee':
                $tabla = 'menu_coffee_break';
                break;
            case 'desayuno':
                $tabla = 'menu_desayunos';
                break;
            default:
                throw new NotFoundHttpException('Tipo de menú no válido');
        }

        $imagen = (new \yii\db\Query())
            ->select(['imagen'])
            ->from($tabla)
            ->where(['id' => $id])
            ->scalar();

        if ($imagen) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $imagen);
            finfo_close($finfo);

            if (!$mimeType) {
                $mimeType = 'image/jpeg';
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            Yii::$app->response->headers->set('Content-Type', $mimeType);
            Yii::$app->response->headers->set('Content-Length', strlen($imagen));
            Yii::$app->response->headers->set('Cache-Control', 'public, max-age=86400');

            return $imagen;
        }

        throw new NotFoundHttpException('Imagen no encontrada');
    }
}
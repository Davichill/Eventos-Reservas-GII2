<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    // Mapeo de estados según tu lógica (1 = activo, 0 = inactivo)
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public static function tableName()
    {
        return '{{%admin_usuarios}}';
    }

    public function behaviors()
    {
        return [
                // Si tu tabla tiene columnas created_at y updated_at (int)
            TimestampBehavior::class,
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'activo' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername($username)
    {
        // 'usuario' es el nombre de tu columna en la DB
        return static::findOne(['usuario' => $username, 'activo' => self::STATUS_ACTIVE]);
    }

    /** * VALIDACIÓN DE ROL: Verifica si el tipo es 'principal'
     */
    public function esPrincipal()
    {
        // Ajusta 'principal' según el texto exacto que tengas en la columna 'tipo'
        return trim(strtolower($this->tipo)) === 'principal';
    }

    // --- Métodos Requeridos por IdentityInterface ---

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    // --- Gestión de Contraseñas ---

    // ... dentro de common\models\User.php ...

    public function validatePassword($password)
    {
        // Cambiamos 'password_hash' por 'password' que es el nombre en tu tabla
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password)
    {
        // Cambiamos 'password_hash' por 'password'
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
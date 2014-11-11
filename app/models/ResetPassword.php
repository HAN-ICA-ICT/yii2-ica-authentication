<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\User;

/**
 * ICA Authentication ResetPassword model.
 *
 * @property integer $id
 * @property integer $userid
 * @property string $request_token
 * @property string $reset_token
 * @property integer $created_at
 * @property integer $updated_at
 */
class ResetPassword extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%resetpassword}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Make sure there's a request token.
        if(null === $this->request_token)
        {
            $this->request_token = Yii::$app->security->generateRandomString();
        }
        parent::init();
    }

    /**
     * Set the value of reset_token.
     */
    public function generateResetToken()
    {
        $this->reset_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Return the user that is associated with this request.
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userid']);

    }

   
}

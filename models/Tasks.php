<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $status
 * @property int|null $priority
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    private const STATUS_TODO = 'todo';
    private const STATUS_DONE = 'done';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 'todo'],
            [['title', 'description', 'priority'], 'required'],
            [['description', 'status'], 'string'],
            [['priority'], 'integer', 'min' => 1, 'max' => 10],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::statuses())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'priority' => 'Priority',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    // filter out some fields, best used when you want to inherit the parent implementation
    // and exclude some sensitive fields.
    public function fields()
    {
        $fields = parent::fields();

        
        unset($fields['updated_at']);

        return $fields;
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function statuses()
    {
        return [
            self::STATUS_TODO => 'todo',
            self::STATUS_DONE => 'done',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::statuses()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusTodo()
    {
        return $this->status === self::STATUS_TODO;
    }

    public function setStatusToTodo()
    {
        $this->status = self::STATUS_TODO;
    }

    /**
     * @return bool
     */
    public function isStatusDone()
    {
        return $this->status === self::STATUS_DONE;
    }

    public function setStatusToDone()
    {
        $this->status = self::STATUS_DONE;
    }
}

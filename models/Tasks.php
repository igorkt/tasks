<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Task",
    properties: [
        new OA\Property(property: "id", ref: "#/components/schemas/TaskId"),
        new OA\Property(property: "title", ref: "#/components/schemas/TaskTitle"),
        new OA\Property(property: "description", ref: "#/components/schemas/TaskDescription"),
        new OA\Property(property: "status", ref: "#/components/schemas/TaskStatus"),
        new OA\Property(property: "priority", ref: "#/components/schemas/TaskPriority"),
        new OA\Property(property: "created_at", type: "string", example: "07.10.2025 15:35:03"),
    ]
)]
#[OA\Schema(
    schema: "TaskAdd",
    properties: [
        new OA\Property(property: "title", ref: "#/components/schemas/TaskTitle"),
        new OA\Property(property: "description", ref: "#/components/schemas/TaskDescription"),
        new OA\Property(property: "priority", ref: "#/components/schemas/TaskPriority")
    ],
    required: ["title", "description", "priority"]
)]
#[OA\Schema(
    schema: "TaskUpdate",
    properties: [
        new OA\Property(property: "title", ref: "#/components/schemas/TaskTitle"),
        new OA\Property(property: "description", ref: "#/components/schemas/TaskDescription"),
        new OA\Property(property: "status", ref: "#/components/schemas/TaskStatus"),
        new OA\Property(property: "priority", ref: "#/components/schemas/TaskPriority")
    ],
)]
#[OA\Schema(
    schema: "TaskId",
    type: "integer",
    example: 5
)]
#[OA\Schema(
    schema: "TaskStatus",
    type: "string",
    enum: ["todo", "done"],
    example: "todo"
)]
#[OA\Schema(
    schema: "TaskTitle",
    type: "string",
    example: "Task title 5"
)]
#[OA\Schema(
    schema: "TaskDescription",
    type: "string",
    example: "Task description"
)]
#[OA\Schema(
    schema: "TaskPriority",
    type: "integer",
    minimum: 1,
    maximum: 10,
    example: 5
)]
#[OA\Schema(
    schema: "ValidationFailedResponse",
    description: "Response on error in Yii2 (Unprocessable Entity)",
    properties: [
        new OA\Property(
            property: "name",
            type: "string",
            example: "Unprocessable entity"
        ),
        new OA\Property(
            property: "message",
            type: "string",
            example: "Validation failed: {\"status\":[\"Status must be a string.\"]}",
            description: "Message about error. Part after 'Validation failed: ' — это JSON object with errors on fields."
        ),
        new OA\Property(
            property: "code",
            type: "integer",
            example: 0
        ),
        new OA\Property(
            property: "status",
            type: "integer",
            example: 422
        ),
        new OA\Property(
            property: "type",
            type: "string",
            example: "yii\\web\\UnprocessableEntityHttpException"
        )
    ],
    required: ["name", "message", "code", "status", "type"]
)]
#[OA\Schema(
    schema: "NotFoundResponse",
    description: "Response on error 404 in Yii2 (Not Found)",
    properties: [
        new OA\Property(
            property: "name",
            type: "string",
            example: "Not Found"
        ),
        new OA\Property(
            property: "message",
            type: "string",
            example: "Task #1001 not found",
            description: "Message about error"
        ),
        new OA\Property(
            property: "code",
            type: "integer",
            example: 0
        ),
        new OA\Property(
            property: "status",
            type: "integer",
            example: 404
        ),
        new OA\Property(
            property: "type",
            type: "string",
            example: "yii\\web\\NotFoundHttpException"
        )
    ],
    required: ["name", "message", "code", "status", "type"]
)]
#[OA\Schema(
    schema: "ServerError",
    description: "Response on server error in Yii2",
    properties: [
        new OA\Property(
            property: "name",
            type: "string",
            example: "Server Error"
        ),
        new OA\Property(
            property: "message",
            type: "string",
            example: "Saving failed",
            description: "Message about error"
        ),
        new OA\Property(
            property: "code",
            type: "integer",
            example: 0
        ),
        new OA\Property(
            property: "status",
            type: "integer",
            example: 500
        ),
        new OA\Property(
            property: "type",
            type: "string",
            example: "yii\\web\\ServerErrorHttpException"
        )
    ],
    required: ["name", "message", "code", "status", "type"]
)]
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

    public function fields()
    {
        $fields = parent::fields();

        $fields['created_at'] = function () {
            return \Yii::$app->getFormatter()->asDatetime($this->created_at);
        };
        
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

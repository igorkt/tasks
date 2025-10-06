<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m251005_153407_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'status' => 'ENUM("todo", "done")',
            'priority' => $this->integer(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $addDefaultStatusQuery = "ALTER TABLE tasks ALTER status SET DEFAULT 'todo'";
        $this->execute($addDefaultStatusQuery);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%retake_requests}}`.
 */
class m250715_060025_create_retake_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('retake_requests', [
            'id' => $this->primaryKey(),
            'grade_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
            'status' => $this->string()->defaultValue('pending'), // pending, approved, denied
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk_retake_grade', 'retake_requests', 'grade_id', 'grades', 'id', 'CASCADE');
        $this->addForeignKey('fk_retake_student', 'retake_requests', 'student_id', 'students', 'id', 'CASCADE');
    }
    

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('retake_requests');
    }
}

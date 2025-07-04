<?php

use yii\db\Migration;

class m250624_124626_fix_timestamp_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250624_124626_fix_timestamp_columns cannot be reverted.\n";

        return false;
    }

    public function up()
{
    // For students table
    $this->alterColumn('students', 'created_at', "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    $this->alterColumn('students', 'updated_at', "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    
    // Repeat for classes and attendance tables
    $this->alterColumn('classes', 'created_at', "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    $this->alterColumn('classes', 'updated_at', "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
   // For attendance table - first add columns if missing
$tableSchema = Yii::$app->db->schema->getTableSchema('attendance');
if (!isset($tableSchema->columns['created_at'])) {
    $this->addColumn('attendance', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
}
if (!isset($tableSchema->columns['updated_at'])) {
    $this->addColumn('attendance', 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
}
}
}

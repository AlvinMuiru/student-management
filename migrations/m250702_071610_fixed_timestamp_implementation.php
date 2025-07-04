<?php

use yii\db\Migration;

class m250702_071610_fixed_timestamp_implementation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
{
    // Students table
    $this->alterColumn('students', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
    $this->alterColumn('students', 'updated_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

    // Classes table
    $this->alterColumn('classes', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
    $this->alterColumn('classes', 'updated_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

    // Attendance table - ADD columns first since they might not exist
    $this->addColumn('attendance', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
    $this->addColumn('attendance', 'updated_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       
    // For proper reversal
    $this->dropColumn('attendance', 'updated_at');
    $this->dropColumn('attendance', 'created_at');
    
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250702_071610_fixed_timestamp_implementation cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

class m250717_081310_add_receipt_number_to_student_fees extends Migration
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
        echo "m250717_081310_add_receipt_number_to_student_fees cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
         $this->addColumn('student_fees', 'receipt_number', $this->string(50)->unique()->after('id'));
    }

    public function down()
    {
       $this->dropColumn('student_fees', 'receipt_number');

        return false;
    }
    
}

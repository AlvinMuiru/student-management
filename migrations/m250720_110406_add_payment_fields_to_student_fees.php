<?php

use yii\db\Migration;

class m250720_110406_add_payment_fields_to_student_fees extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    $this->addColumn('student_fees', 'payment_method', $this->string()->defaultValue(null));
    $this->addColumn('student_fees', 'bank_name', $this->string()->defaultValue(null));
    $this->addColumn('student_fees', 'transaction_ref', $this->string()->defaultValue(null));
   
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    $this->dropColumn('student_fees', 'payment_method');
    $this->dropColumn('student_fees', 'bank_name');
    $this->dropColumn('student_fees', 'transaction_ref');
        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250720_110406_add_payment_fields_to_student_fees cannot be reverted.\n";

        return false;
    }
    */
}

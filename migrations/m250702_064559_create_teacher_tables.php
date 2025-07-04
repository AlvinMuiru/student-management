<?php

use yii\db\Migration;

class m250702_064559_create_teacher_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    
public function safeUp()
{
     $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
    }

    // Create teachers table
    $this->createTable('{{%teachers}}', [
        'id' => $this->primaryKey(),
        'first_name' => $this->string(50)->notNull(),
        'last_name' => $this->string(50)->notNull(),
        'email' => $this->string(100)->unique(),
        'phone' => $this->string(20),
        'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
    ], $tableOptions);

    // Add teacher_id to classes table if it doesn't exist
    if (!$this->db->getTableSchema('{{%classes}}')->getColumn('teacher_id')) {
        $this->addColumn('{{%classes}}', 'teacher_id', $this->integer());
    }

    // Add foreign key
    $this->addForeignKey(
        'fk_class_teacher',
        '{{%classes}}',
        'teacher_id',
        '{{%teachers}}',
        'id',
        'SET NULL',
        'CASCADE'
    );
}



    

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       // Remove foreign key first
    $this->dropForeignKey('fk_class_teacher', '{{%classes}}');
    
    // Remove teacher_id column
    $this->dropColumn('{{%classes}}', 'teacher_id');
    
    // Finally drop teachers table
    $this->dropTable('{{%teachers}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250702_064559_create_teacher_tables cannot be reverted.\n";

        return false;
    }
    */
}

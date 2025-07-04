<?php

use yii\db\Migration;

class m250702_072509_fixed_timestamp_columns_v2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$tableSchema = Yii::$app->db->schema->getTableSchema('attendance');
    
    // Only add columns if they don't exist
    if (!isset($tableSchema->columns['created_at'])) {
        $this->addColumn('attendance', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
    } else {
        $this->alterColumn('attendance', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
    }
    
    if (!isset($tableSchema->columns['updated_at'])) {
        $this->addColumn('attendance', 'updated_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
    } else {
        $this->alterColumn('attendance', 'updated_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
    }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
    // For safety, we won't automatically drop these columns
    // as they might contain important data
    echo "Migration cannot be automatically reverted.\n";
    return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250702_072509_fixed_timestamp_columns_v2 cannot be reverted.\n";

        return false;
    }
    */
}

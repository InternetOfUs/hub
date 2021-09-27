<?php

use yii\db\Migration;

/**
 * Class m210922_084611_add_community_id_in_app_table
 */
class m210922_084611_add_community_id_in_app_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `app` ADD `community_id` VARCHAR(128)  NULL  DEFAULT NULL  AFTER `conversational_connector`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210922_084611_add_community_id_in_app_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210922_084611_add_community_id_in_app_table cannot be reverted.\n";

        return false;
    }
    */
}

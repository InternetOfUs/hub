<?php

use yii\db\Migration;

/**
 * Class m200819_103617_add_connectors_fileds_to_app_table
 */
class m200819_103617_add_connectors_fileds_to_app_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("ALTER TABLE `app` ADD `data_connector` INT(11)  NOT NULL  AFTER `owner_id`;");
        $this->execute("ALTER TABLE `app` ADD `conversational_connector` INT(11)  NOT NULL  AFTER `data_connector`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200819_103617_add_connectors_fileds_to_app_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_103617_add_connectors_fileds_to_app_table cannot be reverted.\n";

        return false;
    }
    */
}

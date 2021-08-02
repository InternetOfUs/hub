<?php

use yii\db\Migration;

/**
 * Class m210720_151916_update_app_badge_table_with_app_id
 */
class m210720_151916_update_app_badge_table_with_app_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `app_badge` ADD `app_id` VARCHAR(128)  NOT NULL  DEFAULT ''  AFTER `id`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210720_151916_update_app_badge_table_with_app_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210720_151916_update_app_badge_table_with_app_id cannot be reverted.\n";

        return false;
    }
    */
}

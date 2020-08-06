<?php

use yii\db\Migration;

/**
 * Class m200806_134257_add_oauth2_app_id
 */
class m200806_134257_add_oauth2_app_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `app_social_login` ADD `oauth_app_id` VARCHAR(128)  NOT NULL  DEFAULT ''  AFTER `status`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200806_134257_add_oauth2_app_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200806_134257_add_oauth2_app_id cannot be reverted.\n";

        return false;
    }
    */
}

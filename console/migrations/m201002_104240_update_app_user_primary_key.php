<?php

use yii\db\Migration;

/**
 * Class m201002_104240_update_app_user_primary_key
 */
class m201002_104240_update_app_user_primary_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `app_user` DROP FOREIGN KEY `app_user_ibfk_1`;");
        $this->execute("ALTER TABLE `app_user` DROP PRIMARY KEY;");
        $this->execute("ALTER TABLE `app_user` ADD PRIMARY KEY (`app_id`, `user_id`);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201002_104240_update_app_user_primary_key cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201002_104240_update_app_user_primary_key cannot be reverted.\n";

        return false;
    }
    */
}

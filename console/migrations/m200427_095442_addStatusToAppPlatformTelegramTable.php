<?php

use yii\db\Migration;

/**
 * Class m200427_095442_addStatusToAppPlatformTelegramTable
 */
class m200427_095442_addStatusToAppPlatformTelegramTable extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("ALTER TABLE `app_platform_telegram` ADD `status` INT(11)  NOT NULL  AFTER `updated_at`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200427_095442_addStatusToAppPlatformTelegramTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200427_095442_addStatusToAppPlatformTelegramTable cannot be reverted.\n";

        return false;
    }
    */
}

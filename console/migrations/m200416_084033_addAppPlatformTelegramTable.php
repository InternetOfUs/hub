<?php

use yii\db\Migration;

/**
 * Class m200416_084033_addAppPlatformTelegramTable
 */
class m200416_084033_addAppPlatformTelegramTable extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("CREATE TABLE `app_platform_telegram` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `app_id` varchar(128) NOT NULL DEFAULT '',
          `bot_username` varchar(128) NOT NULL DEFAULT '',
          `created_at` int(11) NOT NULL,
          `updated_at` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `app_id` (`app_id`),
          CONSTRAINT `app_platform_telegram_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200416_084033_addAppPlatformTelegramTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200416_084033_addAppPlatformTelegramTable cannot be reverted.\n";

        return false;
    }
    */
}

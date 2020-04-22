<?php

use yii\db\Migration;

/**
 * Class m200417_122304_addUserAccountTelegramTable
 */
class m200417_122304_addUserAccountTelegramTable extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("CREATE TABLE `user_account_telegram` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `app_id` varchar(128) NOT NULL,
          `telegram_id` int(11) NOT NULL,
          `created_at` int(11) NOT NULL,
          `updated_at` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `user_id` (`user_id`),
          KEY `app_id` (`app_id`),
          CONSTRAINT `user_account_telegram_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
          CONSTRAINT `user_account_telegram_ibfk_2` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m200417_122304_addUserAccountTelegramTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200417_122304_addUserAccountTelegramTable cannot be reverted.\n";

        return false;
    }
    */
}

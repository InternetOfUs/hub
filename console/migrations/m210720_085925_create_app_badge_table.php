<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%app_badge}}`.
 */
class m210720_085925_create_app_badge_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `app_badge` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `creator_id` int(11) NOT NULL,
          `incentive_server_id` varchar(256) NOT NULL DEFAULT '',
          `created_at` int(11) NOT NULL,
          `updated_at` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210720_085925_create_app_badge_table cannot be reverted.\n";
        
        return false;
    }
}

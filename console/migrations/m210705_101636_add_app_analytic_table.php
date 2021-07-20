<?php

use yii\db\Migration;

/**
 * Class m210705_101636_add_app_analytic_table
 */
class m210705_101636_add_app_analytic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `app_analytic` (
          `id` varchar(128) NOT NULL,
          `app_id` varchar(128) NOT NULL DEFAULT '',
          `dimension` varchar(128) NOT NULL DEFAULT '',
          `metric` varchar(128) NOT NULL DEFAULT '',
          `timespan` varchar(128) NOT NULL DEFAULT '',
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
        echo "m210705_101636_add_app_analytic_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210705_101636_add_app_analytic_table cannot be reverted.\n";

        return false;
    }
    */
}

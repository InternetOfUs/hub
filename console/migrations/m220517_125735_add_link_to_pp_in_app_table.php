<?php

use yii\db\Migration;

/**
 * Class m220517_125735_add_link_to_pp_in_app_table
 */
class m220517_125735_add_link_to_pp_in_app_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `app` ADD `privacy_policy_url` TEXT  NULL  AFTER `community_id`;");
        $this->execute("ALTER TABLE `app` ADD `privacy_policy_text` TEXT  NULL  AFTER `privacy_policy_url`;");
        $this->execute("UPDATE app set status = 0 WHERE status = 1 and (privacy_policy_url is null or privacy_policy_text is null);;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220517_125735_add_link_to_pp_in_app_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220517_125735_add_link_to_pp_in_app_table cannot be reverted.\n";

        return false;
    }
    */
}

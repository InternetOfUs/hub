<?php

use yii\db\Migration;

/**
 * Class m200422_103257_addDeveloperFieldToUserTable
 */
class m200422_103257_addDeveloperFieldToUserTable extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("ALTER TABLE `user` ADD `developer` TINYINT(1)  NOT NULL  DEFAULT '0'  AFTER `verification_token`;");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m200422_103257_addDeveloperFieldToUserTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200422_103257_addDeveloperFieldToUserTable cannot be reverted.\n";

        return false;
    }
    */
}

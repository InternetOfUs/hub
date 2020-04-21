<?php

use yii\db\Migration;

/**
 * Class m200421_085234_addMetadataAndActiveFieldsInUserAccountTable
 */
class m200421_085234_addMetadataAndActiveFieldsInUserAccountTable extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("ALTER TABLE `user_account_telegram` ADD `metadata` TEXT  NULL  AFTER `updated_at`;");
        $this->execute("ALTER TABLE `user_account_telegram` ADD `active` TINYINT(1)  NOT NULL  AFTER `metadata`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200421_085234_addMetadataAndActiveFieldsInUserAccountTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200421_085234_addMetadataAndActiveFieldsInUserAccountTable cannot be reverted.\n";

        return false;
    }
    */
}

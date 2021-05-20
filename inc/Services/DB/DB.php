<?php
namespace MAM\Services\DB;

use ORM;
use MAM\Services\ServiceInterface;

class DB implements ServiceInterface
{
    /**
     * @inheritDoc
     * Configure ORM and run init tables afterwords.
     */
    public function register()
    {
        ORM::configure("mysql:host=".$_ENV['MYSQLHOST'].";dbname=".$_ENV['DB_NAME']."");
        ORM::configure('username', $_ENV['USERNAME']);
        ORM::configure('password', $_ENV['PASSWORD']);

        ORM::configure('id_column_overrides', array(
            'red_x_investment' => 'id',
            'red_x_leasing' => 'id',
            'red_x_investment_b' => 'id',
            'red_x_linkedin' => 'id'
        ));

        $this->init_tables();

    }

    /**
     * Check if the tables exists and add them if they are not.
     */
    private function init_tables(){
        $db = ORM::get_db();
        $db->exec("CREATE TABLE IF NOT EXISTS `red_x_investment` ( `id` INT NOT NULL AUTO_INCREMENT , `received` DATETIME NOT NULL , `name` TEXT NOT NULL , `email` TEXT NOT NULL , `phone` TEXT NOT NULL , `purchased` TEXT NOT NULL , `investment_level` TEXT NOT NULL , `ad_set` TEXT NOT NULL , `campaign` TEXT NOT NULL , `status` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $db->exec("CREATE TABLE IF NOT EXISTS `red_x_leasing` ( `id` INT NOT NULL AUTO_INCREMENT , `received` DATETIME NOT NULL , `name` TEXT NOT NULL , `email` TEXT NOT NULL , `phone` TEXT NOT NULL , `company` TEXT NOT NULL , `city` TEXT NOT NULL , `ad_set` TEXT NOT NULL , `campaign` TEXT NOT NULL , `status` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $db->exec("CREATE TABLE IF NOT EXISTS `red_x_investment_b` ( `id` INT NOT NULL AUTO_INCREMENT , `received` DATETIME NOT NULL , `name` TEXT NOT NULL , `email` TEXT NOT NULL , `phone` TEXT NOT NULL , `purchased` TEXT NOT NULL , `for_investment` TEXT NOT NULL , `ad_set` TEXT NOT NULL , `campaign` TEXT NOT NULL , `status` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $db->exec("CREATE TABLE IF NOT EXISTS `red_x_linkedin` ( `id` INT NOT NULL AUTO_INCREMENT , `received` DATETIME NOT NULL , `name` TEXT NOT NULL , `country` TEXT NOT NULL , `phone` TEXT NOT NULL , `purchased` TEXT NOT NULL , `for_investment` TEXT NOT NULL , `ad_set` TEXT NOT NULL , `campaign` TEXT NOT NULL , `status` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    }
}
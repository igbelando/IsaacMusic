<?php 

namespace Fuel\Migrations;

class users
{

    function up()
    {
        \DBUtil::create_table('users', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'username' => array('type' => 'varchar', 'constraint' => 100),
            'password' => array('type' => 'varchar', 'constraint' => 200),
            'email' => array('type' => 'varchar', 'constraint' => 100),
            'id_device' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'profile_picture' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),
            'description' => array('type' => 'varchar', 'constraint' => 400, 'null' => true),
            'birthday' => array('type' => 'varchar', 'constraint' => 20, 'null' => true),
            'coordinate_X' => array('type' => 'float', 'constraint' => 50),
            'coordinate_Y' => array('type' => 'float', 'constraint' => 50),
            'id_rol' => array('type' => 'int', 'constraint' => 11),
            'id_privacity' => array('type' => 'int', 'constraint' => 11),

            //'ciudad' => array('type' => 'varchar', 'constraint' => 100, 'null' => true),

        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci',
		    array(
		        array(
		            'constraint' => 'foreignKeyUsersToRoles',
		            'key' => 'id_rol',
		            'reference' => array(
		                'table' => 'roles',
		                'column' => 'id'
		            ),
		            'on_update' => 'CASCADE',
		            'on_delete' => 'RESTRICT'
		        )
		    )
		);

        \DB::query("ALTER TABLE `users` ADD UNIQUE (`username`)")->execute();
        \DB::query("ALTER TABLE `users` ADD UNIQUE (`email`)")->execute();
    }

    function down()
    {
       \DBUtil::drop_table('users');
    }
}
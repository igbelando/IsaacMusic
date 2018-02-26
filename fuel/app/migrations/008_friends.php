<?php 

namespace Fuel\Migrations;

class friends
{

    function up()
    {
        \DBUtil::create_table('friends', array(
            'id_user_follower' => array('type' => 'int', 'constraint' => 11),
            'id_user_followed' => array('type' => 'int', 'constraint' => 11),
        ), array('id_user_follower', 'id_user_followed'), false, 'InnoDB', 'utf8_unicode_ci',
		    array(
		        array(
		            'constraint' => 'foreignKeyFriendsToUsersFollower',
		            'key' => 'id_user_follower',
		            'reference' => array(
		                'table' => 'users',
		                'column' => 'id'
		            ),
		            'on_update' => 'CASCADE',
		            'on_delete' => 'RESTRICT'
		        ),
		        array(
		            'constraint' => 'foreignKeyFriendsToUsersFollowed',
		            'key' => 'id_user_followed',
		            'reference' => array(
		                'table' => 'users',
		                'column' => 'id'
		            ),
		            'on_update' => 'CASCADE',
		            'on_delete' => 'RESTRICT'
		        )
		    )
		);
    }

    function down()
    {
       \DBUtil::drop_table('friends');
    }
}
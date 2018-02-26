<?php 

class Model_Friends extends Orm\Model
{
    protected static $_table_name = 'friends';

    protected static $_primary_key = array('id_user_follower','id_user_followed');
    protected static $_properties = array(
        'id_user_follower',
        'id_user_followed' // both validation & typing observers will ignore the PK
       
        
    );

    /*protected static $_has_many = array(
        'songs' => array(
            'key_from' => 'id_song',
            'model_to' => 'Model_Songs',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        ),
        'users' => array(
            'key_from' => 'id_lists',
            'model_to' => 'Model_Lists',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        )
    );*/
}
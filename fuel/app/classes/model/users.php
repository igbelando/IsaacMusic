<?php 

class Model_Users extends Orm\Model
{
    protected static $_table_name = 'users';

    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'username' => array(
            'data_type' => 'varchar'   
        ),
        'password' => array(
            'data_type' => 'varchar'   
        ),
        'email' => array(
            'data_type' => 'varchar'   
        ),
        'id_device' => array(
            'data_type' => 'int'   
        ),
        'profile_picture' => array(
            'data_type' => 'varchar'
        ),
        'city' => array(
            'data_type' => 'varchar'   
        ),
        'birthday' => array(
            'data_type' => 'varchar'   
        ),
        'email' => array(
            'data_type' => 'varchar'   
        ),
        'id_rol' => array(
            'data_type' => 'int'   
        ),
        'coordinate_X' => array(
            'data_type' => 'decimal'
        ),
        'coordinate_Y' => array(
            'data_type' => 'decimal'
        )
    );
}
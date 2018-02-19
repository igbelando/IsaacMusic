<?php 

class Model_Usuarios extends Orm\Model
{
    protected static $_table_name = 'usuarios';

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
        'foto_perfil' => array(
            'data_type' => 'varchar'
        ),
        'ciudad' => array(
            'data_type' => 'varchar'   
        ),
        'cumple' => array(
            'data_type' => 'varchar'   
        ),
        'email' => array(
            'data_type' => 'varchar'   
        ),
        'id_rol' => array(
            'data_type' => 'int'   
        ),
        'coordenada_X' => array(
            'data_type' => 'decimal'
        ),
          'coordenada_Y' => array(
            'data_type' => 'decimal'
        )
    );
}
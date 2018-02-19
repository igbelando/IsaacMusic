<?php 

class Model_Roles extends Orm\Model
{
    protected static $_table_name = 'roles';

    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'tipo' => array(
            'data_type' => 'varchar'   
        )
        
    );
}
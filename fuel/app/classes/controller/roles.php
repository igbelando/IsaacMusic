<?php 

use \Firebase\JWT\JWT;

class Controller_Roles extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";

    private $getEmail = "";

                                    //Crear roles
    public function post_create()
    {
        try {
            if ( ! isset($_POST['type']) ) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Error en las credenciales, prueba otra vez',
                    'data' => []
                ));

                return $json;
            }

            $input = $_POST;
            
                $roles = new Model_Roles();
                $roles->type= $input['type'];
               
                if ($roles->type == "" )
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Se necesita introducir todos los parametros',
                        'data' => []
                    ));
                }
                else
                {

                    $roles->save();
                    
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Rol creado correctamente',
                        'data' => $roles
                    ));

                    return $json;
                }
        } 
        catch (Exception $e) 
        {
           
                $json = $this->response(array(
                    'code' => 500,
               // 'message' => $e->getCode()
                    'message' => $e->getMessage(),
                    'data' => []
                ));

                return $json;
        }        
    }
}    

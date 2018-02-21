<?php 

use \Firebase\JWT\JWT;

class Controller_Privacidad extends Controller_Rest
{
    private $key = "juf3dhu3hufdchv3xui3ucxj";
    public function post_privacidadAmigos()
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

        
      

                $users = Model_Usuarios::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));

            }    
            catch (Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
               
            }

            $privacidad = Model_Privacidad::find('all', array(
                        'where' => array(
                            array('id_usuario', $dataJwtUser->id),
                            
                        )          
                    ));

            

           

            
            
            if ( ! empty($privacidad) )
            {
                

                foreach ($privacidad as $key => $privado)
                {

                    
                }

                        $privado->amigos == 2;
                           $privado->save();
                     
                 if($privado->amigos == 1)
                    {

                      


                    }
                    else
                    {
                        $privado->amigos == 1;
                    }

                   

                if($privado->amigos == 1)
                    {
                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'Campo amigos puesto en privado',
                            'data' => $privado
                        ));
                return $json;


                    }
                    else
                    {
                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'Campo amigos puesto en publico',
                            'data' => []
                        ));
                return $json;
                    }

                
               
            }
            else
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'Usuario  no encontrado',
                    'data' => []
                    ));
            }

               
    }

}    

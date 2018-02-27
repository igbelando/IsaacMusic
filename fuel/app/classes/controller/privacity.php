<?php 

use \Firebase\JWT\JWT;

class Controller_Privacity extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";
    
    public function post_privacityFriends()
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

                $users = Model_Users::find('all', array(
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

        $privacity = Model_Privacity::find('all', array(
                    'where' => array(
                        array('id_user', $dataJwtUser->id),
                        
                    )          
                ));

        if ( ! empty($privacity) )
        {
            
            foreach ($privacity as $key => $private)
            {

            
            }

            $private->friends == 2;
            $private->save();
                 
            if($private->friends == 1)
                {

                  
                }
                else
                {
                    $private->friends == 1;
                }

            if($private->friends == 1)
                {
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Campo amigos puesto en privado',
                        'data' => $private
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
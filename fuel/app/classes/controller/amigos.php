<?php 

use \Firebase\JWT\JWT;

class Controller_Amigos extends Controller_Rest
{
    private $key = "juf3dhu3hufdchv3xui3ucxj";
   

                                    //Crear usuario
    public function post_add()
    {

                    
        
        try {

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
            foreach ($users as $key => $user)
            {
                $rol = $user->id_rol;
            }

            
            if ($rol == 1)
            {

                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Solo los usuarios pueden añadir amigos',
                    'data' => []
                ));
                return $json;

            }
            else
            {  




                if (  ! isset($_POST['username'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

                /*if (strlen($_POST['password']) < 6 || strlen($_POST['password']) >12){
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Contraseña: entre 6 y 12 caracteres',
                        'data' => []
                    ));

                    return $json;

                }*/


              

                $input = $_POST;
                $usuarios = Model_Usuarios::find('all', array(
                    'where' => array(
                        
                        array('username', $input['username']),
                        
               
                    )
                 ));


                if  (empty($usuarios))
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Usuario no encontrado',
                        'data' => []
                    ));

                    return $json;

                }



                foreach ($usuarios as $key => $usuario) {
                    # code...
                }

                if ($usuario->id_rol == 1)
                {
                   $json = $this->response(array(
                        'code' => 400,
                        'message' => 'No puedes ser amigo de un admin',
                        'data' => []
                    ));

                    return $json;  
                }
                

                
                    $amigos = new Model_Amigos();
                    $amigos->id_usuario_seguidor = $dataJwtUser->id;
                    $amigos->id_usuario_seguido = $usuario->id;

                   
                   


                    
                
                
               
                    if ($amigos->id_usuario_seguido == "" || $amigos->id_usuario_seguidor == ""   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Se necesita introducir todos los parametros',
                            'data' => []
                        ));
                        return $json;
                    }
                     if ($amigos->id_usuario_seguido ==  $amigos->id_usuario_seguidor    )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'No puedes ser amigo de ti mismo',
                            'data' => []
                        ));
                        return $json;
                    }
                    
                    
                   


                    
                    
                        
                        


                            $amigos->save();
                            
                            

                            $json = $this->response(array(
                                'code' => 200,
                                'message' => $dataJwtUser->username. ' es amigo de '. $usuario->username,
                                'data' => $amigos
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

    public function get_amigos()
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
        $input = $_GET;


        $amigos = Model_Amigos::find('all', array(
                        'where' => array(
                            array('id_usuario_seguidor', $dataJwtUser->id),


                            
                            
                   
                        )
                     ));
        if (empty($amigos))
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'No has hecho amigos',
                'data' => []
            ));
            return $json;

        }
        else
        {
           


                foreach ($amigos as $key => $amigo) {
                    $añadido[] = $amigo;
                    # code...
                }

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Lista de amigos',
                    'data' => $añadido
                ));

                return $json;
            
        }

        //return $this->response(Arr::reindex($users));

    }
   

    public function post_delete()
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
        $input = $_POST;

        if (  ! isset($_POST['username']) ) 
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Error en las credenciales, prueba otra vez',
                            'data' => []
                        ));

                        return $json;
                    }

        $usuarios = Model_Usuarios::find('all', array(
                        'where' => array(
                            
                            array('username', $input['username']),


                            
                   
                        )
                     ));
        if (empty($usuarios))
        {
            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'Usuario no encontrado o no le has hecho amigo ',
                                'data' => []
                            ));


        } 
        else
        {


            foreach ($usuarios as $key => $usuario) {
                # code...
            }
            
            
                 
             



                $amigos = Model_Amigos::find('all', array(
                                'where' => array(
                                    array('id_usuario_seguidor', $dataJwtUser->id),
                                    array('id_usuario_seguido', $usuario->id),
                                    
                           
                                )
                             ));
                if(! empty($amigos))
                {

                    foreach ($amigos as $key => $amigo) {
                        
                        # code...
                    }

                    Model_Anyadir::find($amigo);
                    try
                    {
                        

                        

                        $amigo->delete(); 
                    }
                    catch (Exception $e)
                    {

                    }


                    $json = $this->response(array(
                        'code' => 200,
                        'message' => $usuario->username. ' eliminado de amigos',
                        'data' => []
                    ));

                    return $json;
                }
                else
                {
                    $json = $this->response(array(
                                'code' => 400,
                                'message' => 'Usuario no encontrado',
                                'data' => []
                            ));

                            return $json;
                }
            
        }   

        //return $this->response(Arr::reindex($users));

    }


    

    

}    



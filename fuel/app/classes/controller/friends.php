<?php 

use \Firebase\JWT\JWT;

class Controller_Friends extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";
   
                                    //Añadir Amigo
    public function post_add()
    {
        try 
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

            foreach ($users as $key => $user)
            {
                $rol = $user->id_rol;
            }

            if ($rol == 1)
            {

                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'No tienes permisos para añadir amigos, necesitas ser usuario',
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

                $input = $_POST;
                $users = Model_Usuarios::find('all', array(
                    'where' => array(
                        
                        array('username', $input['username']),
                        
                    )
                 ));

                if  (empty($users))
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'El usuario no ha sido encontrado o no existe',
                        'data' => []
                    ));

                    return $json;
                }

                foreach ($users as $key => $user) {
                    # code...
                }

                if ($user->id_rol == 1)
                {
                   $json = $this->response(array(
                        'code' => 400,
                        'message' => 'No puedes ser amigo de un admin',
                        'data' => []
                    ));

                    return $json;  
                }
                
                $friends = new Model_Friends();
                $friends->id_user_follower = $dataJwtUser->id;
                $friends->id_user_followed = $user->id;
           
                if ($friends->id_user_follower == "" || $friends->id_user_followed == ""   )
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Falta por introducir algún los parametros',
                        'data' => []
                    ));
                    return $json;
                }

                if ($friends->id_user_follower ==  $friends->id_user_followed    )
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'No se puede ser amigo de ti mismo',
                        'data' => []
                    ));
                    return $json;
                }

                $friends->save();
                $json = $this->response(array(
                    'code' => 200,
                    'message' => $dataJwtUser->username. ' es amigo de '. $user->username,
                    'data' => $friends
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

                                        //Mostrar Amigos 
    public function get_friends()
    {
        try
        {
            $headers = apache_request_headers();
            $token = $headers['Authorization'];
            $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

            $users = Model_Userçs::find('all', array(
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
        $tens = $input['tens_friends']-1;

        if($input['tens_friends'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }

        if($input['tens_friends'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }

       $friends = Model_Friends::query()->where('id_user_follower', $dataJwtUser->id)->offset( $tens * 10)->limit(10)->get();

        if (empty($friends))
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'Todavia no tienes amigos',
                'data' => []
            ));
            return $json;

        }
        else
        {
           
            foreach ($friends as $key => $friend) {
                $add[] = $friend;
                # code...
            }

            $json = $this->response(array(
                'code' => 200,
                'message' => 'Lista de amigos',
                'data' => $add
            ));

            return $json;
        
        }
    }
                                            //Mostrar Seguidores

     public function get_followers()
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

        $input = $_GET;
        $tens = $input['tens_friends']-1;

        if($input['tens_friends'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }

        if($input['tens_friends'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }

       $friends = Model_Friends::query()->where('id_user_followed', $dataJwtUser->id)->offset( $tens * 10)->limit(10)->get();

        if (empty($friends))
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'No tienes amigos',
                'data' => []
            ));
            return $json;
        }

        else
        {

            foreach ($friends as $key => $friend) {
                $add[] = $friend;
                # code...
            }

            $json = $this->response(array(
                'code' => 200,
                'message' => 'Lista de seguidores',
                'data' => $add
            ));

            return $json;
        }
    }

                                                //Eliminar Amigos
   
    public function post_delete()
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

        $users = Model_Users::find('all', array(
                        'where' => array(
                            array('username', $input['username']),
                        )
                    ));

        if (empty($users))
        {
            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'Usuario no encontrado o no le has hecho amigo ',
                                'data' => []
                            ));

        } 
        else
        {

            foreach ($users as $key => $user) {
                # code...
            }
            
            $friends = Model_Friends::find('all', array(
                            'where' => array(
                                array('id_user_follower', $dataJwtUser->id),
                                array('id_user_followed', $user->id),
                            )
                         ));

            if(! empty($friends))
            {

                foreach ($friends as $key => $friend) {
                    
                    # code...
                }

                Model_Add::find($friend);
                try
                {
                    $friend->delete(); 
                }
                catch (Exception $e)
                {

                }

                $json = $this->response(array(
                    'code' => 200,
                    'message' => $user->username. ' eliminado de amigos',
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
    }
}    
<?php 

use \Firebase\JWT\JWT;

class Controller_Users extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";

    private $getEmail = "";

                                    //Crear usuario
    public function post_create()
    {
        try {

           $roles = Model_Users::find('all', array(
                'where' => array(
                    array('id_rol', 1),
                   
                )          
            ));

            if(empty($roles))
            {
                $json = $this->response(array(
                'code' => 400,
                'message' => 'No se puede usar la app hasta que haya minimo un administrador',
                'data' => []
            ));

            return $json;

            }
            else
            {
                if ( ! isset($_POST['username']) && ! isset($_POST['password'])&& ! isset($_POST['repeatPassword']) && ! isset($_POST['email'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

                $input = $_POST;
                if ($input['password'] != $input['repeatPassword'])
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'password y repeatPassword han de coincidir',
                        'data' => []
                    ));

                    
                }

                else
                {
                    $user = new Model_Users();
                    $user->username = $input['username'];
                    $user->password = $input['password'];
                    $user->email = $input['email'];
                    $user->coordinate_X = 0.00;
                    $user->coordinate_Y = 0.00;
                    $user->id_rol = 2;

                    if ($user->username == "" || $user->email == "" || $user->password == "")
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Se necesita introducir todos los parametros',
                            'data' => []
                        ));
                    }
                    else
                    {

                        $user->save();
                        $dataToken = array(
                                "id" => $user->id,
                                "username" => $user->username,
                                "password" => $user->password,
                                "email" => $user->email,
                                "id_rol" => $user->id_rol,
                                "coordinate_X"=>$user->coordinate_X,
                                "coordinate_Y"=>$user->coordinate_Y
                            );

                        $token = JWT::encode($dataToken, $this->key);

                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'Usuario creado correctamente',
                            'data' => $token
                        ));

                        return $json;
                    }
                }
            }
        } 
        catch (Exception $e) 
        {
            if($e->getCode() == 23000)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                //'message' => $e->getMessage(),
                ));

                return $json;

            }
            else
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

                                    //Mostrar usuarios
    public function get_users()
    {
        $input = $_GET;

    	$tens = $input['tens_users']-1;
        if($input['tens_users'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['tens_users'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }

        $users = Model_Users::query()->where('id_rol',2)->offset( $decena * 10)->limit(10)->get();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de usuarios',
            'data' => $users

        ));

        return $json;

    }

                                      //Eliminar usuario
    public function post_delete()
    {
        $user = Model_Users::find($_POST['id']);
        $userName = $user->name;
        $user->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Usuario borrado',
            'name' => $userName
        ));

            return $json;
    }
                                    //login del usuario
    public function get_login()
    {
    try {

            if ( empty($_GET['username']) || empty($_GET['password']))
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'Hay campos vacios',
                    'data' => []
                ));
            }
            $input = $_GET;

            //$password = ['password' => $users->Password];

          
           // $passwordDecode = JWT::decode($password, $this->key, array('HS256'));

            $users = Model_Users::find('all', array(
                        'where' => array(
                            array('username', $input['username']),
                            array('password', $input['password']),
                        )          
                    ));

            if ( ! empty($users) )
            {
                
                foreach ($users as $key => $value)
                {
                    $id = $users[$key]->id;
                    $name = $users[$key]->username;
                    $password = $users[$key]->password;
                    $id_rol = $users[$key]->id_rol;
                    
                }
                foreach ($users as $key => $user) 
                {
                    
                    $user->id_device = random_int(0, 1000000);
                    $user-> save();
                }
            }
            else
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'Usuario y password no coinciden o son incorrectos',
                    'data' => []
                    ));
            }
                
            $dataToken = array(
                "id" => $id,
                "username" => $name,
                "password" => $password,
                "id_rol" => $id_rol,
                "coordinate_X"=>$user->coordinate_X,
                "coordinate_Y"=>$user->coordinate_Y
                );

            $token = JWT::encode($dataToken, $this->key);

            return $this->response(array(
                'code' => 200,
                'message'=> 'Login correcto',
                'data' => $token
                ));
                        
        } 
        catch (Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Error de servidor',
                    'data' => $e->getMessage()
                    //'message' => $e->getMessage(),
                ));
                return $json;
            }
        }
                                     //Cambiar la contraseña
    public function get_recoveryPassword()
    {
        try {
            if ( empty($_GET['email'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' =>  'Parametro email no introducido',
                    'data' => []
                ));
                return $json;
            }
            // Validación de e-mail
            $input = $_GET;
            $users = Model_Users::find('all', array(
                'where' => array(
                    array('email', $input['email'])
                )
            ));
            if ( ! empty($users) )
            {
                foreach ($users as $key => $value)
                {
                    $id = $users[$key]->id;
                    $email = $users[$key]->email;

                }
            }
            else
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'El email introducido no existe',
                    'data' => []
                    ));
            }
            
                $tokendata = array(
                    'id' => $id,
                    'email' => $email
                   
                );
                $token = JWT::encode($tokendata, $this->key);
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Email validado',
                    'data' => $token
                ));
                return $json;
            
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
    }

    public function post_changePassword()
    {

        try
        {
            $header = apache_request_headers();
            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }

            if (empty($_POST['password'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' =>  'No puede haber campos vacios',
                        'data' => []
                    ));
                    return $json;
                }

            if (($_POST['password']) != ($_POST['repeatPassword'])){
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Password y repeatPassword han de ser iguales',
                        'data' => []
                    ));

                    return $json;

                }

                $input = $_POST;
                $user = Model_Users::find($dataJwtUser->id);
                $user->password = $input['password'];
               
                $user->save();
                                
                $json = $this->response(array(
                    'code' => 200,
                    'message' =>  'Password cambiada correctamente',
                    'data' => []
                ));
                return $json;
              
           
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
    }

    function post_modifyUser()
    {
        try {
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

                    $users = Model_Users::find('all', array(
                            'where' => array(
                                array('id', $dataJwtUser->id)
                            )
                     ));

                    $user = Model_Users::find($dataJwtUser->id);
                    if(empty($user)){
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Usuario no encontrado',
                            'data' => []
                        //'message' => $e->getMessage(),
                        ));

                        return $json;
                    }

                    if($input['password'] != '')
                    {  $user->password = $input['password'];

                    }
                     if($input['coordinate_Y'] != '')
                    {

                    $user->coordinate_Y = $input['coordinate_Y'];

                    }
                     if($input['coordinate_X'] != '')
                    {
                        $user->coordinate_X = $input['coordinate_X'];

                    }
                     if($input['birthday'] != '')
                    {
                         $user->birthday = $input['birthday'];

                    }
                     if($input['city'] != '')
                    {
                         $user->city = $input['city'];

                    }
                    if($input['description'] != '')
                    {
                         $user->description = $input['description'];

                    }
                  
                     if($input['password'] != '')
                    {
                        $this->uploadImage();

                    }
                     
                    $user->save();
                                
                    $json = $this->response(array(
                        'code' => 200,
                        'message' =>  'Cambios realizados correctamente',
                        'data' => $users
                     ));
                    return $json;

                
        } catch (Exception $e) {
            if($e->getCode() == 23000)
            {
                return $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                    ));
            }
        }
    }
}    
<?php 

use \Firebase\JWT\JWT;

class Controller_Usuarios extends Controller_Rest
{
    private $key = "juf3dhu3hufdchv3xui3ucxj";

    private $getEmail = "";

                                    //Crear usuario
    public function post_create()
    {

     
        try {

               $roles = Model_Usuarios::find('all', array(
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

                    /*if (strlen($_POST['password']) < 6 || strlen($_POST['password']) >12){
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Contraseña: entre 6 y 12 caracteres',
                            'data' => []
                        ));

                        return $json;

                    }*/


                  

                    $input = $_POST;
                    if ($input['password'] != $input['repeatPassword'])
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'password y repeatPassword han de coincidir',
                            'data' => []
                        ));

                        
                    }



                    /*$password = ['password' => $input['password']];
                    $dataJwtPassword = JWT::decode($password, $this->key);*/

                    
                    
                    else
                    {
                        $user = new Model_Usuarios();
                        $user->username = $input['username'];
                        $user->password = $input['password'];
                        $user->email = $input['email'];
                        $user->coordenada_X = 0.00;
                        $user->coordenada_Y = 0.00;
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
                                    "id_rol" => $user->id_rol
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
    	$users = Model_Users::find('all');

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de usuarios',
            'data' => $users

        ));

        return $json;

    	//return $this->response(Arr::reindex($users));

    }
    public function get_user()
    {

            $header = apache_request_headers();
            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }
        $user = Model_Users::find($dataJwtUser->id);

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Este es el usuario',
            'data' => $user

        ));

        return $json;

        //return $this->response(Arr::reindex($users));

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

            

            $users = Model_Usuarios::find('all', array(
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
                    $user->coordenada_Y = 20.90;
                    $user->coordenada_X = 5.789;
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
                "id_rol" => $id_rol
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
            $users = Model_Usuarios::find('all', array(
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
                $user = Model_Usuarios::find($dataJwtUser->id);
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
            $header = apache_request_headers();

            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }

            if (empty($_POST['name'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' =>  'No puede haber campos vacios',
                        'data' => []
                    ));
                    return $json;
                }

                    $input = $_POST;

                    $users = Model_Users::find('all', array(
                            'where' => array(
                                array('name', $input['name'])
                            )
            ));

                    if($users != null){
                        $json = $this->response(array(
                            'code' => 500,
                            'message' => 'Ya existe un usuario con ese nombre',
                            'data' => []
                        //'message' => $e->getMessage(),
                        ));

                        return $json;
                    }

                    $user = Model_Users::find($dataJwtUser->id);
                    $user->name = $input['name'];
                    
                    
               
                    $user->save();
                                
                    $json = $this->response(array(
                        'code' => 200,
                        'message' =>  'Cambios realizados correctamente',
                        'data' => []
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

    public function post_uploadImage()
    {

        try{
            $header = apache_request_headers();

            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }


        // Custom configuration for this upload
        $config = array(
            'path' => DOCROOT . 'assets/img',
            'randomize' => true,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        );

        // process the uploaded files in $_FILES
        Upload::process($config);

        // if there are any valid files
        if (Upload::is_valid())
        {
            
            // save them according to the config
            Upload::save();

            foreach(Upload::get_files() as $file)
            {
                $user = Model_Usuarios::find($dataJwtUser->id);
                $user->foto_perfil = 'http://' . $_SERVER['SERVER_NAME'] . '/appmusicfinal/public/assets/img/' . $file['saved_as'];
                //$user->picture = 'http://' . $_SERVER['SERVER_NAME'] . '/shigui/Shigui/public/assets/img/' . $file['saved_as'];
                $user->save();
               // $this->updatePhoto($user->picture);
                

            }
        }

        return $this->response(array(
            'code' => 200,
            'message' => 'Datos actualizados',
            'data' => [$user]
        ));


        // and process any errors
        

        foreach (Upload::get_errors() as $file)
        {
            return $this->response(array(
                'code' => 500,
                'message' => 'No se ha podido subir la imagen',
                'data' => []
            ));
        }
      
        }catch (Exception $e){
            return $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));

    }

}
    private function updatePhoto($photo)
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

        
      

                $users = Model_Users::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('name', $dataJwtUser->name),
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
       

        


        $valuations = Model_Valuations::find('all', array(
            'where' => array(
                array('id_users', $dataJwtUser->id),
                
               
            )
        ));

        
            
            foreach ($valuations as $key => $valuation) 
            {
                $valuation->user_picture = $photo;
                $valuation->save();

                
            }


            
           
    }

/*
        function decodeToken()
    {

        $jwt = apache_request_headers()['Authorization'];
        $token = JWT::decode($jwt, $this->key , array('HS256'));
        return $token;
    }

    function userNotRegistered($email)
    {

        $users = Model_Users::find('first', array(
            'where' => array(
                array('email', $email),
                array('is_registered', 0)
                )
            )); 

        if($users != null){
            return true;
        }else{
            return false;
        }
    }
    

    function validateToken($jwt)
    {
        $token = JWT::decode($jwt, $this->key, array('HS256'));

        $email = $token->data->email;
        $password = $token->data->password;
        $id = $token->data->id;

        $users = Model_Users::find('all', array(
            'where' => array(
                array('email', $email),
                array('password', $password),
                array('id',$id)
                )
            ));
        if($users != null){
            return true;
        }else{
            return false;
        }
    }
*/
    



       /*public function post_editProfile()
        {


    //nombre email y foto

        }*/

    }    

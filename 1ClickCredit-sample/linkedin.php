    <?php  
        class Linkedin extends CI_Controller{  

            function signin()  
            {  

                $this->load->helper('url');
              //  echo APPPATH; die;
               // require_once(APPPATH.'third_party/linkedin/index.php');
              //  print_r($lk); 
             //  $this->load->view('demo');
              //  die;
                 $this->load->model('user_profiles_model');  
                    $data['linkedin_ids'] = $this->user_profiles_model->get_linkedin_id(); 
                    $lk_id_array = array();
                    if(!empty($data['linkedin_ids'])){
                      foreach($data['linkedin_ids'] as $key=>$value){
                        $lk_id_array[$key]=$value->linkedin_id;
                      } 
                    }
                    
              $data['registered_linkedin_ids'] =  $lk_id_array;
              $data['body'] = 'linkedin/signin.php';

              isset($_SESSION['id']) ? redirect('user/myprofile', 'location') : $this->load->view('template', $data) ;  



            }  

            function save()  
            { 
              $this->load->model('user_profiles_model');  
              if ($this->input->post('lk-val-submit')==true) {
             /* echo '<pre>';
              print_r($_POST);
              print_r($_FILES);

              echo '</pre>';*/
              $form_data = $_POST;
               $form_data['profile']['created_on'] = time();
              if($this->user_profiles_model->insert_into_db($form_data['profile'])){
                  //$last_id = $this->user_profiles_model->insert_into_db($form_data['profile']);
                }
              redirect('user/myprofile', 'location');
              }
            }



            function message()  
            { 
             
               $data['body'] = 'linkedin/send-message.php';
               $this->load->view('template', $data);
            }


        }  
    ?>  
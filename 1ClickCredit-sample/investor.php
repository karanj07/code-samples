    <?php  
        class Investor extends CI_Controller{  
         
            function newInvestor()  
            {  
      
              if(isset($_POST['invest'])){
               $this->load->model('investors_model'); 
               unset($_POST['invest']) ;
               $form_data = $_POST; 
                $project_id = $form_data['project_id'];
                $form_data['created_on'] = time();
         
                $this->load->model('projects_model'); 
                $old_funded =  $this->projects_model->custom_sql("SELECT funded from projects WHERE id=". $project_id);


                $pdata['id'] = $project_id;
                $pdata['funded'] = $old_funded[0]->funded + $form_data['amount'];

                $this->projects_model->update_by_id($pdata);
                     $this->investors_model->insert_into_db( $form_data);
                   redirect('project/single?project_id='.$project_id, 'location');
              }
            }  


             function ajaxsave(){  
             
              $form_data = explode('&', $_POST['form_data']);
              $project= array();
              foreach($form_data as $value){

                $value = explode('=', $value );
                $project[$value[0]] = urldecode($value[1]);
              }

              $this->load->model('projects_model');
              if($this->projects_model->update_by_id($project)){
                $arr = array('success'=>true);
                echo json_encode($arr);
              }else{
                 $arr = array('success'=>false);
                echo json_encode($arr);
              } 
              return false;
        
            }
             
        }  
    ?>  
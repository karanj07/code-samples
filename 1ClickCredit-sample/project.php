    <?php  
        class Project extends CI_Controller{  
         
            function create()  
            {  
      
              if(isset($_POST['confirm_terms']) && $_POST['confirm_terms']==1){
               $this->load->model('projects_model');  
               $form_data = $_POST; 
                $project_id = $this->projects_model->insert_into_db();
               // echo $project_id; die;
                   redirect('project/edit?project_id='.$project_id, 'location');
              }

              $data['body'] = 'project/create.php';
               $this->load->view('template', $data);  
            }  

            function edit()  
            {  
                $this->load->model('projects_model');
                $data['project_details'] = $this->projects_model->custom_sql("SELECT * from projects WHERE id=".$_GET['project_id']." AND user_id=".$_SESSION['id']); 
              $data['body'] = 'project/edit.php';
               $this->load->view('template', $data);  
            }  

             function delete()  
            {  
                $this->load->model('projects_model');
                $status = $this->projects_model->delete_by_id($_GET['project_id']); 
                redirect('user/myaccount?status='.$status, 'location');
            }  

             function single()  
            {  
              
              $this->load->model('shortlists_model');
              $this->load->model('user_profiles_model');
              $this->load->model('projects_model');
              $this->load->model('investors_model');
               $data['shortlist'] = false;
              $user_id = $_SESSION['id'];
              $project_id = $_GET['project_id'];
               $data['message'] = $this->session->flashdata('invest');

               
               /*Shortlist Project*/
               if(isset($_GET['shortlist']) && $_GET['shortlist']=="true"){
                  $formdata['project_id'] = $project_id;
                  $formdata['user_id'] = $user_id;
                  $count = $this->shortlists_model->custom_sql("SELECT id from shortlists WHERE user_id =".$user_id." AND project_id =".$project_id." LIMIT 1");
                  $count = count($count[0]);
                  if($count==0){
                    $this->shortlists_model->insert_into_db($formdata);
                    $data['shortlist'] = true;
                    $data['message'] = "<p class='message success'>successfully added to your shortlisted projects</p>";
                  }

               } 
              if(isset($_GET['shortlist']) && $_GET['shortlist']=="false"){
                  if($this->shortlists_model->delete_by_id($_GET['shortlist_id'])){
                    $data['shortlist'] = false;
                    $data['message'] = "<p class='message success'>This project has been removed from your shortlisted projects</p>";
                  }
               } 

              $shortlist_status = $this->shortlists_model->custom_sql("SELECT id from shortlists WHERE user_id =".$user_id." AND project_id =".$project_id." LIMIT 1"); 
              if(!empty($shortlist_status)){
                 $data['shortlist_id'] = $shortlist_status[0]->id; 
                 $data['shortlist'] = true;
              }

               /*Ask Question*/
              if(isset($_POST['ask_question'])){
                 $this->load->model('messages_model');  
                 $form_data = $_POST; 
                 unset($form_data['ask_question']);
                  if($message_id = $this->messages_model->insert_into_db($form_data)){
                    $update_data['id'] = $message_id;
                    $update_data['message_id']= $message_id;
                    $this->messages_model->update_by_id($update_data);
                   $data['message'] = "<p class='message success'>Your message has been successfully sent</p>";
                  }else{
                     $data['message'] = "<p class='message error'>Your message could not be sent. Please try again</p>";
                  }
              }

              /*Count investors*/
               $investors = $this->investors_model->custom_sql("SELECT user_id from investors WHERE project_id =".$project_id); 
              
                $a = array();
                 foreach($investors as $key => $val){
                  $a[] = $val->user_id;
                 }
                $a = array_unique($a);
                 $data['total_investors'] = count($a);
              

               
                $data['project_details'] = $this->projects_model->get_data_by_id($_GET['project_id']); 
 
                $data['author_details'] = $this->user_profiles_model->getData_by_id($data['project_details'][0]->user_id);
                $data['author_details'] = $data['author_details'][0];
                 $data['investor_details'] = $this->investors_model->get_data_by_project_id($_GET['project_id']);
                $data['body'] = 'project/single.php';
                $this->load->view('template', $data);  
            }  

              function investment_opportunities()  
            {  
                 $this->load->model('projects_model');
                 $data['all_projects'] = $this->projects_model->custom_sql("SELECT * from projects WHERE status=1 AND user_id!=".$_SESSION['id']); 
                 $data['body'] = 'project/investment-opportunities.php';
               $this->load->view('template', $data);  


            }  

            function save()  
            {  
       
            }

             function ajaxsave()  
            {  
             
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

             function publish()  
            {  
             $this->load->library('session');
             $this->load->model('projects_model');  
              if ($this->input->post('publish_project')==true) {
                $form_data = $_POST;
                $project['id'] =  $form_data['id'];
                $project['status']=1;
                $project['published_on'] = time();
                if($this->projects_model->update_by_id($project)){
                  $message ='<p class="message success">Your project "'.$form_data['title'].'" has been approved and published</p>';
                  $this->session->set_flashdata('publish', $message);
                  redirect('user/myaccount', 'location');
                }else{
                   $message ='<p class="message error">There was some error in publishing you project "'.$form_data['title'].'". Please try again</p>';
                   $this->session->set_flashdata('publish', $message);
                   redirect('user/myaccount', 'location');
                }
             
              
              }
        
            }
             
            function like_count(){

             $this->load->model('projects_model'); 
             $project['id'] = $_POST['project_id'];
             $count = $_POST['count'];
             $count++;
             $project['like_count'] = $count;
              $this->projects_model->update_by_id($project);
            echo json_encode($count);
           }   

        }  
    ?>  
<?php  
    class Admin extends CI_Controller{  
        function index()  
        {  
        
           $data['error'] ='';
            if ($this->input->post('login')==true) {

              $this->load->model('admin_users_model');  
              $form_data = $_POST;
              
              if($admin_data = $this->admin_users_model->login_check($form_data['email'], $form_data['password'])){
                  //$last_id = $this->user_profiles_model->insert_into_db($form_data['profile']);
                $admin_data   = $admin_data[0];
                 $_SESSION['admin_id'] =  $admin_data->id;
                 $_SESSION['email'] =  $admin_data->email;
                 $_SESSION['name'] =  $admin_data->name;
                 $_SESSION['role'] =  $admin_data->role;
                $data['v'] = true;
                  }else{

                  $data['error'] = "email/password combination is incorrect";
                }
              }

                $data['body'] = 'admin/index.php';
           $this->load->view('admin/template', $data);   
        }  

         function users()  
            {  
              $this->load->model('admin_users_model');
              $data['users'] = $this->admin_users_model->custom_sql("SELECT * FROM user_registered_profiles"); 

              // $this->session->sess_destroy();
              $data['body'] = 'admin/users.php';
              $this->load->view('admin/template', $data);   
            }  

             function staff(){
                $this->load->model('admin_users_model');
                $data['admin_users'] = $this->admin_users_model->custom_sql("SELECT * FROM admin_users"); 

               // $this->session->sess_destroy();
               $data['body'] = 'admin/admin-users.php';
               $this->load->view('admin/template', $data);   
             }  



             function projects()  
            {  
               $this->load->model('admin_users_model');
              $data['projects'] = $this->admin_users_model->custom_sql("SELECT * FROM projects"); 
               
             // $this->session->sess_destroy();
           $data['body'] = 'admin/projects.php';
           $this->load->view('admin/template', $data);   


            }  

             function lender_offers()  
            {  
               $this->load->model('admin_users_model');
              $data['lender_offers'] = $this->admin_users_model->custom_sql("SELECT * FROM lender_offers"); 
               
             // $this->session->sess_destroy();
           $data['body'] = 'admin/lender-offers.php';
           $this->load->view('admin/template', $data);   


            }  

          
          function loan_applications(){
              $this->load->model('admin_users_model');
              $data['loan_applications'] = $this->admin_users_model->custom_sql("SELECT * FROM loan_applications"); 
               
          $data['body'] = 'admin/loan-applications.php';
           $this->load->view('admin/template', $data);  
          }

           function loan_application_details(){
              $id = $_GET['id'];
              $this->load->model('loan_applications_model');
              $application_details = $this->loan_applications_model->get_data_by_id($id);
              $application_details =  $application_details[0];
              $data['applicationkeys'] = explode(";",  $application_details->keys);

              $data['applicationvalues'] = explode(";",  $application_details->values);

              $data['body'] = 'admin/loan-application-details.php';
              $this->load->view('admin/template', $data);
          }



           function loan_types(){
            $data['message'] = "";
            $this->load->model('admin_users_model');  
            $this->admin_users_model->table = "loan_types";
            

            if ($this->input->post('save_loan_type')==true && $this->input->post('form_type')=="create") {
              $form_data = $_POST;
              unset($form_data['form_type']);
              unset($form_data['save_loan_type']);
              //$form_data['html'] =  htmlentities($form_data['html']);
              if($this->admin_users_model->insert_into_db($form_data)){
              $data['message'] = "<p class='message success'>Your data has been saved</p>";
                }else{
                $data['message'] = "<p class='message error'>Your data could not be saved</p>";
              }
            }

            if ($this->input->post('save_loan_type')==true && $this->input->post('id')==true) {
              $form_data = $_POST;
              unset($form_data['save_loan_type']);
             //   $form_data['html'] =  htmlentities($form_data['html']);
              if($this->admin_users_model->update_by_id($form_data)){
                  $data['message'] = "<p class='message success'>Your data has been updated</p>";
              }else{
                  $data['message'] = "<p class='message error'>Your data could not be updated</p>";
              }
            }    

            $data['loan_types'] = $this->admin_users_model->custom_sql("SELECT * FROM loan_types");
            $data['body'] = 'admin/loan-types.php';
            $this->load->view('admin/template', $data);   
          }

          function logout()  
            {  
             // $this->session->sess_destroy();
              session_destroy();

              redirect('admin', 'location');


            }  
          
          function edit_data(){
              $update_data=array();
              $update_data[$_POST['field']] =  $_POST['data'];
                $update_data['id'] = $_POST['id'];
              $this->load->model('admin_users_model');
              $this->admin_users_model->table = $_POST['table'];
              $result = $this->admin_users_model->update_by_id($update_data) ?  json_encode('true') :  json_encode('false');
              echo $result;             
          }

          function delete_data(){
            $this->load->model('admin_users_model');
            $this->admin_users_model->table = $_POST['table'];
            $result = $this->admin_users_model->delete_by_id($_POST['id']) ?  json_encode('true') :  json_encode('false');
            echo $result;             
          }


    }  
?>  
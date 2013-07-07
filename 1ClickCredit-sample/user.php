    <?php  
        class User extends CI_Controller{  

            function logout()  
            {  
             // $this->session->sess_destroy();
              session_destroy();
              redirect(base_url(), 'location');


            }  

             function myprofile()  
            {  
                $this->load->model('user_profiles_model');
               if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
                    $data['profile'] = $this->user_profiles_model->getData_by_id($_SESSION['id']); 
               }else{
                $data['profile'] = $this->user_profiles_model->getData_by_linkedin_id($_SESSION['linkedin_id']); 

               
               }

          $profile_data = $data['profile'];
                $profile_data = array_shift($profile_data);

                $_SESSION['id'] =  $profile_data->id;
                $_SESSION['first_name'] =  $profile_data->first_name;
                $_SESSION['last_name'] =  $profile_data->last_name;
                $_SESSION['name'] =  $profile_data->first_name.' '.$profile_data->last_name;
                $_SESSION['address'] =  $profile_data->address;
                $_SESSION['phone'] =  $profile_data->phone;
                $_SESSION['profile_image'] =  $profile_data->profile_image;
                $_SESSION['email'] =  $profile_data->email;

                 $data['body'] = 'user/myprofile.php';
               $this->load->view('template', $data);  


            }  
             function myaccount()  
            {  
               $this->load->library('session');
                 $this->load->model('projects_model');
                 $this->load->model('loan_applications_model');
                 $this->load->model('shortlists_model');
                 $this->load->model('investors_model');

                 $data['projects'] = $this->projects_model->custom_sql("SELECT * FROM projects WHERE  user_id = ".$_SESSION['id']." ORDER BY created_on DESC");

                 $data['applications'] = $this->loan_applications_model->custom_sql("SELECT * FROM lender_offers INNER JOIN loan_applications ON lender_offers.id=loan_applications.loan_id WHERE  loan_applications.applicant_id = ".$_SESSION['id']." ORDER BY loan_applications.created_on DESC");

                 $data['shortlists'] = $this->shortlists_model->custom_sql("SELECT * FROM projects INNER JOIN shortlists ON shortlists.project_id=projects.id WHERE  shortlists.user_id = ".$_SESSION['id']." ORDER BY shortlists.created_on DESC");

                 $data['inv_projects'] = $this->projects_model->custom_sql(" SELECT * FROM investors INNER JOIN projects ON investors.project_id = projects.id WHERE  investors.user_id = ".$_SESSION['id']." ORDER BY investors.created_on DESC"); 
               
/*                 echo '<pre>';
print_r($data['applications']);
echo '</pre>';
die;
*/
                 $data['body'] = 'user/myaccount.php';
                  $data['flash_message'] = $this->session->flashdata('publish'); 
               $this->load->view('template', $data);  


            }  

             function credit_deals()  
            {  
                $this->load->model('lender_offers_model');
                 $this->load->model('loan_types_model');
                  
                 $data['loan_types'] = $this->loan_types_model->custom_sql("SELECT type FROM loan_types");
                 $data['loan_types'] = $data['loan_types'];
                $data['loan_offers'] = $this->lender_offers_model->custom_sql("SELECT * FROM lender_offers WHERE  approved = 1 ORDER BY created_on DESC");

                

                 $data['body'] = 'user/credit-deals.php';
               $this->load->view('template', $data);  


            }  

             function credit_deals_ajax_fetch(){

              $this->load->model('lender_offers_model');
                 $this->load->model('loan_types_model');
                  $loan_type_query="";
                  if(isset($_POST['data'])){

                    $loan_filters = $_POST['data'];
                   
                  
                      $loan_type_query = " AND ";
                      foreach($loan_filters as $loan_filter){
                        $loan_type_query .= "loan_type = '".$loan_filter."' OR ";
                      }
                      $loan_type_query = substr($loan_type_query,0,-3);
                  
                    
                 }

                 $data['loan_types'] = $this->loan_types_model->custom_sql("SELECT type FROM loan_types");
                 $data['loan_types'] = $data['loan_types'];
                $data['loan_offers'] = $this->lender_offers_model->custom_sql("SELECT * FROM lender_offers WHERE  approved = 1 ".$loan_type_query." ORDER BY created_on DESC");

               //$data['body'] = 'user/credit_deals_ajax_fetch.php';
               if(is_array($data['loan_offers'])){

                $this->load->view('user/credit_deals_ajax_fetch.php', $data);
               }else{echo "<i>no deal found for you</i>";}  

             }   
           
        }  
    ?>  
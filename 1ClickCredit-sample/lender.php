<?php  
    class Lender extends CI_Controller{  

        function loanform()  
        {  
        $data['message'] = "";

      
        $this->load->model('lender_offers_model');
        $this->load->model('loan_types_model');
         $this->load->model('industries_model');
        $data['loan_types']  =  $this->loan_types_model->custom_sql("SELECT * from loan_types" );
         $data['industries']  =  $this->loan_types_model->custom_sql("SELECT * from industries" );
           if ($this->input->post('loanformsubmit')==true) {
             
              $form_data = $_POST;

              $form_data['loan_type_html'] = $this->loan_types_model->custom_sql("SELECT html from loan_types WHERE type='".$form_data['loan_type']."' LIMIT 1");
              $form_data['loan_type_html'] =  $form_data['loan_type_html'][0]->html;
              empty($form_data['loan_roidec'])? $form_data['loan_roidec'] = 0:false;
              !empty($form_data['loan_roi'])?$form_data['loan_roi'] = $form_data['loan_roi'].'.'.$form_data['loan_roidec']:false;
              unset($form_data['loan_roidec']);
              unset($form_data['loanformsubmit']);
               $form_data['created_on'] = time();

              if($this->lender_offers_model->insert_into_db($form_data)){
                  $data['message'] = "<p class='message success'>Your data has been saved</p>";
              }else{
                  $data['message'] = "<p class='message error'>Your data could not be saved</p>";
              }
        }  
          $data['body'] = 'lender/loanform.php';
          $this->load->view('template', $data);  
       }

        function loan_application()  {
          $data['message'] = "";
          $this->load->model('lender_offers_model');
          $this->load->model('loan_applications_model');

          $offer_id = isset($_GET['id'])?$_GET['id']:false;
          if ($this->input->post('loanapplicationsubmit')==true) {

          $loan_form_data = $_POST;
          $form_data['loan_id'] = $loan_form_data['loan_id'];
          $form_data['company_name'] = $loan_form_data['company_name'];
          $form_data['company_image'] = $loan_form_data['company_image'];

           unset($loan_form_data['loan_id']);
           unset($loan_form_data['company_name']);
           unset($loan_form_data['company_image']);
           unset($loan_form_data['loanapplicationsubmit']);
          $keys = array();
          $values=array();
          $loan_form_data['type'] = implode('+', $loan_form_data['type']);
  
          foreach($loan_form_data as $key=>$value){
            $keys[] = $key;
            $values[] = $value;
          }
          $form_data['applicant_id'] = $_SESSION['id'];
          $form_data['applicant_name'] = $_SESSION['name'];
          $form_data['keys'] = implode(';', $keys);
          $form_data['values'] = implode(';', $values);
          $form_data['created_on'] = time();

            if($this->loan_applications_model->insert_into_db($form_data)){
                  $data['message'] = "<p class='message success'>Your data has been saved</p>";
              }else{
                  $data['message'] = "<p class='message error'>Your data could not be saved</p>";
              }

          $offer_id = $form_data['loan_id'];
        }
            

           $data['loan_offer'] =  $this->lender_offers_model->get_data_by_id($offer_id);
            $data['loan_offer'] = $data['loan_offer'][0];
             $data['body'] = 'lender/loanapplication.php';
             
          $this->load->view('template', $data);  
        }



       function details(){
        $id = $_GET['id'];
         $this->load->model('loan_applications_model');
          $application_details = $this->loan_applications_model->get_data_by_id($id);
           $application_details =  $application_details[0];
          $data['applicationkeys'] = explode(";",  $application_details->keys);
         
          $data['applicationvalues'] = explode(";",  $application_details->values);
 
          $data['body'] = 'lender/details.php';
          $this->load->view('template', $data);


       }


    }  
?>  
    <?php  
        class Files extends CI_Controller{  
           
          private $upload_folder = "assets/uploads/";
            function upload(){
              $usrfolder = $this->upload_folder;
                  if ($_FILES["images"]["error"] == UPLOAD_ERR_OK) {
                     // $img_name = basename($_FILES["images"]["name"]);
                      $img_name = "img_".time().".jpg";
                      $ipath = $usrfolder.$img_name;
                      move_uploaded_file( $_FILES["images"]["tmp_name"], $usrfolder.$img_name);
                  }
              
              echo base_url().$ipath;   
             
                  }      
              
            
          function delete(){
              $usrfolder = $this->upload_folder;
              $fname = basename($_POST['fname']);
              
              if(is_file( $usrfolder.'/'.$fname)){unlink($usrfolder.'/'.$fname );}    
              echo $fname;
          }
        }  
    ?>  
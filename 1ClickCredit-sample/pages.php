    <?php  
        class Pages extends CI_Controller{  
            function index()  
            {  
      
              $data['body'] = 'home.php';
               $this->load->view('template', $data);  



            }  

            function for_investors()  
            {  
              $data['body'] = 'pages/for-investors.php';
               $this->load->view('template', $data);  
            }  

            function looking_for_loan()  
            {  
              $data['body'] = 'pages/looking-for-loan.php';
               $this->load->view('template', $data);  
            }  

            function for_lenders()  
            {  
              $data['body'] = 'pages/for-lenders.php';
               $this->load->view('template', $data);  
            }  

            function how_it_works()  
            {  
              $data['body'] = 'pages/how-it-works.php';
               $this->load->view('template', $data);  
            }  
        }  
    ?>  
<?php

class Page extends CI_Controller {

    function __construct() {
        parent::__construct();

        /* Loads the form and url helper */
        $this->load->helper(array('form', 'url'));

        //load pagination library
        $this->load->library('pagination');

        //load model 
         
        $this->load->model('AddressBook');

        //per page limit
        $this->perPage = 3;
    }

    public function index() {

        $data = array();

        // Count all record of table "contact_info" in database.
        $totalRec = $this->AddressBook->record_count();

        //Pagination Configuration
        $config['base_url'] = base_url()."index.php/Page/index";
        $config['url_segment'] = 3;
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;
        
        //Initialise the Pagination ibrary
        $this->pagination->initialize($config);
        
        //Define Offset
        $page = $this->uri->segment(3);
        $offset = !$page?0:$page;
        
        
        $data['page'] = $this->AddressBook->selectContactsPerPage($this->perPage, $offset);

        $this->load->view('ContactsPerPageView', $data);
    }
}
?>
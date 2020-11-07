<?php

class Home extends CI_Controller {

	function __construct() {
        parent::__construct();
		
		//Loads the AddressBook model  
        $this->load->model('AddressBook');
    }

	
    public function index() {

        $this->load->view('mymenu');
    }

 
    function AddEntry() {
		//Validation Rules for addEntry View
        $user_validation_rules = array(
            array('field' => 'f_name',
                'label' => 'Firstname',
                'rules' => 'required',
                'errors' => array('required' => 'You must provide a %s.')),
            array('field' => 'l_name',
                'label' => 'Surname',
                'rules' => 'required',
                'errors' => array('required' => 'You must provide a %s.')),
            array('field' => 'address',
                'label' => 'Address',
                'rules' => 'required',
                'errors' => array('required' => 'You must provide an %s.')),
            array('field' => 'city',
                'label' => 'City',
                'rules' => 'required',
                'errors' => array('required' => 'You must provide an %s.')),
            array('field' => 'town',
                'label' => 'Town',
                'rules' => 'required',
                'errors' => array('required' => 'You must provide an %s.')),
            array('field' => 'tel_number',
                'label' => 'Telephone Number',
                'rules' => 'required',
                'errors' => array('required' => 'You must provide an %s.')),
            array('field' => 'email',
                'label' => 'Email',
                'rules' => 'required',
                'errors' => array('required' => 'You must provide an %s.'))
        );

		//Set the validation rules for the addEntry view 
        $this->form_validation->set_rules($user_validation_rules);
 
		//If validation rules fail
        if ($this->form_validation->run() == FALSE) {
            //Load the Main Menu view
            $this->load->view('addentry');
        } else {

			//Add details to master table and if successfull add the to the other tables
            if ($this->AddressBook->addEntryMaster()) {  //Calls the addEntry function in the AddressBook model                             
                //Get the master_id inserted 
                $master_id = $this->db->insert_id();
				//Insert the contact details for this $master_id into the other tables
				$this->AddressBook->addEntryAddress($master_id);
                $this->AddressBook->addEntryEmail($master_id);
                $this->AddressBook->addEntryTelephone($master_id);
                $this->AddressBook->addEntryFax($master_id);
				$this->AddressBook->addEntryNotes($master_id);
								
                //Reload the main menu
                $this->load->view('mymenu');
            }
        }
    }

	function SelectEntry() {

		//Method to fill the select box on the SelectEntry View 
		
        //Select all contacts in the addressbook
        $data['display_block'] = $this->AddressBook->selectContacts();

        //Render the View SelectEntry with the contact list.            
        $this->load->view('SelectEntry', $data);	
	}

	function SelectedContact() {
	
		//Method to select the details of the selected Contact
		
		$data['display_block2'] = "";
			
		//Get the master id of the selected contact from the Post variable
        $master_id = $this->input->post('master_id');

		//Get the contact details for this master_id from all tables
        $data['display_block2'] .= $this->AddressBook->getSelectedContactDetails($master_id);

        //Fill the select box again - better to save this off but do like this for the moment
        $data['display_block'] = $this->AddressBook->selectContacts();

        //View the selected contacts dropdown and the details for the selected contact           
        $this->load->view('SelectEntry', $data);
    }
	
		function DeleteEntry() {
			
		//This method fills a select box and allows the user to 
		//to choose an option to delete the selected user. 

        //Select all contacts in the addressbook
        $data['display_block'] = $this->AddressBook->selectContacts();

        //Render the DeleteEntry View. All the options for the 
		//the Select box on DeleteEntry view are in the field $data['display_block']
        $this->load->view('DeleteEntry', $data);	
	}	
	
	function DeleteSelectedContact() {

      	//Call model function to delete contact with the id of the selected contact 
        $this->AddressBook->deleteContact($this->input->post('master_id'));
			
		//Update the dropdown Select options so that removes the one just deleted
		//Select all contacts in the addressbook
			 
		$data['display_block'] = $this->AddressBook->selectContacts();

		//Render the DeleteEntry View with the new list to delete
		$this->load->view('DeleteEntry', $data);
	}
	function UpdateEntry() {

        //Select all contacts in the addressbook
        $data['display_block'] = $this->AddressBook->selectContacts();

        //View the selected contacts dropdown            
        $this->load->view('UpdateEntry', $data);

        if ($this->input->post('submit')) {
			 
			$this->getSelectedContactDetails($_POST['master_id'] );
          }
    }		
	
	function getSelectedContactDetails($master_id) {
		 
		$data2['master_id'] = $master_id;
		$data2['contact_details'] = $this->AddressBook->getSelectedContactDetailsForUpdate($master_id);

		
		//View the selected contacts dropdown 
        		
	   $this->load->view('UpdateContactDetails', $data2);	
	   
		if (isset($_POST['update'])) {
		 
			$this->AddressBook->updateAddressDetails($master_id,$_POST['address'],$_POST['city'],$_POST['town'],$_POST['add_type']);
			$this->AddressBook->updateTelephoneDetails($master_id,$_POST['tel_number'],$_POST['tel_type']);
			$this->AddressBook->updateFaxDetails($master_id,$_POST['fax'],$_POST['fax_type']);
			$this->AddressBook->updateEmailDetails($master_id,$_POST['email'],$_POST['email_type']);
			$this->AddressBook->updatePersonalNotesDetails($master_id,$_POST['note']);
		}
	}
		
	function UpdateSelectedContact($master_id) {
 
		$this->AddressBook->updateAddressDetails($master_id,$_POST['address'],$_POST['city'],$_POST['town'],$_POST['add_type']);
		$this->AddressBook->updateTelephoneDetails($master_id,$_POST['tel_number'],$_POST['tel_type']);
		$this->AddressBook->updateFaxDetails($master_id,$_POST['fax'],$_POST['fax_type']);
		$this->AddressBook->updateEmailDetails($master_id,$_POST['email'],$_POST['email_type']);
		$this->AddressBook->updatePersonalNotesDetails($master_id,$_POST['note']);

    }

}
?>
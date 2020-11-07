<?php

class AddressBook extends CI_Model {


    //Adds new name to the Master table             
    function addEntryMaster() {
		
        $master_data['f_name'] = $this->input->post('f_name'); // retrieve f_name from form post
        $master_data['l_name'] = $this->input->post('l_name'); // retrieve l_name from form post
        $master_data['date_added'] = date('Y-m-d');  // retrieve current system date and time
        $master_data['date_modified'] = date('Y-m-d');// retrieve current system date and time
  
        //Returns whether this function ran successfully 
        return $this->db->insert('master_name', $master_data);     // returns True or False whether the insert was correct
    }

    //Adds new Address details to the Address Table
    function addEntryAddress($master_id) {
		
		//Build associative array with details for insert
        $address_data['master_id'] = $master_id;
		$address_data['address'] = $this->input->post('address');
        $address_data['city'] = $this->input->post('city');
        $address_data['town'] = $this->input->post('town');
        $address_data['type'] = $this->input->post('add_type');
        $address_data['date_added'] = date('Y-m-d'); //current date and time
        $address_data['date_modified'] = date('Y-m-d');
		
        $this->db->insert('address', $address_data);    // returns True or False whether the insert was correct
    }

    //Adds new Telephone Details to the Telephone Table
    function addEntryTelephone($insert_id) {
        //Inserts telephone details from form into associative array with keys
		//same name as database fields
        $tel_data['master_id'] = $insert_id;
        $tel_data['telephoneNo'] = $this->input->post('tel_number');
        $tel_data['type'] = $this->input->post('tel_type');
        $tel_data['date_added'] = date('Y-m-d');
        $tel_data['date_modified'] = date('Y-m-d');

        $this->db->insert('telephone', $tel_data);    // returns True or False whether the insert was correct
    }

    //Adds new Email details to the Email table
    function addEntryEmail($insert_id) {
        //Inserts email details from form into associative array with 
		//keys same name as database fields
        $email_data['master_id'] = $insert_id;
        $email_data['email'] = $this->input->post('email');
        $email_data['type'] = $this->input->post('email_type');
        $email_data['date_added'] = date('Y-m-d');
        $email_data['date_modified'] = date('Y-m-d');

        $this->db->insert('email', $email_data);    // returns True or False whether the insert was correct
    }
	
	   //Adds new Fax details to the Fax table
    function addEntryFax($insert_id) {
        //Inserts email details from form into associative array 
		// with keys same name as database fields
		$fax_data['master_id'] = $insert_id;
        $fax_data['fax_number'] = $this->input->post('fax');
        $fax_data['type'] = $this->input->post('fax_type');
        $fax_data['date_added'] = date('Y-m-d');
        $fax_data['date_modified'] = date('Y-m-d');

        $this->db->insert('fax', $fax_data);    // returns True or False whether the insert was correct
    }
	
	//Adds new Notes details to the Personal Notes table
    function addEntryNotes($insert_id) {
        //Inserts email details from form into associative array with keys 
		//same name as database fields
        $note_data['master_id'] = $insert_id;
        $note_data['note'] = $this->input->post('note');
        $note_data['date_added'] = date('Y-m-d');
        $note_data['date_modified'] = date('Y-m-d');

        $this->db->insert('Personal_Notes', $note_data);    // returns True or False whether the insert was correct
    }
	
    function selectContacts() {
		
		//Method to select all the entries in the master_name table

        $display_block = "";
		
		//Selects the master_id and l_name, f_name from the master_name table
        $contacts = $this->db->select("master_id, CONCAT(l_name,' ', f_name) AS display_name", false);
        $query = $this->db->get('master_name');

		//If records returned form the table	
        if ($query->num_rows() > 0) {
			//Loop through each record returned
            foreach ($query->result_array() as $contact) {
                $id = $contact['master_id'];
				//Remove any tabs etc with stripslashes
                $display_name = stripslashes($contact['display_name']);
				//Build the option list for select box with the details`
                $display_block .= "<option value=\"" . $id . "\">" . $display_name . "</option>";
            }
        } else {
			//Master_name table was empty 
            $display_block .= "<option>No Contacts to Select</option>";
        }
		//Return the display_block to controller Home
        return $display_block;
    }

    function getSelectedContactDetails($master_id) {
		
		//This method selects the all the details for the contact = master_id 
		
        $display_block2 = "";

		// Get the Details from the relevant tables for the selected contact 
		// tables are joined based on master_id

	   	$this->db->select('Master_name.master_id, l_name, f_name, Address.address, 
						Address.city, Address.town, Address.type as AddType, 
						telephone.telephoneNo, Telephone.type as TelType, Email, 
						Email.type as EmailType, Fax_number, Fax.type as FaxType, note');
						
		$this->db->from('Master_name');
		$this->db->join('address', 'address.master_id = master_name.master_id', 'left');	
		$this->db->join('telephone', 'telephone.master_id = master_name.master_id', 'left');
		$this->db->join('email', 'email.master_id = master_name.master_id', 'left');
		$this->db->join('fax', 'fax.master_id = master_name.master_id', 'left');
		$this->db->join('personal_notes', 'personal_notes.master_id = master_name.master_id', 'left');
	    $this->db->where('Master_name.master_id', $master_id);
		$query = $this->db->get();

		//If the a record is found
        if ($query->num_rows() > 0) {
			$result = $query->row(); // one row retuned as master_id is a primary key
            $display_block2 .= "<h3>Showing Record for: " .$result->l_name." ".$result->f_name."</h3>";
            $display_block2 .= 'Address: ' . $result->address . ',  ' . $result->city . ',  ' . $result->town . ',  (' . $result->AddType . ')</br>';
			$display_block2 .= 'Telephone: ' . $result->telephoneNo . '   (' . $result->TelType . ')</br>';
            $display_block2 .= 'Email: ' . $result->Email . '(    ' . $result->EmailType . ')</br>';
            $display_block2 .= 'Fax: ' . $result->Fax_number . '(    ' . $result->FaxType . ')</br>';
            $display_block2 .= 'Notes: ' . $result->note.'</br>';		
        } else {
            $display_block2 = "Selected Contact not found in Address Book";
        }
        return $display_block2;
    }
	
	   function deleteContact($master_id) {

        //Delete from the master name table
        $this->db->where('master_id', $master_id);
        $this->db->delete('master_name');

        //If the row in master_table was deleted
        if ($this->db->affected_rows() > 0) {

            //Delete from the address table  
            $this->db->where('master_id', $master_id);
            $this->db->delete('address');

            //Delete from the telephone  table
            $this->db->where('master_id', $master_id);
            $this->db->delete('telephone');

            //Delete from the email  table
            $this->db->where('master_id', $master_id);
            $this->db->delete('email');
			
			
            //Delete from the fax  table
            $this->db->where('master_id', $master_id);
            $this->db->delete('fax');
			
			
            //Delete from the personal_notes  table
            $this->db->where('master_id', $master_id);
            $this->db->delete('personal_notes');
        }
    }
	//-------------------------------------------------------------------------
	
	function UpdateContact($contact_details) {
		
			$this->updateAddressDetails($contact_details);
			$this->updateTelephoneDetails($contact_details);
			$this->updateFaxDetails($contact_details);
			$this->updateEmailDetails($contact_details);
			$this->updatePersonalNotesDetails($contact_details);
	}
	
	//-------------------------------------------------------------------------
	
    function updateAddressDetails($update_id,$address, $city, $town, $address_type) {
		//Updates the Address details to the Address Table


        //Update address details from form into associative array with keys same name as database fields        
        $address_data['date_modified'] = date('Y-m-d');
        $address_data['address'] = $address;
        $address_data['city'] = $city;
        $address_data['town'] = $town;
        $address_data['type'] = $address_type;

		$this->db->where('master_id', $update_id);
        $this->db->update('address', $address_data);
    }

    //Adds new Telephone Details to the Telephone Table
    function updateTelephoneDetails($update_id,$telephone_number, $telephone_type) {

        //Inserts telephone details from form into associative array with keys same name as database fields
        $tel_data['date_modified'] = date('Y-m-d');
        $tel_data['telephoneNo'] = $telephone_number;
        $tel_data['type'] = $telephone_type;

        $this->db->where('master_id', $update_id);
        $this->db->update('telephone', $tel_data);
    }

    //Adds new Email details to the Email table
    function updateEmailDetails($update_id,$email, $email_type) {
		
        //Inserts email details from form into associative array with keys same name as database fields        
        $email_data['date_modified'] = date('Y-m-d');
        $email_data['email'] = $email;
        $email_data['type'] = $email_type;

        $this->db->where('master_id', $update_id);
        $this->db->update('email', $email_data);
    }

	
    //Update Fax table with new fax details if changed
    function updateFaxDetails($update_id,$fax_number, $fax_type) {

        //Inserts email details from form into associative array with keys same name as database fields        
        $fax_data['date_modified'] = date('Y-m-d');
        $fax_data['fax_number'] = $fax_number;
        $fax_data['type'] = $fax_type;

        $this->db->where('master_id', $update_id);
        $this->db->update('fax', $fax_data);
    }

    //Update PersonalNotes table with new note details if changed
    function updatePersonalNotesDetails($update_id,$note) {

        //Inserts note details from form into associative array with keys same name as database fields        
        $note_data['date_modified'] = date('Y-m-d');
        $note_data['note'] = $note; 
		
        $this->db->where('master_id', $update_id);
        $this->db->update('personal_notes', $note_data);
    }
	
    //Select the contact name details for the selected contact
    function getSelectedContactDetailsForUpdate($master_id) {

        $display_block2 = "";

            //Get the Contact Address Details
            $this->db->select("address, city, town, type", false);
            $query = $this->db->get_where('address', array('master_id' => $master_id));

            if ($query->num_rows() > 0) {
                $result = $query->row();
                $display_block2 .= "<strong>Address: </strong>";
                $display_block2 .= "<input type=\"text\" name=\"address\" size=\"55\" maxlength=\"60\" value=$result->address></p>";
                $display_block2 .= "<strong>City:  </strong>";
                $display_block2 .= "<input type=\"text\" name=\"city\" size=\"20\" maxlength=\"50\" value=$result->city></p>";
                $display_block2 .= "<strong>Town:  </strong>";
                $display_block2 .= "<input type=\"text\" name=\"town\" size=\"20\" maxlength=\"30\" value=$result->town></p>";
				$display_block2 .= "<strong>Address Type: </strong>";
	
				if ($result->type = 'home') {
					$display_block2 .="<input type=\"radio\" name=\"add_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"add_type\" value=\"work\"> work
									<input type=\"radio\" name=\"add_type\" value=\"other\"> other</p>";
				} else if ($result->type = 'work') {
					$display_block2 .="<input type=\"radio\" name=\"add_type\" value=\"home\"> home
									<input type=\"radio\" name=\"add_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"add_type\" value=\"other\"> other</p>";
				}
				else {
				 	$display_block2 .="<input type=\"radio\" name=\"add_type\" value=\"home\"> home
									<input type=\"radio\" name=\"add_type\" value=\"work\"> work
									<input type=\"radio\" name=\"add_type\" value=\"other\" checked> other</p>";
				}
			}
			
			//Get the Contact Telephone Details
            $this->db->select("telephoneNo, type", false);

            $query = $this->db->get_where('telephone', array('master_id' => $master_id));

            if ($query->num_rows() > 0) {
                $result = $query->row();                
                $display_block2 .= "<strong>Telephone:  </strong>";
                $display_block2 .= "<input type=\"text\" name=\"tel_number\" size=\"18\" maxlength=\"45\" value=$result->telephoneNo></p>";               
   				$display_block2 .= "<strong>Telephone Type: </strong>";
	         	
				if ($result->type = 'home') {
					$display_block2 .="<input type=\"radio\" name=\"tel_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"tel`_type\" value=\"work\"> work
									<input type=\"radio\" name=\"tel_type\" value=\"other\"> other</p>";
				} else if ($result->type = 'work') {
					$display_block2 .="<input type=\"radio\" name=\"tel_type\" value=\"home\"> home
									<input type=\"radio\" name=\"tel_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"tel_type\" value=\"other\"> other</p>";
				}
				else {
					
				 	$display_block2 .="<input type=\"radio\" name=\"tel_type\" value=\"home\"> home
									<input type=\"radio\" name=\"tel_type\" value=\"work\"> work
									<input type=\"radio\" name=\"tel_type\" value=\"other\" checked> other</p>";
				}		
			}

            //Get the Contact Email Details
            $this->db->select("email, type", false);
 
            $query = $this->db->get_where('email', array('master_id' => $master_id));

            if ($query->num_rows() > 0) {
                $result = $query->row();
                $display_block2 .= "<strong>Email:  </strong>";
                $display_block2 .= "<input type=\"text\" name=\"email\" size=\"30\" maxlength=\"150\" value=$result->email></p>";
    			$display_block2 .= "<strong>Email Type: </strong>";
	
            	if ($result->type = 'home') {
					$display_block2 .="<input type=\"radio\" name=\"email_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"email_type\" value=\"work\"> work
									<input type=\"radio\" name=\"email_type\" value=\"other\"> other</p>";
				} else if ($result->type = 'work') {
					$display_block2 .="<input type=\"radio\" name=\"email_type\" value=\"home\"> home
									<input type=\"radio\" name=\"email_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"email_type\" value=\"other\"> other</p>";
				}
				else {
				 	$display_block2 .="<input type=\"radio\" name=\"email_type\" value=\"home\"> home
									<input type=\"radio\" name=\"email_type\" value=\"work\"> work
									<input type=\"radio\" name=\"email_type\" value=\"other\" checked> other</p>";
				}
			}
			
			//Get the fax Details
            $this->db->select("fax_number, type", false);
 
            $query = $this->db->get_where('fax', array('master_id' => $master_id));

            if ($query->num_rows() > 0) {
                $result = $query->row();
                $display_block2 .= "<strong>Fax:  </strong>";
                $display_block2 .= "<input type=\"text\" name=\"fax\" size=\"30\" maxlength=\"150\" value=$result->fax_number></p>";
				$display_block2 .= "<strong>Fax Type: </strong>";
	
            	if ($result->type = 'home') {
					$display_block2 .="<input type=\"radio\" name=\"fax_type\" value=\"home\" checked> home
									<input type=\"radio\" name=\"fax_type\" value=\"work\"> work
									<input type=\"radio\" name=\"fax_type\" value=\"other\"> other</p>";
				} else if ($result->type = 'work') {
					$display_block2 .="<input type=\"radio\" name=\"fax_type\" value=\"home\"> home
									<input type=\"radio\" name=\"fax_type\" value=\"work\" checked> work
									<input type=\"radio\" name=\"fax_type\" value=\"other\"> other</p>";
				}
				else {
				 	$display_block2 .="<input type=\"radio\" name=\"fax_type\" value=\"home\"> home
									<input type=\"radio\" name=\"fax_type\" value=\"work\"> work
									<input type=\"radio\" name=\"fax_type\" value=\"other\" checked> other</p>";
				}
			}
			
			//Get the personal_notes Details
            $this->db->select("note", false);
 
            $query = $this->db->get_where('personal_notes', array('master_id' => $master_id));

            if ($query->num_rows() > 0) {
                $result = $query->row();
                $display_block2 .= "<strong>Personal Notes:  </strong>";
                $display_block2 .= "<input type=\"text\" name=\"note\" size=\"80\" maxlength=\"80\" value=$result->note></p>";
            }	 
		return $display_block2;
    }
}

?>

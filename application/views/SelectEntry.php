<?php
//If the submit button not selected. Only displays selected details
//of contact once contact is selected from select box
if ($_POST) {    
   echo $display_block2;
}
    
?>
<html>
    <head>
        <title>My Records</title>
    </head>
    <body>
        <h2>View Contact Details</h2>
			<?php echo form_open('Home/SelectedContact'); ?> <!--on submit goes to this method -->
            <p><strong>Select a Record to View:</strong><br/>
                <select name="master_id">
                    <?php echo $display_block; ?>
                </select>
            <p><input type="submit" name="submit" value="View Selected Entry"></p>
            
            <p><a href="<?php echo base_url(); ?>index.php/Home/index/">Back to Home Menu</a></p>
        </form>               

    </body>
</html>
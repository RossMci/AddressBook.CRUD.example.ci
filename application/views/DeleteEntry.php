
<html>
    <head>
        <title>My Records</title>
    </head>
    <body>
        <h1>Select an Entry</h1>
		<?php echo form_open("Home/DeleteSelectedContact"); ?>
				 <p><strong>Delete a Record:</strong><br/>
                    <select name="master_id">
                    <?php echo $display_block; ?>                    
                    </select>			
                <p><input type="submit" name="submit" value="Delete Selected Entry"></p>

                <p><a href="<?php echo base_url(); ?>index.php/Home/index/">Back to Home Menu</a></p>
            </form>               

    </body>
</html>

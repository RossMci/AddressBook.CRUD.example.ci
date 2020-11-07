<html>
    <head>
        <title>My Address Book</title>
    </head>
    <body>
        <h1>My Address Book</h1>
        <!-- Home Page with links on it to perform the CRUD Actions -->

        <p><strong>Address Book Management</strong></p>
        <ul>
            <li><a href="<?php echo site_url('Home/AddEntry'); ?>">Add an Entry</a><br></li>
            <li><a href="<?php echo site_url('Home/DeleteEntry'); ?>">Delete an Entry</a><br></li>
            <li><a href="<?php echo site_url('Home/UpdateEntry'); ?>">Update an Entry</a><br></li>
        </ul>
        <p><strong>View Address Book Records</strong></p>
        <ul>
            <li><a href="<?php echo site_url('Home/SelectEntry'); ?>">Select an Entry to view</a></li>
        </ul>
        <p><strong>Display Address Book Contacts Per Page</strong></p>
       
        <ul>
            <li><a href="<?php echo site_url('Page/Index'); ?>">View All Contacts</a></li>
        </ul>
    </body>
</html>

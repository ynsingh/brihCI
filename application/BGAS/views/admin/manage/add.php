<?php
	echo form_open('admin/manage/add');

	echo "<p>";
	echo form_label('Label', 'database_label');
	echo "<br />";
	echo form_input($database_label);
	echo "<br />";
	echo "<span class=\"form-help-text\">Example : bgasiitk</span>";
	echo "</p>";
	
	echo "<p>";
        echo form_label('Organisation Name', 'org_name');
        echo "<br />";
        echo form_input($org_name);
        echo "</p>";
        
        echo "<p>";
        echo form_label('Account Unit Name', 'unit_name');
        echo "<br />";
        echo form_input($unit_name);
        echo "</p>";

	echo "<p>";
	echo form_label('Database Name', 'database_name');
	echo "<br />";
	echo form_input($database_name);
	echo "</p>";

	echo "<p>";
	echo form_label('Database Username', 'database_username');
	echo "<br />";
	echo form_input($database_username);
	echo "</p>";

	echo "<p>";
	echo form_label('Database Password', 'database_password');
	echo "<br />";
	echo form_password($database_password);
	echo "</p>";

	echo "<p>";
	echo form_label('Database Host', 'database_host');
	echo "<br />";
	echo form_input($database_host);
	echo "</p>";

	echo "<p>";
	echo form_label('Database Port', 'database_port');
	echo "<br />";
	echo form_input($database_port);
	echo "</p>";

	echo "<p>";
	echo form_submit('submit', 'Add');
	echo " ";
	echo anchor('admin/manage', 'Back', array('title' => 'Back to active account list'));
	echo "</p>";

	echo form_close();


<?php

	echo form_open('group/add_fund_group');

	echo "<p>";
	echo form_label('Group Name', 'group_name');
	echo "<br />";
	echo form_input($group_name);
	echo "</p>";

	echo "<p>";
	echo form_label('Short Name', 'short_name');
	echo "<br />";
	echo form_input($short_name);
	echo "</p>";

	echo "<p>";
	echo form_label('Parent Group', 'group_parent');
	echo "<br />";
	echo form_input($group_parent);
	echo "</p>";

	echo "<p>";
	echo form_label('Group Description', 'group_description');
	echo "<br />";
	echo form_textarea($group_description);
	echo "</p>";

	echo "<p>";
	echo form_submit('submit', 'Create');
	echo " ";
	echo anchor('account', 'Back', 'Back to Chart of Accounts');
	echo "</p>";

	echo form_close();

?>
<div>
	<div id="up-row">
	<div class="settings-container">
		<div class="settings-title">
			<?php print '<strong><font size=5>'. "Before Carrying forward the account to next year, make sure that you have set the account type and ledger name for transfer or profit/loss in the account settings.".'</font></strong>';?>
		</div>
		<div class="settings-desc">
		</div>
	</div>
	</div>
	<div id="left-col">
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('setting/account', 'Account Settings', array('title' => 'Account Settings')); ?>
			</div>
			<div class="settings-desc">
				Setup account details, currency, time, etc.
			</div>
		</div>
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('setting/cf', 'C / F Account', array('title' => 'Carry Forward Account')); ?>
			</div>
			<div class="settings-desc">
				Carry forward account to next year
			</div>
		</div>
<!--		<div class="settings-container">
			<div class="settings-title">
				<?php //echo anchor('setting/email', 'Email Settings', array('title' => 'Email Settings')); ?>
			</div>
			<div class="settings-desc">
				Setup outgoing email
			</div>
		</div>
-->		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('setting/printer', 'Printer Settings', array('title' => 'Printer Settings')); ?>
			</div>
			<div class="settings-desc">
				Setup printing options for entries, reports, etc.
			</div>
		</div>
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('setting/backup/available_backup', 'Create Backups', array('title' => 'Available Backup')); ?>
			</div>
			<div class="settings-desc">
				Available backup of current accounts data
			</div>
		</div>
	</div>
	<div id="right-col">
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('tag', 'Tags', array('title' => 'Tags')); ?>
			</div>
			<div class="settings-desc">
				Manage Entry Tags
			</div>
		</div>
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('setting/entrytypes', 'Entry Types', array('title' => 'Entry Types')); ?>
			</div>
			<div class="settings-desc">
				Manage Entry Types
			</div>
		</div>
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('setting/logo', 'Upload Logo', array('title' => 'Upload Logo')); ?>
			</div>
			<div class="settings-desc">
				Upload Logo and set Institute Name
			</div>
		</div>
	</div>
</div>
<div class="clear">
</div>

<div>
	<div id="left-col">
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('admin/create', 'Create Account', array('title' => 'Create a new account')); ?>
			</div>
			<div class="settings-desc">
				Create a new account
			</div>
		</div>
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('admin/manage', 'Manage Accounts', array('title' => 'Manage existing accounts')); ?>
			</div>
			<div class="settings-desc">
				Manage existing accounts
			</div>
		</div>
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('admin/user', 'Manage Users', array('title' => 'Manage users')); ?>
			</div>
			<div class="settings-desc">
				Manage users and permissions
			</div>
		</div>
		<div class="settings-container">
	        <div class="settings-title">
	                <?php echo anchor('admin/emailSet', 'Manage Mail Settings', array('title' => 'Manage Mail Settings')); ?>
	        </div>
	        <div class="settings-desc">
	                Manage Mail Settings 
	        </div>
        </div>

        <div class="settings-container">
	        <div class="settings-title">
	                <?php echo anchor('admin/authorities/auth_allocation', 'Manage Authority Allocation', array('title' => 'Allocate Authorities to Users')); ?>
	        </div>
	        <div class="settings-desc">
	                Allocate Authorities to Users 
	        </div>
        </div>

	</div>
	<div id="right-col">
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('admin/setting', 'General Settings', array('title' => 'General Application Settings')); ?>
			</div>
			<div class="settings-desc">
				General application settings
			</div>
		</div>
		<div class="settings-container">
			<div class="settings-title">
				<?php echo anchor('admin/status', 'Status Report', array('title' => 'Status Report')); ?>
			</div>
			<div class="settings-desc">
				Status report of the application
			</div>
		</div>
		<div class="settings-container">
            <div class="settings-title">
                    <?php echo anchor('admin/sqlAdmin', 'MySQL Admin Setting', array('title' => 'MySQL Admin Setting')); ?>
            </div>
            <div class="settings-desc">
                    MySQL administrator Setting of the application
            </div>
        </div>

        <div class="settings-container">
	        <div class="settings-title">
	                <?php echo anchor('admin/authorities', 'Manage Authorities', array('title' => 'Manage Authorities')); ?>
	        </div>
	        <div class="settings-desc">
	                Manage Authorities
	        </div>
        </div>

	</div>
</div>
<div class="clear">
</div>

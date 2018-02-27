<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>BGAS<?php if (isset($page_title)) echo ' | ' . $page_title; ?></title>

<?php echo link_tag(base_url() . 'assets/bgas/images/favicon.ico', 'shortcut icon', 'image/ico'); ?>
<link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>assets/bgas/css/style.css">
<link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>assets/bgas/css/tables.css">
<link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>assets/bgas/css/custom.css">
<link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>assets/bgas/css/menu.css">
<link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>assets/bgas/css/jquery.datepick.css">
<link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>assets/bgas/css/thickbox.css">

<script type="text/javascript">
	var jsSiteUrl = '<?php echo base_url(); ?>';
</script>

<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/jquery.datepick.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/custom.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/superfish.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/supersubs.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/thickbox-compressed.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/ezpz_tooltip.min.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/shortcutslibrary.js"></script>
<script type="text/javascript" src="<?php echo asset_url(); ?>assets/bgas/js/shortcuts.js"></script>
<style>
.chcol{
        animation-name: blink;
        animation-duration: 4s;
        animation-iteration-count: infinite;
        animation-direction: alternate;
	text-decoration: none;
}

@keyframes blink {
        0%   {color:Turquoise;}
        10%   {color:SteelBlue;}
        20%   {color:SlateBlue;}
        30%   {color:SkyBlue;}
        40%   {color:RoyalBlue;}
        50%   {color:PowderBlue;}
        60%   {color:MediumTurquoise;}
        70%   {color:MediumSlateBlue;}
        80%   {color:MediumBlue;}
        90%   {color:DodgerBlue;}
        100%  {color:DeepSkyBlue;}
}
</style>
</head>
<body>
<div id="container">
	<div id="mainheader">
		<div id="admin">
		<?php
                        if ($this->session->userdata('user_name')) 
			{
			$username=$this->session->userdata('user_name');
                        $db1=$this->load->database('login', TRUE);
                        $db1->select('id')->from('edrpuser')->where('username', $username);
                        $query_result = $db1->get();
                        foreach ($query_result->result() as $row) 
			{
                                $userid = $row->id;
                        }
                        $db1->from('bgasuserrolegroup')->where('userid', $userid);
                        $userrec = $db1->get();
                        foreach($userrec->result() as $row)
            		{
                		$type=$row->aggtype;
                                $userrole=$row->role;
            		}
                                echo anchor('', 'Accounts', array('title' => "Accounts", 'class' => 'anchor-link-b'));
                                echo " | ";
                                /* Check if allowed administer rights */
                                if (check_access('administer')) 
				{
                                        echo anchor('admin', 'Administer', array('title' => "Administer", 'class' => 'anchor-link-b'));
                                        echo " | ";
                                }
                                //if (check_access('administer')) ||(check_access('manager')) {
                                //if ($userrole=='manager') {
                                if(check_access('administer'))
				{
                                        if($type=='agg')
                                        {
                                        	echo anchor('admin/aggregator', 'Aggregater', array('title' => "Aggregater", 'class' => 'anchor-link-b'));
                                        	echo " | ";
                                        }
                                }
                                echo anchor('user/profile', 'Profile', array('title' => "Profile", 'class' => 'anchor-link-b'));
                                echo " | ";
                                echo anchor('user/logout', 'Logout', array('title' => "Logout", 'class' => 'anchor-link-b'));
			}
		?>
		</div>

		<div id="logo1">
			<?php //echo anchor('', 'Brihaspati General Accounting System', array('class' => 'anchor-link-b')); ?>
			<a class="chcol" href=''>Brihaspati General Accounting System</a>
		</div>
		<?php
			 /* Check applist table exists in brihaspati database*/
//        	        $db2=$this->load->database('brihaspati',TRUE);
		
/*			$applist="";
			if ($db2){
	                /* check if table exist *
                	$table="APPLIST";
			$Flag=FALSE;
                	if($db2->query("SHOW TABLES LIKE '".$table."'")->num_rows()==1){
				$this->messages->add('Brihaspati database with APPLICATION LIST table exists.', 'success');
				$db2->from('APPLIST');
                                $db2->select('*')->where('APPSTATUS = ', 0);
                                $applist = $db2->get();
				$Flag=TRUE;
                	}	
			else{
				$this->messages->add('Brihaspati database with APPLICATION LIST table is not exists. so contact to administrator for application header', 'success');
			}
			}
*/
		/*	echo "<div id=\"admin\">";
			$username=$this->session->userdata('user_name');
			$db1=$this->load->database('login', TRUE);
			$db1->select('id')->from('edrpuser')->where('username', $username);
			$query_result = $db1->get();
			foreach ($query_result->result() as $row) {
				$userid = $row->id;
			}

			$db1->from('bgasuserrolegroup')->where('userid', $userid);
			$userrec = $db1->get();
			
			foreach($userrec->result() as $row)
            {
                $type=$row->aggtype;
				$userrole=$row->role;
            }

			
			if ($this->session->userdata('user_name')) {
				echo anchor('', 'Accounts', array('title' => "Accounts", 'class' => 'anchor-link-b'));
				echo " | ";
				/* Check if allowed administer rights */
		/*		if (check_access('administer')) {
					echo anchor('admin', 'Administer', array('title' => "Administer", 'class' => 'anchor-link-b'));
					echo " | ";
				}
				//if (check_access('administer')) ||(check_access('manager')) {
				//if ($userrole=='manager') {
				if(check_access('administer')){
					if($type=='agg')
					{
					echo anchor('admin/aggregator', 'Aggregater', array('title' => "Aggregater", 'class' => 'anchor-link-b'));
					echo " | ";
					}
				}
				echo anchor('user/profile', 'Profile', array('title' => "Profile", 'class' => 'anchor-link-b'));
				echo " | ";
				echo anchor('user/logout', 'Logout', array('title' => "Logout", 'class' => 'anchor-link-b'));
			}
			echo "</div>";
			echo "<div>";
/*				if(($db2)&&($Flag)){
                                foreach($applist->result() as $row)
                                {
					$appacrm ="";
					if($row->ACRONYM == "")
						$appacrm = $row->APPNAME;
					else
						$appacrm = $row->ACRONYM;

					$urlf="";
					if ($this->session->userdata('user_name')) 
						$urlf=$row->APPURL."?lgdst=lgdn";
					else
						$urlf=$row->APPURL."?lgdst=nlgdn";

					if((strcasecmp(($row->ACRONYM), "BGAS") != 0 )||(strcasecmp(($row->APPNAME),"Brihaspati General Accounting System")!=0)){
					//if(($row->ACRONYM) != "BGAS"){
						echo anchor($urlf, $appacrm, array('title' => $row->APPNAME, 'class' => 'anchor-link-b'));
						echo " | ";
					}
                                }
			$db2->close();
				}
*/
		/*	echo "</div>";*/
		?>
	</div>
	<div id="header">
	<div id="menu">
		<ul class="sf-menu">
			<li class="current">
			</li>
		</ul>
	</div>
	<div id="content">
		<div id="sidebar">
			<?php if (isset($page_sidebar)) echo $page_sidebar; ?>
		</div>
		<div id="main">
			<div id="main-title">
				<?php if (isset($page_title)) echo $page_title; ?>
			</div>
			<div id="main-links">
				<?php if (isset($nav_links)) {
					echo "<ul id=\"main-links-nav\">";
					foreach ($nav_links as $link => $title) {
						if ($title == "Print Preview")
							echo "<li>" . anchor_popup($link, $title, array('title' => $title, 'class' => 'nav-links-item', 'style' => 'background-image:url(\'' . base_url() . 'assets/bgas/images/buttons/navlink.png\');', 'width' => '1024')) . "</li>";
						else
							echo "<li>" . anchor($link, $title, array('title' => $title, 'class' => 'nav-links-item', 'style' => 'background-image:url(\'' . base_url() . 'assets/bgas/images/buttons/navlink.png\');')) . "</li>";
					}
					echo "</ul>";
				} ?>
			</div>
			<div class="clear">
			</div>
			<div id="main-content">
				<?php
				$messages = $this->messages->get();
				if (is_array($messages))
				{
					if (count($messages['success']) > 0)
					{
						echo "<div id=\"success-box\">";
						echo "<ul>";
						foreach ($messages['success'] as $message) {
							echo ('<li>' . $message . '</li>');
						}
						echo "</ul>";
						echo "</div>";
					}
					if (count($messages['error']) > 0)
					{
						echo "<div id=\"error-box\">";
						echo "<ul>";
						foreach ($messages['error'] as $message) {
							if (substr($message, 0, 4) == "<li>")
								echo ($message);
							else
								echo ('<li>' . $message . '</li>');
						}
						echo "</ul>";
						echo "</div>";
					}
					if (count($messages['message']) > 0)
					{
						echo "<div id=\"message-box\">";
						echo "<ul>";
						foreach ($messages['message'] as $message) {
							echo ('<li>' . $message . '</li>');
						}
						echo "</ul>";
						echo "</div>";
					}
				}
				?>
				<?php echo $contents; ?>
			</div>
		</div>
	</div>
<div id="footer">
	<?php if (isset($page_footer)) echo $page_footer ?>
<a href="/~brihaspati/BGAS/ListOfDocument.html" target="_blank">Importants Links</a> Based on <a href="http://webzash.org" target="_blank"> Webzash<a/> and licensed is <a href="/~brihaspati/BGAS/brihaspati-license.txt" target="_blank">BGAS License</a> and <a href="/~brihaspati/BGAS/acknowledgement.txt" target="_blank">BGAS Acknowledgement</a>
</div>
</body>
</html>

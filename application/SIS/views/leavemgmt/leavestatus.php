<!--@name leavestatus.php 
    @author Shobhit Tiwari(tshobhit206@gmail.com)
    @author Ankur Singh (ankursingh206@gmail.com)
-->
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
	<head>
 		<title>View Leave Details</title> 
 		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/profile.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
	</head>

<body>
   <?php $this->load->view('template/header'); ?>
		 <table width="100%">
          <tr><td>
             <div>
                <td><?php echo anchor('leavemgmt/staffleavedetails/', "View Leave Details", array('title' => 'View Leave Taken' ,'class' =>'top_parent'));?></td>
					 <td><?php echo anchor('leavemgmt/viewels/', "View Earned Leave Details", array('title' => 'View Earned Leave Taken' ,'class' =>'top_parent'));?></td>
					 <?php echo "<td align=\"center\" width=\"34%\" style=\"font-size:16px\">";
                    	 echo "<b>Leave Status</b>";
                    	 echo "</td>";
					 ?>
                <?php
                echo "<td align=\"right\" width=\"34%\">";
                $help_uri = site_url()."/help/helpdoc#Role";
                echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
                echo "</td>";
                ?>
                <div  style="width:100%;">
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php if(isset($_SESSION['success'])){?>
                <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                <?php
                };
                ?>
                <?php if(isset($_SESSION['err_message'])){?>
                <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>
                <?php
                };
                ?>
              </div>
            </td></tr>
	     </table>
		  <div class="scroller_sub_page">
 		  <table class="TFtable" >
          <thead>
					<tr>
                  <th width="2%">S.No</th>
		            <th width="2%">Leave Type</th>
                  <th width="2%">Leave Description</th>
                  <th width="2%">From Date</th>
                  <th width="2%">To Date</th>
						<th width="2%">Leave Days</th>
						<th width="2%">Reason for Rejecting Leave</th>
                  <th width="2%">Status</th>
					</tr>
          </thead>
			 <tbody>
				<?php
		 			if(!empty($this->fldata)):
        			$count =0;
               foreach ($this->fldata as $row)
        			{
         	?>
            <tr>
            	<td> <?php echo ++$count; ?> </td>
            	<td> <?php 	echo $this->sismodel->get_listspfic1('leave_type_master','lt_name','lt_id',$row['lname'])->lt_name; ?></td>
			   	<td> <?php echo $row['ldesc'] ?></td>
            	<td> <?php echo $row['lfdate'] ?></td>
            	<td> <?php echo $row['ltdate'] ?></td>
					<td> <?php 
								if($row['ldays']==0.5)	
									{
									echo "Half Day"; 
									}
								 else 
									{
									echo $row['ldays']; 	
									}
							?></td>
					<td> <?php echo $row['lrejres'] ?></td>
            	<td> <?php $lst = $row['lstatus'];
								if($lst == 0){
								?><font color="blue"><?php echo "Pending"; ?></font>
								<?php
								}
								if($lst == 1){
								?><font color="green"><?php echo "Approved"; ?></font>
								<?php
								}
								if($lst == 2){
								?><font color="red"><?php echo "Rejected"; ?></font>
								<?php
								}
 						  ?></td>
	         </tr>
				<?php
		        }
			   ?>
				<?php else : ?>
              <td colspan= "8" align="center"> No Records found...!</td>
            <?php endif;?>
			</tbody>
		</table></div>
</body>
<div align="center"> <?php $this->load->view('template/footer');?></div>
</html>

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name profiletab.php
@ author Nagendra Kumar Singh[nksinghiitk@gmail.com]
 -->
<head>
<style>
/* Style the buttons */
.btn {
   background-color: blue;
    cursor: pointer;
    font-size: 12px;
    color: blue;
    text-decoration: none;
	font-size:13px;
}

.btn1 {
    background-color: grey;
    cursor: pointer;
    font-size: 12px;
    color: white;
    text-decoration: none;
}
/* Style the active class, and buttons on mouse-over */
 .btn1:hover {
    background-color: lightgreen;
}

.active, .btn {
    background-color: pink;
}
</style>
</head>
<!--
If you make any change in this file then you must change same file in report directory

-->
<div id="myDIV">
	<table border=0 >
                <tr>
			<?php if($current == 'basic') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/staffmgmt/editempprofile/".$emp_id?>'style="color:white; text-decoration: none" >Basic Profile</a> </b></td>
			<?php if($current == 'academic') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/academic_profile/".$emp_id?>' style="color:white;text-decoration: none">Academic Qualification</a> </b></td>
			<?php if($current == 'technical') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/technical_profile/".$emp_id?>' style="color:white;text-decoration: none">Technical Qualification</a> </b></td>
			<?php if($current == 'promotional') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/promotional_profile/".$emp_id?>' style="color:white;text-decoration: none">Promotional Details</a></b> </td>
			<?php if($current == 'service') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/service_profile/".$emp_id?>' style="color:white;text-decoration: none">Service Particulars</a></b> </td>
			<?php if($current == 'addional') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/addionalassign_profile/".$emp_id?>' style="color:white;text-decoration: none">Addional Assignment Particulars</a></b> </td>
			<?php if($current == 'perform') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/performance_profile/abs/".$emp_id?>' style="color:white;text-decoration: none">Performance Details</a></b> </td>
			<?php if($roleid == 1){ ?>
			<?php if($current == 'leave') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/leave_profile/".$emp_id?>' style="color:white;text-decoration: none">Leave Particulars</a></b> </td>
			<?php } ?>
			<?php if($current == 'deputation') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/deputation_profile/".$emp_id?>' style="color:white;text-decoration: none">Deputation Particulars</a></b> </td>
			<?php if($current == 'deptexam') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/deptexam_profile/".$emp_id?>' style="color:white;text-decoration: none">Departmental Exam Passed Details</a></b> </td>
			<?php if($current == 'workorder') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/workorder_profile/".$emp_id?>' style="color:white;text-decoration: none">Working Arrangement Particulars</a></b> </td>
			<?php if($current == 'recruit') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/recruit_profile/".$emp_id?>' style="color:white;text-decoration: none">Recruitment Particulars</a></b> </td>

 <?php if($current == 'uplsdoc') { ?>
                                <td class=btn active>
                        <?php }else{ ?>
                                <td class=btn1>
                        <?php } ?>
                        <b>  <a href='<?php echo site_url()."/upl/viewuploaddocument/".$emp_id?>' style="font-size:13px;color:white;text-decoration: none;size:55;" >Upload Support Documents</a></b> </td>
<!--			<?php //if($current == 'upldoc') { ?>
				<td class=btn active>
			<?php// }else{ ?>
				<td class=btn1>
			<?php //} ?>
			<b>  <a href='<?php //echo site_url()."/upl/uploaddocumentlist/".$emp_id?>' style="color:white;text-decoration: none">Upload Support Documents</a></b> </td>
-->
			<?php if($current == 'disciplin') { ?>
				<td class=btn active>
			<?php }else{ ?>
				<td class=btn1>
			<?php } ?>
			<b>  <a href='<?php echo site_url()."/report/disciplin_profile/".$emp_id?>' style="color:white;text-decoration: none">Disciplinary Action Details</a></b> </td>
		</tr>
	</table>
</div>

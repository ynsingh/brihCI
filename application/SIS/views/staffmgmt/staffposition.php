<!--@name staffposition.php 
  @author Om Prakash(omprakashkgp@gmail.com)
 -->
 <?php defined('BASEPATH') OR exit('No direct script access allowed');?>
 <html>
    <head>    
        <?php $this->load->view('template/header'); ?>
        <?php $this->load->view('template/menu');?>
        <!--    <h1>Welcome <?= $this->session->userdata('username') ?>  </h1>-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css"> 
    </head>
    <body>
<table id="uname"><tr><td align=center>Welcome <?= $this->session->userdata('username') ?>  </td></tr></table>
<table width="100%">
            <tr colspan="2">
         <?php
	    echo "<td align=\"left\" width=\"33%\">";
            echo anchor('staffmgmt/newstaffposition/', ' Staff Position ', array('class' => 'top_parent'));
            echo "</td>";
            echo "<td align=\"center\" width=\"34%\">";
            echo "<b>Staff Position Details</b>";
            echo "</td>";
            echo "<td align=\"right\" width=\"33%\">";
	    $help_uri = site_url()."/help/helpdoc#ViewStaffPosition";
            echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
           echo "</td>";
         ?>
       <div>
        <?php echo validation_errors('<div class="isa_warning">','</div>');?>
         <?php echo form_error('<div class="isa_error">','</div>');?>
          <?php if(isset($_SESSION['success'])){?>
              <div class="isa_success"><?php echo $_SESSION['success'];?></div>
          <?php
          };
          ?>
        <?php if  (isset($_SESSION['err_message'])){?>
	     <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>

	<?php
	};
	?>
 </div>
</tr>
 </table>
        <div class="scroller_sub_page">
        <table class="TFtable" >
            <thead>
                <tr>
		<th> Sr.No </th>
		<th> Campus Name </th>
		<th> U O Control </th>
		<th> Department Name </th>
		<th> Working Type </th>
		<th> Employee Type </th>
		<th> Employee Post </th>
		<th> Pay Band </th>
		<th> Method of Recruitment </th>
		<th> Sanction Strength Position </th>
		<th> Present Position </th>
		<th> Vacant Position </th>
		<th> Action </th>
	</thead>
	<tbody>
	<?php $count = 0;
	 if( count($this->result) ) {
		foreach ($this->result as $row)
		{
		?>    
			<tr>
			 <td><?php echo ++$count; ?> </td>
                         <td><?php echo $this->commodel->get_listspfic1('study_center', 'sc_name', 'sc_id', $row->sp_campusid)->sc_name; ?> </td>
			 <td><?php echo $this->lgnmodel->get_listspfic1('authorities', 'name', 'id', $row->sp_uo)->name ?> </td>
			 <td><?php echo $this->commodel->get_listspfic1('Department', 'dept_name', 'dept_id', $row->sp_dept)->dept_name; ?> </td>
			 <td><?php echo $row->sp_tnt ?> </td>
			 <td><?php echo $row->sp_type ?> </td>
			 <td><?php echo $this->commodel->get_listspfic1('designation', 'desig_name', 'desig_id', $row->sp_emppost)->desig_name ?> </td>
			 <td><?php echo $row->sp_scale ?> </td>
			 <td><?php echo $row->sp_methodRect ?> </td>
			 <td><?php echo $row->sp_sancstrenght ?> </td>
			 <td><?php echo $row->sp_position ?> </td>
			 <td><?php echo $row->sp_vacant ?> </td>
		        <td><?php echo anchor('staffmgmt/editstaffposition/' . $row->sp_id , "Edit", array('title' => 'Edit Details' , 'class' => 'red-link')) ?> </td> 	
		      </tr>
	<?php  } 
	}else{
  	?>  
        <tr><td colspan= "13" align="center"> No Records found...!</td></tr>
       <?php }?>
	</tbody>
        </table>
        </div><!------scroller div------> 
   </body>
   <div align="center">  <?php $this->load->view('template/footer');?></div>
</html>
 

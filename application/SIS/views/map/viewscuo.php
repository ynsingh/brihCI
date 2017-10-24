<!--@name viewscuo.php 
  @author Om Prakash(omprakashkgp@gmail.com)
 -->
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<html>
    <head>    
        <?php $this->load->view('template/header'); ?>
            <h1>Welcome <?= $this->session->userdata('username') ?>  </h1>
        <?php $this->load->view('template/menu');?>
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css"> 
    </head>
    <body>
   <table style="padding: 8px 8px 8px 20px;width:100%;">
     <tr><td>
      <div align="left">
        <font color=blue size=4pt>
         <?php
            echo anchor('map/studycenteruo', 'Map Study Center with UO', array('class' => 'top_parent'));
         ?>
        </div>
       </div>
       <div style="margin-left:2%;width:90%;">
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
 </td></tr>
 </table>

 <div align="left">
	<table cellpadding="11" style="margin-left:2%;" class="TFtable">
	<thead >
		<tr align="center">
		<th>Sr.No</th>
		<th> Campus Name</th>
		<th> University Officer Control </th>
		<th> Action </th>
	</thead>
	<tbody>
	<?php $count = 0;
	 if( count($this->result) ) {
		 foreach ($this->result as $row)
		 {
	?>    
			<tr align="center">
			    <td><?php echo ++$count; ?> </td>
			    <td><?php echo $this->commodel->get_listspfic1('study_center', 'sc_name', 'sc_id', $row->scuo_scid)->sc_name; ?> </td>
			    <td><?php echo $this->loginmodel->get_listspfic1('authorities', 'name', 'id', $row->scuo_uoid)->name; ?> </td>
			    <td><?php echo anchor('map/updatescuo/' . $row->scuo_id , "Edit", array('title' => 'Edit Details' , 'class' => 'red-link')) ." " ?> </td>	
			</tr>
	<?php } 
	}else{
  	?>  
           <tr><td colspan= "12" align="center"> No Records found...!</td></tr>
       <?php }?> 
       </tbody>
         </table>
       </div>
   </body>
   <div align="center">  <?php $this->load->view('template/footer');?></div>
</html>
 

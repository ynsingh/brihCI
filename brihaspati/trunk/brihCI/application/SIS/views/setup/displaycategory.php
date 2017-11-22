<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name displaycategory.php 
  @author Om Prakash(omprakashkgp@gmail.com)
 -->

<html>
<title>View Category</title>
<head>    
    <?php $this->load->view('template/header'); ?>
    <?php $this->load->view('template/menu');?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css"> 	
</head>    
 <body>
<table id="uname"><tr><td align=center>Welcome <?= $this->session->userdata('username') ?>  </td></tr></table>
<table width='100%'>
     <tr colspan="2"><td>
         <?php
            echo anchor('setup/category/', 'Add Category', array('class' => 'top_parent'));
	    $help_uri = site_url()."/help/helpdoc#ViewCategaryDetail";
	     echo "<a target=\"_blank\" href=$help_uri><b style=\"float:right;position:absolute;margin-left:54%\">Click for Help</b></a>";
         ?>

        <div>
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
        <th>Sr.No</th>
        <th> Category Name </th>
        <th> Category Code </th>
        <th> Category Short Name </th>
        <th> Category Description </th>
        <th> Action </th>
        </thead>
	<tbody>
	     <?php
		$count = 0;
	        foreach ($this->result as $row)
                {
              ?>    
		<tr>
                    <td><?php echo ++$count; ?> </td>
                    <td><?php echo $row->cat_name ?> </td>
                    <td><?php echo $row->cat_code ?> </td>
                    <td><?php echo $row->cat_short ?></td>
		    <td><?php echo $row->cat_desc ?> </td>
		     <?php if($row->cat_id >1){ ?>
             	    <td><?php //echo anchor('setup/deletecategory/' . $row->cat_id , "Delete", array('title' => 'Details' , 'class' => 'red-link' ,'onclick' => "return confirm('Are you sure you want to delete this category record... ')")); ?> &nbsp;&nbsp; <?php echo anchor('setup/editcategory/' . $row->cat_id , "Edit", array('title' => 'Edit Details' , 'class' => 'red-link')); ?>
	       </td>
		<?php } else {
			echo "<td> </td>";
		}?>
               </tr>
 	  <?php } ?>  
	</tbody>		            
    </table>
   </div><!------scroller div------>
  </body>
 <div align="center"> <?php $this->load->view('template/footer');?></div>
</html>
      

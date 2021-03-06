<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name viewprogramcat.php 
  @author Raju Kamal(kamalraju8@gmail.com)
 -->
<html>
<title>View Program Category</title>
<head>    
    <?php $this->load->view('template/header'); ?>
    <?php //$this->load->view('template/menu');?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
</head>
<body>
<!--<table id="uname"><tr><td align=center>Welcome <?//= $this->session->userdata('username') ?>  </td></tr></table>-->
<table width="100%">
     <tr><td>
      <div>
         <?php
            echo "<td align=\"left\" width=\"33%\">";
            echo anchor('setup/programcat/', 'Add Program Category', array('class' => 'top_parent'));
            echo "</td>";
            echo "<td align=\"center\" width=\"34%\">";
            echo "<b>Program Category Details</b>";
            echo "</td>";
            echo "<td align=\"right\" width=\"33%\">";
            $help_uri = site_url()."/help/helpdoc#ViewProgramCategory";
            echo "<a style=\"text-decoration:none\" target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
           echo "</td>";
          ?>
      </div>
       <div>
          <?php echo validation_errors('<div class="isa_warning">','</div>');?>
          <?php echo form_error('<div class="isa_error">','</div>');?>
<?php 
	    	 if(!empty($_SESSION['success'])){
			if(isset($_SESSION['success'])){?>
        		      <div class="isa_success"><?php echo $_SESSION['success'];?></div>
              <?php
              		}
		 }
              ?>
<?php 
	    	 if(!empty($_SESSION['err_message'])){
			if(isset($_SESSION['err_message'])){?>
                        <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>

                <?php
                	}
		 }
                ?>
        </div>
    </td></tr>
  </table>
<div class="scroller_sub_page">
  <table class="TFtable">
        <thead >
        <th>Sr.No</th>
        <th> Program Category Name </th>
        <th> Program Category Code </th>
        <th> Program Category Short Name </th>
        <th> Program Category Description </th>
        <th> Action </th>
        </thead>
        <tbody>
             <?php
                $count = 0;
                foreach ($result as $row)
                {
              ?>
                <tr align="center">
                    <td><?php echo ++$count; ?> </td>
                    <td><?php echo $row->prgcat_name ?> </td>
                    <td><?php echo $row->prgcat_code ?> </td>
                    <td><?php echo $row->prgcat_short ?></td>
                    <td><?php echo $row->prgcat_desc ?> </td>
                    <td><?php //echo anchor('setup/deleteprgcat/' . $row->prgcat_id , "Delete", array('title' => 'Details' , 'class' => 'red-link' ,'onclick' => "return confirm('Are you sure you want to delete this Program category record... ')")); ?> &nbsp;&nbsp; <?php echo anchor('setup/editprogramcat/' . $row->prgcat_id , "Edit", array('title' => 'Edit Details' , 'class' => 'red-link')); ?>
               </br>
               </tr>
          <?php } ?>
        </tbody>
    </table></div>
  </body>
 <div align="center"> <?php $this->load->view('template/footer');?></div>
</html>


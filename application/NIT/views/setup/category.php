<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name category.php 
  @author Om Prakash(omprakashkgp@gmail.com)
 -->


<html>
<title>Add Category</title>
 <head>    
		<!--<link rel="shortcut icon" href="<?php //echo base_url('assets/images'); ?>/index.jpg">-->
	<link rel="icon" href="<?php echo base_url('uploads/logo'); ?>/nitsindex.png" type="image/png">	

	<?php $this->load->view('template/header'); ?>
	<?php //$this->load->view('template/menu');?>

 </head>    
   <body>
<!--<table id="uname"><tr><td align=center>Welcome <?//= $this->session->userdata('username') ?>  </td></tr></table>-->
    <table width="100%"> 
       <tr>
       	<?php
           echo "<td align=\"left\" width=\"33%\">";
           echo anchor('setup/displaycategory', 'Category List', array('class' => 'top_parent'));
           echo "</td>";

           echo "<td align=\"center\" width=\"34%\">";
           echo "<b>Add Category Details</b>";
           echo "</td>";

           echo "<td align=\"right\" width=\"33%\">";
	   $help_uri = site_url()."/help/helpdoc#Category";
	   echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
           echo "</td>"
       	?>
        </tr>
       </table>
       <table width="100%">
       <tr><td>
        <div>
        <?php echo validation_errors('<div class="isa_warning">','</div>');?>
        <?php echo form_error('<div class="isa_error">','</div>');?>
        <?php if(isset($_SESSION['success'])){?>
        <div class="alert alert-success"><?php echo $_SESSION['success'];?></div>
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
        <form action="<?php echo site_url('setup/category');?>" method="POST" class="form-inline">
          <table>
            <tr>  
                <td><label for="cname" class="control-label"> Category Name :</label></td>
                <td>
                <input type="text" name="cname" class="form-control" size="40" />
                </td>
		<td> Example : Scheduled Tribe, Other Backward Class, General etc. </td>
            </tr>
            <tr> 
                <td>    
                <label for="ccode" class="control-label">Category Code :</label>
                </td>
                <td>
                    <input type="text" name="ccode" size="40" class="form-control"/> 
                </td>
		<td>Example : 01, 02, 03, st04, sc-5 etc.</td>
            </tr>
            <tr>
                <td>   
                    <label for="csname" class="control-label">Category Short Name :</label>
                </td>
                <td>
                    <input type="text" name="csname" size="40"  class="form-control"/>
                </td>
		<td> Example : ST, SC, OBC, GEN, PH etc. </td>
            </tr>
            <tr>
                <td>   
                <label for="cdesc" class="control-label">Category Description :</label>
                </td>
                <td>
                    <input type="text" name="cdesc"  size="40"/> 
                </td>
		<td> Example : Reserved Category, Unreserved Category. </td>
            </tr>
            <tr><td></td>
                <td>   
                <button name="category" >Add Category </button>
		<input type="reset" name="Reset" value="Clear"/>
                </td>
            </tr>
     
    </table>
 </form>     
  </body>
    <div align="center"> <?php $this->load->view('template/footer');?></div>
</html>
      

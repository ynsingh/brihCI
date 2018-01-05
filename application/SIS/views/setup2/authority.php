<!---@name authority.php                                                                                                
  @author Nagendra Kumar Singh (nksinghiitk@gmail.com)
  @modification: Om Prakash (omprakashkgp@gmail.com)
  @modification: Manorama Pal (palseema30@gmail.com)
 -->
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<title>View  Authorities</title>
  <head>
   <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
   <?php $this->load->view('template/header'); ?>
   
  </head>
 <body>

    <!--<//?php
        echo "<table border=\"0\" align=\"left\" style=\"color: black;  border-collapse:collapse; border:1px;\">";
        echo "<tr style=\"text-align:left; \">";
        echo "<td style=\"padding: 8px 8px 8px 20px; decoration:none;\">";
        echo anchor('setup/degreerules/', "Add degree rules", array('title' => 'Add degree rules'));
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        ?>--!>

      <table width= "100%">
	<tr colspan="2">
                <?php
                echo "<td align=\"left\" width=\"33%\">";  
		echo anchor('setup2/addauthority/', "Add  Authority", array('title' => 'Add   Authority  Detail','class' =>'top_parent'));
		echo "</td>";
                echo "<td align=\"center\" width=\"34%\">";
                echo "<b>Authority Details</b>";
                echo "</td>";
                echo "<td align=\"right\" width=\"33%\">";
		$help_uri = site_url()."/help/helpdoc#ViewAuthority";
                 echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
                echo "</td>";
                ?>
                  </div>
                <div align="left" style="margin-left:0%;width:95%;">
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
              <?php if(!empty(isset($_SESSION['success']))){?>
                <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                <?php
                };
                ?>
                <?php if(!empty(isset($_SESSION['err_message']))){?>
                        <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>
                <?php
                };
                ?>
              </div>
             </tr>
       </table>
        <div class="scroller_sub_page">
        <table class="TFtable" >
                <tr>
<thead><th>Authority Priority</th><th>Authority Code</th><th>Authority Name</th><th>Authority Nickname</th><th>Authority  Email</th><th>Action</th></tr></thead>
<tbody>
 <?php
        $count =0;
        if( count($this->result) ):
        foreach ($this->result as $row)
        {
         ?>
             <tr>
            <!--<td> <//?php echo ++$count; ?> </td>-->
            <td> <?php
                    $count=$row->priority;
                    echo $count;
                    //echo $row->priority 
                ?>
            </td>  
            <td> <?php echo $row->code ?></td>  
             <td> <?php echo $row->name ?></td>
            <td> <?php echo $row->nickname   ?></td>
            <td> <?php echo $row->authority_email  ?></td>
            <td>
        <?php
              //  if($row->dr_id > 6){
                        //echo anchor('setup2/deleteauthority/' . $row-> id  , "Delete", array('title' => 'Edit Details' , 'class' => 'red-link','onclick' => "return confirm('Are you sure you want to delete this record')")) . " ";

                        echo anchor('setup2/editauthority/' . $row-> id  , "Edit", array('title' => 'Details' , 'class' => 'red-link')) . " ";
         //    }
            echo "</td>";
            echo "</tr>";

        }
        else :
        echo "<tr>";
            echo "<td colspan= \"12\" align=\"center\"> No Records found...!</td>";
        echo "</tr>";
        endif;
           ?>
	 </tbody>
       </table>
     </div><!------scroller div------>
</body>
<div align="center">  <?php $this->load->view('template/footer');?></div>
</html>



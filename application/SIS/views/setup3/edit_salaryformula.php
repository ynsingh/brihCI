
<!--@name edit_salaryformula.php  @author Manorama Pal(palseema30@gmail.com) -->

 <?php defined('BASEPATH') OR exit('No direct script access allowed');?>
 <html>
    <title>Salary Head</title>
    <head>
        <?php $this->load->view('template/header'); ?>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    </head>
    <body>
    
        <table width="100%">
            <tr>
                <?php
                    echo "<td align=\"left\" width=\"33%\">";
                    echo anchor('setup3/salaryformula_list/', "View Salary Head Formula" ,array('title' => 'View salary head formula' , 'class' => 'top_parent'));
                    echo "</td>";
                    echo "<td align=\"center\" width=\"34%\">";
                    echo "<b>Edit Formula</b>";
                    echo "</td>";
                    echo "<td align=\"right\" width=\"33%\">";

                ?>
            </tr>
        </table>
        <table width="100%">
           <tr><td>
           <div>
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php echo form_error('<div class="isa_error">','</div>');?>
                <?php
                if((isset($_SESSION['success'])) && ($_SESSION['success'])!=''){
                    echo "<div  class=\"isa_success\">";
                    echo $_SESSION['success'];
                    echo "</div>";
                }
                if((isset($_SESSION['err_message'])) && (($_SESSION['err_message'])!='')){
                    echo "<div  class=\"isa_error\">";
                    echo $_SESSION['err_message'];
                    echo "</div>";
                }
                ;?>
            </div>
            </td></tr>
        </table>
        <div>
            <form id="myform" action="<?php echo site_url('setup3/edit_salaryformula/'.$id);?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo  $id ; ?>">
            <table>
                <tr>
                	<td><label for="salh_code" class="control-label">Salary Head Code:</label></td>
                        <td><input type="text" name="salh_code" value="<?php 
                        echo $this->sismodel->get_listspfic1('salary_head','sh_code','sh_id',$salhdata->sf_salhead_id)->sh_code;
                        ?>"  size="40" readonly /><br></td>
	     	</tr>
                <tr>
                	<td><label for="salh_name" class="control-label">Salary Head Name:</label></td>
                	<td><input type="text" name="salh_name" value="<?php 
                        echo $this->sismodel->get_listspfic1('salary_head','sh_name','sh_id',$salhdata->sf_salhead_id)->sh_name;
                        echo "( ".$this->sismodel->get_listspfic1('salary_head','sh_tnt','sh_id',$salhdata->sf_salhead_id)->sh_tnt." )";
                        ?>" size="40" readonly /><br></td>
	     	</tr>
                <tr>
                    <td><label for="salh_formula" class="control-label">Apply Formula:</label></td>
                    <td><textarea name="salh_formula" rows="5" cols="50"  placeholder="Write formula..."><?php echo $salhdata->sf_formula ;?></textarea><br></td>
                </tr>
                <tr>
                	<td><label for="salh_desc" class="control-label">Salary Head Description:</label></td>
                	<td><input type="text" name="salh_desc" value="<?php echo $salhdata->sf_description ;?>" class="form-control" size="40" /><br></td>
	     	</tr>
                <tr>
                    <td></td>
                    <td>
                    <button name="edit_salhformula">Update</button>
                    </form>    
                    <button onclick="goBack()" >Back</button>

                    </td>
            </tr>
                
            </table>
            
        </div>   
        
        <p> &nbsp; </p>
        <div align="center"> <?php $this->load->view('template/footer');?></div>   
    </body>   
</html>    
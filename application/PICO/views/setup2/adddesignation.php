<!---@name adddesignation.php                                                                                                                                                               
    @author Nagendra Kumar Singh (nksinghiitk@gmail.com)
 -->
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<title>Add Designation</title>

 <head>
      <?php $this->load->view('template/header'); ?>
    
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js" ></script>

 <script>
	 $(document).ready(function(){
	 $('#tnt').on('change',function(){
                var worktype = $(this).val();
                //alert(worktype);
                if(worktype == ''){
                    $('#grouppost').prop('disabled',true);
                   
                }
                else{
                    $('#grouppost').prop('disabled',false);
                    $.ajax({
                       // url: "<?php echo base_url();?>slcmsindex.php/setup2/getworkingtype",
			url: "<?php echo base_url();?>sisindex.php/staffmgmt/getworkingtype",
                        type: "POST",
                        data: {"groupp" : worktype},
                        dataType:"html",
                        success:function(data){
                            //alert("data==1="+data);
                            $('#grouppost').html(data.replace(/^"|"$/g, ''));
                                                 
                        },
                        error:function(data){
                            alert("data in error==="+data);
                            alert("error occur..!!");
                 
                        }
                    });
                }
            }); 
	});
</script>
</head>
<body>

<!--<//?php
        echo "<table border=\"0\" align=\"left\" style=\"color: black;  border-collapse:collapse; border:1px;\">";
        echo "<tr style=\"text-align:left; \">";
                echo "<td style=\"padding: 8px 8px 8px 20px; decoration:none;\">";
        echo anchor('setup/adddegreerules/', "View degree rule ", array('title' => 'Add Rule'));
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        ?>-->

     <table width="100%">
            <tr><td>
                <?php echo anchor('setup2/designation/', "View Designation list", array('title' => 'View Designation list' ,'class' =>'top_parent'));
                echo "<td align=\"right\">";
		$help_uri = site_url()."/help/helpdoc#Designation";
                echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
                echo "</td>";
 		?>
                <div>
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php if(isset($_SESSION['success'])){
			if(!empty($_SESSION['success'])){
		?>
                        <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                <?php
                };
		}
                if(isset($_SESSION['err_message'])){
			if(!empty($_SESSION['err_message'])){
		?>
                <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>
                <?php
                };
		}
               ?>
              </div>
         </td></tr>
    </table>
    <form action="<?php echo site_url('setup2/adddesignation');?>" method="POST" class="form-inline">
	<table>
        	<tr>
                	<td><label for="desig_code" class="control-label">Designation Code:</label></td>
                	<td><input type="text" name="desig_code" class="form-control" size="33" /><br></td>
	     	</tr>	
		<tr>
	     		<td>Designation Type</td>
               		<td>
				<select name="tnt" id="tnt" class="my_dropdown" style="width:100%;">
                			<option value="" disabled selected >------Select----------------</option>
                			<option value="Teaching">Teaching</option>
                			<option value="Non Teaching">Non Teaching</option>
				</select>
			</td>
		</tr>
                 <tr>
                	<td><label for="grouppost" class="control-label">Designation Sub Type:</label></td>
                        <td>
                        	<select name="grouppost" id="grouppost" style="width:100%;">
                        	<option value="" disabled selected >------Select Designation Subtype------</option>
                        	</select>
                        </td>
		</tr>

                 <td>Designation Payscale: <font color='Red'> *</font> </td>
                <td><select name="desig_payscale" id="desigid" class="my_dropdown" style="width:100%;">
                <option selected="selected" disabled selected>--------Select-------------</option>
                <!--         <option value="<?php //echo $datas->sgm_name." ( ". $datas->sgm_min." - ".$datas->sgm_max." ) ".$datas->sgm_gradepay; ?>"<?php //echo set_select('desig_payscale', $datas->sgm_name." ( ". $datas->sgm_min." - ".$datas->sgm_max." ) ".$datas->sgm_gradepay);?>><?php //echo $datas->sgm_name." ( ". $datas->sgm_min." - ".$datas->sgm_max." ) ".$datas->sgm_gradepay; ?> -->
                <?php foreach($this->payresult as $datas): ?>
                         <option value="<?php echo $datas->sgm_id; ?>"><?php echo $datas->sgm_name." ( ". $datas->sgm_min." - ".$datas->sgm_max." ) ".$datas->sgm_gradepay; ?>
                          </option>
                  <?php endforeach; ?>
                </select></td>

                    <tr>
                <td></td>
                <tr>
               <td><label for="desig_name" class="control-label">Designation Name:</label></td>
               <td>
               <input type="text" name="desig_name"  class="form-control" size="33" /><br>
               </td>
               <td>
                  <?php //echo form_error('dr_mincredit')?>
               </td>
            </tr>
              
               <tr>
                       <td><label for="desig_name" class="control-label">Designation Group :</label></td> 
                        <td>
                        <select name="desig_group" id="" class="my_dropdown" style="width:100%;">
                        <option value="" disabled selected >------Select Group------</option>
                        <option value="A" class="dropdown-item">A</option>
                        <option value="B" class="dropdown-item">B</option>
                        <option value="C" class="dropdown-item">C</option>
                        <option value="D" class="dropdown-item">D</option>
                        </select>
                        </td></tr>
            <tr>

                      
              <tr>
                <td><label for="desig_short" class="control-label">Designation short :</label></td>
                <td>
                <input type="text" name="desig_short" class="form-control" size="33" /><br>
                </td>
                <tr>
                <td><label for="desig_desc" class="control-label">Designation Description :</label></td>
                <td>
                <input type="text" name="desig_desc" class="form-control" size="33" /><br>
                </td>
                <td>
                    <?php //echo form_error('dr_minsubcredit')?>
                </td>
            </tr>
            <tr>
		<td></td>
                <td>
                <button name="adddesignation">Add Designation</button>
                <button name="reset" >Clear</button>

                </td>
            </tr>
            </table>
    </form>
  <p><br></p>
    <div align="left"> <?php $this->load->view('template/footer');?></div>
    </html>

                                                                                                                                                      

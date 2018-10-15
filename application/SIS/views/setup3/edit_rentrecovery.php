<!--@name edit_rentrecovery.php  @author Manorama Pal(palseema30@gmail.com) -->
 <?php defined('BASEPATH') OR exit('No direct script access allowed');?>
 <html>
    <title>Edit HRA Grade</title>
    <head>
       <script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
        <?php $this->load->view('template/header'); ?>
        
        <script> 
        </script>
    </head>
    <body>
        <table width="100%">
            <tr>
                <?php
                    echo "<td align=\"left\" width=\"33%\">";
                    echo anchor('setup3/rentrecovery/', "View Rent Recovery Percentage for Govt.Quarters" ,array('title' => 'View Rent Recovery Percentage for Govt.Quarters' , 'class' => 'top_parent'));
                    echo "</td>";
                    echo "<td align=\"center\" width=\"34%\">";
                    //echo "<b></b>";
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
        <form action="<?php echo site_url('setup3/edit_rentrecovery/'.$id);?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo  $id ; ?>">
            <table>
                <tr>
                	<td><label for="worktype" class="control-label">Working Type:</label></td>
                        <td>
                            <select name="worktype" id="worktype" class="my_dropdown" style="width:100%;">
                                <?php if(!empty($rrdata->rr_workingtype)):;?>
                                <option value="<?php echo $rrdata->rr_workingtype;?>"><?php echo $rrdata->rr_workingtype;?></option>      
                                <?php else:?>
                		<option value="" disabled selected >------ Select Working Type -----------</option>
                                 <?php endif;?>
                		<option value="Teaching">Teaching</option>
                		<option value="Non Teaching">Non Teaching</option>
			    </select>
                        </td>
	     	</tr>
		<tr>
                        <td><label for="paycomm" class="control-label">Pay Commission:</label></td>
                        <td>
                            <select name="paycomm" id="paycomm" class="my_dropdown" style="width:100%;">
                                <?php if(!empty($rrdata->rr_paycomm)):;?>
                                <option value="<?php echo $rrdata->rr_comm;?>"><?php echo $rrdata->rr_paycomm;?></option>
                                <?php else:?>
                                <option value="" disabled selected >------ Select Working Type -----------</option>
                                 <?php endif;?>
                                <option value="6th">6th</option>
                                <option value="7th">7th</option>
                            </select>
                        </td>
                </tr>

                <tr>
                	<td><label for="payscale" class="control-label">Pay Scale:</label></td>
                        <td>
                            <select name="payscale" id="payscale" class="my_dropdown" style="width:100%;">
                                <?php if(!empty($rrdata->rr_payscaleid)):;?>
                                <option value="<?php echo $rrdata->rr_payscaleid;?>"><?php 
                                    $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$rrdata->rr_payscaleid)->sgm_name;
                                    $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$rrdata->rr_payscaleid)->sgm_min;
                                    $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$rrdata->rr_payscaleid)->sgm_max;
                                    $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$rrdata->rr_payscaleid)->sgm_gradepay;
                                    $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                                    echo $fullstr;?></option>      
                                <?php else:?>
                		<option value="" disabled selected >------Select Pay Scale ---------------</option>
                                 <?php endif;?>
                                <?php foreach($this->salgrade as $sgdata): ?>	
   				<option value="<?php echo $sgdata->sgm_id; ?>"><?php echo $sgdata->sgm_name."( ".$sgdata->sgm_min." - ".$sgdata->sgm_max." )".$sgdata->sgm_gradepay; ?></option> 
                                <?php endforeach; ?>
                            </select>
			    </select>
                        </td>
	     	</tr>
		<tr>
                    <td><label for="payrange" class="control-label">Pay Range:</label></td>
                    <td><input type="text" name="payrange" value="<?php echo $rrdata->rr_payrange; ?>" placeholder="Pay Range ( min - max)" size="30" /></td>
                </tr> 
                <tr>
                	<td><label for="hragrade" class="control-label">HRA Grade:</label></td>
                        <td>
                            <select name="hragrade" id="hragrade" class="my_dropdown" style="width:100%;">
                                 <?php if(!empty($rrdata->rr_gradeid)):;?>
                                <option value="<?php echo $rrdata->rr_gradeid;?>"><?php 
                                    $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$rrdata->rr_gradeid)->hgc_gradename;
                                    echo $hragradename;?></option>      
                                <?php else:?>
                		<option value="" disabled selected >------Select HRA Grade -------</option>
                                 <?php endif;?>
                                <?php foreach($this->hragrade as $hgcdata): ?>	
   				<option value="<?php echo $hgcdata->hgc_id; ?>"><?php echo $hgcdata->hgc_gradename;?></option> 
                                <?php endforeach; ?>
                            </select>
			    </select>
                        </td>
	     	</tr>
                <tr>
                    <td><label for="percentage" class="control-label">Rent Recovery Percentage(in %):</label></td>
                    <td><input type="text" name="percentage" value="<?php echo $rrdata->rr_percentage; ?>" placeholder="Rent Recovery Percentage..." size="30" /></td>
                </tr>
                <tr>
                    <td><label for="description" class="control-label">Description</label></td>
                    <td><input type="text" name="Description" value="<?php echo $rrdata->rr_description; ?>" placeholder="Rent Recovery Description..." size="30" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button name="edit_rentrecovery">Update</button>
                        <button type="button" onclick="history.back();">Back</button>
                    </td>
                </tr>
            </table>    
        </form>
        <p> &nbsp; </p>
        <div align="center"> <?php $this->load->view('template/footer');?></div>
    </body>    
</html>    

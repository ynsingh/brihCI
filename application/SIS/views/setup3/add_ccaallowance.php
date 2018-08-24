<!--@name add_ccaallowance.php  @author Manorama Pal(palseema30@gmail.com) -->
 <?php defined('BASEPATH') OR exit('No direct script access allowed');?>
 <html>
    <title>Salary Head</title>
    <head>
        <?php $this->load->view('template/header'); ?>
       
    </head>
    <body>
        <table width="100%">
            <tr>
                <?php
                    echo "<td align=\"left\" width=\"33%\">";
                    echo anchor('setup3/cca_allowance/', "View City Compensatory Allowance(CCA) " ,array('title' => 'View City Compensatory Allowance(CCA) According to Grade' , 'class' => 'top_parent'));
                    echo "</td>";
                    echo "<td align=\"center\" width=\"34%\">";
                    echo "<b>Add City Compensatory Allowance(CCA)</b>";
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
        <form action="<?php echo site_url('setup3/add_ccaallowance');?>" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                	<td><label for="worktype" class="control-label">Working Type:</label></td>
                        <td>
                            <select name="worktype" id="worktype" class="my_dropdown" style="width:100%;">
                		<option value="" disabled selected >------Select ---------------</option>
                		<option value="Teaching">Teaching</option>
                		<option value="Non Teaching">Non Teaching</option>
			    </select>
                        </td>
	     	</tr>
                <tr>
                	<td><label for="payscale" class="control-label">Pay Scale:</label></td>
                        <td>
                            <select name="payscale" id="payscale" class="my_dropdown" style="width:100%;">
                		<option value="" disabled selected >------Select Pay Scale ---------------</option>
                                <?php foreach($this->salgrade as $sgdata): ?>	
   				<option value="<?php echo $sgdata->sgm_id; ?>"><?php echo $sgdata->sgm_name."( ".$sgdata->sgm_min." - ".$sgdata->sgm_max." )".$sgdata->sgm_gradepay; ?></option> 
                                <?php endforeach; ?>
                            </select>
			    </select>
                        </td>
	     	</tr> 
                <tr>
                	<td><label for="hragrade" class="control-label">HRA Grade:</label></td>
                        <td>
                            <select name="hragrade" id="hragrade" class="my_dropdown" style="width:100%;">
                		<option value="" disabled selected >------Select HRA Grade -------</option>
                                <?php foreach($this->hragrade as $hgcdata): ?>	
   				<option value="<?php echo $hgcdata->hgc_id; ?>"><?php echo $hgcdata->hgc_gradename;?></option> 
                                <?php endforeach; ?>
                            </select>
			    </select>
                        </td>
	     	</tr>
                <tr>
                    <td><label for="amount" class="control-label">Amount:</label></td>
                    <td><input type="text" name="amount" value="<?php echo isset($_POST["amount"]) ? $_POST["amount"] : ''; ?>" placeholder="Amount In Rupees..." size="30" /></td>
                </tr>
                <tr>
                    <td><label for="description" class="control-label">Description:</label></td>
                    <td><input type="text" name="Description" value="<?php echo isset($_POST["Description"]) ? $_POST["Description"] : ''; ?>" placeholder="Description..." size="30" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    <button name="add_ccaalowance">Submit</button>
                    <input type="reset" name="Reset" value="Clear"/>
                    </td>
                </tr>
            </table>    
        </form>
        <p> &nbsp; </p>
        <div align="center"> <?php $this->load->view('template/footer');?></div>
    </body>    
</html>    
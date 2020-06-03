
<!--@name edit_servicedata.php  @author Manorama Pal(palseema30@gmail.com) -->
 <?php defined('BASEPATH') OR exit('No direct script access allowed');?>
 <html>
    <title>Service Details</title>
    <head>
        <?php $this->load->view('template/header'); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/jquery-ui.css">
        <script type="text/javascript" src="<//?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-1.12.4.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-ui.js" ></script>
        <script>
            $(document).ready(function(){
                $('#Datefrom,#Dateto').datepicker({
                    dateFormat: 'yy-mm-dd',
                    autoclose:true,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: 'c-70:c+20',
               
                }).on('changeDate', function (ev) {
                    $(this).datepicker('hide');
                });


            }); 
        </script>
  
    </head>
    <body>
        <table width="100%">
            <tr>
                <?php
                    echo "<td align=\"left\" width=\"33%\">";
                    if($this->roleid == 4){
                        echo anchor('empmgmt/viewempprofile', 'View Profile ', array('class' => 'top_parent'));
                    }
                    else{
                        echo anchor('report/service_profile/'.$ottservicedata->empottsd_empid, 'View Profile ', array('class' => 'top_parent'));
                    }
                    echo "</td>";
            
                    echo "<td align=\"center\" width=\"34%\">";
                    echo "<b>Edit Other Than TANUVAS Service Details</b>";
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
                <?php if(isset($_SESSION['success'])){?>
                    <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                <?php  }; ?>
                <?php if  (isset($_SESSION['err_message'])){?>
                    <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>
                <?php }; ?>
            </div>
            </td></tr>
        </table>
        <div> 
            <form id="myform" action="<?php echo site_url('empmgmt/update_ottservicedata/'.$id);?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo  $id ; ?>">
            <table style="width:100%; border:1px solid gray;" align="center" class="TFtable">
                <tr><thead><th style="color:white;background-color:#0099CC; text-align:left; height:30px;" colspan=63">&nbsp;&nbsp; Update Other Than TANUVAS Service Details</th></thead></tr>
                <tr></tr><tr></tr>
                <tr>
                    <td> Post<font color='Red'></font></td>
                        <td><input type="text" name="post" id="post" value="<?php echo $ottservicedata->empottsd_post;?>"  size="40" >
                    </td>
                </tr>
                <tr>
                    <td>Establishment<font color='Red'></font></td>
                        <td><input type="text" name="orderno" id="orderno" value="<?php echo $ottservicedata->empottsd_estd;?>"  size="40" >
                    </td>
                </tr>
                <tr>
                    <td>USO No.<font color='Red'></font></td>
                        <td><input type="text" name="usono" id="usono" value="<?php echo $ottservicedata->empottsd_uso;?>"  size="40" >
                    </td>
                </tr>
                <tr>
                    <td>Date From<font color='Red'>*</font></td>
                        <td><input type="text" name="Datefrom" id="Datefrom" value="<?php echo $ottservicedata->empottsd_datefrom; ?>"  size="40" required="required">
			<select name="fsession" style="width:140px;" id="fsession" >
			<option value="<?php echo $ottservicedata->empottsd_fsession; ?>"><?php echo $ottservicedata->empottsd_fsession; ?></option>
                	<option  disabled >Select Session</option>
                        <option value="Forenoon">Forenoon</option>
                        <option value="Afternoon">Afternon</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Date To<font color='Red'></font></td>
                        <td><input type="text" name="Dateto" id="Dateto" value="<?php echo $ottservicedata->empottsd_dateto; ?>"  size="40" >
			<select name="tsession" style="width:140px;" id="tsession" >
			<option value="<?php echo $ottservicedata->empottsd_tsession; ?>"><?php echo $ottservicedata->empottsd_tsession; ?></option>
               		<option  disabled >Select Session</option>
                        <option value="Forenoon">Forenoon</option>
                        <option value="Afternoon">Afternon</option>
                        </select>
                    </td>   
                </tr>
<!--
		<tr>
            <td>Upload Attachment<br>(Max size 20MB, Allowed Type- pdf)</td>
            <td><input type='file' name='userfile' size='20' style="font-size:15px;"/>
            <?php //if(!empty($servicedata->empsd_filename)):;?>
            <td colspan="2">
		<a href="<?php //echo base_url().'uploads/SIS/serviceattachment/'.$servicedata->empsd_filename ; ?>"
                               target="_blank" type="application/octet-stream" download="<?php //echo $servicedata->empsd_filename ?>">Download the pdf</a>
            </td>
            <?php //endif;?>  
            </td>
        </tr>
-->
                <tr></tr><tr></tr>
                <tr style="background-color:#0099CC; text-align:left; height:30px;">
                    <td colspan="3">
                    <button name="editottservdata" >Update</button>
                    <!--<button  onclick="goBack();">Back</button> -->
                    </td>
                </tr>    
        
            </table>
            </form>
        </div>    
        <p> &nbsp; </p>
        <div align="center"> <?php $this->load->view('template/footer');?></div>
    </body>
</html>    
    

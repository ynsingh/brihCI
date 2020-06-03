<!--@name add_servicedata.php  @author Manorama Pal(palseema30@gmail.com) -->
 <?php defined('BASEPATH') OR exit('No direct script access allowed');?>
 <html>
    <title>Service Details</title>
    <head>
        <?php $this->load->view('template/header'); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/jquery-ui.css">
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
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
                        echo anchor('report/service_profile/'.$this->emp_id, 'View Profile ', array('class' => 'top_parent'));
                    }
                    echo "</td>";
            
                    echo "<td align=\"center\" width=\"34%\">";
                    echo "<b>Add Other Than TANUVAS Service Details</b>";
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
            <form id="myform" action="<?php echo site_url('empmgmt/add_ottservicedata/'.$this->emp_id);?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="empid" value="<?php echo  $this->emp_id ; ?>">
            <table style="width:100%; border:1px solid gray;" align="center" class="TFtable">
                <tr><thead><th  style="color:white;background-color:#0099CC; text-align:left; height:30px;" colspan=63">&nbsp;&nbsp; Add Other Than TANUVAS Service Details</th></thead></tr>
                <tr></tr><tr></tr>
                
                <tr>
                    <td>Post<font color='Red'>*</font></td>
		    <td colspan=2>
                            <input type="text" name="post" id="post" value="" size="40" required >
                    </td>
                </tr>
                <tr>
                    <td>Establishment<font color='Red'>*</font></td>

		    <td colspan=2>
                            <input type="text" name="orderno" id="orderno" value="" size="40"  required >
                    </td>
                </tr>
                <tr>
                    <td>USO No.<font color='Red'>*</font></td>
		    <td colspan=2>
                            <input type="text" name="usono" id="usono" value="" size="40" required >
                    </td>
                </tr>
                <tr>
                    <td>Date From<font color='Red'>*</font></td>
                        <td><input type="text" name="Datefrom" id="Datefrom" value="<?php echo isset($_POST["Datefrom"]) ? $_POST["Datefrom"] : ''; ?>"  size="40" required="required" >
                    
			 <select name="fsession" style="width:110px;" id="fsession" required>
                <option selected="selected" disabled selected>Select Session</option>
                        <option value="Forenoon">Forenoon</option>
                        <option value="Afternoon">Afternon</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Date To<font color='Red'></font></td>
                        <td><input type="text" name="Dateto" id="Dateto" value="<?php echo isset($_POST["Dateto"]) ? $_POST["Dateto"] : ''; ?>"  size="40" >
			 <select name="tsession" style="width:110px;" id="tsession" >
               		 <option selected="selected" disabled selected>Select Session</option>
                        <option value="Forenoon">Forenoon</option>
                        <option value="Afternoon">Afternon</option>
                        </select></td>
                </tr>
<!--        <tr>
            <td>Upload Attachment<br>(Max size 20MB, Allowed Type- pdf)</td>
            <td colspan=2><input type='file' name='userfile' size='20' style="font-size:15px;"/>
            </td>
        </tr>
-->
                <tr></tr><tr></tr>
                <tr style="color:white;background-color:#0099CC; text-align:left; height:30px;">
                    <td colspan="3">
                    <button name="addottservdata" >Submit</button>
		    <!--input type="reset" name="Reset" value="Clear"/-->
			<button type="button" onclick="history.back();">Back</button>
                    </td>
                </tr>    
        
            </table>
            </form>
        </div>    
        <p> &nbsp; </p>
        <div align="center"> <?php $this->load->view('template/footer');?></div>
    </body>
</html>    
   

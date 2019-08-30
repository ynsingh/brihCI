<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--@name authusertype.php 
  @author Neha Khullar(nehukhullar@gmail.com)
 -->
<html>
<title>Add Authorities</title>
<head>     
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/stylecal.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-1.12.4.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js" ></script>


<script>
$(document).ready(function(){
$("#StartDate").datepicker({
onSelect: function(value, ui) {
  console.log(ui.selectedYear)
  var today = new Date(), 
  dob = new Date(value), 
  age = 2017-ui.selectedYear;
  //$("#age").text(age);
                                },
                                //(set for show current month or current date)maxDate: '+0d',
                                
  changeMonth: true,
  changeYear: true,
  dateFormat: 'yy-mm-dd',
  defaultDate: '1yr',
  yearRange: 'c-47:c+50',
});

$("#EndDate").datepicker({ 
onSelect: function(value, ui) {
 console.log(ui.selectedYear)
var today = new Date(), 
dob = new Date(value), 
age = 2017-ui.selectedYear;

//$("#age").text(age);
},
                                //(set for show current month or current date)maxDate: '+0d',
changeMonth: true,
changeYear: true,
dateFormat: 'yy-mm-dd',
defaultDate: '1yr',
yearRange: 'c-47:c+50',
});
});
</script>
<script>
/*$(document).ready(function(){
$("#StartDate").datepicker({
dateFormat: 'yy/mm/dd',
numberOfMonths: 1,
onSelect: function(selected) {
$("#EndDate").datepicker("option","minDate", selected)
}
});

$("#EndDate").datepicker({ 
dateFormat: 'yy/mm/dd',
numberOfMonths: 1,
onSelect: function(selected) {
$("#StartDate").datepicker("option","maxDate", selected)
}
}); 
});
</script>
  <div>
        <?php $this->load->view('template/header'); ?>
        <?php //$this->load->view('template/menu'); ?>
<!--<table id="uname"><tr><td align=center>Welcome <?//= $this->session->userdata('username') ?>  </td></tr></table>-->
</div>
<table style="width:100%;">
                <tr>
                <?php 
                echo "<td align=\"left\" width=\"33%\">";
                echo anchor('map/viewauthuser',' Map Authority and User List',array('title'=>'View Detail','class' => 'top_parent'  ));
                echo "</td>";
     
                echo "<td align=\"center\" width=\"34%\">";
                echo "<b>Add Authority and User Details</b>";
                echo "</td>";

                echo "<td align=\"right\" width=\"33%\">";
                $help_uri = site_url()."/help/helpdoc#ProgramFees";
                echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
                ?>
                 </tr>
           </table>
           <table width="100%">
           <tr><td>
                <div>
                    <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                    <?php echo form_error('<div style="margin-left:30px;" class="isa_error">','</div>');?>

                    <?php if(isset($_SESSION['success'])){?>
                        <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                    <?php
                    };
                    ?>
                </div>
            </td></tr>
</table>
 <div>
     <form action="<?php echo site_url('map/authusertype');?>" method="POST" class="form-inline">   
                      <table>
                       <td> Authority Name: </td><td>
                        <select name="authorities" class="my_dropdown" style="width:100%;">
                        <option value=""disabled selected>---------Select authority ---------</option>
                        <?php foreach($authuserresult as $datas): ?>
                        <option value="<?php echo $datas->id;?>"><?php echo $datas->name; ?></option>
                        <?php endforeach; ?>
                        </select>
                        </td></tr>
 
                        <tr>
                                        
                        <td> User Name: </td><td>
                        <select name="edrpuser" class="my_dropdown" style="width:100%;">
                        <option value=""disabled selected>---------Select Name ---------</option>                        
                        <?php foreach($result as $datas): ?>
                        <option value="<?php echo $datas->id; ?>"><?php echo $this->loginmodel->get_listspfic1('userprofile', 'firstname', 'userid', $datas->id)->firstname .' '. $this->loginmodel->get_listspfic1('userprofile', 'lastname', 'userid', $datas->id)->lastname; ?></option>
                        <?php endforeach; ?>
                        </select>
                        </td>
                        </tr>
                        <tr>

			<tr>
                        <td> Authority Type: </td><td>
                        <select name="authority_type" style="width:100%;">
                        <option value=""disabled selected>----Select Type----</option>
			<option value="Full Time" class="dropdown-item">Full Time</option>
                        <option value="Acting" class="dropdown-item">Acting</option>
                        </select>
                        </td>
                        </tr>
                         <tr>
                        
                        <td>From Date:<font color='Red'>*</font></td>
                        <td><input type="text"placeholder="From Date" name="map_date" id="StartDate"  size="40" value="<?php echo isset($_POST["map_date"]) ? $_POST["map_date"] : ''; ?>" required="required"/><br> </td></tr>
                        
                        <tr>
                        <td>Till Date:<font color='Red'>*</font></td>
                        <td><input type="text"placeholder="Till Date" name="till_date" id="EndDate"  size="40" value="<?php echo isset($_POST["map_date"]) ? $_POST["till_date"] : ''; ?>" required="required"/><br> </td></tr>
                        
                        <tr>
                        <td></td>
                        <td>
                        <button name="authusertype">Add Authorities </button>
                        <button name="clear">Clear</button>
                        </td>
                        </tr>
                    </form>
                  </div>
        </table>
</body>
<div align="center">  <?php $this->load->view('template/footer');?></div>
</head>
</html>





































                                      
                     








































                                                                                                                   
 


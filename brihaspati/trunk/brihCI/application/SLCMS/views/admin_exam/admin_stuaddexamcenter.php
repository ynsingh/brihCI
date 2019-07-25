<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!--
 * @name adminstu_examschedule.php
   @author sumit saxena (sumitsesaxena@gmail.com)
 --->


<html>
<title>Add Exam Center</title>
<link rel="shortcut icon" href="<?php echo base_url('assets/images'); ?>/index.jpg">
 <head>
     <script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
     <?php $this->load->view('template/header'); ?>
     <?php //$this->load->view('template/menu');?>
 </head>
 <body>

<!--<table id="uname"><tr><td align=center>Welcome <?//= $this->session->userdata('username') ?>  </td></tr></table>-->
<script>
    $(document).ready(function(){
    $('#state_id').on('change',function(){
           var sid = $(this).val();
           if(sid == ''){
               $('#citname').prop('disabled',true);
               
           }
           else{
                 $('#citname').prop('disabled',false); 
               $.ajax({
                   url: "<?php echo base_url();?>slcmsindex.php/setup/get_city",
                   type: "POST",
                   data: {"sid" : sid},
                   dataType:"html",
                   success:function(data){
                      $('#citname').html(data.replace(/^"|"$/g, ''));
                       
                   },
                   error:function(data){
                       
                   }
               });
           }
       }); 
    });
</script>  
<script>
function calculate() {
		var myBox1 = document.getElementById('box1').value;	
		var myBox2 = document.getElementById('box2').value;
		var result = document.getElementById('result');	
		var myResult = myBox1 * myBox2;
		result.value = myResult;
	
	}
</script>
 <table width="100%">
            <tr>
                <?php
                echo "<td align=\"left\" width=\"33%\">"; 
                echo anchor('adminstuexam/stu_examcenter/','Exam Center List',array('title'=>'View Detail','class' => 'top_parent'  ));
                echo "</td>";

                echo "<td align=\"center\" width=\"34%\">";
                echo "<b>Add Exam Center Details</b>";
                echo "</td>";

                echo "<td align=\"right\" width=\"33%\">";
               // $help_uri = site_url()."/help/helpdoc#ProgramFees";
               // echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
                echo "</td>";
                ?>
                </tr>
           </table>
           <table width="100%">
           <tr><td> 
		<div>
                <?php
                     echo validation_errors('<div class="isa_warning">','</div>');
                     echo form_error('<div  class="isa_error">','</div>');

                     if(isset($_SESSION['success'])){?>
                        <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                 <?php
                     };
                     if(isset($_SESSION['err_message'])){?>
                        <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>

                 <?php
                    };
                 ?>
                </div>
            </td></tr>
</table>
    <form action="<?php echo site_url('adminstuexam/stu_addexamcenter');?>" method="POST" class="form-inline">
            <table>
            <tr>
                <td><label for="eec_code" class="control-label">Exam Center Code:</label></td>
                <td>
                <input type="text" name="eec_code"  class="form-control" size="30" value="<?php echo isset($_POST["eec_code"]) ? $_POST["eec_code"] : ''; ?>" />
                </td>
            </tr>
 		<tr>
                <td><label for="eec_name" class="control-label">Exam Center Name:</label></td>
                <td>
                <input type="text" name="eec_name"  class="form-control" size="30"  value="<?php echo isset($_POST["eec_name"]) ? $_POST["eec_name"] : ''; ?>"/>
                </td>
            </tr>
 		 <tr>
                 <td valign=top><label for="eec_address" class="control-label"> Exam Center Address:</label></td>
                 <td><textarea rows= "" cols="39" name="eec_address" size="30" value="<?php echo isset($_POST["eec_address"]) ? $_POST["eec_address"] : ''; ?>"> </textarea></td>
                 </tr>
		<tr><td><label>State: </label></td><td>
                <select style="width:100%" name="eec_state"  id="state_id"  >
                <option value="">Select State</option>
                <?php foreach($cresult as $datas): ?>
                <option value="<?php echo $datas->id; ?>"><?php echo $datas->name; ?></option>
                <?php endforeach; ?>
                </select>
                <tr><td><label>City:</label> </td><td>
                <select style="width:100%" name="eec_city" id="citname" disabled="">
                <option value="">Select city</option>
                </select>
		<tr>
                <td><label for="eec_incharge" class="control-label"> Exam Center Incharge:</label></td>
                <td>
                <input type="text" name="eec_incharge"  class="form-control" size="30" value="<?php echo isset($_POST["eec_incharge"]) ? $_POST["eec_incharge"] : ''; ?>"/>
                </td>
            </tr>
		<tr>
                <td><label for="eec_noofroom" class="control-label">Exam Center Number of Room:</label></td>
                <td>
                <input id="box1" type="text" name="eec_noofroom"  class="form-control" size="30" oninput="calculate()" value="<?php echo isset($_POST["eec_noofroom"]) ? $_POST["eec_noofroom"] : ''; ?>"/>
                </td>
            </tr>
		<tr>
                <td><label for="eec_capacityinroom" class="control-label">Exam Center Capacity in Room:</label></td>
                <td>
                <input id="box2" type="text" name="eec_capacityinroom"  class="form-control" size="30" oninput="calculate()" value="<?php echo isset($_POST["eec_capacityinroom"]) ? $_POST["eec_capacityinroom"] : ''; ?>"/>
                </td>
            </tr>
		<tr>
                <td><label for="eec_totalcapacity" class="control-label">Exam Center Total Capacity:</label></td>
                <td>
                <input id="result" type="text" name="eec_totalcapacity"  class="form-control" size="30" value="<?php echo isset($_POST["eec_totalcapacity"]) ? $_POST["eec_totalcapacity"] : ''; ?>" readonly/>
                </td>
            </tr>
		<tr>
                <td><label for="eec_contactno" class="control-label">Exam Center Contact No:</label></td>
                <td>
                <input type="text" name="eec_contactno"  class="form-control" size="30" value="<?php echo isset($_POST["eec_contactno"]) ? $_POST["eec_contactno"] : ''; ?>"  MaxLength='12' />
                </td>
            </tr>
		<tr>
                <td><label for="eec_contactemail" class="control-label">Exam Center Contact Email:</label></td>
                <td>
                <input type="text" name="eec_contactemail"  class="form-control" size="30" value="<?php echo isset($_POST["eec_contactemail"]) ? $_POST["eec_contactemail"] : ''; ?>"/>
                </td>
            </tr>
		 <tr>
                <td></td><td>
                <button name="addexamcenter" >Add Exam Center</button>
                <button name="reset" >Clear</button>
                </td>
           </tr>
           </table>
    </form>
    </body>
    <div align="center"> <?php $this->load->view('template/footer');?></div>
    </html>


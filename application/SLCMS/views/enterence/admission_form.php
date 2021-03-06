<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
    @name admission_form.php  
    @author Sumit Saxena(sumitsesaxena@gmail.com)
    
*/

    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
<head>
<title>IGNTU - Print Form</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/message.css">

    <script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.js" ></script>
 <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js" ></script>	
    

</head>
<body>
<?php
    $this->load->view('template/header'); ?></br>
<?php $this->load->view('enterence/enterence_head'); ?>

       <div>
        <?php echo validation_errors('<div class="isa_warning">','</div>');?>
        <?php echo form_error('<div style="margin-left:30px;" class="">','</div>');?>
        <?php if(isset($_SESSION['success'])){?>
        <div class="alert alert-success"><?php echo $_SESSION['success'];?></div>
        <?php
    	 };
       	?>
	
        <?php if(isset($_SESSION['err_message'])){?>
             <div class="isa_error"><div ><?php echo $_SESSION['err_message'];?></div></div>
        <?php
        };
	?>  


<center>
    <form action="<?php echo site_url('Enterence/prtadmission_form'); ?>" method="POST" >
	<h2>Complete / Print admission application </h2>
    <table style="border:0px solid black; margin-top:0px;width:40%;">

        <tr><td>
        <label for="text">Email Id :</label></td>
        </td><td>

        <input type="text" name="applicantemail" placeholder="Enter your email id" class="keyup-email" value="<?php echo isset($_POST["applicantemail"]) ? $_POST["applicantemail"] : ''; ?>" style="width:100%;height:35px;"/> <br>
        </td></tr>
    
        <tr><td>
        <label for="text">Mobile No :</label></td>
        </td><td>
        <input type="text" name="applicantmobile" placeholder="Enter your mobile no" class="keyup-numeric" value="<?php echo isset($_POST["applicantmobile"]) ? $_POST["applicantmobile"] : ''; ?>" style="width:100%;height:35px;"  maxlength="12"/> <br>
        </td></tr>

        <tr><td>
        <label for="text">Date Of Birth :</label></td>
        <td>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css">
  	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.min.js" ></script>
	<input id="dob" type="text"  name="dateofbirth" placeholder="Enter Your Dob" value="<?php echo isset($_POST["dateofbirth"]) ? $_POST["dateofbirth"] : ''; ?>" style="width:100%;height:35px;">

			<script>
				$('#dob').datepicker({
 				onSelect: function(value, ui) {
 			        console.log(ui.selectedYear)
       				var today = new Date(), 
         			dob = new Date(value), 
          			age = 2017-ui.selectedYear;

   				$("#age").text(age);
   				},
  	 			//(set for show current month or current date)maxDate: '+0d',
				
    				changeMonth: true,
    				changeYear: true,
    				dateFormat: 'yy-mm-dd',
     				//defaultDate: '-1yr',
    				 yearRange: 'c-36:c+10',
				});
			</script>	
       
        </td>
        </tr>
        
        <tr><td>
        <label for="text">Program Name :</label></td>
        </td><td>
		<select name="applicantprogram" class="form-control" id="register_name" style="height:37px;font-size:18px;font-weight:bold;">
			<option  disabled selected>Program</option>
				<?php foreach($this->prgname as $data){?>
				<option value="<?php echo $data->prg_id;?>"><?php echo $data->prg_name.'('.$data->prg_branch.')'; ?></option>
				<?php }?>
	  		</select>
	
        </td></tr>
        <tr><td align="center" colspan="2"><b><i></i></b></td></tr> 
        <tr><td>
        <label for="text">Verification Code :</label></td>
        </td><td>
        <input type="text" name="applicantvercode" placeholder="Enter verification code" class="keyup-numeric" value="<?php echo isset($_POST["applicantvercode"]) ? $_POST["applicantvercode"] : ''; ?>" maxlength="8" style="width:100%;height:35px;"/> <br>
        </td></tr>
      
        <tr>
        <td></td>
        <td>
        <button name="submit" style="height:30px;font-size:20px;">Submit</button>
     
        </td></tr>
    
    </table>
    </form>

</center>
<?php
     $this->load->view('template/footer'); ?>

</body>
</html>




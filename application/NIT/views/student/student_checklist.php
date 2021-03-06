<!-------------------------------------------------------
    -- @name student_checklist.php --	
    -- @author Sumit saxena(sumitsesaxena@gmail.com) --
--------------------------------------------------------->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//if (isset($this->session->userdata['sm_id'])) {
//$id = ($this->session->userdata['sm_id']);
//$firstname = ($this->session->userdata['sm_fname']);
//$applino = ($this->session->userdata['sm_applicationno']);
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Student Document Check List</title>
		<!--<link rel="shortcut icon" href="<?php //echo base_url('assets/images'); ?>/index.jpg">-->
	<link rel="icon" href="<?php echo base_url('uploads/logo'); ?>/nitsindex.png" type="image/png" >	
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/1.12.4jquery.min.js" ></script>

<style type="text/css">
label{font-size:18px;}
input[type='text']{font-size:17px;width:120%;height:30px;}
input[type='button']{font-size:16px;}
#step2{border:1px solid black;width:70%;}
#firsttb tr td {padding:10px 0px 10px 0px;}
#secondtb tr td {padding:10px 0px 10px 20px;}
input[type="checkbox"] {
  visibility: hidden;
}
label {
  cursor: pointer;
}
input[type="checkbox"] + label:before {
  border: 1px solid #333;
  content: "\00a0";
  display: inline-block;
  font: 16px/1em sans-serif;
  height: 16px;
  margin: 0 .25em 0 0;
  padding: 0;
  vertical-align: top;
  width: 16px;
}
input[type="checkbox"]:checked + label:before {
  background: #fff;
  color: black;
  content: "\2713";
  text-align: center;
}
input[type="checkbox"]:checked + label:after {
  font-weight: bold;
}

input[type="checkbox"]:focus + label::before {
    outline: rgb(59, 153, 252) auto 5px;
}
</style>

</head>
<body>


<div>
	<div id="body">
	<?php $this->load->view('template/header2'); ?>
	<div class="welcome"><h2>Welcome : <?php echo $email?></h2></div>

	<?php $this->load->view('student/stuStepshead');?>
	
	<?php echo validation_errors('<div class="isa_warning">','</div>');?>
        <?php echo form_error('<div class="">','</div>');?>
        <?php 
	    if(!empty($_SESSION['success'])){	
		if(isset($_SESSION['success'])){?>
         <div class="isa_success" style="font-size:18px;"><?php echo $_SESSION['success'];?></div>
         <?php
          } };
         ?>
	
        <?php if(isset($_SESSION['err_message'])){?>
             <div class="isa_error"><div ><?php echo $_SESSION['err_message'];?></div></div>
        <?php
        };
	?>  

	<center>
</br>

<form action='<?php echo site_url('student/student_checklist');?>' method='POST'>
	<table style="width:100%;font-size:22px;" border=1>
		<table style="width:100%;font-size:22px;" border=1 id='firsttb'>
		<tr>
			<td style="text-align:center;"><b>List of Original/Duplicate certificates deposited during <?php //prg name?> Admission, NIT Sikkim</b></td>
		</tr>
		<tr>
			<td style="padding:10px 0px 10px 20px;"><b>Name of Student (BLOCK LETTERS) :</b> <?php echo strtoupper($stuname);?></td>
		</tr>
		<tr>
			<td style="text-align:center;"><b>List of Collected Items (Please tick √ the appropriate box. Write any remarks next to the box)</b></td>
		</tr>
		</table>
		
		<table style="width:100%;font-size:22px;" border=1 id='secondtb'>
			<tr>
				<!--<td>Sr.No.</td>-->
				<td colspan=2></td>
				<td><b>Original</b></td>
				<td><b>Duplicate</b></td>
				<td><b>Remarks(If Any)</b></td>
			</tr>
			<tr>
				<td>1</td>
				<td>JEE (Main) Score Card
				<input type="hidden" name="stu_check11" style=" " value="JEE (Main) Score Card" readonly>
				</td>
				<td>
   					 <input type="checkbox" id="c1" name="cb12" value='Original' class="example" >
      					 <label for="c1"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c2" name="cb13" value='Duplicate' class="example">
      					 <label for="c2"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark14' style="width:80%;" autofocus> </textarea></td>
						
			</tr>
<script>
//$('input.example').on('change', function() {
//    $('input.example').not(this).prop('checked', false);  
//});
</script>
			<tr>
				<td>2</td>
				<td>JEE (Main) Admit Card<input type="hidden" name="stu_check21" style=" " value="JEE (Main) Admit Card" readonly></td>
				<td>
   					 <input type="checkbox" id="c3" name="cb22" value='Original' class="example">
      					 <label for="c3"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c4" name="cb23" value='Duplicate' class="example">
      					 <label for="c4"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark24' style="width:80%;" autofocus></textarea></td>
						
			</tr>
<script>
//$('input.example').on('change', function() {
 //   $('input.example').not(this).prop('checked', false);  
//});
</script>
			<tr>
				<td>3</td>
				<td>Date of birth proof (10<sup>th</sup> standard/ matriculation or equivalent certificate or marks sheet)
				<input type="hidden" name="stu_check31" style=" " value="Date of birth proof (10th standard/ matriculation or equivalent certificate or marks sheet)" readonly></td>
				<td>
   					 <input type="checkbox" id="c5" name="cb32" value='Original' class="example">
      					 <label for="c5"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c6" name="cb33" value='Duplicate' class="example">
      					 <label for="c6"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark34' style="width:80%;" autofocus></textarea></td>
						
			</tr>
<script>
//$('input.example').on('change', function() {
//    $('input.example').not(this).prop('checked', false);  
//});
</script>

			</tr>

			<tr>
				<td>4</td>
				<td>10<sup>th</sup> standard/ matriculation or equivalent certificate or marks sheet
				<input type="hidden" name="stu_check41" value="10th standard/ matriculation or equivalent certificate or marks sheet" readonly></td>
				<td>
   					 <input type="checkbox" id="c7" name="cb42" value='Original' class="example">
      					 <label for="c7"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c8" name="cb43" value='Duplicate' class="example">
      					 <label for="c8"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark44' style="width:80%;" autofocus></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
  //  $('input.example').not(this).prop('checked', false);  
//});
</script>

			<tr>
				<td>5</td>
				<td>Mark Sheet & Pass Certificate of the Qualifying Exam
				<input type="hidden" name="stu_check51" value="Mark Sheet & Pass Certificate of the Qualifying Exam" readonly></td>
				<td>
   					 <input type="checkbox" id="c9" name="cb52" value='Original' class="example">
      					 <label for="c9"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c10" name="cb53" value='Duplicate' class="example">
      					 <label for="c10"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark54' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
//   $('input.example').not(this).prop('checked', false);  
//});
</script>
	
			<tr>
				<td>6</td>
				<td>School Certificate/Transfer certificate from the institute last<input type="hidden" name="stu_check61" value="School Certificate/Transfer certificate from the institute last" readonly>
attended</td>
				<td>
   					 <input type="checkbox" id="c11" name="cb62" value='Original' class="example">
      					 <label for="c11"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c12" name="cb63" value='Duplicate' class="example">
      					 <label for="c12"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark64' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
   // $('input.example').not(this).prop('checked', false);  
//});
</script>	

			<tr>
				<td>7</td>
				<td>Migration Certificate<input type="hidden" name="stu_check71" value="Migration Certificate" readonly></td>
				<td>
   					 <input type="checkbox" id="c13" name="cb72" value='Original' class="example">
      					 <label for="c13"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c14" name="cb73" value='Duplicate' class="example">
      					 <label for="c14"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark74' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
   // $('input.example').not(this).prop('checked', false);  
//});
</script>

			<tr>
				<td>8</td>
				<td>Character and Conduct certificate from the institute last attended
				<input type="hidden" name="stu_check81" value="Character and Conduct certificate from the institute last attended" readonly></td>
				<td>
   					 <input type="checkbox" id="c15" name="cb82" value='Original' class="example">
      					 <label for="c15"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c16" name="cb83" value='Duplicate' class="example">
      					 <label for="c16"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark84' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
 //   $('input.example').not(this).prop('checked', false);  
//});
</script>	

			<tr>
				<td>9</td>
				<td>Gap Certificate (applicable for candidates who have passed the</br>
qualifying exam in years prior to the current academic year)
				<input type="hidden" name="stu_check91" value="Gap Certificate" readonly></td>
				<td>
   					 <input type="checkbox" id="c17" name="cb92" value='Original' class="example">
      					 <label for="c17"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c18" name="cb93" value='Duplicate' class="example">
      					 <label for="c18"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark94' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
   // $('input.example').not(this).prop('checked', false);  
//});
</script>


			<tr>
				<td>10</td>
				<td>Community certificate in the form prescribed by Govt. of India and</br>
Issued by the competent authority in case of SC/ST candidates
				<input type="hidden" name="stu_check101" value="Community certificate in case of SC/ST candidates" readonly></td>
				<td>
   					 <input type="checkbox" id="c19" name="cb102" value='Original' class="example">
      					 <label for="c19"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c20" name="cb103" value='Duplicate' class="example">
      					 <label for="c20"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark104' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
    //$('input.example').not(this).prop('checked', false);  
//});
</script>	


			<tr>
				<td>11</td>
				<td>Community certificate in the case of OBC candidates from a</br>
competent authority indicating the status regarding creamy layer
				<input type="hidden" name="stu_check111" value="Community certificate in case of OBC candidates" readonly></td>
				<td>
   					 <input type="checkbox" id="c21" name="cb112" value='Original' class="example">
      					 <label for="c21"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c22" name="cb113" value='Duplicate' class="example">
      					 <label for="c22"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark114' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
   // $('input.example').not(this).prop('checked', false);  
//});
</script>	
			<tr>
				<td>12</td>
				<td>Certificate for persons with disabilities (PwD)
					<input type="hidden" name="stu_check121" value="Certificate for persons with disabilities (PwD)" readonly></td>
				<td>
   					 <input type="checkbox" id="c23" name="cb122" value='Original' class="example">
      					 <label for="c23"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c24" name="cb123" value='Duplicate' class="example">
      					 <label for="c24"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark124' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
   // $('input.example').not(this).prop('checked', false);  
//});
</script>	

			<tr>
				<td>13</td>
				<td>Medical Certificate [format given in Annexure 8 of JoSAA
business rules]
				<input type="hidden" name="stu_check131" value="Medical Certificate" readonly></td>
				<td>
   					 <input type="checkbox" id="c25" name="cb132" value='Original' class="example">
      					 <label for="c25"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c26" name="cb133" value='Duplicate' class="example">
      					 <label for="c26"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark134' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
  //  $('input.example').not(this).prop('checked', false);  
//});
</script>	

			<tr>
				<td>14</td>
				<td>Family Annual Income Proof and Affidavit declaration+
				<input type="hidden" name="stu_check141" value="Family Annual Income Proof and Affidavit declaration+" readonly></td>
				<td>
   					 <input type="checkbox" id="c27" name="cb142" value='Original' class="example">
      					 <label for="c27"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c28" name="cb143" value='Duplicate' class="example">
      					 <label for="c28"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark144' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
    //$('input.example').not(this).prop('checked', false);  
//});
</script>	

			
		<tr>
				<td>15</td>
				<td>Three recent passport size photographs not older than six month
				<input type="hidden" name="stu_check151" value="Three recent passport size photographs" readonly></td>
				<td>
   					 <input type="checkbox" id="c29" name="cb152" value='Original' class="example">
      					 <label for="c29"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c30" name="cb153" value='Duplicate' class="example">
      					 <label for="c30"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark154' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
   //$('input.example').not(this).prop('checked', false);  
//});
</script>	
	
		<tr>
				<td>16</td>
				<td>Class XII performance check [format given in Annexure 7 (b) of
JoSAA business rules]
				<input type="hidden" name="stu_check161" value="Class XII performance check" readonly></td>
				<td>
   					 <input type="checkbox" id="c31" name="cb162" value='Original' class="example">
      					 <label for="c31"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c32" name="cb163" value='Duplicate' class="example">
      					 <label for="c32"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark164' autofocus style="width:80%;"></textarea></td>
						
			</tr>	
<script>
//$('input.example').on('change', function() {
   // $('input.example').not(this).prop('checked', false);  
//});
</script>
			<tr>
				<td>17</td>
				<td>Provisional Seat allotment letter
				<input type="hidden" name="stu_check171" value="Provisional Seat allotment letter" readonly></td>
				<td>
   					 <input type="checkbox" id="c33" name="cb172" value='Original' class="example">
      					 <label for="c33"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c34" name="cb173" value='Duplicate' class="example">
      					 <label for="c34"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark174' autofocus style="width:80%;"></textarea></td>
						
			</tr>	

<script>
//$('input.example').on('change', function() {
    //$('input.example').not(this).prop('checked', false);  
//});
</script>

			<tr>
				<td>18</td>
				<td>Photo identity card
				<input type="hidden" name="stu_check181" value="Photo identity card" readonly></td>
				<td>
   					 <input type="checkbox" id="c35" name="cb182" value='Original' class="example">
      					 <label for="c35"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c36" name="cb183" value='Duplicate' class="example">
      					 <label for="c36"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark184' autofocus style="width:80%;"></textarea></td>
						
			</tr>	

<script>
//$('input.example').on('change', function() {
    //$('input.example').not(this).prop('checked', false);  
//});
</script>

			<tr>
				<td>19</td>
				<td>Registration-cum-locked choices for seat allotment
				<input type="hidden" name="stu_check191" value="Registration-cum-locked choices for seat allotment" readonly></td>
				<td>
   					 <input type="checkbox" id="c37" name="cb192" value='Original' class="example">
      					 <label for="c37"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c38" name="cb193" value='Duplicate' class="example">
      					 <label for="c38"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark194' autofocus style="width:80%;"></textarea></td>
						
			</tr>	

<script>
//$('input.example').on('change', function() {
    //$('input.example').not(this).prop('checked', false);  
//});
</script>

			<tr>
				<td>20</td>
				<td>Document Verification-cum-Seat Acceptance Letter
				<input type="hidden" name="stu_check201" value="Document Verification-cum-Seat Acceptance Letter" readonly></td>
				<td>
   					 <input type="checkbox" id="c39" name="cb202" value='Original' class="example">
      					 <label for="c39"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c40" name="cb203" value='Duplicate' class="example">
      					 <label for="c40"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark204' autofocus style="width:80%;"></textarea></td>
						
			</tr>	

<script>
//$('input.example').on('change', function() {
    //$('input.example').not(this).prop('checked', false);  
//});
</script>

		
			<tr>
				<td>21</td>
				<td>Proof of fee payment to JoSAA
				<input type="hidden" name="stu_check211" value="Proof of fee payment to JoSAA" readonly></td>
				<td>
   					 <input type="checkbox" id="c41" name="cb212" value='Original' class="example">
      					 <label for="c41"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c42" name="cb213" value='Duplicate' class="example">
      					 <label for="c42"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark214' autofocus style="width:80%;"></textarea></td>
						
			</tr>	

<script>
//$('input.example').on('change', function() {
    //$('input.example').not(this).prop('checked', false);  
//});
</script>

			<tr>
				<td>22</td>
				<td>Any other item<input type="hidden" name="stu_check221" value="Any other item" readonly></td>
				<td>
   					 <input type="checkbox" id="c43" name="cb222" value='Original' class="example">
      					 <label for="c43"></label></label>
				</td>
				<td>
   					 <input type="checkbox" id="c44" name="cb223" value='Duplicate' class="example">
      					 <label for="c44"></label></label>
				</td>
				
				<td><textarea rows="1" cols="10" name='stu_remark224' autofocus style="width:80%;"></textarea></td>
						
			</tr>	

<script>
//$('input.example').on('change', function() {
   // $('input.example').not(this).prop('checked', false);  
//});
</script>


		</table>
	</table>
	</br>	

	<table style="width:15%;">
		<tr><td>
			<input type="submit" name="stu_doclist" value="Submit" style="width:100%;height:40px;font-size:22px;">
		</td></tr>
	</table>	
</form>	

</div>
<?php //$thisPage2="studentCrieteria2"; 
$this->load->view('template/footer'); ?>
	<!--<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>-->
</div>

<?php  //} //else {  header("location:student/studentForm"); }
//else{header("location:".base_url()."Student/Step0");}?>
</body>
</html>


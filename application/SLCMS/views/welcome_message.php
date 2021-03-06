<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome </title>
	 <link rel="shortcut icon" href="<?php echo base_url('assets/images'); ?>/index.jpg">
         <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/helpdoc.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
	 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/tablestyle.css">	
	<style>
		table,th, td{
   		 border: 0px solid black;}
	</style>
</head>
<body style="border:0px solid black;">


<div>
	<div>
<?php $this->load->view('template/header'); ?>
<?php echo "<center>";

	if($this->session->flashdata('msg')){
echo "<div style='font-size:20px;text-align:center;background-color:#DFF2BF;width:50%;height:30px;color:green;'>";
	echo $this->session->flashdata('msg');
echo "<div>";	
}

echo "</center>"; ?>

<table style="width:100%;" >
            <tr colspan="2">
                <td>
                    <div align="left" style="">
                    <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                   <?php echo form_error('<div style="margin-left:30px;" class="isa_error">','</div>');?>
                    <?php if(isset($_SESSION['success'])){?>
                    <div class="isa_success" style="font-size:18px;"><?php echo $_SESSION['success'];?></div>
                    <?php
                    };
                    ?>
                    <?php if(isset($_SESSION['err_message'])){?>
                    <div class="isa_error" style="font-size:18px;"><?php echo $_SESSION['err_message'];?></div>
                    <?php
                    };
                ?>
                </div>
            </td>
     </tr>
</table>

<div class="welcome"style="width:98.6%;"><h2>Welcome to Student Life Cycle Management System</h2></div>
</br>

<?php $this->load->view('enterence/enterence_head');?>

</br>
<table style="width:100%;border:0px solid black;" align=center border=0> 
	
	<tr>
		<td style="width:30%;" valign="top">
			<div id="cssmenu" style="">
                				<ul>
							<li><a href="ADMISSION NOTIFICATION">ADMISSION NOTIFICATON</a></li>
								<?php 
								$cdate = date('Y-m-d H:i:s');
								foreach($scrlist as $row){
									$scid = $row->sc_id;
									$wharray = array('prg_scid'  =>  $scid);
									$prgcat = $this->commodel->get_distinctrecord('program','prg_category',$wharray);
									foreach($prgcat as $row){	
										$prgcatname = $row->prg_category;
										$selectfield=array('admop_prgname_branch');
										$data=array(
      											'admop_prgcat' => $prgcatname,
      											'admop_lastdate >=' => $cdate,
       										);
										$progid = $this->commodel->get_distinctrecord('admissionopen',$selectfield,$data);
									?>
        						 		<li class='has-sub'><a href=""><?php echo $prgcatname;?></a>
                      	  						    	<ul>
											<?php foreach($progid as $row){
												$id = $row->admop_prgname_branch;
												$pname = $this->commodel->get_listspfic1('program','prg_name','prg_id',$id)->prg_name;
									
												?>
                        									<li><a href="<?php echo site_url('welcome/ginstruction/');echo $id;?>"><?php echo $pname ."(".$this->commodel->get_listspfic1('program','prg_branch','prg_id',$id)->prg_branch .")" ;?></a></li>
											<input type="hidden" value="<?php echo $id;?>" name="prgid">
										<?php }?>
                        							</ul>
										
									</li>
							<?php }
									}?>
						</ul>
               			 </div>
       			 </div>
        		
		</td>

		<td align=center style="width:30%;" valign="top">
		<div style="overflow:auto;height:300px;">
			<table style="width:100%" class="TFtable" >
				<tr style="background-color:#38B0DE;color:white;font-size:21px;">
				<td style="border:2px solid white;" align=center colspan=5>Announcement</td></tr>
				<?php
				$count =0;
				foreach($annoresult as $aname){
                                echo "<tr style=\"font-size:15px;\">";
				echo "<td>";
                                echo  "<b>".++$count."</b>" ; 
                                echo ".";
                                echo "&nbsp;&nbsp;";
                                echo"</td>";
                                echo "<td>";
                                echo  "<b>" .$aname->anou_title."</b>";
				echo "&nbsp;&nbsp;";                                
				echo"</td>";
				echo "<td>";
                                echo  $aname->anou_description;
				echo"</td>";
				?>
				<td><a href ="<?php echo base_url('uploads/announcement/'.$aname->anou_attachment);?>"target=_blank><?php echo $aname->anou_attachment;?></a></td>
                                <?php
 				echo "</tr>";
				}
				?>
</div>
	      </td>
</table>
	
		<td align=right style="width:30%;" valign="top">
		<form action="<?= site_url('welcome') ?>" method="post">
        		<table>
			<tr>
			<td align=left>	
				<label>Username</label></br>	
        			<input type="text" name="username" size="33%" style="height:33px;"/>
			</td>
			</tr>
				<tr>
				<td align=left><label>Password</label></br>
        			<input type="password" name="password" size="33%" style="height:33px;" placeholder="********"/></td></tr>
				<tr>
				<td >
        			<button type="submit" style="width:30%" id="button"><b>Login</b></button>
		
				<a href="<?php echo site_url('Student/student_step0');?>" style="" title="Click to open student detail form">
				<input type="button" value="New Student" style="font-size:17px;width:58%;"></a></td></tr>
			<tr>
               			 <td> 
				<a href="<?php echo site_url('forgotpassword/forgotpass');?>" style="" title="Forgot Password">
                		<input type="button" value="Forgot Password" style="font-size:17px;width:60%"></a></td>
			</tr>
				
			</table>
    			</form>	
		</td>
	</tr>
	
</table>


<div>
<?php $this->load->view('template/footer'); ?>
</div>
</body>
</html>

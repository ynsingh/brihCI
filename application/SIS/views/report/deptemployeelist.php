<!--@filename deptemployeelist.php  @author Nagendra Kumar Singh(nksinghiitk@gmail.com) 
    @filename deptemployeelist.php  @author Neha Khullar(nehukhullar@gmail.com) 
    @author Manorama Pal(palseema30@gmail.com)

-->

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
    <head>
        <title>Department wise Employee List</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
        <script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-1.12.4.js" ></script>
        <style type="text/css" media="print">
        @page {
                size: auto;   /* auto is the initial value */
                margin:0;  /* this affects the margin in the printer settings */
            }
        </style>
        <script type="text/javascript">
             function printDiv(printme) {
                var printContents = document.getElementById(printme).innerHTML; 
                //alert("printContents==="+printContents);
                var originalContents = document.body.innerHTML;      
                document.body.innerHTML = "<html><head><title></title></head><body style='width:100%;'><img src='<?php echo base_url(); ?>uploads/logo/logotanuvas.jpeg' alt='logo' style='width:100%;height:100px;'>"+" <div style='width:100%;height:100%;'>  " + printContents + "</div>"+"</body>";
                window.print();     
                document.body.innerHTML = originalContents;
            }
            
            $(document).ready(function(){
                
                /****************************************** start of uofficer********************************/
                $('#wtype').on('change',function(){
                    var workt = $(this).val();
                    //alert(sc_code);
                    if(workt == ''){
                        $('#uoff').prop('disabled',true);
                   
                    }
                    else{
                        $('#uoff').prop('disabled',false);
                        $.ajax({
				 url: "<?php echo base_url();?>sisindex.php/report/getspuolist",
//                            url: "<?php echo base_url();?>sisindex.php/report/getuolist",
                            type: "POST",
                            data: {"worktype" : workt},
                            dataType:"html",
                            success:function(data){
                            //alert("data==1="+data);
                                $('#uoff').html(data.replace(/^"|"$/g, ''));
                                                 
                            },
                            error:function(data){
                                //alert("data in error==="+data);
                                alert("error occur..!!");
                 
                            }
                        });
                    }
                }); 
                /******************************************end of uofficer********************************/
                
                /****************************************** start of deptarment********************************/
                $('#uoff').on('change',function(){
                    var wtcode = $('#wtype').val();
                    var uoid = $('#uoff').val();
                    //alert(sc_code);
                    var wrktypeuo = wtcode+","+uoid;
                    if(wtcode == ''){
                        $('#dept').prop('disabled',true);
                   
                    }
                    else{
                        $('#dept').prop('disabled',false);
                        $.ajax({
                            //url: "<?php echo base_url();?>sisindex.php/report/getdeptlist",
                            url: "<?php echo base_url();?>sisindex.php/jslist/getdeptlist",
                            type: "POST",
                            data: {"worktypeuo" : wrktypeuo},
                            dataType:"html",
                            success:function(data){
                            //alert("data==1="+data);
                                $('#dept').html(data.replace(/^"|"$/g, ' '));
                            },
                            error:function(data){
                                //alert("data in error==="+data);
                                alert("error occur..!!");
                 
                            }
                        });
                    }
                }); 
                /******************************************end of department********************************/
                           
               
            });
            function verify(){
                    var x=document.getElementById("wtype").value;
                    var y=document.getElementById("uoff").value;
                    //var z=document.getElementById("dept").value;
                   // alert("value==x==="+x+"\nvalue==y=="+y);
                    if((x == 'null' && y == 'null') || (x == '' && y == '')||(y == 'null')||(x == 'null')){
                     
                        alert("please select at least any two combination for search !!");
                        return false;
                    };
                    /*if(x !='null' && z!='null'){
                        if(y == 'null' || y == ''){
                            alert("if you want deptartment wise list then please select UO for search  list !!");
                            return false;
                        };    
                    }; */   

            }

        </script>        
    
    </head>
    <body>
        
        
        <?php $this->load->view('template/header'); ?>
        
        <form action="<?php echo site_url('report/deptemployeelist');?>" id="myForm" method="POST" class="form-inline">
          <?php //echo "seema===".$this->wtyp.$this->uolt.$this->deptmt;?>   
            
         <table width="100%" border="0">
            <tr style="font-weight:bold;">
                <td>  Select Type<br>
                    <select name="wtype" id="wtype" style="width:250px;"> 
			<?php if(!empty($this->wtyp)){ ?>
                        <option value="<?php echo $this->wtyp; ?>" > <?php echo $this->wtyp; ?></option>
                        <?php  }else{ ?>

                      <option value="" disabled selected>--------Select Working Type-------</option>
                       <?php  } ?> 
                      <option value="Teaching">Teaching</option>
                      <option value="Non Teaching"> Non Teaching</option>
                    </select> 
                                    
                </td> 
               <td>  Select UO<br>
                    <select name="uoff" id="uoff" style="width:270px;"> 
			 <?php if((!empty($this->uolt))&&($this->uolt != 'All')){ ?>
                        <option value="<?php echo $this->uolt; ?>" > <?php echo $this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$this->uolt)->name ." ( ". $this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$this->uolt)->code ." )"; ?></option>
                        <?php  }else{ ?>

                      <option value="" disabled selected>-------- Select University officer------</option>  
		 <?php  } ?>
                    </select> 
                </td> 
                <td><div>  Select Department<br>
                    <select name="dept[]" id="dept" style="width:400px;"  title="You have to choose multiple subject by pressing Ctrl" multiple> 
			 <?php if((!empty($this->deptmt))&&($this->deptmt != 'All')){ ?>
                        <option value="<?php echo $this->deptmt; ?>" > <?php echo $this->commodel->get_listspfic1('Department','dept_name','dept_id' ,$this->deptmt)->dept_name ." ( ". $this->commodel->get_listspfic1('Department','dept_code','dept_id' ,$this->deptmt)->dept_code ." )"; ?></option>
                        <?php  }else{ ?>

                      <option selected="selected" disabled selected>-------------Select Department-----------</option>  
			 <?php  } ?>

                    </select> 
                    
                </td>
	<!--	<td>
                   You have to<br> choose multiple <br>subject by<br> pressing Ctrl

                </td>
-->
                <td><input type="submit" name="filter" id="crits" value="Search"  onClick="return verify()"/></td>
            </tr>    
        </table>
        </form>
	<script>
                $('document').ready(function(){
                        $('#dept').multiselect({
                                columns:1,
                                placeholder: 'Select Department',
                                search: true,
                                selectAll: true
                        });
                });
        </script>
        <table width="100%"><tr style=" background-color: graytext;">
            <td valign="top"> 
                <?php //$this->wtyp='';$this->uolt.='';$this->deptmt='';?>  
                
                <img src='<?php echo base_url(); ?>uploads/logo/print1.png' alt='print'  align="left" onclick="javascript:printDiv('printme')" style='width:30px; height:30px;float:right;padding:2px; margin-right:30px;' title="Click for print" >
                <form action="<?php echo site_url('Pdfgen/del/'.str_replace(' ','_',$this->wtyp).'/'.$this->uolt.'/'.$this->deptmt) ?>">
                    <input type="submit" value="" style="width:30px; height:30px;float:right;padding:2px; margin-right:10px;background-image:url('<?php echo base_url(); ?>assets/sis/images/pdf.jpeg')" title="Click for pdf">     
                </form>
              
                <div style="margin-left:500px;"><b>Department Wise Staff List Details</b></div>
                   
            </td>
           
            <div>
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php echo form_error('<div class="isa_error">','</div>');?>

                <?php if(isset($_SESSION['success'])){?>
                    <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                <?php
                };
                ?>
                 <?php if(isset($_SESSION['err_message'])){?>
                    <div  class="isa_error"><?php echo $_SESSION['err_message'];?></div>
                <?php
                };
                ?>

            </div>
        </tr></table>
    <div id="printme" align="left" style="width:100%;">  
        <div class="scroller_sub_page">
        <table class="TFtable" >
            <thead>
                <tr>
                    <th>Sr.No</th>
                   <!-- <th></th> -->
                    <th>Employee Name</th>
                <!--    <th>Designation</th>
                    <th>University Officer Control</th>
                    <th>Department Name</th>
                    <th>Specialisation(Major Subject)</th> -->
                    <th>Designation</th>
                    <th>Scheme Name</th>
                    <!--<th>Employee Post</th>-->
                   <!-- <th>Pay Band</th>-->
                 <!--   <th>E-Mail ID</th>
                    <th>Contact No</th>
                    <th>Aadhaar No</th>
                    <th>Action</th>
                    -->
                </tr>
            </thead>
            <tbody>
                <?php $serial_no = 1;
		$ouoid = 0;
		$odid = 0;
		
               if( count($records) ):  ?>
                    <?php foreach($records as $record){
//                        print_r($record);
			if($ouoid !=$record->emp_uocid){
			echo "<tr>";
			echo "<td colspan=4 style=\"text-align:center;\">";
			echo " <b> UO CONTROL : ";
			echo $this->lgnmodel->get_listspfic1('authorities','name','id' ,$record->emp_uocid)->name;
			echo "</b></td>";
			echo "</tr>";
			$ouoid=$record->emp_uocid;
			}
			if($odid !=$record->emp_dept_code){
                        echo "<tr><td colspan=4 align=left><b> Department : ";
			echo $this->commodel->get_listspfic1('Department','dept_name','dept_id',$record->emp_dept_code)->dept_name;
			echo " ( ". $this->commodel->get_listspfic1('Department','dept_code','dept_id',$record->emp_dept_code)->dept_code ." )";
                        echo "</b></td></tr>";
			$odid = $record->emp_dept_code;
			$serial_no = 1;
                        }
			echo "<tr>";
			echo "<td>". $serial_no++ ." </td>";
			echo "<td>";
			echo anchor("report/viewfull_profile/{$record->emp_id}",$record->emp_name." ( "."PF No:".$record->emp_code." )" ,array('title' => 'View Employee Profile' , 'class' => 'red-link'));

		//	$record->emp_name.
			echo "</td>";
			echo "<td>"; 
			echo $this->commodel->get_listspfic1('designation','desig_name','desig_id',$record->emp_desig_code)->desig_name;
			 if ($record->emp_head == "HEAD"){
                                        echo " & ";
                                        echo $record->emp_head;
                                }
	
			echo "</td>";
			echo "<td>";
			echo $this->sismodel->get_listspfic1('scheme_department','sd_name','sd_id',$record->emp_schemeid)->sd_name ;	
			echo " ( ".$this->sismodel->get_listspfic1('scheme_department','sd_code','sd_id',$record->emp_schemeid)->sd_code ." )";	
			echo "</td>";
?>
                        </tr>
                    <?php }; ?>
                <?php else : ?>
                    <td colspan= "13" align="center"> No Records found...!</td>
                <?php endif;?>
                </tbody>
        </table>

        </div><!------scroller div------>
        </div><!------print div------>
       
	<p> &nbsp; </p>
        <div align="center">  <?php $this->load->view('template/footer');?></div>

    </body>
</html>

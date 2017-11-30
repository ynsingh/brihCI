<!--@filename transferordercopy.php  @author Manorama Pal(palseema30@gmail.com) -->
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
    <head>
        <title> </title>
	<style>
		.TFtable tr td{border:0px solid black;}	
                .table2 th,td {
                    border: 1px solid black;
                    border-collapse:separate;
                    border-spacing: 1px; 
                }
	</style>
    </head>
    <body style="border:1px solid black;margin-top:5px;">
	<div >
	<img src="uploads/logo/logotanuvas.jpeg" alt="logo" style="width:100%;height:70px;"><br/>
	<table style="width:100%;margin-top:10px;">
        <tr><th>
            <center style="font-size:9;">PROCEEDINGS OF THE REGISTRAR<br/>
            <?php echo $this->orgname;?><br/>
            <?php echo $this->orgaddres."  " .$this->orgpincode;?></center>
            
        </th></tr>
        <tr><th>
            <center style="font-size:9;">PRESENT : <?php echo $this->regname;?><br/>
            <?php echo $this->uitdesig;?></center>
            </center>
        </th></tr>
        </table><br/>
        <table style="width:100%;" class="TFtable">
            <thead  style="background-color:#38B0DE;color:white;font-size:18px;"><tr><td colspan="8"></td></tr></thead><br/><br/>
        <!--</table> 
        <table style="width:100%;" class="TFtable" border="1">-->
            <br/><?php 
               // foreach ($this->data as $detail){
                    echo "<tr><td style=font-size:9;> USO No.</td><td style=font-size:9;>". $detail->uit_uso_no ."</td>";
                    echo "<td align=right style=\"font-size:9;float:right;margin-left:500px;\">Dated: ". $detail->uit_date. "</td></tr>";
                    echo "<tr><td style=font-size:9;>Rc No.</td><td style=font-size:9;>".$detail->uit_rc_no."</td></tr>";
                    echo "<tr><td style=font-size:9;>Subject:</td><td style=font-size:9;>".$detail->uit_subject."</td></tr>";
                    echo "<tr><td style=font-size:9;>Reference:</td><td style=font-size:9;>".$detail->uit_referenceno."</td></tr>";
                    echo "<tr><td style=font-size:9;>Order:</td><td style=font-size:9;>".$detail->uit_ordercontent."</td></td></tr>";
            ;?>
            </table><br/>
           <!-- <tr><td>-->
            <table style="font-size: 9;border:1px solid black;" align="center"> 
                <!--<thead>-->
                    <tr align="center" >
                        <th style="border:1px solid black;">Sl.No.</th>
                        <th style="border:1px solid black;">Name and designation</th>
                        <th style="border:1px solid black;">Post & Place to which transferred</th>
                    </tr>
                                                
                <!--</thead>
                <tbody>-->
                    <tr align="center">
                        <td><?php echo 1; ?></td>
                        <td><?php echo $this->sismodel->get_listspfic1('employee_master','emp_name','emp_id',$detail->uit_staffname)->emp_name;?>
                            &nbsp;<?php echo " , ". $this->commodel->get_listspfic1('designation','desig_name','desig_id',$detail->uit_desig_to)->desig_name;?></td>
                        <td><?php echo $this->commodel->get_listspfic1('designation','desig_name','desig_id',$detail->uit_post_to)->desig_name.", ".$this->commodel->get_listspfic1('Department','dept_name','dept_id',$detail->uit_dept_to)->dept_name;?></td>
                    </tr>    
               <!-- </tbody> -->   
            </table><br/><br/>
            <table class="TFtable" style="width:100%;" >
                <tr><td style="font-size:9;">TTA Detail: <?php echo $detail->uit_tta_detail;?></td>
                    <td style="font-size:9;" >Date of relieve : <?php echo $detail->uit_dateofrelief;?></td>
                    <td style="font-size:9;">Expected Date of joining : <?php echo $detail->uit_dateofjoining;?></td>
                </tr>
            </table>
            <br/><table class="TFtable" style="width:100%;" >
                <tr><td align="right" style="font-size:9;">Sd/-<?php echo $this->regname;?>                        
                </td></tr>
                <tr><td align="right" style="font-size:9;"><?php echo $this->uitdesig;?>                        
                </td></tr>
            </table>
            <br/><table class="TFtable" style="width:100%;" >
                <tr><td style="font-size:9;">To </td></tr>
                <tr><td style="font-size:9;">The <?php echo $this->sismodel->get_listspfic1('employee_master','emp_name','emp_id',$detail->uit_staffname)->emp_name;?></td></tr>
                <br/><br/>
                <tr><td style="font-size:9;">Copy To</td></tr>
                <tr><td style="font-size:9;">Head of department <?php echo $this->commodel->get_listspfic1('Department','dept_name','dept_id',$detail->uit_dept_to)->dept_name ."," .$detail->uit_workdept_from; ?></td></tr>
                <tr><td style="font-size:9;">UO concerned <?php echo $detail->uit_uoc_from.",".$this->lgnmodel->get_listspfic1('authorities','name','id' ,$detail->uit_uoc_to)->name;?></td></tr>
                <tr><td style="font-size:9;">The Administrative Officer,TANUVAS</td></tr>
                <tr><td style="font-size:9;"><?php echo $detail->uit_email_sentto;?></td></tr>
            </table>
            <br/><table class="TFtable" style="width:100%;"><tr><td align="right" style="font-size:9;">ADMINISTRATIVE ORDER                 
            </td></tr></table> 
                   
            <!--</table>-->
        </div>   
    </body> 
   
</html>    

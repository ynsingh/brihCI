<!--@filename professorlist.php  @author Manorama Pal(palseema30@gmail.com) 
    @filename professorlist.php  @author Neha Khullar(nehukhullar@gmail.com) 
-->

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
    <head>
        <title>Welcome to TANUVAS</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">   
        <style type="text/css" media="print">
        @page {
                size: auto;   /* auto is the initial value */
                margin:0;  /* this affects the margin in the printer settings */
            }
        </style>
          <script>
             function printDiv(printme) {
                var printContents = document.getElementById(printme).innerHTML; 
                //alert("printContents==="+printContents);
                var originalContents = document.body.innerHTML;      
                document.body.innerHTML = "<html><head><title></title></head><body style='width:100%;' ><img src='<?php echo base_url(); ?>uploads/logo/logotanuvas.jpeg' alt='logo' style='width:100%;height:100px;' >"+" <div style='width:100%;height:100px;'>  " + printContents + "</div>"+"</body>";
                window.print();     
                document.body.innerHTML = originalContents;
            }
        </script>     
       
        
    </head>
    <body>
    <?php $this->load->view('template/header'); ?>    
    <table width="100%">
       <tr colspan="2"><td>
        <td>
            <img src='<?php echo base_url(); ?>uploads/logo/print1.png' alt='print'  onclick="javascript:printDiv('printme')" style='width:30px;height:30px;' title="Click for print" >  
        </td>       
       <div>
       <?php
       echo "<td align=\"center\" width=\"100%\">";
       echo "<b> List of Professors - ( Seniority on the basis of date of appt. as Prof )</b>";
       echo "</td>";
       ?>
       
        </div>

        </td></tr></table>
        <div id="printme" align="left" style="width:100%;">
        <div class="scroller_sub_page">
            <table class="TFtable" >
                <thead>
                <tr>
                    
                    <th>Sr.No</th>
                    <th>Employee Name</th>
                    <th>DOR</th>
                    <th>Discipline</th>
                    <th>Department</th>
                    <th>Date of Joining <br/> as Prof.</th>
                    <th>Total Service as Prof. <br/> as on (<?php echo date("Y/m/d");?>)<br/>YY/MM/DD</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php $serial_no = 1;
                    
                    if( count($emplist) ):  ?>
                        <?php    echo "<tr>";
                            echo "<td colspan=7>";
                            echo " <b> Designation : PROFESSOR";
                            echo "</b></td>";
                            echo "</tr>";
                         foreach($emplist as $record){
                            echo "<tr>";
                            echo "<td>".$serial_no++."</td>";
                            echo "<td>". $record-> emp_name." </td>";
                            echo "<td> ".implode('-', array_reverse(explode('-', $record->emp_dor)))."</td>";
                            echo "<td>";
                            if(!empty($record->emp_specialisationid)){
                                echo  $this->commodel->get_listspfic1('subject','sub_name','sub_id',$record->emp_specialisationid)->sub_name;
                                echo  "</td>";
                            }
                            echo "<td>";
                            echo  $this->commodel->get_listspfic1('Department','dept_name','dept_id',$record->emp_dept_code)->dept_name;
                            echo  "</td>";
                            echo "<td>". implode('-', array_reverse(explode('-', $record->emp_doj)))."</td>";
                            $date1 = new DateTime($record->emp_doj);
                            $date2 = new DateTime();
                            $diff = $date1->diff($date2);
                            echo "<td> ".$diff->y . "&nbsp;&nbsp;&nbsp;&nbsp;" . $diff->m." &nbsp;&nbsp;&nbsp;&nbsp; ".$diff->d."</td>";
                            echo "</tr>"
                    
                        ?>
               
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



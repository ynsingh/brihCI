<!--@filename leavelipredesign.php  @author Manorama Pal(palseema30@gmail.com) -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
    <head>
        <title>Leave salary slip</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-1.12.4.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-ui.js" ></script>
        <!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js " ></script --> 
        
        <style>
            /* Create two equal columns that floats next to each other */
            .column {
                //float: left;
                width: 50%;
                //  padding: 10px;
                // height: 300px; ss
            }

            /* Clear floats after the columns */
            .row:after {
                content: "";
                display: table;
                clear: both;
            }
           .dggh td, th {
                border: 1px solid grey;
            }
        </style>
        <script>
        
            $(document).ready(function(){ 
            /*********************************************for Earnings ************************************/ 
                $(".headamtI").on("blur", function(){
                    var sum=0;
                    $(".headamtI").each(function(){
                        if($(this).val() != "")
                            sum += parseInt($(this).val());   
                          //  alert("seema-----"+sum);
                    });
                    $("#Tincome").val(sum);
                    var tincome = parseInt($("#Tincome").val());
                    var tdeduction = parseInt($("#Tdeduction").val());
                    var dfpay = tincome - tdeduction;
                    $("#netPay").val(dfpay);
                });
                    
                /*********************************************for Earnings end ************************************/ 
                
                /*********************************************for Deduction ************************************/  
                
                $(".headamtD").on("blur", function(){
                    var sumded=0;
                    $(".headamtD").each(function(){
                        if($(this).val() != "")
                            sumded += parseInt($(this).val());   
                           // alert("seema--ded---"+sumded);
                    });
    
                    $("#Tdeduction").val(sumded);
                    var tdeduction = parseInt($("#Tdeduction").val());
                    var tincome = parseInt($("#Tincome").val());
                    var dfpay = tincome - tdeduction;
                    $("#netPay").val(dfpay);
                });
                
                /*********************************************for Deduction end************************************/  
             
        }); 
               
        </script>    
    </head>
    <body>
        <?php $this->load->view('template/header'); ?>
        
        <table width="100%"><tr>
            <?php
            echo "<td align=\"left\" width=\"33%\">";
            echo anchor('setup3redesign/salaryprocess/', "View Salary Processing Staff List" ,array('title' => 'View View Salary Processing Staff List' , 'class' => 'top_parent'));
            echo "</td>";
            echo "<td align=\"center\" width=\"34%\">";
            echo "<b>Salary Slip</b>";
            echo "</td>";
            echo "<td align=\"right\" width=\"33%\">";

            ?>
        </tr></table>
        <table width="100%">
           <tr><td>
           <div>
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php echo form_error('<div class="isa_error">','</div>');?>
               <?php
                if((isset($_SESSION['success'])) && ($_SESSION['success'])!=''){
                    echo "<div  class=\"isa_success\">";
                    echo $_SESSION['success'];
                    echo "</div>";
                }
                if((isset($_SESSION['err_message'])) && (($_SESSION['err_message'])!='')){
                    echo "<div  class=\"isa_error\">";
                    echo $_SESSION['err_message'];
                    echo "</div>";
                }
                ;?>
                
            </div>
            </td></tr>
        </table>
        <?php
            $empid=$this->uri->segment(3);
            $month=$this->uri->segment(4);
            $year=$this->uri->segment(5);
                           
            $cnomonth= date("m",strtotime($month));
            $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$year);
            $paldays=''; $eoldays=''; 
            /************** no leave days **********************/
            $stffieldsal ="sle_pal,sle_eol";
            $wstfsal = array ('sle_empid'=>$empid,'sle_month'=>$month,'sle_year'=>$year);
            $slevalsal = $this->sismodel->get_orderlistspficemore('salary_leave_entry',$stffieldsal,$wstfsal,'');
                if(!empty($slevalsal)){
                    $paldays= $slevalsal[0]->sle_pal;
                    $eoldays= $slevalsal[0]->sle_eol;
                    $totaldl= $paldays +  $eoldays; 
                    $leftdays= $nodaysmonth - $totaldl; 
                   // echo "left days for slalry===".$leftdays;
                }            
            
           
        ;?>
 
        <table border="1" cellpadding=10 width="100%" bgcolor="#95a5a6"><tr><td>  
            <table  cellspacing="10" width="100%" bgcolor="#d5dbdb"> 
               
                <input type="hidden" name="empid" value="<?php $empid; ?>">
                <input type="hidden" name="month" value="<?php $month; ?>">
                <input type="hidden" name="year" value="<?php echo $year; ?>">
               
                <tr>
                    <td><b>Name:</b><?php echo $this->sismodel->get_listspfic1('employee_master','emp_name','emp_id',$empid)->emp_name; ?></td>
                    <td><b>PF No:</b><?php echo $this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$empid)->emp_code; ?></td>
                    <td colspan="1"><b>Month/Year:</b><?php echo strtoupper($this->uri->segment(4)).'/'.$year;?></td>
                </tr>
                <tr>
                    <td><b>Campus:</b><?php $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
                    echo $this->commodel->get_listspfic1('study_center','sc_name','sc_id',$scid)->sc_name;?></td>
                    <td><b>UO Control:</b><?php $uocid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
                    echo $this->lgnmodel->get_listspfic1('authorities','name','id',$uocid)->name;?></td>
                    <td colspan="1"><b>Department:</b><?php $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
                    echo $this->commodel->get_listspfic1('Department','dept_name','dept_id',$deptid)->dept_name;?></td>
                </tr>
                <tr>
                    <td><b>Scheme:</b><?php $shmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
                    echo $this->sismodel->get_listspfic1('scheme_department','sd_name','sd_id',$shmid)->sd_name;?></td>
                    <td><b>DDO:</b><?php $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
				if(!empty($ddoid)){
                    		echo $this->sismodel->get_listspfic1('ddo','ddo_name','ddo_id',$ddoid)->ddo_name;
			}else{
				echo '';
			}

//                    echo $this->sismodel->get_listspfic1('ddo','ddo_name','ddo_id',$ddoid)->ddo_name;?>
			</td>
                    <td colspan="1"><b>Designation:</b><?php $deptid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
                    echo $this->commodel->get_listspfic1('designation','desig_name','desig_id',$deptid)->desig_name;?></td>
                </tr>
                <tr>
                    <td><b>Bank Ac No:</b><?php echo $this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;?></td>
                    <td><b>Pay Scale:</b><?php $pbid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
                            $payband=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$pbid)->sgm_name;
                            $pay_max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$pbid)->sgm_max;
                            $pay_min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$pbid)->sgm_min;
                            $gardepay=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$pbid)->sgm_gradepay;
                            $paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
                            echo $payband."(".$pay_min."-".$pay_max.")".$gardepay."/".$paycomm;
                            ;?>
                            
                    </td>
                    <td colspan="1"><b>DOB:</b><?php $dob=$this->sismodel->get_listspfic1('employee_master','emp_dob','emp_id',$empid)->emp_dob;
                        echo date('d-m-Y',strtotime($dob));?></td>
                </tr>
                <tr>
                    <td><b>DOJ:</b><?php $doj=$this->sismodel->get_listspfic1('employee_master','emp_doj','emp_id',$empid)->emp_doj;
                        echo date('d-m-Y',strtotime($doj));?></td>
                    <td><b>DOR:</b><?php $dor=$this->sismodel->get_listspfic1('employee_master','emp_dor','emp_id',$empid)->emp_dor;
                        echo date('d-m-Y',strtotime($dor));?></td>
                    <td colspan="1"><b>working type/Group:</b><?php $worktype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
                        $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
                        $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
                        echo $worktype ."(". $group .") / ".$emptype?></td>
                </tr>
                      
            </table>
            <?php
                if($totaldl > $nodaysmonth ){
                    //case for leave days greter than no of days in a  month.
                    
                    echo "<br/><b><font color=\"0033FF\">Paid Annual Leave : ".$paldays."</style></font></b>";
                    echo "<br/><b><font color=\"0033FF\">Extra Ordinary Leave : ".$eoldays."</style></font></b>";
                    echo "<br/><b><font color=\"0033FF\">Total Leave Days : ".$totaldl."</style></font></b>";
                    echo "<br/><b><font color=\"0033FF\"><style=\"text-align:right\">Total No.of working days : 0 </style></font></b>";
                    echo "<br/><br/><font color=\"red\"><font size=\"4\"> Number of leave days should not be greater than the number of days in a month.</font>" ;
                }
                else{   //case for leave days not greter than no of days in a  month.
            ?>  
                    
            <form action="<?php echo site_url('setup3/leavesalaryslip/'.$empid.'/'.$month."/".$year);?>" method="POST" enctype="multipart/form-data">           
                <tr bgcolor="lightgrey"><td colspan="3">
                <?php echo "<b><font color=\"teal\">Paid Annual Leave : (".$paldays.")</style></font></b>"; ?>
                <?php echo "<b><font color=\"teal\">Extra Ordinary Leave : (".$eoldays.")</style></font></b>"; ?>
                <?php echo "<b><font color=\"teal\"><style=\"text-align:right\">Total no. of working days : (".$leftdays.")</style></font></b>"; ?>    
                </td></tr>             
            <tr><td>            
              
            <table class="dggh" width="50%" border="1"  heignt="50%" cellpadding=10 style="float:left;border-width: thin;border-spacing: 2px;border-style: none;" bgcolor="#95a5a6"> 
              
                 <tr>
                    <th colspan="2">Earnings</th>
                    
                </tr>
                <tr>
                    <th>Head</th>
                    <th>Amount</th>
                   
                </tr>
                           
                <tr>
               
                    <?php $sumincome=0;$i=0;$j=0;$sumdeduction=0;$finalval=0;
                     
                    foreach($incomes as $incomedata){ ?>
                    
                    <tr>
                    <?php if($incomedata->sh_tnt == $worktype || $incomedata->sh_tnt == 'Common') :?>    
                    <td>
                        <?php  echo $incomedata->sh_name/*."==id==".$incomedata->sh_id*/;
                            if(in_array($incomedata->sh_id,$allowedhead)){
                                echo "<font color=\"red\">*</font>";
                            }
                       
                        ?>
                    </td>
                    <td><?php 
                           
                            $selectfield ="seh_headamount";
                            $whdata = array('seh_empid' =>$empid,'seh_headid'=>$incomedata->sh_id);
                           // $headval=  $this->sismodel->get_maxvalue('salary_earnings_head',$selectfield,$whdata); ;        
                            //print_r($whdata);
                            $headval=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$selectfield,'seh_modifydate=',$whdata);
                            //print_r($headval);	
                            
                            ?>
                        <?php if(!empty($headval) && in_array($incomedata->sh_id,$allowedhead)):?>
                        <?php  if($incomedata->sh_calc_type == 'Y'):
                            $formula1=$this->sismodel->get_listspfic1('salary_formula','sf_formula','sf_salhead_id',$incomedata->sh_id);
                            if(!empty($formula1)){
                            $formula=$formula1->sf_formula;
                            preg_match('/(.*)\((.*?)\)(.*)/', $formula, $match);
                            //echo "in parenthesis inside: " . $match[2];
                            //echo "before and after inside: " . $match[1] . $match[3] . "\n";
                            $strfmla=explode("+",$match[2]);
                            $strfmla2=explode("*",$match[3]);
                           
                            $sfield ="seh_headamount";
                            if(!empty($strfmla[0])){
                                $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                                $wdata = array('seh_empid' =>$empid,'seh_headid' =>$tok1id);
                                //$headval1= $this->sismodel->get_maxvalue('salary_earnings_head',$sfield,$wdata,'');    
                                $headval1= $this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfield,'seh_modifydate=',$wdata);
                                $headval1=$headval1[0]->seh_headamount;
                            }
                            else{
                                $headval1=0;
                            }
                            if(!empty($strfmla[1])){
                                
                                $tok2id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                                $wdata = array('seh_empid' =>$empid,'seh_headid'=> $tok2id);
                                //$headval2= $this->sismodel->get_maxvalue('salary_earnings_head',$sfield,$wdata,''); 
                                $headval2=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfield,'seh_modifydate=',$wdata);
                                $headval2=$headval2[0]->seh_headamount;
                            }
                            else{
                               $headval2=0; 
                            }
                            
                            $rawfor=$headval1 + $headval2 ;
                            //$rawfor=$headval1[0]->shdv_defaultvalue + $headval2[0]->shdv_defaultvalue ;
                            $finalvalP=$rawfor * $strfmla2[1];
                            
                            $finalval=$finalvalP * $leftdays/$nodaysmonth;
                            
                            }
                            else{
                               // echo "f y no f exists";
                                
                                $ccaid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','CCA')->sh_id;
                                $hraid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','HRA')->sh_id;
                                                                                     
                                if($incomedata->sh_id == $ccaid || $incomedata->sh_id == $hraid){
                                    
                                    $sfbp="seh_headamount";
                                    $wdatabp = array('seh_empid' =>$empid,'seh_headid'=>1);
                                    //$headbp= $this->sismodel->get_maxvalue('salary_earnings_head',$sfbp,$wdatabp,'');
                                    $headbp= $this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfbp,'seh_modifydate=',$wdatabp);
                                    $bpamt=$headbp[0]->seh_headamount;
                                   // echo "bpamt==".$bpamt;
                                    
                                    if($incomedata->sh_id == $ccaid){
                                        $ccagrade=$this->sismodel->get_listspfic1('employee_master_support','ems_ccagrade','ems_empid',$empid);
                                        
                                        $ccaamt=$this->sismodel->getcca_amount($bpamt,$paycomm);
                                        //echo "====in vmbpamt===".$bpamt."=====in vmccaamt===".$ccaamt[0];
                                        
                                        if(!empty($ccagrade)){
                                            $ccagrade= $ccagrade->ems_ccagrade;
                                            $sfield="cca_amount";
                                            $wdata = array('cca_payrange'=>$ccaamt[0],'cca_paycomm' =>$paycomm,'cca_gradeid' =>$ccagrade);
                                          //  echo $pbid,$worktype,$ccagrade;
                                            $headvalc= $this->sismodel->get_orderlistspficemore('ccaallowance_calculation',$sfield,$wdata,'');  
                                            if(!empty($headvalc)){
                                                $headvalcca=$headvalc[0]->cca_amount;
                                                //get cca grade from payrollprofile
                                                //$ccgrade=$this->sismodel->get_listspfic1('employee_master_support','ems_ccagrade','ems_empid',$empid)->ems_ccagrade;
                                                $finalval=$headvalcca * $leftdays/$nodaysmonth;
                                            
                                            }
                                            else{
                                                $finalval=0;
                                            }
                                        }
                                        else{
                                            $finalval=0;
                                        }
                                    }
                                    if($incomedata->sh_id == $hraid){
                                        $rfhragrade=$this->sismodel->get_listspfic1('employee_master_support','ems_erfq','ems_empid',$empid);
                                        if(!empty($rfhragrade)&&($rfhragrade->ems_erfq == 'yes')){
                                            
                                            $hragrade=$this->sismodel->get_listspfic1('employee_master_support','ems_erfqhra','ems_empid',$empid);
                                        }
                                        else{
                                            $hragrade=$this->sismodel->get_listspfic1('employee_master_support','ems_hragrade','ems_empid',$empid);
                                        }
                                        $hraamt=$this->sismodel-> gethra_amount($bpamt,$paycomm);
                                      //  echo "====in vmbpamt===".$bpamt."=====in vmccaamt===".$hraamt[0];
                                                                                
                                        if(!empty($hragrade) || !empty($rfhragrade)){
                                            if($rfhragrade->ems_erfq == 'yes'){
                                                $hragrade= $hragrade->ems_erfqhra;   
                                                
                                                $sfield="rfh_amount";
                                                $wdata = array('rfh_payrange'=>$hraamt[0],'rfh_paycomm' =>$paycomm,'rfh_gradeid' =>$hragrade);
                                                // $wdata = array('hg_payscaleid' =>$pbid,'hg_workingtype' =>$worktype,'hg_gradeid' =>$hragrade);
                                                $headvalh= $this->sismodel->get_orderlistspficemore('rent_free_hra',$sfield,$wdata,'');  
                                                if(!empty($headvalh)){
                                                    $headvalhra=$headvalh[0]->rfh_amount;
                                                    $finalval=$headvalhra * $leftdays/$nodaysmonth; 
                                                   // echo "seemain hra". $finalval;
                                                }
                                                else{
                                                    $finalval=0;    
                                                }
                                            }
                                            else{
                                                                                      
                                                $hragrade= $hragrade->ems_hragrade;
                                                $sfield="hg_amount";
                                                // $wdata = array('hg_payscaleid' =>$pbid,'hg_workingtype' =>$worktype,'hg_gradeid' =>$hragrade);
                                                $wdata = array('hg_payrange'=>$hraamt[0],'hg_paycomm' =>$paycomm,'hg_gradeid' =>$hragrade);
                                                $headvalh= $this->sismodel->get_orderlistspficemore('hra_grade',$sfield,$wdata,'');  
                                                if(!empty($headvalh)){
                                                    $headvalhra=$headvalh[0]->hg_amount;
                                                    //echo $hragrade,$ccaamt[0],$headvalh,$headvalhra;
                                                    $finalval=$headvalhra * $leftdays/$nodaysmonth; 
                                                }
                                                else{
                                                    $finalval=0;    
                                                }
                                            }    
                                        }
                                        else{
                                            $finalval=0;
                                        }
                                    
                                    }
                                }
                                else{
                                    $finalval=0;
                                }    
                            }
                            
                            
                        ?>
                        
                        <input type="text"  class="headamtI" name="headamtI<?php echo $i;?>" id="headamtI<?php echo $i;?>"  value="<?php echo (round($finalval,2));  ?>" >
                        <?php  $sumincome+=$finalval; // endif formula yes if formula exists if part;?> 
                        <?php else :?>
                        <?php // endif; ?>
                        <?php $finalval=$headval[0]->seh_headamount * $leftdays/$nodaysmonth; 
                             //   echo "seema===90==".$finalval."djhgjhse ee==94==".$headval[0]->seh_headamount;
                        // if($incomedata->sh_calc_type == 'N' && $headval[0]->shdv_defaultvalue != 0):?>
                        <input type="text" class="headamtI" name="headamtI<?php echo $i;?>" id="headamtI<?php echo $i;?>"  value="<?php  echo (round($finalval,0));  ?>" >
                        <?php // $sumincome+=$headval[0]->shdv_defaultvalue;
                             $sumincome+=(round($finalval,0));
                        ?>
                        <?php endif; // endif  if h_calc_type == 'N' default value exists part; ?>
                    <?php else :
                    // if($incomedata->sh_calc_type == 'N' && $headval[0]->shdv_defaultvalue == 0): ?>
                        <input type="text" class="headamtI" name="headamtI<?php echo $i;?>" id="headamtI"  value="<?php echo 0; ?>" >    
                    <?php // $sumincome+=$finalval; // endif;?> 
                    <?php endif; //endif h_calc_type == 'N' default vallue is 0 part; ?>   
                    
                    </td>
                     <?php endif;?>  
                     </tr> 
                    <input type="hidden" name="sheadidin<?php echo $i;?>" value="<?php echo $incomedata->sh_id ; ?>">
                      <?php  //$sumincome+=$finalval; // endif;?> 
                    <?php  $i++; }?>
                    
                    <tr><td><b>Total Earning </b></td><td> <input type="text" id="Tincome" value="<?php echo (round($sumincome,0));?>" size="10" readonly></span></td></tr> 
                </table>
                
                <table class="dggh" border=1 width="50%" heignt="50%" cellpadding=10 style="float:right;border-width: thin;border-spacing: 2px;border-style: none;" bgcolor="#95a5a6">     
                <tr>
                    <th colspan="2">Deductions</th>
                    </tr>
                <tr>
                    <th>Head</th>
                    <th>Amount</th>
                   
                </tr>
                <tr>
                    
                    
                     <?php foreach($deduction as $deductdata){ ?>
                   
                        <tr>
                         <?php if($deductdata->sh_tnt == $worktype || $deductdata->sh_tnt == 'Common') :?>       
                        <td><?php echo $deductdata->sh_name/* ."=for==".$deductdata->sh_id */;
                            if(in_array($deductdata->sh_id,$allowedhead)){
                            echo "<font color=\"red\">*</font>" ;
                        }
                        ?>
                        </td>
                        <td>
                            <?php 
                            if($deductdata->sh_type =='D'){
                                $selectfield ="ssdh_headamount";
                                $whdata = array('ssdh_empid' =>$empid,'ssdh_headid' =>$deductdata->sh_id);
                               // $headvalDed= $this->sismodel->get_maxvalue('salary_subsdeduction_head',$selectfield,$whdata);
                                $headvalDed=$this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$selectfield,'ssdh_modifydate=',$whdata);
                                //echo "seema==".$headvalDed[0]->ssdh_headamount;
                            }
                            else{
                                $selectfield ="slh_id";
                                $whdata = array('slh_empid' =>$empid,'slh_headid' =>$deductdata->sh_id);
                               // $headvalDed= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata);
                                $headvalDed=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                               // echo "seema==lll".$headvalDed[0]->slh_headamount;
                            }
                           
                            ?>
                            
                            <?php if(!empty($headvalDed) && in_array($deductdata->sh_id,$allowedhead)):?>
                            <?php  if($deductdata->sh_calc_type == 'Y'):
                            $formula1=$this->sismodel->get_listspfic1('salary_formula','sf_formula','sf_salhead_id',$deductdata->sh_id);
                            if(!empty($formula1)){
                            $formula=$formula1->sf_formula;
                            preg_match('/(.*)\((.*?)\)(.*)/', $formula, $match);
                            //echo "in parenthesis inside: " . $match[2];
                            //echo "before and after inside: " . $match[1] . $match[3] . "\n";
                            
                            $strfmla=explode("+",$match[2]);
                            $strfmla2=explode("*",$match[3]);
                           
                           
                            if(!empty($strfmla[0])){
                                
                                $shtypetok1id=$this->sismodel->get_listspfic1('salary_head','sh_type','sh_code',$strfmla[0])->sh_type;
                                if($shtypetok1id == 'I' ){
                                    $sfield ="seh_headamount";
                                    $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                                    $wdata = array('seh_empid' =>$empid,'seh_headid' =>$tok1id);
                                    //$headval1= $this->sismodel->get_maxvalue('salary_earnings_head',$sfield,$wdata,'');
                                    $headval1= $this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfield,'seh_modifydate=',$wdata);
                                    $headval1=$headval1[0]->seh_headamount;
                                //  echo "hval1inside===".$headval1. "\n";
                                } 
                                elseif($shtypetok1id == 'D'){
                                    $sfield ="ssdh_headamount";    
                                    $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                                    $wdata = array('ssdh_empid' =>$empid,'ssdh_headid' =>$tok1id);
                                    //$headval1= $this->sismodel->get_maxvalue('salary_subsdeduction_head',$sfield,$wdata,'');
                                    $headval1=$this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfield,'ssdh_modifydate=',$wdata);
                                    $headval1=$headval1[0]->ssdh_headamount;
                                    //echo "hval1inside===".$headval1. "\n";
                                }
                                else{
                                    
                                    $sfield ="slh_headamount";
                                    $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                                    $wdata = array('slh_empid' =>$empid,'slh_headid' =>$tok1id);
                                    //$headval1= $this->sismodel->get_maxvalue('salary_loan_head',$sfield,$wdata,'');
                                    $headval1=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$sfield,'slh_modifydate=',$wdata);
                                    $headval1=$headval1[0]->slh_headamount;
                                }
                            }
                            else{
                                $headval1=0;
                            }
                            if(!empty($strfmla[1])){
                                $shtypetok2id=$this->sismodel->get_listspfic1('salary_head','sh_type','sh_code',$strfmla[1])->sh_type;
                                
                                if($shtypetok2id == 'I' ){
                                    $sfield ="seh_headamount";    
                                    $tok2id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                                    $wdata = array('seh_empid' =>$empid,'seh_headid' =>$tok2id);
                                    //$headval1= $this->sismodel->get_maxvalue('salary_earnings_head',$sfield,$wdata,'');
                                    $headval1=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfield,'seh_modifydate=',$wdata);
                                    $headval1=$headval1[0]->seh_headamount;
                                    // echo "hval1inside===".$headval1. "\n";
                                        
                                }
                                elseif($shtypetok2id == 'D' ){
                                    $sfield ="ssdh_headamount";   
                                    $tok2id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                                    // echo "tokwen2----".$tok2id;
                                    $wdata = array('ssdh_empid' =>$empid,'ssdh_headid'=> $tok2id);
                                    //$headval2= $this->sismodel->get_maxvalue('salary_subsdeduction_head',$sfield,$wdata,''); 
                                    $headval2=$this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfield,'ssdh_modifydate=',$wdata);
                                    $headval2=$headval2[0]->ssdh_headamount;
                                        //echo "hval2inside===".$headval2. "\n";
                                }
                                else{
                                    $sfield ="slh_headamount";
                                    $tok2id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                                    // echo "tokwen2----".$tok2id;
                                    $wdata = array('slh_empid' =>$empid,'slh_headid'=> $tok2id);
                                   // $headval2= $this->sismodel->get_maxvalue('salary_loan_head',$sfield,$wdata,''); 
                                    $headval2=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$sfield,'slh_modifydate=',$wdata);
                                    $headval2=$headval2[0]->slh_headamount;
                                    //echo "hval2inside===".$headval2. "\n"; 
                                }
                                
                            }
                            else{
                               $headval2=0; 
                            }
                            
                            $rawfor=$headval1 + $headval2 ;
                            $finalvalP=$rawfor * $strfmla2[1]. "\n";
                          //  $finalval=$finalvalP * $leftdays/$nodaysmonth;
                            
                            
                            if($deductdata->sh_code == 'UPFsub'){
                                $finalval=$finalvalP * $leftdays/$nodaysmonth;
                                $upfsubid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','UPFsub')->sh_id;
                                $sfupfsub ="ssdh_headamount";   
                                $wdatausub = array('ssdh_empid' =>$empid,'ssdh_headid'=>$upfsubid);
                                $headval2= $this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfupfsub,'ssdh_modifydate=',$wdatausub);
                                if(!empty($headval2)){
                                    $headvalfnl1=$headval2[0]->ssdh_headamount;
                                    $headvalfnl =$headvalfnl1 * $leftdays/$nodaysmonth;
                                    if($headvalfnl > $finalval){
                                        $finalval= $headvalfnl;
                                    }
                                    else{
                                        $finalval=$finalval;
                                    }
                                }
                                                                 
                            }//upfsubcription closer
                            elseif($deductdata->sh_code == 'CPS'){
                                $finalval=$finalvalP * $leftdays/$nodaysmonth;
                                $cpssubid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','CPS')->sh_id;
                                $sfcpssub ="ssdh_headamount";   
                                $wdatacssub = array('ssdh_empid' =>$empid,'ssdh_headid'=>$cpssubid);
                                $hvalcps= $this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfcpssub,'ssdh_modifydate=',$wdatacssub);
                                if(!empty($hvalcps)){
                                    $hvalcpsfnl1=$hvalcps[0]->ssdh_headamount;
                                    $hvalcpsfnl =$hvalcpsfnl1 * $leftdays/$nodaysmonth;
                                    if($hvalcpsfnl > $finalval){
                                        $finalval= $hvalcpsfnl;
                                    }
                                    else{
                                        $finalval=$finalval;
                                    }
                                }
                                    
                            }//cpssubcription closer
                            else{
                                $finalval=$finalvalP * $leftdays/$nodaysmonth;
                               
                            }
                            
                           // echo "finalval==".$finalval;
                            }//empty check for formula closer of if
                            else{
                                
                                $rentid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','Rent')->sh_id; 
                                if($deductdata->sh_id == $rentid){
                                    /********write code for the rent recovery if occupied quarter*************/
                                    $qtrocc=$this->sismodel->get_listspfic1('employee_master_support','ems_qoccupai','ems_empid',$empid);
                                    if(!empty($qtrocc) && $qtrocc->ems_qoccupai == 'yes'){
                                        $rentgrade=$this->sismodel->get_listspfic1('employee_master_support','ems_rentgrade','ems_empid',$empid);
                                        if(!empty($rentgrade)){ 
                                            $rentgradeid=$rentgrade->ems_rentgrade;
                                    
                                            $sfbp="seh_headamount";
                                            $wdatabp = array('seh_empid'=>$empid,'seh_headid' =>1);
                                            //$headbp= $this->sismodel->get_maxvalue('salary_earnings_head',$sfbp,$wdatabp,'');
                                            $headbp=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfbp,'seh_modifydate=',$wdatabp);
                                            $bpamt=$headbp[0]->seh_headamount;
                                            $hraamt=$this->sismodel->gethraper_amount($bpamt,$paycomm);
                                        
                                            $sfield="rr_percentage";
                                            $wdata = array('rr_payrange'=>$hraamt[0],'rr_paycomm' =>$paycomm,'rr_gradeid' =>$rentgradeid);
                                            $headvalh= $this->sismodel->get_orderlistspficemore('rent_recovery',$sfield,$wdata,'');  
                                            if(!empty($headvalh)){
                                                $rentrper=$headvalh[0]->rr_percentage;
                                                
                                                $rawrrcal=$bpamt*$rentrper;
                                                $headvalhra=$rawrrcal;
                                                $finalval=$headvalhra * $leftdays/$nodaysmonth; 
                                            }
                                            else{
                                                $finalval=0;  
                                            }
                                        }
                                        else{
                                            $finalval=0;   
                                        }
                                    }
                                    else{
                                        $finalval=0;
                                    }
                                                                        
                                } //ifrentrecoveery    
                                /**************************************************************************/
                                else{
                                
                                    $finalval=0;
                                }
                            }
                            
                            
                        ?>
                        
                        <input type="text" class="headamtD" name="headamtD<?php echo $j;?>" id="headamtD<?php echo $j;?>"  value="<?php echo (round($finalval,0));  ?>" >
                        <?php  $sumdeduction+=(round($finalval,0)); // endif;?> 
                        <?php else :?>
                            <?php
                                if($deductdata->sh_type == 'D'){
                                    $finalval=$headvalDed[0]->ssdh_headamount * $leftdays/$nodaysmonth ;
                                //    $finalval=$headval[0]->shdv_defaultvalue * $leftdays/$nodaysmonth;
                                }
                                else{
                                    
                                    $finalval=$this->sismodel->get_listspfic1('salary_loan_head','slh_installamount','slh_id',$headvalDed[0]->slh_id)->slh_installamount; 
                                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$headvalDed[0]->slh_id)->slh_totalintall; 
                                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$headvalDed[0]->slh_id)->slh_intallmentno;  
                                }
                            ?>
                            <input type="text"  class="headamtD" name="headamtD<?php echo $j;?>" id="headamtD<?php echo $j;?>"  value="<?php echo (round($finalval,0));  ?>" >
                           
                            <?php if($deductdata->sh_type == 'L' && $finalval != 0.00){
                                    echo $installno."/".$totalinstall;
                                        
                                } 
                            ;?>
                            <?php $sumdeduction+=(round($finalval,0)); //$sumdeduction+=$headval[0]->shdv_defaultvalue;?>
                        <?php endif;?>    
                            <?php else : ?>
                            
                            <input type="text"  class="headamtD" name="headamtD<?php echo $j;?>" id="headamtD" value="<?php echo 0; ?>" >
                            
                            <?php endif;?>
                        </td>
                        <?php endif;?>
                        </tr>
                          <input type="hidden" name="sheadidded<?php echo $j;?>" value="<?php echo $deductdata->sh_id ; ?>">   
                    <?php $j++; };?>
                    <tr><td><b>Total Deduction:</b></td><td><input type="text" id="Tdeduction" value="<?php echo (round($sumdeduction,0));?>" size="10" readonly></td></tr>  
                </tr> 
                </table>
                <tr><td><b>Net Pay:</b><input type="text" id="netPay" value="<?php $sum_total = $sumincome - $sumdeduction; echo (round($sum_total,0))?>" readonly >
                        <input type="hidden" name="incometotal" value="<?php echo (round($sumincome,0));?>">  
                        <input type="hidden" name="deductiontotal" value="<?php echo (round($sumdeduction,0));?>">
                        <input type="hidden" name="netpay" value="<?php echo (round($sum_total,0));?>" >
                 <input type="hidden" name="totalcount" id="tcount" value="<?php echo $i;?>">   
                 <input type="hidden" name="totalded" id="tcount" value="<?php echo $j;?>">
                 &nbsp;
                <button name="upsalhdval" id="btnUpload" style="align:right" onclick="return confirm('Are you sure you want to process salary?');">Update</button></span> 
                </td></tr>
            </td></tr> 
            <?php };?>
            <!--<input type="hidden" name="tcase" id="tcase" value="<?php echo $tcase;?>"> -->
        </td></tr></table>            
    </body>    
</html>    


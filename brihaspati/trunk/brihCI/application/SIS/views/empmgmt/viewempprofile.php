
<!--@filename viewempprofile.php  @author Manorama Pal(palseema30@gmail.com)
-->

<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<html>
    <head>
        <title>Welcome to TANUVAS</title>
       <!-- <link rel="stylesheet" type="text/css" href="<?php// echo base_url(); ?>assets/css/profile.css">-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
    </head>
    <body>
        <?php $this->load->view('template/header'); ?>
        <!--<?php  $this->load->view('template/staffmenu');?>
       <table id="uname"><tr><td align=center>Welcome <?= $this->session->userdata('username')?>  </td></tr></table>-->
       <table width="100%">
           <tr colspan="2"><td>
                <?php
                    echo "<td align=\"left\" width=\"33%\">";
                    echo "</td>";
                    echo "<td align=\"center\" width=\"34%\" style=\"font-size:16px\">";
                    echo "<b>View Employee Profile</b>";
                    echo "</td>";
                    echo "<td align=\"right\" width=\"33%\" style=\"font-size:16px\">";
                    $help_uri = site_url()."/help/helpdoc#ViewProfile";
                    echo "<a style=\"text-decoration:none\"target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
                    echo "</td>";
		    echo "</tr>";
		    echo "</table>";
                ?>
                <?php echo validation_errors('<div class="isa_warning">','</div>');?>
                <?php echo form_error('<div style="margin-left:30px;" class="isa_error">','</div>');?>
                <?php if(isset($_SESSION['success'])){?>
                <div class="isa_success"><?php echo $_SESSION['success'];?></div>
                <?php
                };
                ?>
                <?php if(isset($_SESSION['err_message'])){?>
                <div class="isa_error"><?php echo $_SESSION['err_message'];?></div>
                <?php
                };
                ?>
                </div>
            </td></tr>
        </table> 
        <table width="100%">
                <tr><td colspan="7">
                    <HR COLOR="#6699FF" SIZE="3">
                </td></tr>
                <tr></tr>
               <tr><td align="center" colspan="7">
                    <?php if(!empty($record->emp_photoname)):;?>
                        <img src="<?php echo base_url('uploads/SIS/empphoto/'.$record->emp_photoname);?>"  alt="" v:shapes="_x0000_i1025" width="85" height="100">
                    <?php else:?>
                        <img src="<?php echo base_url('uploads/SIS/empphoto/'."empdemopic.png");?>"  id="output_image" v:shapes="_x0000_i1025" width="78" height="94"/>
                    <?php endif?>
                    <!--<td colspan="2" style="text-align:right;" valign="top"> 
                    <?php echo anchor("staffmgmt/editempprofile/{$record->emp_id}","Edit",array('title' => 'Edit Details' , 'class' => 'red-link')); ?>    
                    </td> -->   
                </td></tr>
               <tr><td colspan="7" align="center"> 
                    <?php echo anchor("staffmgmt/editempprofile/{$record->emp_id}","Edit Information",array('title' => 'Edit Details' , 'class' => 'red-link')); ?>
                </td></tr>
               <tr></tr>
                <tr><td>   
                    <p><b>Work Information:</b></p>
                </td></tr>
                <tr></tr>
                <tr>
                    <td>Campus Name :</td>
                    <td><?php echo $this->commodel->get_listspfic1('study_center','sc_name','sc_id',$record->emp_scid)->sc_name;?>
                    </td>
                    <td>University Officer Control : </td>
                    <td><?php $authname=$this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$record->emp_uocid)->name;
                             $authcode=$this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$record->emp_uocid)->code;
                        echo  $authname." " . "( ".$authcode." )";    
                        ?>
                    </td>
                    <td>Department :</td>
                    <td colspan="2"> <?php echo $this->commodel->get_listspfic1('Department','dept_name','dept_id',$record->emp_dept_code)->dept_name?>
                    </td>
                    <!--<td>
                         <?php if(!empty($record->emp_photoname)):;?>
                        <img src="<?php echo base_url('uploads/SIS/empphoto/'.$record->emp_photoname);?>"  alt="" v:shapes="_x0000_i1025" width="85" height="100">
                    <?php else:?>
                        <img src="<?php echo base_url('uploads/SIS/empphoto/'."empdemopic.png");?>"  id="output_image" v:shapes="_x0000_i1025" width="78" height="94"/>
                    <?php endif?>    
                    </td>-->
                    
                </tr>
                <tr></tr>
                <tr> 
                    <td>Scheme Name : </td>
                    <td><?php echo $this->sismodel->get_listspfic1('scheme_department','sd_name','sd_id',$record->emp_schemeid)->sd_name?></td> 
                    <td>Drawing and Disbursing Officer :</td>
                    <td> <?php echo $this->sismodel->get_listspfic1('ddo','ddo_name','ddo_id',$record->emp_ddouserid)->ddo_name;?></td> 
                    <td>Working Type :</td>
                    <td colspan="2"><?php echo $record->emp_worktype;?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Group : </td>
                    <td><?php echo $record->emp_group;?></td> 
                    <td>Designation :</td>
                    <td> <?php echo $this->commodel->get_listspfic1('designation','desig_name','desig_id',$record->emp_desig_code)->desig_name;?></td> 
                    <td>Shown Against The Post :</td>
                    <td colspan="2"><?php echo $record->emp_post; ?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Plan / Non : </td>
                    <td><?php echo $record->emp_pnp;?></td> 
                    <td>Employee Type :</td>
                    <td> <?php echo $record->emp_type_code;?></td> 
                    <td>Application Order No :</td>
                    <td colspan="2"><?php echo $record->emp_apporderno ?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Specialisation(Major Subject) : </td>
                    <td><?php echo $this->commodel->get_listspfic1('subject','sub_name','sub_id',$record->emp_specialisationid)->sub_name;?></td> 
                    <td>Pay Band :</td>
                    <td>  <?php
                            $payband=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$record->emp_salary_grade)->sgm_name;
                            $pay_max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$record->emp_salary_grade)->sgm_max;
                            $pay_min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$record->emp_salary_grade)->sgm_min;
                            $gardepay=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$record->emp_salary_grade)->sgm_gradepay;
                        echo $payband."(".$pay_min."-".$pay_max.")".$gardepay;
                        ?>    
                    </td> 
                    <td>Basic Pay :</td>
                    <td colspan="2"><?php echo $record->emp_basic; ?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Emolution: </td>
                    <td><?php echo $record->emp_emolution; ?></td> 
                    <td>NHIS ID No :</td>
                    <td> <?php echo $record->emp_nhisidno; ?></td> 
                    <td>Date Of Appointment :</td>
                    <td colspan="2"><?php echo $record->emp_doj; ?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Date Of Retirement: </td>
                    <td><?php echo $record->emp_dor; ?></td> 
                    <td>Date of Probation :</td>
                    <td><?php echo $record->emp_doprobation;?></td> 
                    <td>Date of Regularisation :</td>
                    <td colspan="2"><?php echo $record->emp_doregular;?></td>               
                </tr>
                <tr></tr>
                <tr>
                    <td>Date Of HGP: </td>
                    <td colspan="6"><?php echo $record->emp_dateofHGP ; ?></td> 
                </tr> 
                <tr></tr>
                <tr><td colspan="7">
                    <HR COLOR="#6699FF" SIZE="3">
                </td></tr>
                <tr></tr> 
                <tr><td>
                    <p><b>Educational Information:</b></p>
                </td></tr>    
                <tr></tr>
                <tr> 
                    <td>Qualification : </td>
                    <td><?php echo $record->emp_qual;?></td> 
                    <td>Phd Status :</td>
                    <td><?php echo $record->emp_phd_status;?></td> 
                    <td>Date Of Phd Completion :</td>
                    <td colspan="2"><?php echo $record->emp_dateofphd; ?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>ASSR Exam : </td>
                    <td><?php echo $record->emp_AssrExam_status;?></td> 
                    <td>Date Of ASSR Exam :</td>
                    <td colspan="3"> <?php echo $record->emp_dateofAssrExam ;?></td> 
                   <!-- <td>Date Of Phd Completion :</td>
                    <td><?php echo $record->emp_post; ?></td>-->
               
                </tr>
                <tr><td colspan="7">    
                    <HR COLOR="#6699FF" SIZE="2">
                </td></tr>
                <tr></tr>
                <tr><td>
                    <p><b>Personal Information :</b></p>
                </td></tr>
                <tr></tr>
                <tr> 
                    <td>Employee PF No : </td>
                    <td><?php echo $record->emp_code;?></td> 
                    <td>Employee Name :</td>
                    <td><?php echo $record->emp_name ;?></td> 
                    <td>Fathers Name :</td>
                    <td colspan="2"><?php echo $record->emp_father; ?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Gender : </td>
                    <td><?php echo $record->emp_gender;?></td> 
                    <td>Community :</td>
                    <td><?php echo $record->emp_community;?></td> 
                    <td>Religion :</td>
                    <td colspan="2"><?php echo $record->emp_religion;?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Caste : </td>
                    <td><?php echo $record->emp_caste; ?></td> 
                    <td>Whether Physically Handicapped :</td>
                    <td><div><input type="radio" name="phstatus" value="yes" <?php echo ($record->emp_phstatus == 'yes'?'checked="checked"':''); ?> >Yes &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="phstatus" value="no" <?php echo ($record->emp_phstatus == 'no'?'checked="checked"':''); ?> >No
                    </div> </td> 
                    <td>Details Of PH :</td>
                    <td colspan="2"><?php echo $record->emp_phdetail; ?><td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Blood Group : </td>
                    <td><?php echo $record->emp_bloodgroup;?></td> 
                    <td>Date Of Birth :</td>
                    <td><?php echo $record->emp_dob; ?></td> 
                    <td>Pan No :</td>
                    <td colspan="2"><?php echo $record->emp_pan_no;?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Bank Name : </td>
                    <td>
                        <?php 
                        $fulldata=$record->emp_bank_ifsc_code;
                        $bname=explode(",",$fulldata);  
                        echo $bname[0]; 
                        ;?>
                    </td> 
                    <td>IFSC Code :</td>
                    <td><?php echo $bname[1]; ?></td> 
                    <td>Bank ACC No :</td>
                    <td colspan="2"><?php echo $record->emp_bank_accno; ?></td>
               
                </tr>
                <tr></tr>
                <tr> 
                    <td>Aadhaar No : </td>
                    <td colspan="6" ><?php echo $record->emp_aadhaar_no; ?></td> 
                    <!--<td>IFSC Code :</td>
                    <td> <?php echo $this->commodel->get_listspfic1('designation','desig_name','desig_id',$record->emp_desig_code)->desig_name;?></td> 
                    <td>Bank ACC No :</td>
                    <td><?php echo $record->emp_post; ?></td>-->
               
                </tr>
                <tr></tr>
                <tr><td colspan="7">    
                    <HR COLOR="#6699FF" SIZE="2">
                </td></tr>
                <tr></tr>
                <tr><td>
                    <p><b>Communication Information:</b></p>
                <tr><td>
                <tr></tr>
                <tr> 
                    <td>E-Mail ID : </td>
                    <td><?php echo $record->emp_email; ?></td> 
                    <td>Phone/Mobile No :</td>
                    <td><?php echo $record->emp_phone; ?></td> 
                    <td>Address :</td>
                    <td colspan="2" ><?php echo $record->emp_address;?></td>
               
                </tr>
                
                <tr><td colspan="7">        
                    <HR  COLOR="#6699FF" SIZE="2">
                </td></tr>
                <tr></tr>
                <tr><td>
                    <p><b>Other Information :</b></p>
                </td></tr>
                <tr></tr>
                <tr> 
                    <td>Mother Tongue : </td>
                    <td><?php echo $record->emp_mothertongue; ?></td> 
                    <td>Nativity :</td>
                    <td><?php echo $record->emp_citizen; ?></td> 
                    <td>Remarks :</td>
                    <td colspan="2"><?php echo $record->emp_remarks;?></td>
               
                </tr>
                <tr></tr>
                <tr><td colspan="7">
                    <HR  COLOR="#6699FF" SIZE="3">
                </td></tr>
                <tr></tr>
                <tr><td>
                    <p><b>Service Data :</b></p>
                </td></tr>
                <tr></tr>
                <tr>
                    <td><b>Place of working</b></td>
                    <td><b>Designation</b></td>
                    <td><b>AGP</b></td>
                    <td><b>Date of AGP</b></td>
                    <td><b>From</b></td>
                    <td><b>To</b></td>
                    <td><b>Total service (YY/MM/DD)</b></td>
                    <tbody>
                        <?php if( count($servicedata->result()) ):  ?>
                            <?php foreach($servicedata->result() as $record){;?>
                            <tr>
                                <td>
                                    
                                    <?php
                                    $cname=$this->commodel->get_listspfic1('study_center','sc_name','sc_code',$record->empsd_campuscode)->sc_name;
                                    echo $cname."&nbsp;"."(".$record->empsd_campuscode.")";
                                    ?>
                                </td>
                                <td>
                                    <?php echo $this->commodel->get_listspfic1('designation','desig_name','desig_code',$record->empsd_desigcode)->desig_name; ?>
                                </td>
                                 <td>
                                    <?php
                                    $pbname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$record->empsd_pbid)->sgm_name; 
                                    $pbmax=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$record->empsd_pbid)->sgm_max;
                                    $pbmin=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$record->empsd_pbid)->sgm_min;
                                    $pbgp= $this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$record->empsd_pbid)->sgm_gradepay;
                                    echo $pbname."(".$pbmin."-".$pbmax.")".$pbgp;
                                    ;?>
                                </td>
                                <td>
                                    <?php echo $record->empsd_pbdate; ?>
                                </td>
                                <td>
                                    <?php echo $record->empsd_dojoin; ?>
                                </td>
                                <td>
                                    <?php echo $record->empsd_dorelev; ?>
                                </td>
                                <td>
                                    <?php 
                                    $date1 = new DateTime($record->empsd_dojoin);
                                    $date2 = new DateTime($record->empsd_dorelev);
                                    $diff = $date1->diff($date2);
                                    echo "<b>&nbsp;&nbsp;".$diff->y . "&nbsp;&nbsp;&nbsp;" . $diff->m."&nbsp;&nbsp;&nbsp; ".$diff->d. "</b>"
                                    ;?>
                                </td>   
                            </tr>
                        <?php }; ?>
                        <?php else : ?>
                            <td colspan= "7" align="center"> No Records found...!</td>
                        <?php endif;?>
                    </tbody>    
		</tr>
                <tr></tr>
                <tr><td colspan="7">
                    <HR  COLOR="#6699FF" SIZE="2">
                </td></tr>
                <tr></tr>
                <tr><td>
                    <p><b>Performance Data :</b></p>
                </td></tr>
                <tr></tr>
                <tr><td colspan="7">
                    <HR  COLOR="#6699FF" SIZE="2">
                </td></tr>
              
        </table>    
        <p> &nbsp; </p>   
        </div><?php $this->load->view('template/footer'); ?></div>
    </body>
</html>
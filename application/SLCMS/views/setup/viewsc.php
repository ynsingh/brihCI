<!--@name viewsc.php
  @author Rekha Devi Pal(rekha20july@gmail.com)
 -->
<html>
<title>View SC</title>
    <head>    
        <?php $this->load->view('template/header'); ?>
        <?php $this->load->view('template/menu');?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tablestyle.css">
    </head>
    <body>
<table id="uname"><tr><td align=center>Welcome <?= $this->session->userdata('username') ?>  </td></tr></table>
<table width="100%">

            <tr><td>
            <?php
                    echo "<td align=\"left\" width=\"33%\">";
                    echo anchor('setup/sc/', "Add Study Center " ,array('title' => ' Add study center Configuration Detail ' , 'class' => 'top_parent'));                   
                    echo "</td>";
                    echo "<td align=\"center\" width=\"34%\">";
                    echo "<b>Study Center Details</b>";
                    echo "</td>";
                    echo "<td align=\"right\" width=\"33%\">";
                    $help_uri = site_url()."/help/helpdoc#ViewStudyCenter";
                    echo "<a style=\"text-decoration:none\" target=\"_blank\" href=$help_uri><b>Click for Help</b></a>";
            ?>

            <div>
                <?php echo validation_errors('<div class="isa_warning>','</div>');?>
                <?php if(isset($_SESSION['success'])){?>
                <div style="margin-left:2%;" class="isa_success"><?php echo $_SESSION['success'];?></div>
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
        <div class="scroller_sub_page">
        <table class="TFtable" >
               <thead>
                        <th>University Name</th>
                        <th>Campus Name</th>
			<th>Address</th>
			<th>Phone</th>
			<th>Fax</th>
			<th>Status</th>
			<th>Date</th>
			<th>Website</th>
			<th>Incharge</th>
                        <th>Action</th>
                        </tr>
            </thead>
            <tbody>
              <?php if( count($this->result) ): ?>
                    <?php foreach($this->result as $row){ ?>
                        <tr>
                        <td>
                        <?php
                        $this->uresult = $this->common_model-> get_listspfic1('org_profile','org_name','org_code',$row->org_code);
                         echo $this->uresult->org_name;?></td>
                        <td><?php echo $row->sc_name .", &nbsp;" . "&nbsp;(". $row->sc_code .", &nbsp; " . "&nbsp;". $row->sc_nickname .") "?></td>
                        <td><?php echo $row->sc_address . ", &nbsp;"." &nbsp;" . "&nbsp;". $this->common_model->get_listspfic1('cities','name','id',$row->sc_city)->name.","
                         . $row->sc_district .", &nbsp;". "&nbsp;". $this->common_model->get_listspfic1('states','name','id',$row->sc_state)->name. ", &nbsp;" ."&nbsp;". $this->common_model->get_listspfic1('countries','name','id',$row->sc_country)->name;?>
                      	<?php echo ",&nbsp;". $row->sc_pincode?></td>
                        <td><?php echo $row->sc_phone?></td>
                        <td><?php echo $row->sc_fax?></td>
                        <td><?php echo $row->sc_status?></td>
                        <td><?php echo $row->sc_startdate . "&nbsp;". "&nbsp;". $row->sc_closedate?></td>
                        <td><?php echo $row->sc_website?></td>
                        <td><?php echo $row->sc_incharge ." &nbsp;(M&nbsp;-&nbsp;". $row->sc_mobile.")"?></td>

                          
 <td> <?php // echo anchor ('setup/deletesc/' . $row->sc_id , "Delete",array('title' => 'Delete' , 'class' => 'red-link' ,'onclick' => "return confirm('Are you sure you want to delete this record')")); ?>&nbsp;
                            &nbsp;<?php  echo anchor ('setup/editsc/' . $row->sc_id , "Edit", array('title' => 'Edit Details' , 'class' => 'red-link')); ?></td>
                        </tr>
                    <?php }; ?>
                <?php else : ?>
                    <td colspan= "10" align="center"> No Records found...!</td>
                <?php endif;?>

            </tbody>
        </table></div>
    </body>
    <div align="center">  <?php $this->load->view('template/footer');?></div>
</html>


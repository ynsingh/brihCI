
<!--
    @author Manorama Pal(palseema30@gmail.com) pdf report
    @author Akash Rathi(akash92y@gmail.com) html part  

-->
<html>
    <head>
        <style>
        .TFtable {border:0px solid black;}
            
            tr { 
                border: solid;
                border-width: 1px 0;
            } 
        </style>    
    </head>

    <body>
<?php $this->load->view('template/pheader'); ?>        
<!--  <img src="uploads/logo/logotanuvas.jpeg" alt="logo" style= " width:100%;height:80px; margin-bottom:15px; " > <br/>-->
        
        <div class="scroller_sub_page">
           <table class="TFtable"width="100 %" border=1 frame=void rules=rows >
           <thead> 
           <!-- <div> -->
                <tr>
                   <!-- <th>Sr.No</th>-->
                    <th>SS</th> 
                    <th>P</th> 
                    <th>V </th> 
                    <th>Name of The Employee </th>
                    <th colspan=10>Designation</th>
                </tr>
           </thead> 
            
           <!-- </div> -->
          <tbody>
            <!--<div>  -->
                <?php $count = 0; 
		$ouoid = 0;
		$odid = 0;
		$oschid = 0;
		$nop=0;

                $ossid=0;
                //initilise the grand total, Uo total, Dept total
                $gtotss=0;              $gtotp=0;               $gtotv=0;
                $uototalss=0;           $uototalp=0;            $uototalv=0;
                $depttotalss=0;         $depttotalp=0;          $depttotalv=0;

                $i=0; $ii=0;

                $type_tnt=$tnttype;
                $ddropdept=$seldept;
               if( count($records) ):  ?>
                    <?php foreach($records as $record){
      		 if($ouoid !=$record->sp_uo){               
			if($ii>0){
                                        echo "<tr>";
                                        echo "<td>". $depttotalss . "</td>";
                                        echo "<td>". $depttotalp . "</td>";
                                        echo "<td>". $depttotalv . "</td>";
                                        echo "<td colspan=10 >";
                                        echo " <b> Department Total  ";
                                        echo "</b></td>";
                                        echo "</tr>";
                                        $depttotalss=0;         $depttotalp=0;          $depttotalv=0; $ii=0;
                                }

                               if($i>1){
                                        echo "<tr>";
                                        echo "<td>". $uototalss. "</td>";
                                        echo "<td>". $uototalp."</td>";
                                        echo "<td>".$uototalv. "</td>";
                                        echo "<td colspan=10 >";
                                        echo " <b> UO CONTROL Total  ";
                                        echo "</b></td>";
                                        echo "</tr>";
                                        $uototalss=0;           $uototalp=0;            $uototalv=0;
                                }

			echo "<tr>";
			echo "<td colspan=\"10\" style=\"text-align:center;\">";
			echo " <b> UO CONTROL : ";
			echo "&nbsp;&nbsp;";
			echo $this->lgnmodel->get_listspfic1('authorities','name','id' ,$record->sp_uo)->name;
                        echo "&nbsp;&nbsp;"."( ".$this->lgnmodel->get_listspfic1('authorities','code','id' ,$record->sp_uo)->code." )";
			echo "</b></td>";
			echo "</tr>";
			$ouoid =$record->sp_uo; 
		}
              
            if($odid !=$record->sp_dept){
				if($ii>0){
                                        echo "<tr>";
                                        echo "<td>". $depttotalss . "</td>";
                                        echo "<td>". $depttotalp . "</td>";
                                        echo "<td>". $depttotalv . "</td>";
                                        echo "<td colspan=10 >";
                                        echo " <b> Department Total  ";
                                        echo "</b></td>";
                                        echo "</tr>";
                                        $depttotalss=0;         $depttotalp=0;          $depttotalv=0;
                                }

                            echo "<tr><td colspan=10 align=left><b> Department : ";
                            echo "&nbsp;&nbsp;";
                            echo $this->commodel->get_listspfic1('Department','dept_code','dept_id',$record->sp_dept)->dept_code;
                            echo "<div style=\"text-align:center;\">";
                            echo "DEPT. OF ".strtoupper($this->commodel->get_listspfic1('Department','dept_name','dept_id',$record->sp_dept)->dept_name);
                            $orgcode=$this->commodel->get_listspfic1('Department','dept_orgcode','dept_id',$record->sp_dept)->dept_orgcode;
                            echo "<br>".strtoupper($this->commodel->get_listspfic1('study_center','sc_name','org_code',$orgcode)->sc_name)."</br>"; 
                            echo "</div>";
                            echo "</b></td></tr>";
				$odid =$record->sp_dept;
		}
      		 if($oschid !=$record->sp_schemecode){               
			echo "<tr>";
			echo "<td colspan=10 style=\"text-align:center\">";
			echo " <b> Scheme : ";
			echo "&nbsp;&nbsp;";
			echo strtoupper($this->sismodel->get_listspfic1('scheme_department','sd_name','sd_id',$record->sp_schemecode)->sd_name);
                        echo " ( ".$this->sismodel->get_listspfic1('scheme_department','sd_code','sd_id',$record->sp_schemecode)->sd_code ." )";
			echo "</b></td>";
			echo "</tr>";
			$oschid =$record->sp_schemecode;
		}
                            
                                
                               echo "<tr><td colspan=10 align=left><b> Name of The Post : ";
                                echo  $this->commodel->get_listspfic1('designation','desig_name','desig_id', $record->sp_emppost)->desig_name; 
                                echo "</b></td></tr>";
                                echo "<tr>";
				$sss=$record->sp_sancstrenght;
                        $sp=$record->sp_position;
                        $sv=$record->sp_vacant;
                        echo "<td>". $sss ." </td>";
                        echo "<td>". $sp."</td>";
                        echo "<td colspan=10>". $sv ."</td>";
                        $gtotss=$gtotss+$sss;                   $gtotp=$gtotp+$sp;                      $gtotv=$gtotv+$sv;
                        $uototalss=$uototalss+$sss;             $uototalp=$uototalp+$sp;                $uototalv=$uototalv+$sv;
                        $depttotalss=$depttotalss+$sss;         $depttotalp=$depttotalp+$sp;            $depttotalv=$depttotalv+$sv;
                        $i++;   $ii++;
                        
                                echo "</tr>";
                       
                                $this->emprec=$this->sismodel->emplist($record->sp_uo,$record->sp_dept,$record->sp_emppost,$record->sp_schemecode);      
                                foreach($this->emprec as $emp){
                                    echo "<tr>";
                                    echo "<td></td><td></td><td></td>";
                                    echo "<td>";
				echo anchor("report/viewfull_profile/{$emp->emp_id}",$emp->emp_name." ( "."PF No:".$emp->emp_code." )" ,array('title' => 'View Employee Profile' , 'class' => 'red-link'));
                                    echo "</td>";
                                    echo "<td>";
                                    //echo  $emp->emp_post;
                                   echo  $this->commodel->get_listspfic1('designation','desig_name','desig_id', $emp->emp_desig_code)->desig_name ;
                                    echo "</td>";
                                    echo "</tr>";
                        
                                }   
                                
                    ?>
                            
                       
             
          
                <?php }; ?>
                <?php else : ?>
                    <td colspan= "13" > No Records found...!</td>
                <?php endif;?> 
                 
		 <?php                echo "<tr>";
                                        echo "<td>". $depttotalss . "</td>";
                                        echo "<td>". $depttotalp . "</td>";
                                        echo "<td>". $depttotalv . "</td>";
                                        echo "<td colspan=10 >";
                                        echo " <b> Department Total  ";
                                        echo "</b></td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td>". $uototalss. "</td>";
                                        echo "<td>". $uototalp."</td>";
                                        echo "<td>".$uototalv. "</td>";
                                        echo "<td colspan=10 >";
                                        echo " <b> UO CONTROL Total  ";
                                        echo "</b></td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td>". $gtotss. "</td>";
                                        echo "<td>". $gtotp."</td>";
                                        echo "<td>".$gtotv. "</td>";
                                        echo "<td colspan=10 >";
                                        echo " <b> Grand Total  ";
                                        echo "</b></td>";
                                        echo "</tr>"; 
                  
               
        ?>
        
         </tbody>
        <!--</div> -->
       </table>
    </div>        
<?php //$this->load->view('template/footer'); ?>
<!--    <img src="uploads/logo/logo23.png" alt="logo" style= " width:100%;height:30px; margin-top:30px; " > <br/>-->
    </body>
</html>

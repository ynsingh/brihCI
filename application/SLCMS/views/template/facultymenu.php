<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

echo "<div>";
echo "<nav>";
echo "<ul class=\"sf-menu\">";

                        echo "<li class=\"current\">";
                                echo "<a href=" . site_url() ."/facultyhome> Dashboard</a>";
                        echo "</li>";
 echo "<li>";
                                echo "<a href=" . ">Subject</a>";
                                echo "<ul>";
                                        echo "<li>";
                                                echo anchor('SubprgList/subjectlist', 'Subject List with Program', array('title' => 'Subject List with Program'));
					echo "</li>";
					echo "<li>";
						echo anchor('welcome/work_underprocess', 'Choose Subject Paper for Academic year and Semester', array('title' => 'Choose Subject Paper for Academic year and Semester'));
                                                //echo anchor('', 'Choose Subject Paper for Academic year and Semester', array('title' => 'Choose Subject Paper for Academic year and Semester'));
                                        echo "</li>";
                                echo "</ul>";
		echo "</li>";

 echo "<li>";
                                echo "<a href=" . ">Student</a>";
                                echo "<ul>";
                                        echo "<li>";
                                                echo anchor('facultyhome/studentlist', 'Student List', array('title' => 'Student List'));
					echo "</li>";
					echo "<li>";
                                                echo anchor('facultyhome/student_attendence_view', 'Attendance', array('title' => 'Attendance'));
					echo "</li>";
					echo "<li>";
						echo anchor('Faculty/studentmarks', 'Marks', array('title' => 'Marks'));
                                        echo "</li>";
                                echo "</ul>";
			echo "</li>";
	echo "<li>";
                                echo "<a href=" . ">TimeTable</a>";
        echo "</li>";
          		echo "<li>";
                                echo "<a href=" . ">Profile</a>";
                                echo "<ul>";
                                        echo "<li>";
                                                echo anchor('profile/viewprofile', 'View Profile', array('title' => 'View Profile'));
                                         echo "</li>";
					echo "<li>";
                                                echo anchor('profile/changepasswd', 'Change Password', array('title' => 'Change Password'));
                                        echo "</li>";
					echo "<li>";
                                                echo anchor('home/logout', 'Logout', array('title' => 'Logout'));
                                        echo "</li>";
                                echo "</ul>";
                        echo "</li>";
			echo "<li>";
                                echo "<a href=" . ">Help</a>";
                        echo "<ul>";
                                        echo "<li>";
                                                echo anchor('help/helpdocfaculty', 'User Manual', array('title' => 'User Manual'));
                                        echo "</li>";
                                        echo "</ul>";
                              echo "</li>";
                        echo "<li>";
                        echo anchor('home/logout', 'Logout', array('title' => 'Logout'));
                        echo "</li>";
echo "</ul>";
echo "</nav>";
echo "</div>";

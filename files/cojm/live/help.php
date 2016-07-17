<?php 
$alpha_time = microtime(TRUE);
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "changejob.php";
?><!doctype html>
<html lang="en"><head><meta charset='utf-8'/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >

<?php
echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>';
?>

<script src="js/jquery.raptorize.1.0.js"></script>
<script type="text/javascript">	$(window).load(function() {	$('.button').raptorize();	});</script>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<script type="text/javascript"> $(function() { $("#tabs").tabs(); }); </script>
<title>COJM : Help</title></head><body><?php  
$filename="help.php";$adminmenu='1'; include "cojmmenu.php"; ?>
<div class="Post Spaceout"><div id="tabs">
<ul>
	<li><a href="#tabs-9">Changelog</a></li>	
	<li><a href="#tabs-1">Job Status</a></li>
	<li><a href="#tabs-2">Pricing / Services</a></li>
	<li><a href="#tabs-3"><?php echo $globalprefrow['glob5']; // rider  ?> Setup</a></li>		
	<li><a href="#tabs-4">GPS Tracking</a></li>
	<li><a href="#tabs-5">Client Login</a></li>	
	<li><a href="#tabs-6">Uploading POD</a></li>
	<li><a href="#tabs-7">Admin </a></li>
	<li><a href="#tabs-8">Errors</a></li>
	<li><a href="#tabs-10">About</a></li>
	<li><a href="#tabs-11">Favourites</a></li>
</ul>
<div id="tabs-9">
<ul class="help">		
<li> The <a href="http://voo2do.com/pub/cojm" target="_blank">current cojm to-do list</a> is hosted by voo2do, password : courier2011</li>
<li> If you think of anything whatsoever, contact us at cojm@cojm.co.uk , ideally with a screenshot or complete description of your suggested improvement!</li>
</ul>


<h4>13th July 2014</h4>
Fixed bug in uploading gpx files - cleans incoming timestamp <br />
Timestamps shown when cojm in debug mode <br />


<h4>8th June 2014</h4>
Moved P + L Income links to main search screen<br />
Added more options to main job search screen <br />
No longer compatible  Android 1.5<br />
Updated invoice PM10 totals for really small amounts<br />
Added JPG as POD, will save as jpg<br />
Added invoice email address when saved to database<br />
Added links to client / department in various pages<br />
Postcode space added in edit clients / rider pages<br />
Removed viewed icon on main job screen<br />
Added link to rider in various pages<br />
Added full address / just postcode / no address option to invoices<br />


<h4>31st May 2014</h4>

Updated Adding New Rider<br />
Unallocated Rider COJM and Public name option moved back to settings menu<br />
Tidied up SSL checking <br />
Fixed redirect from new favourite with same postcode <br />
remove sent / unpaid invoice statuses in main job screen order.php <br />


<h4>6th April 2014</h4>

Added css to bulkjobs.php <br />
Added GP% to P + L <br />
Various table css changes <br />


<h4>20th March 1.5.30</h4>
Increased memory limit and max execution time on uploaded GPX files
<br />


<h4>21st October 1.5.29</h4>
Moved date search to _GET ( address bar urls )
<br />


<h4>7th October 1.5.28</h4>
Fixed css error links not clickable on smaller screen size<br />
Invoice ref removed from job when invoice deleted, comments added to job log<br />


<h4>6th October 1.5.27</h4>
Various php debugging<br />

<h4>28th September 1.5.26</h4>
Added auto resize for job comments in job screen<br />
Added main error text to audit log<br />
Top search bar resizes when long text entered<br />

<h4>24th September 1.5.25</h4>
Removed check for invoices with payment date before invoice date so can process overpayments easier (eg Printbots)
<br />

<h4>23rd September 1.5.24</h4>
Added new system wide log page , System Log on Settings Menu <br />

<h4>21st September 1.5.23</h4>
Added new audit trail for jobs
<br />


<h4>18th September 1.5.22</h4>
Added reality check for imported gpx positions, also added speed and heading data from gpx import.
<br />

<h4>16th September 1.5.21</h4>
Added new style log text to individual job<br />

<h4>15th September 1.5.20</h4>
 Improved handling when job status reduced<br />
 Added overdue invoice reminder to bottom of create new job<br />
 More efficient css in new job screens<br />

<h4>14th September 1.5.19</h4>
No  more php errors in global invoice settings<br />

<h4>13th September 1.5.18</h4>
Added back to top button for long pages <br />


<h4>12th September 1.5.17</h4>
Rider birthday / start date javascript now fully compliant <br />
Added job audit database - much better job / system change history <br />

<h4>11th September 1.5.16</h4>
Tidied code for google map display in main job screen<br />

<h4>10th September 1.5.15</h4>
Tidied code for top menu, including form timeout code, css improvements<br />

<h4>9th September 1.5.14</h4>
Added server port and https info to system info <br />
Added delete job from mobile option (places in admin queue with comment)<br />


<h4>8th September 1.5.13</h4>
Department can be reselected in new job <br />
Added new style address defaults to new job departments<br />
Check boxes in new job displayed when no default service or service set to checkbox<br />
Optimised generated javascript in index and main job screen<br />
Updated change favourite address icons <br />
If favourite address has comments, icon will be displayed desktop / small text on mobile<br />


<h4>7th September 1.5.12</h4>
Added global variable filename for main javascript and css files<br />
Removed leading zeros in javascript date/times in order.php - no javascript errors :-) <br />

<h4>6th September 1.5.11</h4>
Improved new job screen layout<br />
Improved mobile top menu<br />


<h4>5th September 1.5.10</h4>
HTML characters in job comments should not crash invoice<br />
Page load Javascript speed improvements <br />
Added new way of defaults to new job client <br />


<h4>29th August - 1.5.9</h4>
Added option to detach POD from a job.<br />

<h4>26th August - ver 1.5.8</h4>
Added dropdown to select default pu / drop addresses from favourites for individual client<br />
Added dropdown to select default pu / drop addresses from favourites for departments<br />


<h4>19th August - ver 1.5.7.1</h4>
Admin displays if birthday today, along with day of birthday reminder (Mon-Sun), 14 days in advance.<br />

<h4>18th August - ver 1.5.7</h4>
New job client and department select auto highlighted on open.<br />
Edit Favourites added to waypoints<br />

<h4>17th August - ver 1.5.6</h4>
Removed department from invoice line if department selected via dropdown.
Department name shown in To Address at top of invoice.<br />

<h4>16th Aug - ver 1.5.5</h4>
http://www.checkmystatus.co.uk/ is the website for checking ecohosting current server status<br />

<h4>15th August - ver 1.5.5</h4>
Increased memory limit for uploading GPX files - now handles files tested up to 1.5Mb<br />

<h4>14th August - ver 1.5.4</h4>
Increased postcode search from tracking location to 150m from 50m<br />

<h4>August</h4>
Changed port check to ssl check after Control Server Change<br />

<h4>1st August</h4>
Added 1st via to email. <br />

<h4>31st July</h4>
Jobs still in admin queue / en-route not included in "Last Collected" table on main invoice screen.<br />

<h4>28th July</h4>
Added admin check to make sure jobs aren't allocated to a department from another client<br />
Added check for same free text details for new favourite address<br />

<h4>27th July</h4>
Moved invoice To address to left hand side, from on right hand side<br />
Invoice does not show mileage / ASAP rates if job is custom price<br />

<h4>25th July</h4>
Added department (if present) to Invoice filename<br />



<h4>24th July</h4>
Added extra detail to info message on adding client info without invoice details<br />
Changed invoice screens last invoiced date to first unvoiced job date  <br />
No dates in invoice search with client / all selected shows list of all unpaid invoices for client <br />


<h4>22nd July</h4>
Date search added download link to tracking file<br />




<h4>15th July</h4>
Fixed bug total volume Date Search
<br />


<h4>14th July</h4>
Added delete tracking positions function to GPX screen.
<br />

<h4>27th June</h4>

Bugfix to send email
<br />

<h4>15th June</h4>
Email sends to department email address if present, uses main company email if not.
<br />

<h4>13th June</h4>
Moved payment on PU and drop text to rhs from lhs in main job list<br />

<h4>12th June</h4>
Added fav addresses to Create new job, tested<br />


<h4>5th June</h4> Added tracking info and comments icons to date search<br />

<h4>1st June</h4> Bug fixed - date on uninvoiced clients<br />

<h4>15th May</h4> Added icons for fav addresses<br />



<h4>10th May</h4>
Fixed unable to create new job when editing client departments bug<br />
Capitalize postcode and auto space for UK postcodes when editing departments<br />
css tweak to prevent fouc on order.php<br />

<h4>9th May</h4>
Changed filename of downloaded tracking kml file to start with public job reference<br />
Added average spend per day to date search.<br />
<h4> 29th April</h4>

Improved Copy / Paste on date search <br />


<h4> 22nd April</h4>

New Client - Improved UI <br />

<h4> 13th April </h4>

New Job - Checkboxes working OK. <br />
New Job - Select Favourites <br />


<h4> 11th April </h4>

Fixed bug comments in last 100 closed, tidied table. <br />
Fixed invoice alternate colour row regression. <br />


<h4> 8th April </h4>

Fixed bug client discount custom price new job <br />


<h4> 17th March </h4>

Added search by department for date search<br />
Editing inactive rider brings back to same sub menu<br />



<h4> 16th March </h4>
In invoice preview only show debug text if checked in settings<br />
Invoice shows number of waypoints.<br />
Main job screen only shows 1 tracking point per minute - faster page rend for tracking<br />
Main job screen Added nearest postcode to tracking position<br />
Added search by department for invoice search<br />




<h4> 10th March </h4>
Added database for favourite addresses<br />

<h4> 26th Feb 2013</h4>
Uploaded latest version to Control

<h4> 25th Feb 2013</h4>
Started changing google map links from %20 to + for quicker page load <br />
Added database fields and changed client settings for client email options<br />
Added check when changing job to see if need to send job complete email (not written this code yet)<br />

<h4> 24th Feb 2013</h4>
Improved email text settings <br />
Added direct link to website reference in email<br />


<h4> 23rd Feb 2013</h4>
Updated manual email to html5, E_W fixes <br />
Changed email text 'Dear XXX' to Good morning / afternoon, dependent if AM / PM.<br />
Updated create new invoice to html5 <br />
Clients reference capitalized <br />


<h4> 21st Feb 2013</h4>
Improvement to cj.php E_W #4353 <br />
FOUC in main menu improvement <br />

<h4> 20th Feb 2013</h4>
Moved changelog from global settings to Help. <br />
Invalid HTML5 input red border. <br />
Added HTML5 compatibility for non HTML5 browsers (IE 9+). <br />
<strong>New Supported Browsers : IE9+, Firefox 4+, Safari 4.0+, iOS 5+(untested), Chrome 16+. Android Browser 2.1+(untested) 4.1 OK, OPERA Mobile (not mini)</strong><br />
<strong>Unsupported Browsers : IE8, Firefox 3, Safari 3, iOS 4+(untested), Chrome 9. Android Browser 1.5, OPERA Desktop , Opera Mini</strong><br />

<h4> 19th Feb 2013</h4>
Improved number items select in single job (html5 browsers only). <br />

<h4> 18th Feb 2013</h4>
Changed copy/paste invoice search to full date, changed styling, 1 line per invoice. <br />
Added due date to main invoice search. <br />
Added 2012 &amp; 2013 date search <br />
Added open link in new page icon <br />
Attempt to stop New job / via flash on page loads, . <br />

<h4> 14th Feb 2013</h4>
Fixed weird to free addresstext / mini spruce date search <br />
Added more of a gap before and after the gap on invoice search in copy/paste mode<br />

<h4> 13th feb 2013 </h4>
Added new field in global settings for error messages to be sent to (advanced settings)<br />
Added automatic email to be sent to error address to assist any instamapper setup issues<br />
Remove gps database from backups (to reduce backup filesize) <br />

<h4> 12th feb 2013 </h4>
Better character checks in freetext / private comments.<br />


<h4> 11th feb 2013 </h4>
Remedied error creating duplicate job.<br />



<h4> 10th feb 2013 </h4>
When job is invoiced, it is also pricelocked.<br />
All pages moved to utf-8 encoding to allow for full european text.<br />
Added changelog


<hr />


<?php


$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
$firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? true : false;
$safari = strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') ? true : false;
$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;

if ($chrome|$firefox) { echo '

<p><a href="#" class="button">Well done for looking at the updates, you deserve a dinosaur</a> :-) </p>
	'; }
	?>

</div>	

<div id="tabs-11">

<ul>
<li>If you edit a clients favourite then COJM will search for any UNCOMPLETED jobs with the OLD address and covert this to the new address.</li>
</ul>

</div>	
	
<div id="tabs-10">	
	
	COJM has been enhanced with help from the following pages :
	
	<br />
	http://afarkas.github.com/webshim/demos/index.html
	<br />
	http://robertnyman.com/2011/08/16/html5-forms-input-types-attributes-and-new-elements-demos-tips-and-tricks/
	<br />
	http://modernizr.com
	
</div>	
	
	
	
<div id="tabs-1">
	
	
<table class="ord"><tbody>
<tr><th>Status</th>	<th>Comments</th></tr>
<tr><td colspan="2"></td>

</tr><tr><td>Collection Scheduled </td><td></td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td> En-route to Collection</td><td></td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td> Onsite at Collection </td><td> Used to calculate if any waiting time needs to be added to the job.</td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td> Scheduled </td><td> Collected, however job has been paused (lunch break / hold until next day).<br />
In future, this will also be used to swap jobs to another <?php echo $globalprefrow['glob5']; ?>.
</td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td> Mail Batch in Progress </td><td> Not shown in non-licensed setting, jobs are not GPS tracked</td></tr>
<tr><td colspan="2"></td></tr>

<tr><td> En-route with POB </td><td> Causes a job with a status less than this to en-route.</td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td>Completed needs Admin</td><td>This is the highest status that a non-Admin user can take a job to.<br />
The job will appear in the main Admin menu job list, where it can be given a quick check by an Admin, along with attaching proof of delivery.
<br />Jobs at this stage will NOT be invoiced, they must first be complete.</td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td>Complete</td><td>Will be invoiced when drawn up with other jobs for the client and any departments.</td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td>Req's Invoicing </td><td>Use this option when you need to make a custom invoice for the client.<br />
The individual job will appear in an individual invoice queue in the main Admin screen.</td></tr>
<tr><td colspan="2"></td></tr>

<tr><td>Complete Invoice Sent</td><td>A job has been invoiced, awaiting payment</td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td>Complete Invoice Paid</td><td> No further work required</td></tr>
<tr><td colspan="2"></td></tr>
	
<tr><td>Requires Receipt</td><td>COJM does NOT currently do auto-receipting, so use this option when you need to make a custom receipt for the client. 
<br /> The individual job will appear in an individual receipt queue in the main Admin screen.</td></tr>
<tr><td colspan="2"></td></tr>
</tbody></table>
				
<p>				
Please note that these were the default status names which may have been changed in the settings menu.<br />
By changing the job status, this flags the job as been viewed and accepted. <br />
If the action has been performed by an admin, this can be reset by re-scheduling the courier.</p>


</div>	




<?php // advanced setup  ?>
<div id="tabs-2">


			
<ul class="help">
<li>There is no limit to the amount of services you can have.</li>
<ul class="helpsub">
<li>Only active services are shown in the dropdown menus.</li>
<li>The order number is which order they are listed on the services dropdown menus, so more popular services can be positioned towards the top of the list.</li>
<li>Recurring services will show an alert in the admin screen if there are less than 10 scheduled jobs of this service type.</li>
<li>Cargo bike services will show an icon in the job queue as a reminder that the job needs to be passed to this type of bike / highlight ASAP services.</li>
<li>Service comments are shown only in the main COJM individual job screen and <?php echo $globalprefrow['glob5']; ?> logins, ie entry code blue door 1234.</li>
</ul>
<div class="line"> </div>

<li>Distance Rate Jobs </li>
<ul class="helpsub">

<li>Numer of Items is used just as a confirmation of number of boxes / items in the shipment.</li>
<li>The order that price modifications are processed is defined by the Mileage rate order settings.</li>
<li>This order is also used to within the main job order screen to define which order to display the various fields.</li>
<li>First mile rate is charge to any mileage above 0, subsequent mileage rates are charged to the nearest 0.1 miles or km.</li>

</ul>
<div class="line"> </div>

<li>Non-Distance Rate Jobs</li>
<ul class="helpsub">

<li>Number of items used as a mutiplier to the base service cost.</li>
<li>This is to facilitate hourly rate jobs.</li>
<li>If carbon savings are entered for the service, these will be used along with the multiplier for the total job CO<sub>2</sub> and PM<sub>10</sub> savings.</li>

<li>Ofcom services are used to highlight reportable jobs for licensed mail operators and do not show tracking data (if ofcom is activated in main settings).</li>
<li>Subcon services can be highlighted as passed to a non-standard subcontractor.</li>

</ul>


</div>	







<div id="tabs-3">



<ul class="help">
<li>COJM Setup</li>
<ul class="helpsub">
<li>Click New <?php echo $globalprefrow['glob5']; ?></li>
<li>Posh Name - Viewable to clients</li>
<li>COJM Name - can be nickname</li>
<li>Is Active - Check to yes</li>
<li>Joomla User ID - Use the user ID created in the Joomla <?php echo $globalprefrow['glob5']; ?> setup</li>
<li>If tracking needed, enter their ID and entry string onto mobile device.</li>

<li>CHECK WORKING!</li>
</ul>
<div class="line"> </div>




<li> <?php echo $globalprefrow['glob5']; ?> COJM Login (not available on most demo sites)</li>

<ul class="helpsub">
<li> Log in to the <?php echo $globalprefrow['glob5']; ?> login Admin area, typically www.mycojmsite.co.uk/cyclist/administrator/ </li>
<li>  User Manager > New </li>
<li>Name : Actual Name ( for future identification of which user which )</li>
<li>Login Name : Their login user name </li>
<li>Password : of your choice</li>
<li>Confirm Password :Repeated</li>
<li>Email : Not really needed but lets them reset their own password</li>
<li>Receive system emails : Set to yes if proper email address</li>
<li>Block this User : No</li>
<li>Assigned User Groups : Ensure just Registered is checked</li>

<li>Leave options on right hand side as default</li>

<li>Save this, then note down the user ID of the created user, enter this ID to their COJM Setup Joomla ID</li>
<li>CHECK WORIKING !</li>

</ul>

</ul>
<br />
<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 1em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
			NB, Setting up <?php echo $globalprefrow['glob5']; ?> login is admittedly an awkward process and will be integrated into the main COJM screens soon.
</p>
			</div>
		</div>




</div>



<div id="tabs-4">
<ul class="help">


<li>Online Tracking</li>
<ul class="helpsub">
					<li> Simon's currently working on the Android App, however in the meantime you can still use live tracking data on jobs./li>
					<li>Install the OpenGPSTracker App</li>
					<li>In More > Settings > Sharing Settings</li>
					<li>Check Streaming Enabled</li>
					<li>Set minimum broadcast times to suit device (experiment)</li>
					<li>Check Stream to Custom Web Server</li>
					
					
					<li>See individual <?php echo $globalprefrow['glob5']; ?> details screen for the custom web server URL to input.</li>
					<li>On failure keep backlog of 200?</li>
						<li>CHECK IS WORKING!</li>
					</ul>




<div class="line"> </div>


<li>Offline Tracking</li>
<ul class="helpsub">
					<li> To save money on data charges, if you don't need the GPS tracking data to be live, you can run an application called OpenGPSTracker on a smartphone to obtain the tracking points.</li>
					<li>Start the tracking app, and start the track name with a name such as Steve Saturday 28th May.</li>
					<li>At the end of the day, stop the tracking app, and export this file to an SD card.</li>
					<li>Transfer the file to COJM Upload GPX, ensure you select the correct <?php echo $globalprefrow['glob5']; ?>, or preview the track!</li>
					</ul>
</ul>
</div>

<div id="tabs-5">
			
			
			
<ul class="help">



<li>Occasional Clients</li>

<ul class="helpsub">

<li> For occasional clients, it may be easier to pass them individual job referennces, rather than provide a full login.</li>
<li> Individual external job references can be copy / pasted from the main job screen.</li>
<li> There is also a view option in the date search screen to display external job links for clients</li>
<li> Jobs viewed without the client being logged in do not show job costs, or invoicing details.</li>


</ul>
<div class="line"> </div>

<li>Regular Users</li>
<ul class="helpsub"> 
<li>You can create 3 online login accounts per client.</li>
<li>Log into your website Joomla Administrator area ( eg www.mycojmtracking.co.uk/administrator/ )</li>
<li>Go to Site > User Manager > New</li>
<li>Name > ( Actual Name )</li>
<li>Username > ( Needs to be unique, their login ID, their email? )</li>
<li>E-mail ></li>
<li>New Password ( 8ish digits )</li>
<li>Confirm Password</li>
<li>Group > Registered or Client</li>
<li>Block User > No</li>
<li>Receive system emails > No ( if adding an admin for account for yourself check Yes ) </li>
<li>Click Save</li>
<li>In Joomla user manager, get the client user ID from column right hand side</li>
<li>Back in COJM, enter this ID into one of the Client Joomla user fields.</li>
<li>You can test their login by adding your own Joomla user ID, although remember to remove afterwards.</li>
<li>CHECK NOW !</li>

</ul>	

</ul>



<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
			NB, Setting up client website login will be integrated into the main COJM screens soon.
</p></div></div>


</div>

<div id="tabs-6">

<h4>COJM Upgrade Coming Soon, for now . . .</h4>

<ul class="help">

<li>Scan in POD Sheet, ideally as a JPG file.</li>
<li>Save files as their job references, eg 123456.jpg , 123457.jpg , 123458.jpg etc.</li>
<li>Open in favourite graphics editing program ( I use Irfanview - freeware )</li>

<li>Crop to individual POD</li>
<li>Resize image to 500px wide</li>
<li>Save new image to referencenumber.jpg , ie 123456.jpg , 123457.jpg </li>
<li>Log onto your FTP account ( ie ftp.catsvsdogs.co.uk )<p> - any probs with this, phone Steve at COJM</p></li>
<li>Go to the POD directory for the year and month of the delivery, eg ftp.catsvsdogs.co.uk/pod/2011/09 </li>
<li>Copy and paste your POD image file into this folder.</li>
<li>If the job is in the admin queue ( status req's admin ), then refresh this screen and on the job entry, the text should read jpg pod file located.</li>


<li>Click on attach files at the bottom of the table, and jpg POD Located will change to pod Located and Attached.</li>
<li>The POD is now visible in COJM to yourselves, clients, and the person undertaking the delivery.  Also linked from the PDF invoice.</li>
			
<li>Click on the job ref to open the job in a new window, may need Surname / times checking.</li>
<li>Now the image is attached in COJM it will be linked to in any invoices / emails etc.</li>

			
</ul>	
</div>

<div id="tabs-7">
			
			<h4> Apart from Jobs with status requiring admin, other checks undertaken include : </h4>
			
<ul class="help">

<li>Total number and cost (to client) of jobs awaiting invoicing</li>

<li>Jobs closed down by <?php echo $globalprefrow['glob5']; ?>, may need POD attaching</li>

<li>Jobs with status specifically requiring Invoicing</li>

<li>Jobs with status specifically requiring receipt</li>

<li>Jobs missing collection or delivery date</li>

<li>Expense references with no cost or date</li>

<li>If job has been paused, resumed date also present and in correct order</li>

<li>CO2 savings present on jobs which are not licensed, with both postcodes present, and ServiceID is not 5, 901, or 9999.</li>

<li>Upcoming <?php echo $globalprefrow['glob5']; ?> Birthday :-)</li>

<li>Recurring services with less than 10 jobs remaining</li>

<li>Inactive Clients do not have uninvoiced jobs</li>

<li>Jobs with Working Window Issues</li>

<li>Jobs with incorrect pause or resume tracking times</li>

<li>Jobs at incorrect status	</li>


<li>Service Types set to Zero		</li>

<li>Client set to Zero		</li>
			</ul>

</div>

<div id="tabs-8">


<ul class="help">			
<li>The large Google Map does not display
<ul class="helpsub">
<li>Clear your browser cache</li>
<li>Check on main job list that todays jobs have recognised postcodes</li>
</li>
</ul>


<div class="line"> </div>			
			
<li > COJM Error 1 (also big map) </li>
<ul class="helpsub">

<li>
Are there any <?php echo $globalprefrow['glob5']; ?> locations for today? </li>
<li>	Are there any jobs scheduled for today ( is it X-Mas Day, Sunday or Bank Holiday ? ) </li>
<li>
Change the current filename to wheris.php to check.
</li>
</ul>








<div class="line"> </div>
<li>
Unable to access COJM, even the Login Page
<ul class="helpsub">
<li>Are you able to access your main website? </li>
<li>
Generally if you are unable to access your main webpage then there is a problem at your web-hosts end, 
although there may be an small chance it's something to do with us if there's a database error message.
</li>
</ul>


<div class="line"> </div>
<li>Cannot connect to server: Can't connect to local MySQL server through socket '/var/lib/mysql/mysql.sock' (2) (2002) </li>

<ul class="helpsub">

<li>Socket error messages are problem for your webhosting company. </li>
<li> if persists for more than 5 mins email them, and also include us.</li>
<li>  If persists for more than 15 mins with no email reply, phone the webhosts.</li>
</ul>
<div class="line"> </div>
<li>Error 324 (net::ERR_EMPTY_RESPONSE): The server closed the connection without sending any data.</li>

<ul class="helpsub">
<li> Again, web host</li>

</ul>
<div class="line"> </div>

<li>Fatal error: Allowed memory size of xx bytes exhausted (tried to allocate xx bytes) </li>

<ul class="helpsub">
<li>Please let us know where the error ocurred, and what activity you were doing at the time.</li>
</ul>
</ul>
</div>








<div class="line"></div>
<br />
</div>	
</div>
<?php		
		echo '<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
});
</script>'; 

include 'footer.php';
  
?>	
	</body>
</html>
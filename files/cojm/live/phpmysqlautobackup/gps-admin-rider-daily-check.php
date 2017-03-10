<?php

/*
    COJM Courier Online Operations Management
	gps-admin-rider-daily-check.php - checks last 10 days GPS tracking, sees if any needs caching.
    Copyright (C) 2017 S.Young cojm.co.uk

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/


$infotext.= ' <br /> In gps-admin-rider-daily-check.php ln 2 <br />';

$checkloop='0';
$ridergpsdayloopa='-1';
$ridergpsdayloopb='0';

while ($checkloop<'10') {
    $checkloop++;
    
    ///  1 day ago
    $collecttime=mktime( 00, 00, 01, $nowmonth, $nowday+$ridergpsdayloopa, $nowyear );
    $delivertime=mktime( 00, 00, 00, $nowmonth, $nowday+$ridergpsdayloopb, $nowyear );
    $tcollecttime=date('H:i:s A D j M ', $collecttime);
    $tdelivertime=date('H:i:s A D j M ', $delivertime);
    $checkdate=date('Y-m-d', $collecttime);
    $infotext.= '<br /> '.$tcollecttime. ' '. $tdelivertime;
    
    $sql = "SELECT DISTINCT device_key FROM `instamapper` 
    WHERE `timestamp` >= ? AND `timestamp` <= ?
    ORDER BY `device_key` ASC"; 

    $stmt = $dbh->prepare($sql);
    $stmt->execute([$collecttime,$delivertime]);
    $data = $stmt->fetchAll();
    
    if ($data) {
        foreach ($data as $row) {
            $infotext.=' device key is '.$row['device_key'];
            $dev_key=$row['device_key'];
            
            $sql = "
            SELECT cojmadmin_id FROM cojm_admin 
            WHERE  cojm_admin_stillneeded='1' 
            AND cojmadmin_rider_gps='1' 
            AND cojmadmin_rider_id= ?
            AND cojm_admin_rider_date= ?
            ORDER BY cojmadmin_id ASC LIMIT 1  ";
            

            $stmt = $dbh->prepare($sql);
            $stmt->execute([$dev_key,$checkdate]);
            $gpsadminrow = $stmt->fetchColumn();
            
            
            if($gpsadminrow) {
                $infotext.=''.' job already outstanding on system ';
            }
            else { 
            
                $infotext.=' no job outstanding on system, ';
                $testfile="cache/jstrack/".date('Y/m', $collecttime).'/'.date('Y_m_d', $collecttime).'_'.$dev_key.'.js';
                $infotext.='<br />'.$shouldhavelastran6.'<br />';
                
                if (file_exists($testfile)) { 
                    $infotext.='  found in cache, ';
                }
                else {
                    $infotext.=' not found '. $testfile.' in cache, ';
                    
                    $sql="INSERT INTO cojm_admin 
                    (cojm_admin_stillneeded, cojmadmin_rider_gps, cojmadmin_rider_id, cojm_admin_rider_date) 
                    VALUES ('1', '1', ?, ? )   ";
                    
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute([$dev_key,$checkdate]);
                    
                    $infotext.='<p>Admin Task created.</p>'; 
    
                } // not found in cache
            } // ends no existing job in q
        } // ends rider check
    } // ends day check
    
    $ridergpsdayloopa--;
    $ridergpsdayloopb--;

} // ends day loop

?>
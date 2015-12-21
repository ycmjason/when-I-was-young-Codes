<?php

require( "psxlsgen.php" );

require( "db_sxlsgen.php" );

$cur_con = mysql_connect( "localhost:3306", "reader", "Fast78Slow" );

$myxls = new Db_SXlsGen;

$myxls->db_type = "mysql";

$myxls->db_name  =  "Unicef";

$myxls->db_con_id = $cur_con;

$myxls->get_type = 0;   

$myxls->filename = "donation_all";

$myxls->header = 0;

$myxls->db_close = 0;      // no close

$sqlstr = "SELECT recordcreated as created_date, amount, ".
	"title, concat( lastname, ' ', firstname ) as Donar_Name,  concat( ' ' ) as First_Name, mem_emailaddr as email, mem_telephone1 as tel_day, mem_telephone2 as tel_night, ".
	"mem_faxphone as fax_no, mem_nationalid as hkid, mem_companyname as company, p_donorid as donor_no, p_donation_target as sol, ".
	"cardType as bank_card_type, flat, bldg, ".
	"mem_address as addr, city, st, country, ".
	"source, deposit, gl, LIST_CODE, PKG_CODE, solic, tyletter, p_receipt as rc_flag ".
	"FROM payment_data2 ORDER BY payment_uid";

$myxls->GetXlsFromQuery( $sqlstr );

?>
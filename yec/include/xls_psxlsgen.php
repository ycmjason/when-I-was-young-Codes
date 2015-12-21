<?php
/****************************************************************
* Script         : PHP Simple Excel File Generator - Base Class
* Project        : PHP SimpleXlsGen
* Author         : Erol Ozcan <eozcan@superonline.com>
* Version        : 0.3
* Copyright      : GNU LGPL
* URL            : http://psxlsgen.sourceforge.net
* Last modified  : 13 Jun 2001
* Description     : This class is used to generate very simple
*   MS Excel file (xls) via PHP.
*   The generated xls file can be obtained by web as a stream
*   file or can be written under $default_dir path. This package
*   is also included mysql, pgsql, oci8 database interaction to
*   generate xls files.
*   Limitations:
*    - Max character size of a text(label) cell is 255
*    ( due to MS Excel 5.0 Binary File Format definition )
*
* Credits        : This class is based on Christian Novak's small
*    Excel library functions.
******************************************************************/

if( !defined( "PHP_SIMPLE_XLS_GEN" ) ) {
   define( "PHP_SIMPLE_XLS_GEN", 1 );

   class  PhpSimpleXlsGen {
      var  $class_ver = "0.3";    // class version
      var  $xls_data   = "";      // where generated xls be stored
      var  $default_dir = "";     // default directory to be saved file
      var  $filename  = "psxlsgen";       // save filename
      var  $fname    = "";        // filename with full path
      var  $crow     = 0;         // current row number
      var  $ccol     = 0;         // current column number
      var  $totalcol = 0;         // total number of columns
      var  $get_type = 0;         // 0=stream, 1=file
      var  $errno    = 0;         // 0=no error
      var  $error    = "";        // error string
      var  $dirsep   = "/";       // directory separator
      var  $header   = 1;         // 0=no header, 1=header line for xls table

     // Default constructor
     function  PhpSimpleXlsGen()
     {
       $os = getenv( "OS" );
       $temp = getenv( "TEMP");
       // check OS and set proper values for some vars.
       if ( stristr( $os, "Windows" ) ) {
          $this->default_dir = $temp;
          $this->dirsep = "\\";
       } else {
         // assume that is Unix/Linux
         $this->default_dir = "/tmp";
         $this->dirsep =  "/";
       }
       // begin of the excel file header
       $this->xls_data = pack( "ssssss", 0x809, 0x08, 0x00,0x10, 0x0, 0x0 );
       // check header text
       if ( $this->header ) {
         $this->Header();
       }
     }

     function Header( $text="" ) {
//        if ( $text == "" ) {
//           $text = "This file was generated  at ".date("D, d M Y H:i:s T");
//        }
        if ( $this->totalcol < 1 ) {
          $this->totalcol = 1;
        }
        $this->InsertText( $text );
//        $this->crow += 2;
        $this->crow = 0;
        $this->ccol = 0;
     }

     // end of the excel file
     function End()
     {
       $this->xls_data .= pack("sssssssC", 0x7D, 11, 3, 4, 25600,0,0,0);
       $this->xls_data .= pack( "ss", 0x0A, 0x00 );
       return;
     }

// write a Number (double) into row, col 
function WriteNumber_pos( $row, $col, $value ) 
{ 
global $encryption_key;
$key=$encryption_key;
$value=(substr($value,-1)=="=")?rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($key))), "\0"):$value;
$this->xls_data .= pack( "sssss", 0x0203, 14, $row, $col, 0x00 ); 
$this->xls_data .= pack( "d", $value ); 
return; 
} 

// write a label (text) into Row, Col 
function WriteText_pos( $row, $col, $value ) 
{ 
global $encryption_key;
$key=$encryption_key;
$value=(substr($value,-1)=="=")?rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($key))), "\0"):$value;
$len = strlen( $value ); 
$this->xls_data .= pack( "s*", 0x0204, 8 + $len, $row, $col, 0x00, $len ); 
$this->xls_data .= $value; 
return; 
} 

     // insert a number, increment row,col automatically
     function InsertNumber( $value )
     {global $encryption_key;
$key=$encryption_key;
$value=(substr($value,-1)=="=")?rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($key))), "\0"):$value;
        if ( $this->ccol == $this->totalcol ) {
           $this->ccol = 0;
           $this->crow++;
        }
        $this->WriteNumber_pos( $this->crow, $this->ccol, $value );
        $this->ccol++;
        return;
     }
	 

     // insert a number, increment row,col automatically
     function InsertText( $value )
     {global $encryption_key;
$key=$encryption_key;
$value=(substr($value,-1)=="=")?rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($key))), "\0"):$value;
        if ( $this->ccol == $this->totalcol ) {
           $this->ccol = 0;
           $this->crow++;
        }
        $this->WriteText_pos( $this->crow, $this->ccol, $value );
        $this->ccol++;
        return;
     }

     // Change position of row,col
     function ChangePos( $newrow, $newcol )
     {
        $this->crow = $newrow;
        $this->ccol = $newcol;
        return;
     }

     // new line
     function NewLine()
     {
        $this->ccol = 0;
        $this->crow++;
        return;
     }

     // send generated xls as stream file
//     function SendFile( $filename )
//     {
//        $this->filename = $filename;
//        $this->SendFile();
//     }
     // send generated xls as stream file
     function SendFile()
     {
        $this->End();
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/x-msexcel;" );
        header ( "Content-Disposition: attachment; filename=$this->filename.xls" );
        print $this->xls_data;
     }

     // change the default saving directory
     function ChangeDefaultDir( $newdir )
     {
       $this->default_dir = $newdir;
       return;
     }

     // Save generated xls file
//     function SaveFile( $filename )
//     {
//        $this->filename = $filename;
//        $this->SaveFile();
//     }

     // Save generated xls file
     function SaveFile()
     {
        $this->End();
        $this->fname = $this->default_dir."$this->dirsep".$this->filename;
        if ( !stristr( $this->fname, ".xls" ) ) {
          $this->fname .= ".xls";
        }
        $fp = fopen( $this->fname, "wb" );
        fwrite( $fp, $this->xls_data );
        fclose( $fp );
        return;
     }

     // Get generated xls as specified type
     function GetXls( $type = 0 ) {
         if ( !$type && !$this->get_type ) {
            $this->SendFile();
         } else {
            $this->SaveFile();
         }
     }
   } // end of the class PHP_SIMPLE_XLS_GEN
}

if( !defined( "DB_SIMPLE_XLS_GEN" ) ) {
   define( "DB_SIMPLE_XLS_GEN", 1 );

   Class Db_SXlsGen extends PhpSimpleXlsGen {
      var $db_class_ver = "0.3";
      var $db_host     = "localhost";
      var $db_user     = "mysql";
      var $db_passwd   = "";
      var $db_name     = "mysql";
      var $db_type     = "mysql";
      var $db_con_id   = "";
      var $db_query    = "";
      var $db_stmt     = "";
      var $db_ncols    = 0;
      var $db_nrows    = 0;
      var $db_fetchrow = array();
      var $col_aliases = array();
      var $db_close    = 1;      // 0 = no close db connection after query fetched, 1 = close it


      // default constructor
      function CDb_SXlsGen()
      {
         $this->PhpSimpleXlsGen();
      }

      // insert column names with checking their aliases
      function InsertColNames( $cmd_colname )
      {
         $this->totalcol = $this->db_ncols;
         for( $i = 0; $i < $this->db_ncols; $i++ ) {
            // variable function is used
            $col = $cmd_colname( $this->db_stmt, $i );
            if ( $this->col_aliases["$col"] != "" ) {
               $colname = $this->col_aliases[$col];
            } else {
               $colname = $col;
            }
            $this->InsertText( $colname );
         }
      }

      // insert rows result of query
      function InsertRows( $cmd_rowfetch )
      {
         $row = array();
         for( $i = 0; $i < $this->db_nrows; $i++ ) {
           if ( $this->db_type == "pgsql" ) {
              $row = $cmd_rowfetch( $this->db_stmt, $i );
           } else {
              $row = $cmd_rowfetch( $this->db_stmt );
           }
           for ( $j = 0; $j < $this->db_ncols; $j++ ) {
              $this->InsertText( $row[$j] );
           }
         }
      }

      function FetchData()
      {
         switch ( $this->db_type ) {
            case "mysql":
                  if ( $this->db_con_id == "" ) {
                    $this->db_con_id = mysql_connect( $this->db_host, $this->db_user, $this->db_passwd );
                  }
                  $this->db_stmt = mysql_db_query( $this->db_name, $this->db_query, $this->db_con_id );
                  $this->db_ncols = mysql_num_fields( $this->db_stmt );
                  $this->InsertColNames( "mysql_field_name" );
                  $this->db_nrows = mysql_num_rows( $this->db_stmt );
                  $this->InsertRows( "mysql_fetch_array" );
                  mysql_free_result ( $this->db_stmt );
                  if ( $this->db_close ) {
                    mysql_close( $this->db_con_id );
                  }
                  break;

            case "pgsql":
                  if ( $this->db_con_id == "" ) {
                     $this->db_con_id = pg_connect( "host=".$this->db_host." dbname=".$this->db_name." user=".$this->db_user." password=".$this->db_passwd );
                  }
                  $this->db_stmt = pg_exec( $this->db_con_id, $this->db_query );
                  $this->db_ncols = pg_numfields( $this->db_stmt );
                  $this->InsertColNames( "pg_fieldname" );
                  $this->db_nrows = pg_numrows( $this->db_stmt );
                  $this->InsertRows( "pg_fetch_row" );
                  pg_freeresult( $this->db_stmt );
                  if ( $this->db_close ) {
                    pg_close( $this->db_con_id );
                  }
                  break;

            case "oci8":
                  if ( $this->db_con_id == "" ) {
                     $this->db_con_id = OCILogon( $this->db_user, $this->db_passwd, $this->db_name );
                  }
                  $this->db_stmt = OCIParse( $this->db_con_id, $this->db_query );
                  OCIExecute( $this->db_stmt );
                  $this->db_ncols = OCINumCols( $this->db_stmt );
                  // fetching column names and rows are differents in OCI8.
                  $tmparr = array();
                  $this->db_nrows = OCIFetchStatement( $this->db_stmt, $results );
                  $this->totalcol = $this->db_ncols;
                  while ( list($key, $val ) = each( $results ) ) {
                     if ( $this->col_aliases[$key] != "" ) {
                       $colname = $this->col_aliases[$key];
                     } else {
                       $colname = $key;
                     }
                     $this->InsertText( $colname );
                  }
                  for ( $i = 0; $i < $this->db_nrows; $i++ ) {
                     reset( $results );
                     while ( $column = each( $results ) ) {
                        $data = $column['value'];
                        $this->InsertText( $data[$i] );
                     }
                  }
                  OCIFreeStatement( $this->db_stmt );
                  if ( $this->db_close ) {
                     OCILogoff( $this->db_con_id );
                  }
                  break;

            case "odbc":
                  if ( $this->db_con_id == "" ) {
                     $this->db_con_id = odbc_connect( $this->db_host, $this->db_user, $this->db_passwd );
                  }
                  $this->db_stmt = odbc_exec( $this->db_con_id, $this->db_query );
                  $this->db_ncols = odbc_num_fields( $this->db_stmt );
                  $this->totalcol = $this->db_ncols;
                  for( $i = 1; $i <= $this->db_ncols; $i++ ) {
                     $col = odbc_field_name( $this->db_stmt, $i );
                     if ( $this->col_aliases["$col"] != "" ) {
                        $colname = $this->col_aliases[$col];
                     } else {
                        $colname = $col;
                     }
                     $this->InsertText( $colname );
                  }
                  $this->db_nrows = odbc_num_rows( $this->db_stmt );
                  for ( $i = 1; $i <= $this->db_nrows; $i++ ) {
                     odbc_fetch_row( $this->db_stmt, $i );
                     for ( $j = 1; $j <= $this->db_ncols; $j++ ) {
                        $colval = odbc_result( $this->db_stmt, $j );
                        $this->InsertText( $colval );
                     }
                  }
                  //odbc_freeresult( $this->db_stmt );
                  if ( $this->db_close ) {
                    odbc_close( $this->db_con_id );
                  }
                  break;

            default:
                  print "<b>Sorry, currently \"$this->db_type\" db is not supported!</b>";
                  exit();
                  break;
         }
      }

      function GetXlsFromQuery( $query )
      {
           $this->db_query = $query;
           $this->FetchData();
           $this->GetXls();
      }
   } // end of class CDb_SXlsGen
}
// end of ifdef PHP_SIMPLE_XLS_GEN

<?php




/***************************CONNECTION DETAILS**************************/
//your server host name
define("HOST", "localhost");



//username of MySQL database
define("USER", "root");



//password of MySQL database
define("PASSWORD", "root");



//choose your database
define("DB", "iiitdmstudentsportal");
/***************************CONNECTION DETAILS**************************/






/***************************COMMON PARAMETERS**************************/
//defining the default log file name
define("LOG_FILE", "./error.log");



//set the default time zone
define("TIME_ZONE", "Asia/Kolkata");
date_default_timezone_set(TIME_ZONE);



//defining the default log file name
define("C_TIME", time());


//Salt for SESSION and USER Hashing
define("SALT",211019931500);


//Salt for SESSION 2 Hashing
define("SALT2",9876501234);

//Salt for POST&COMMENTS HASHING
define("POCHASH", "tlastsop21");

//Salt for Comments
define("COMHASH", "21HCAOSMHents");

//salt for POLLS&EVENTS
define("POEVHASH", "sloppnstneve21");

/***************************COMMON PARAMETERS**************************/






?>

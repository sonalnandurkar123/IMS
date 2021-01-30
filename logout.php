<?php  
session_start(); 
session_unset(); 
session_destroy();  
header("Location: login");//use for the redirection to some page  
?>
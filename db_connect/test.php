<? 
error_reporting(E_ALL);
$tcs = new DOTNET('Connection_String, Version=1.0.0.0, Culture=neutral, PublicKeyToken=9d4d8c43253f345e', "Connection_String.Test_Conn");
$source = $tcs->Get_Centra_Conn_Ora();
print_r($source);
die();
?>
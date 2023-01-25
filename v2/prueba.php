<?php  
// $s = '96529EFFF9E21FFB30818C7C7CCDE50462123C5129FB4AA4E668B350C10CB961';
// $signature = base64_encode(hash("sha256", $s, True)); 
// print  $signature; // 0QtyIu4B+lU+TLqM/zfJz5ULVpyXgfLRs5mKXCQvbHM=

$homepage = file_get_contents('/home/bygsdybfp6u6/public_html/apiTSv4/v1/class/files/2022/12/07/pruebaversion3.pdf');
// $byteArr = str_split($homepage);
// foreach ($byteArr as $key=>$val) {
//   echo $byteArr[$key] = ord($val); 
// }
// echo $homepage;

// $fichero = '/home/bygsdybfp6u6/public_html/apiTSv4/v1/class/files/2022/12/07/pruebaversion3.pdf';
//  $hash = hash_file('sha256', $fichero);
//  $file = file_get_contents('/home/bygsdybfp6u6/public_html/apiTSv4/v1/class/files/2022/12/07/pruebaversion3.pdf');
//  $database64 = base64_encode($hash);
//  echo $database64;


// print base64_encode(hash("sha256",mb_convert_encoding("abcdefg","UTF-16LE"),true));

 $hex = "96529EFFF9E21FFB30818C7C7CCDE50462123C5129FB4AA4E668B350C10CB961";
 $string = hex2bin($hex);
 $arraybytes = array();

 $byteLen = strlen($string);
for($i=0; $i < $byteLen; $i++){
  $byte = $string[$i];
  // echo ord($byte).",";
  
  array_push($arraybytes, ord($byte));
}

$packed = pack("c*", ...$arraybytes);
$database64 = base64_encode($packed);
echo $database64;
?>
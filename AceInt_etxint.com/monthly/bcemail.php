<pre>
<?

 $Lines = file("Query20.txt");
 $Count = 1;

 foreach($Lines as $Email) {

  if(!strpos($Email, "\"")) {

   if(!strpos($Email, " ")) {

    print trim($Email).",";

    if($Count % 99 == 0) {

     print "\r\n\r\n";

    }

   }

  }

  $Count++;

 }

?>
</pre>

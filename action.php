<html>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML' async></script>

<?php

 $vars = $_GET["vars"];
 $polys = $_GET["polys"];
 $ord = $_GET["ord"];
 $rcp = $_GET["rcp"];
 $hide = $_GET["hide"];
 $terms = $_GET["terms"];

 $polysA = explode("\n", $polys);

 $code = 'LIB "teachstd.lib";' . "\n" . 'LIB "latex.lib";' . "\n";
 $code .= 'ring r = 0, (' . $vars . '), ' . $ord . ';' . "\n";

 $inputcode = 'list input = ';
 for ($i = 0; $i < count($polysA); $i++) {
     $code .= 'poly p' . ($i+1) . ' = ' . rtrim($polysA[$i]) . ';' . "\n";
     if ($i > 0)
         $inputcode .= ', ';
     $inputcode .= 'p' . ($i+1);
     }
 $inputcode .= ';' . "\n";
 $code .= $inputcode;

 $code .= 'int hidechains = ';
 if ($hide == 'yes')
     $code .= '1';
 else
     $code .= '0';
 $code .= ';' . "\n";

 $code .= 'int rcp = ';
 if ($rcp == 'yes')
     $code .= '1';
 else
     $code .= '0';
 $code .= ';' . "\n";

 $code .= 'int TeXwidth = ';
 $code .= $terms . ';' . "\n";

 $code .= file_get_contents("buchberger2-singular.txt");

 $tmp_singularcode_file = tmpfile();
 $tmp_singularcode_path = stream_get_meta_data($tmp_singularcode_file)['uri'];
 fwrite($tmp_singularcode_file, $code);
 $commandline = "Singular -q -b < " . $tmp_singularcode_path . " 2>&1";
 exec($commandline, $output, $retval);
 fclose($tmp_singularcode_file);

 echo "<body><div>\n";
 for ($i=0; $i<count($output); $i++) {
     echo $output[$i];
     }
 echo "</div></body>";
 
?>

</html>
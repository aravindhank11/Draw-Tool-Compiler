<?php
  session_start();
  if(isset($_POST['submit']))
  {
    //Getting User Input
    $codeByUser=$_POST['code'];
    $codeArray=explode("\n",$codeByUser);

    //Variable Declaration
    $lex_array=array(); //token 2D array => Tokens for every word of everyline
    $arrayCounter=0; // To count the number of lines in the code
    $word=''; // Will hold words
    $quoteStart=0; // 0 when there is no unmatched quote and 1 if there is an unmatched quote

    //Looping and making Tokens
    foreach($codeArray as $code)
    {

      $code=preg_replace('/\s+/',' ',$code); //To avoid multiple spacing
      $limit = strlen($code); //Number of characeters in a line of code
      $i=0; // To count the number of words in a line
      if($limit>1)
      {
        $lex_array[$arrayCounter]=array();
        if(preg_match('/\s/',$code)==0)
        {
          if($code=='//')
            array_push($lex_array[$arrayCounter],array("Type"=>"Comment","Value"=>"//"));
          else
            array_push($lex_array[$arrayCounter],array("Type"=>"Word","Value"=>$code));
        }
        else
        {
          while($i<$limit)
          {
            //For a word without any unmatched quote before it
            if($code[$i]!="'" && $code[$i]!=' ' && $quoteStart==0)
              $word = $word.$code[$i];
            //For a word with unmatched quote before it
            else if($code[$i]!="'" && $quoteStart==1)
              $word = $word.$code[$i];
            //Array push of a word with no unmatched quote before it
            else if($code[$i]==' ' && $quoteStart==0 && $word!='')
            {
              if($word=='//')
                array_push($lex_array[$arrayCounter],array("Type"=>"Comment","Value"=>"//"));
              else if($word!='')
                array_push($lex_array[$arrayCounter],array("Type"=>"Word","Value"=>$word));
              $word='';
            }
            //For first match of an unmatched quote
            else if($code[$i]=="'" && $quoteStart==0)
            {
              $quoteStart=1;
              $word = $word.$code[$i];
            }
            //Array push of a word with unmatched quote before it
            else if($code[$i]=="'" && $quoteStart==1)
            {
              $quoteStart=0;
              $word = $word.$code[$i];
              if($word!='')
                array_push($lex_array[$arrayCounter],array("Type"=>"Word","Value"=>$word));
              $word='';
            }
            $i=$i+1;
          }
          if($word!='')
            array_push($lex_array[$arrayCounter],array("Type"=>"Word","Value"=>$word));
        }
      }
      $arrayCounter++;
    }

    //echo "<pre>";
    //print_r($lex_array);
    //print_r($codeArray);
    //echo sizeof($codeArray[2]);
    $_SESSION['lex_array']=$lex_array;
    header('Location:syntactical.php');
  }
?>

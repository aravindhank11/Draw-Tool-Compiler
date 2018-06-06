<?php
  session_start();
  //From lexical.php
  $lex_array=$_SESSION['lex_array'];

  //Variable Initialisation
  $counter=0;
  $linesOfCode=0;
  $syntacticalTree=array();
  $error=array();
  //echo "<pre>";
  //print_r($lex_array);

  //Running through the lex_array
  while($counter<sizeof($lex_array))
  {
    //Ignoring the entire comment section
    if($lex_array[$counter][0]["Type"]=="Comment")
      $counter++;

    //GRAPH SYNTAX
    //graph name='GRAPH NAME'
    else if($lex_array[$counter][0]["Value"]=="graph")
    {
      //Checking if GRAPH is the first line of code
      if($linesOfCode==0)
      {
        //Checking number of arguments
        if(sizeof($lex_array[$counter])!=2)
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Lesser number of arguments for GRAPH.\nSugested Syntax:graph name='GRAPH NAME'\n";
          $counter++;
          continue;
        }
        else
        {
          //To check name
          $nameMatch="/name='[A-Za-z0-9,_\-\/ ]+'/";
          if(!preg_match($nameMatch,$lex_array[$counter][1]["Value"]))
          {
            $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for NAME in GRAPH.\nSugested Syntax:graph name='GRAPH NAME'\n";
            $counter++;
            continue;
          }

          //Exploding to get graph name
          $name=explode('=',$lex_array[$counter][1]["Value"]);
          $graphName=substr($name[1],0);

          //If there is no continue forced then the parameters are added to the tree
          array_push($syntacticalTree,array("title"=>$graphName));
          $linesOfCode++;
          $counter++;
        }
      }
      else
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": First line of the code must start with the GRAPH syntax.\nSugested Syntax:graph name='GRAPH NAME'\n";
        $counter++;
        continue;
      }
    }

    //PLOT FUNCTION
    //function f(x)='FUNCTION IN TERMS OF x' color='COLOR'
    else if($lex_array[$counter][0]["Value"]=="function")
    {
      $linesOfCode++;
      //Checking number of arguments
      if(sizeof($lex_array[$counter])!=3)
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": Lesser number of arguments for FUNCTION.\nSugested Syntax:function f(x)='FUNCTION IN TERMS OF x' color='COLOR'\n";
        $counter++;
        continue;
      }
      else
      {
        //To check function name
        $functionMatch="/f\(x\)='.+'/";
        if(!preg_match($functionMatch,$lex_array[$counter][1]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for FUNCTION NAME.\nSugested Syntax:function f(x)='FUNCTION IN TERMS OF x' color='COLOR'\n";
          $counter++;
          continue;
        }

        //To check color
        $colorMatch="/color='.+'/";
        if(!preg_match($colorMatch,$lex_array[$counter][2]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for COLOR.\nSugested Syntax:function f(x)='FUNCTION IN TERMS OF x' color='COLOR'\n";
          $counter++;
          continue;
        }


        //function f(x)='FUNCTION IN TERMS OF x' color='COLOR' range=[x1,x2](optional)
        //Exploding array to get function
        $function=explode('=',$lex_array[$counter][1]["Value"]);


        //Exploding array to get color
        $color=explode('=',$lex_array[$counter][2]["Value"]);

        //If there is no continue forced then the parameters are added to the tree
        array_push($syntacticalTree,array("fn"=>$function[1],"color"=>$color[1]));
        $linesOfCode++;
        $counter++;
      }
    }

    //implicit-function f(x,y)='FUNCTION IN TERMS OF x AND y' color='COLOR'
    else if($lex_array[$counter][0]["Value"]=="implicit-function")
    {
      $linesOfCode++;
      //Checking number of arguments
      if(sizeof($lex_array[$counter])!=3)
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": Lesser number of arguments for IMPLICIT-FUNCTION.\nSugested Syntax:implicit-function f(x,y)='FUNCTION IN TERMS OF x AND y' color='COLOR'\n";
        $counter++;
        continue;
      }
      else
      {
        //To check function name
        $functionMatch="/f\(x,y\)='.+'/";
        if(!preg_match($functionMatch,$lex_array[$counter][1]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for FUNCTION NAME.\nSugested Syntax:implicit-function f(x,y)='FUNCTION IN TERMS OF x AND y' color='COLOR'\n";
          $counter++;
          continue;
        }

        //To check color
        $colorMatch="/color='.+'/";
        if(!preg_match($colorMatch,$lex_array[$counter][2]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for COLOR.\nSugested Syntax:implicit-function f(x,y)='FUNCTION IN TERMS OF x AND y' color='COLOR'\n";
          $counter++;
          continue;
        }

        //Exploding array to get function
        $function=explode('=',$lex_array[$counter][1]["Value"]);
        //Exploding array to get color
        $color=explode('=',$lex_array[$counter][2]["Value"]);
        //If there is no continue forced then the parameters are added to the tree
        array_push($syntacticalTree,array("fn"=>$function[1],"color"=>$color[1],"fnType"=>"'implicit'"));
        $linesOfCode++;
        $counter++;
      }
    }

    //parametric-function x='FUNCTION IN TERMS OF t' y='FUNCTION IN TERMS OF t' color='COLOR'
    else if($lex_array[$counter][0]["Value"]=="parametric-function")
    {
      $linesOfCode++;
      //Checking number of arguments
      if(sizeof($lex_array[$counter])!=4 && sizeof($lex_array[$counter])!=5)
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": Lesser number of arguments for PARAMETRIC-FUNCTION.\nSugested Syntax:parametric-function x='FUNCTION IN TERMS OF t' y='FUNCTION IN TERMS OF t' color='COLOR'\n";
        $counter++;
        continue;
      }
      else
      {
        //To check function name
        $xMatch="/x='.+'/";
        if(!preg_match($xMatch,$lex_array[$counter][1]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for x-FUNCTION.\nSugested Syntax: parametric-function x='FUNCTION IN TERMS OF t' y='FUNCTION IN TERMS OF t' color='COLOR'\n";
          $counter++;
          continue;
        }

        $yMatch="/y='.+'/";
        if(!preg_match($yMatch,$lex_array[$counter][2]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for y-FUNCTION.\nSugested Syntax: parametric-function x='FUNCTION IN TERMS OF t' y='FUNCTION IN TERMS OF t' color='COLOR'\n";
          $counter++;
          continue;
        }
        //To check color
        $colorMatch="/color='.+'/";
        if(!preg_match($colorMatch,$lex_array[$counter][3]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for COLOR.\nSugested Syntax: parametric-function x='FUNCTION IN TERMS OF t' y='FUNCTION IN TERMS OF t' color='COLOR'\n";
          $counter++;
          continue;
        }

        //Exploding array to get function
        $xfunction=explode('=',$lex_array[$counter][1]["Value"]);

        $yfunction=explode('=',$lex_array[$counter][2]["Value"]);

        //Exploding array to get color
        $color=explode('=',$lex_array[$counter][3]["Value"]);


        //If there is no continue forced then the parameters are added to the tree
        array_push($syntacticalTree,array("x"=>$xfunction[1],"y"=>$yfunction[1],"color"=>$color[1],"fnType"=>"'parametric'","graphType"=>"'polyline'"));
        $counter++;
        $linesOfCode++;
      }
    }
    //PLOT DERIVATIVE TO FUNCTION
    //derivative-to-function f(x)='FUNCTION IN TERMS OF x' color='COLOR' df(x)='DERIVATIVE FUNCTION' d-color='COLOR'
    else if($lex_array[$counter][0]["Value"]=="derivative-to-function")
    {
      $linesOfCode++;
      //Checking number of arguments
      if(sizeof($lex_array[$counter])!=4)
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": Lesser number of arguments for DERIVATIVE-TO-FUNCTION.\nSugested Syntax: derivative-to-function f(x)='FUNCTION IN TERMS OF x' color='COLOR' df(x)='DERIVATIVE FUNCTION'\n";
        $counter++;
        continue;
      }
      else
      {
        //To check function name
        $functionMatch="/f\(x\)='.+'/";
        if(!preg_match($functionMatch,$lex_array[$counter][1]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for FUNCTION NAME.\nSugested Syntax: derivative-to-function f(x)='FUNCTION IN TERMS OF x' color='COLOR' df(x)='DERIVATIVE FUNCTION'\n";
          $counter++;
          continue;
        }

        //To check color
        $colorMatch="/color='.+'/";
        if(!preg_match($colorMatch,$lex_array[$counter][2]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for COLOR.\nSugested Syntax: derivative-to-function f(x)='FUNCTION IN TERMS OF x' color='COLOR' df(x)='DERIVATIVE FUNCTION'\n";
          $counter++;
          continue;
        }

        //To check derivaitve function name
        $dFunctionMatch="/df\(x\)='.+'/";
        if(!preg_match($dFunctionMatch,$lex_array[$counter][3]["Value"]))
        {
          $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for DERIVATIVE FUNCTION NAME.\nSugested Syntax: derivative-to-function f(x)='FUNCTION IN TERMS OF x' color='COLOR' df(x)='DERIVATIVE FUNCTION'\n";
          $counter++;
          continue;
        }

        //function f(x)='FUNCTION IN TERMS OF x' color='COLOR' range=[x1,x2](optional)
        //Exploding array to get function
        $function=explode('=',$lex_array[$counter][1]["Value"]);
        //Exploding array to get color
        $color=explode('=',$lex_array[$counter][2]["Value"]);
        //Exploding array to get function
        $dfunction=explode('=',$lex_array[$counter][3]["Value"]);
        //If there is no continue forced then the parameters are added to the tree
        array_push($syntacticalTree,array("fn"=>$function[1],"color"=>$color[1],"derivative"=>array("fn"=>$dfunction[1],"updateOnMouseMove"=>"true")));
        $linesOfCode++;
        $counter++;
      }
    }

    //PLOT POINTS / POLYLINES / VECTOR
    //points  [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]
    //polylines  [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]
    //vector [x1,y1] [x0,y0](This is the offset and is optional)
    else if($lex_array[$counter][0]["Value"]=="points" || $lex_array[$counter][0]["Value"]=="polylines" || $lex_array[$counter][0]["Value"]=="vector")
    {
      $linesOfCode++;
      //Checking number of arguments
      if(sizeof($lex_array[$counter])<=1 && $lex_array[$counter][0]["Value"]=="points")
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": Atleast 1 point must be given for POINTS.\nSugested Syntax: points [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]\n";
        $counter++;
        continue;
      }
      else if(sizeof($lex_array[$counter])<=2 && $lex_array[$counter][0]["Value"]=="polylines")
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": Atleast 2 point must be given for POLYLINES.\nSugested Syntax: polylines [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]\n";
        $counter++;
        continue;
      }

      else if(sizeof($lex_array[$counter])!=2 && sizeof($lex_array[$counter])!=3 && $lex_array[$counter][0]["Value"]=="vector")
      {
        $error[sizeof($error)]="Error in line ".($counter+1).": Improper syntax given for VECTOR.\nSugested Syntax: vector [x1,y1] [x0,y0](This is the offset and is optional)\n";
        $counter++;
        continue;
      }

      else
      {
        $pointCounter=1;
        $errorFlag=0;
        $rangeMatch="/\[\+?-?[0-9]\d*(\.\d+)?,+-?[0-9]\d*(\.\d+)?\]/";
        while($pointCounter<sizeof($lex_array[$counter]))
        {
          if(!preg_match($rangeMatch,$lex_array[$counter][$pointCounter]["Value"]))
          {
            if($lex_array[$counter][0]["Value"]=="points")
              $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for POINTS.\nSugested Syntax: points [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]\n";
            else if($lex_array[$counter][0]["Value"]=="polylines")
              $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for POLYLINES.\nSugested Syntax: polylines [x1,y1] [x2,y2] [x3,y3] .... [xn,yn]\n";
            else
              $error[sizeof($error)]="Error in line ".($counter+1).": Improper Syntax for POLYLINES.\nSugested Syntax: vector [x1,y1] [x0,y0](This is the offset and is optional)\n";
            $errorFlag=1;
            break;
          }
          $pointCounter++;
        }
        if($errorFlag==1)
        {
          $counter++;
          continue;
        }
        if($lex_array[$counter][0]["Value"]=="vector")
        {
          if(sizeof($lex_array[$counter])==2)
            array_push($syntacticalTree,array("vector"=>$lex_array[$counter][1]["Value"],"graphType"=>"'polyline'","fnType"=>"'vector'"));
          else
            array_push($syntacticalTree,array("vector"=>$lex_array[$counter][1]["Value"],"offset"=>$lex_array[$counter][2]["Value"],"graphType"=>"'polyline'","fnType"=>"'vector'"));
          $counter++;
        }
        else
        {
          $pointArray=array();
          for($i=1;$i<sizeof($lex_array[$counter]);$i++)
            array_push($pointArray,$lex_array[$counter][$i]["Value"]);
          if($lex_array[$counter][0]["Value"]=="points")
            array_push($syntacticalTree,array("points"=>$pointArray,"graphType"=>"'scatter'","fnType"=>"'points'"));
          else
            array_push($syntacticalTree,array("points"=>$pointArray,"graphType"=>"'polyline'","fnType"=>"'points'"));
          $linesOfCode++;
          $counter++;
        }
      }
    }
    else
    {
      $counter++;
      $linesOfCode++;
    }
  }
  $_SESSION['syntacticalTree']=$syntacticalTree;
  $_SESSION['errorInSyntacticalTree']=$error;
  if(sizeof($error)==0)
    header('Location:transformation.php');
  else
  {
    echo "<pre>";
    echo "<b>ERROR OCCURED</b><br>";
    print_r($error);
    print_r($syntacticalTree);
    echo "</pre>";
  }
?>

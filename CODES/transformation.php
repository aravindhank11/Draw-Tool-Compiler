<?php
  session_start();
  $syntax=$_SESSION['syntacticalTree'];
  $error=$_SESSION['errorInSyntacticalTree'];
  $dataArray=array();
  if(sizeof($error)==0)
  {
    $count=1;
    while($count<sizeof($syntax))
    {
      $data="{";
      foreach ($syntax[$count] as $key => $value)
      {
        if(!is_array($value))
          $data=$data.$key.": ".$value.",";
        else if(is_array($value) && $key=="derivative")
        {
          $data=$data.$key.": {";
          foreach($syntax[$count]['derivative'] as $keyOfDerivative => $valueOfDerivative)
            $data=$data.$keyOfDerivative.": ".$valueOfDerivative.",";
          $data=$data."},";
        }
        else if(is_array($value) && $key=='points')
        {
          $data=$data.$key.": [";
          foreach ($syntax[$count]['points'] as $valueOfPoints)
            $data=$data.$valueOfPoints.",";
          $data=$data."],";
        }
      }
      $data=$data."},";
      array_push($dataArray,$data);
      $data='';
      $count++;
    }
    $transformation="functionPlot({
      title: ".$syntax[0]["title"].",
      width: 500,
      height: 500,
      target: '#demo',
      grid: true,
      tip: {
        xLine: true,
        yLine: true,
        renderer: function (x, y, index){}
      },
      data:[
        ";
    foreach($dataArray as $element)
      $transformation = $transformation.$element;
    $transformation=$transformation."]});";
    $_SESSION['transformation']=$transformation;
    echo "<html><head><script src='http://d3js.org/d3.v3.min.js'></script><script src='http://maurizzzio.github.io/function-plot/js/function-plot.js'></script><meta charset='utf-8'><title>FINAL OUTPUT</title></head>";
    echo "<body><div id='demo'></div></body>";
    echo "<script>$transformation</script></html>";
  }
?>

<Html>
    <Head>
        <style>
            #error{
               border: 1px solid lightgrey;
                width: 65%;
                 border-spacing:0; /* Removes the cell spacing via CSS */
                    border-collapse: collapse;
                
            }
            #error td{
                font-weight: bold;
                column-gap: normal;
                border-bottom: 1px solid lightgrey;
                border-width: thin;
                border-right:1px solid lightgrey;
                background-color: #F5f4F6;
                
            }
            #error td+td{
                text-align: center;
                font-weight: normal;
                border-right: none;
                background-color: #FCFBFD;
            }
            #table1{
                border: 1px solid lightgrey;
                background-color: whitesmoke;
                padding-right: 20px;
                padding-bottom: 27px;
            }
            
        #newstable
            {   
                border: 1px solid lightgrey;
                width: 75%;
                border-spacing:0; /* Removes the cell spacing via CSS */
                border-collapse: collapse;
                background-color: #F5f4F6;
            }
            #newstable td{
                border-width: thin;
                border-bottom: 1px solid lightgrey;
            }
            
            a:link{
                text-decoration: none;
            }
            a:visited {
    color: blue;
}
            #table2{
                border: 1px solid lightgrey;
                width: 75%;
                 border-spacing:0; /* Removes the cell spacing via CSS */
                    border-collapse: collapse;
                
            }
            #table2 td{
                font-weight: bold;
                border-bottom: 1px solid lightgrey;
                border-width: thin;
                border-right:1px solid lightgrey;
                background-color: #F5f4F6;
                
            }
            #table2 td+td{
                text-align: center;
                font-weight: normal;
                border-right: none;
                background-color: #FCFBFD;
            }
            #indicators{
                
                float left: 80%;
            }
            #container{
                width:1065px;
                height:600px;
                border: 1px solid;
                border-color: lightgrey;
            }
        
        </style>
        <script src="https://code.highcharts.com/highcharts.js"></script>
    </Head>
    <body>
    
    <form action="" name="myform" method="POST" id="location">
    
        <center><table id="table1"><tr><td id="row1"><center><i><font size="6px" >Stock Search</font></i></center></td></tr>
        <tr><td><hr color=lightgrey width=105%>Enter Stock Ticker Symbol:*
        <input type="text" id="equity1" name="equity" maxlength="260" size="20"  value="<?php echo isset($_POST['equity'])?$_POST['equity']:"" ?>">
            </td></tr>
        <tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" onclick="check()" value="Search" >
             <input type="button" id="reset" onclick="ret()" value="Clear" ></center></td></tr>
            <tr><td>*- <i>Mandatory fields.</i></td></tr>
        </table> </center> </form>
        
        
        <script type="text/javascript">
            
            function ret()
            {
               document.getElementById("full").style.display= " none ";
                document.getElementById("equity1").value="";
                document.getElementById("err").style.display= " none ";
            }
            
            
            
            
        </script>
        
       
        <!-- Handling wrong input -->
         <?php    if(isset($_POST["submit"]) && (!empty($_POST["equity"]))):  ?>
         <?php
       $url=file_get_contents("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&outputsize=full&symbol=".$_POST['equity']."&apikey=T0J7V1FAZRAMUL5I");
        //var_dump($url);
        $t=false;
        if(preg_match("/Error/",$url))
        {
            echo "<div id='err'><center><Table id='error'><tr><td>Error&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td> Error:NO Record has been found,please enter a valid symbol</td></tr></table></center></div>";
            $t=true;
        } 
        
        
    ?>
        <?php endif; ?>
        
        <div id="full" >
        
        <!-- Search blank handling -->
        <script type="text/javascript">
            function check()
            {
            if(document.getElementById("equity1").value=="" ||document.getElementById("equity1").value==null)
            {
                alert("Please enter a Symbol");
            }
            
            }
        
        
        
        </script>
        
    
    <?php    if(isset($_POST["submit"]) && (!empty($_POST["equity"])) && !($t)):  ?>
   
    
        
      <?php   
    $url=file_get_contents("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&outputsize=full&symbol=".$_POST['equity']."&apikey=T0J7V1FAZRAMUL5I");
        
    /*$indicators=file_get_contents("https://www.alphavantage.co/query?
function=&symbol=".$_POST['equity']."&interval=weekly&time_period=10&series_type=open&apikey=T0J7V1FAZRAMUL5I")   */
        
    
    $response = json_decode($url,true);
    $Tsd=$response["Time Series (Daily)"];
    //var_dump($Tsd);    
    $alldays= array_keys($Tsd);
    $lastref=$response["Meta Data"]["3. Last Refreshed"];
    $close=$response["Time Series (Daily)"]["$lastref"]["4. close"];
    $pclose=$response["Time Series (Daily)"]["$alldays[2]"]["4. close"];
    $change=round($close-$pclose,2);
    $changepert=round($change*100/$pclose,2);
    $alldays1=array_slice($alldays,0,130);
    $reversedays=array_reverse($alldays1);
    $minclose=$response["Time Series (Daily)"][$reversedays[0]]["4. close"];
    $maxclose=0;
    $maxvol=0;
      
       // var_dump($response);
    echo "<center><Table id='table2' width='25%'>"."<tr>";
    echo "<td><b>Stock Ticker Symbol</b></td><td>".$response["Meta Data"]["2. Symbol"]."</td></tr>";
    echo "<tr>";
       
    echo "<td>Close</td><td>".$response["Time Series (Daily)"]["$lastref"]["4. close"]."</tr>";
    echo "<tr>";
    echo "<td>Open</td><td>".$response["Time Series (Daily)"]["$lastref"]["1. open"]."</tr>";
    echo "<tr>";
    echo "<td>Previous close</td><td>".$response["Time Series (Daily)"]["$alldays[2]"]["4. close"]."</tr>"; 
    
    if($change>=0)
    {
    echo "<tr>";
    echo "<td>Change</td><td>".$change."<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='12px' height='12px'></img></td></tr>";
    echo "<tr>";
    echo "<td>Change Percent</td><td>".$changepert."%<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='12px' height='12px'></img></td></tr>";
    }
    else
    {
    echo "<tr>";
    echo "<td>Change</td><td>".$change."<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='12px' height='12px'></img></td></tr>";
    echo "<tr>";
    echo "<td>Change Percent</td><td>".$changepert."%<img src='
http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='12px' height='12px'></img></td></tr>";
    
    
    }
    echo "<tr>";
    echo "<td>Day's Range</td><td>".$response["Time Series (Daily)"]["$lastref"]["3. low"]."-".$response["Time Series (Daily)"]["$lastref"]["2. high"].
        "</tr>";
    echo "<tr>";
    echo "<td>Volume</td><td>".$response["Time Series (Daily)"]["$lastref"]["5. volume"]."</tr>";
    echo "<tr>";
    echo "<td>Timestamp</td><td>".$response["Meta Data"]["3. Last Refreshed"]."</tr>";
    echo "<tr class='last'>";
    echo "<td>Indicators</td><td> <div id='indicators'><a href =# onclick='getprice()'>Price
      </a>
      <a href =# onclick='getsma()'>&nbsp&nbspSMA
      </a>
      <a href =# onclick='getema()'>&nbsp&nbspEMA
      </a>
      <a href =# onclick='getstoch()'>&nbsp&nbspSTOCH
      </a>
      <a href =# onclick='getrsi()'>&nbsp&nbspRSI
      </a>
      <a href =# onclick='getadx()'>&nbsp&nbspADX
      </a>
      <a href =# onclick='getcci()'>&nbsp&nbspCCI
      </a>
      <a href =# onclick='getbbands()'>&nbsp&nbspBBANDS
      </a>
      <a href =# onclick='getmacd()'>&nbsp&nbspMACD
      </a>
      </div></td></tr>"; 
    
    
        
    
    echo "</center></table><br>";
     //foreach($alldays as $keys=>$value)
         //echo $value;
        ?> 
    
        
    
    <?php
       for($j=0;$j<sizeof($reversedays);$j++)
            {
                if(($response["Time Series (Daily)"][$reversedays[$j]]["4. close"])>$maxclose)
                {
                    $maxclose=$response["Time Series (Daily)"][$reversedays[$j]]["4. close"];
                }
                if(($response["Time Series (Daily)"][$reversedays[$j]]["4. close"])<$minclose)
                {
                    $minclose=$response["Time Series (Daily)"][$reversedays[$j]]["4. close"];
                }
                if(($response["Time Series (Daily)"][$reversedays[$j]]["5. volume"])>$maxvol)
                {
                    $maxvol=$response["Time Series (Daily)"][$reversedays[$j]]["5. volume"];
                }
            } 
            
       // echo "Maxi: ".$maxclose;
        //echo "Mini: ".$minclose;
        
    ?>    
        
    <?php echo '<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"> </div>'?>
    <?php 
    $weeks=file_get_contents("https://www.alphavantage.co/query?function=TIME_SERIES_WEEKLY&symbol=".$_POST['equity']."&apikey=T0J7V1FAZRAMUL5I");
    $week=json_decode($weeks,true);
    $weekly=$week["Weekly Time Series"];
       
    $weekdays= array_keys($weekly);   
    $revweeklydays=array_reverse($weekdays);
     
    for($i=0;$i<60;$i++)
    {
        $tempdays[]=$weekdays[$i];
    }
    $revtemp=array_reverse($tempdays);  
    //var_dump($revtemp);
    $date= substr("$lastref",5,2).'/'.substr("$lastref",8,2).'/'.substr("$lastref",0,4);
                
                                   
     //echo $date;                             
        
        
        ?>    
        
    
   <script>    
      // var q=$minclose;
    Highcharts.chart('container', {
    chart: {
        zoomType: 'xy',
        
    },
    title: {
        text: 'Stock Price (<?php echo $date ?>)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
        
    },
    xAxis: [{
        categories: [<?php for($i=0;$i<sizeof($reversedays);$i++)
                        {echo "'" ;
                        
                        echo substr("$reversedays[$i]",5,2);
                        echo "/";   
                        echo substr("$reversedays[$i]",8,2);
                        
                        echo "'";
                        echo ",";
                        }
                    ?>
                ],
        
        labels: {
            rotation: -45
        },
        crosshair: true,
        tickInterval: 5
    }],
    yAxis: [{
       min: [<?php echo $minclose-5; ?>],
        max: [<?php echo $maxclose; ?>],
        title: {
            text: 'Stock Price',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
             labels: {
            format: '{value}',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
            
            tickInterval: 5,
        tooltip: {
        shared: true,
        formatter: function()
        {
            var s ='<b>' +this.x+ '</b><b>'+ this.y+'</b>';
            return s;
        }
        },
    
        
    }, { // Secondary yAxis
        max: [<?php echo $maxvol*4; ?>],
        title: {
            text: 'Volume',
            formatter: function(){
                return this.value/1000000 +"M"
            },
            style: {
                color: Highcharts.getOptions().colors[1]
            }
            
        },
        labels: {
            formatter: function(){
                return this.value/1000000 +"M"
            },
            
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        opposite: true
    }],
        legend:{
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            
        },
    tooltip: {
        shared: false,
        useHTML: true,
        
    },
   
    
    series: [ 
    
         {    
        
        name: '<?php echo $_POST['equity'] ?>',
        type: 'area',
        color: '#FF0000',
        fillOpacity: 0.6,
        
        data: [<?php
            for($j=0;$j<sizeof($reversedays);$j++)
            {
                echo $response["Time Series (Daily)"][$reversedays[$j]]["4. close"];
                echo ",";
                
            }
            
            ?>],
            marker: {
                enabled: false
            },
         },
    
    {
        name: '<?php echo $_POST['equity'] ?> Volume',
        color: 'white',
        type: 'column',
        yAxis: 1,
        data: [<?php
            for($j=0;$j<sizeof($reversedays);$j++)
            {
                echo $response["Time Series (Daily)"][$reversedays[$j]]["5. volume"];
                echo ",";
                
            }
            
            ?>],
       
    },
    
    
    ]
        });
        
        
        
    
       </script>
        
        
        
        
        <!--//Price waala -->
        
        
           
      
   <script>    
      // var q=$minclose;
       function getprice()
       {
    Highcharts.chart('container', {
    chart: {
        zoomType: 'xy',
        
    },
    title: {
        text: 'Stock Price (<?php echo $date ?>)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
        
    },
    xAxis: [{
        categories: [<?php for($i=0;$i<sizeof($reversedays);$i++)
                        {echo "'" ;
                        
                        echo substr("$reversedays[$i]",5,2);
                        echo "/";   
                        echo substr("$reversedays[$i]",8,2);
                        
                        echo "'";
                        echo ",";
                        }
                    ?>
                ],
        
        labels: {
            rotation: -45
        },
        crosshair: true,
        tickInterval: 5
    }],
    yAxis: [{
       min: [<?php echo $minclose-5; ?>],
        max: [<?php echo $maxclose; ?>],
        title: {
            text: 'Stock Price',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
             labels: {
            format: '{value}',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
            
            tickInterval: 5,
        tooltip: {
        shared: true,
        formatter: function()
        {
            var s ='<b>' +this.x+ '</b><b>'+ this.y+'</b>';
            return s;
        }
        },
    
        
    }, { // Secondary yAxis
        max: [<?php echo $maxvol*4; ?>],
        title: {
            text: 'Volume',
            formatter: function(){
                return this.value/1000000 +"M"
            },
            style: {
                color: Highcharts.getOptions().colors[1]
            }
            
        },
        labels: {
            formatter: function(){
                return this.value/1000000 +"M"
            },
            
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        opposite: true
    }],
        legend:{
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            
        },
    tooltip: {
        shared: false,
        useHTML: true,
        
    },
   
    
    series: [ 
    
         {    
        
        name: '<?php echo $_POST['equity'] ?>',
        type: 'area',
        color: '#FF0000',
        fillOpacity: 0.6,
        
        data: [<?php
            for($j=0;$j<sizeof($reversedays);$j++)
            {
                echo $response["Time Series (Daily)"][$reversedays[$j]]["4. close"];
                echo ",";
                
            }
            
            ?>],
            marker: {
                enabled: false
            },
         },
    
    {
        name: '<?php echo $_POST['equity'] ?> Volume',
        color: 'white',
        type: 'column',
        yAxis: 1,
        data: [<?php
            for($j=0;$j<sizeof($reversedays);$j++)
            {
                echo $response["Time Series (Daily)"][$reversedays[$j]]["5. volume"];
                echo ",";
                
            }
            
            ?>],
       
    },
    
    
    ]
        });
        
        
        
       }
       </script>
        
        
       
     
       <!-- //SMA Waala -->  
        
        
        
           <script type="text/javascript">
               
            
            function getsma(){
            var x=document.getElementById("equity1").value;
            console.log(x);
            var url="https://www.alphavantage.co/query?function=SMA&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
                console.log(data);
                
            var data1=[];
                data1=data["Technical Analysis: SMA"];
                var data2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                //console.log(data["Technical Analysis: SMA"][item[1]]["SMA"]);
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: SMA"][item[i]]["SMA"]));
                        
                    }
               // console.log(tempdata);
                
                //data2= data1.slice(0,130);
              //  console.log(data2);
               // console.log(data["Technical Analysis: SMA"][data1[0]]);
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["SMA"])); 
                        
                    }
                //console.log(maindatasma);
                data2=maindatasma.slice(0,130);
                data2.reverse();
                
                //console.log(data2);
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         //cat.push("'" + (itemrev.substr(5,2))+ "/"+ (itemrev.substr(8,2))+ "'");
                        /*cat.push( itemrev.substr(5,2));
                        cat.push( "/");   
                        cat.push( itemrev.substr(8,2));
                        cat.push( "'");
                        cat.push( ",");*/
                    }
                console.log(cat);
                
                Highcharts.chart('container', {
                    chart: {
        
        
    },

    title: {
        text: 'Simple Moving Average (SMA)'
    },

    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis:{
        categories: cat
                ,
        /*tickInterval: 1,*/
       
        labels: {
            rotation: -45
        },
       tickInterval: 5,
        
    
    },
    yAxis: {
        title: {
            text: 'SMA'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
                    plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
                   

    /*plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }*/
    //},

    series: [{
        name: '<?php echo $_POST['equity'] ?>',
        
        data: data2,
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }],

    

});
                
                
                
                
                
                
                
            }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
       
        <!-- //EMA Waala --> 
        
       <script type="text/javascript">
               
            
            function getema(){
            var x=document.getElementById("equity1").value;
           
            var url="https://www.alphavantage.co/query?function=EMA&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: EMA"];
                var data2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                //console.log(data["Technical Analysis: SMA"][item[1]]["SMA"]);
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: EMA"][item[i]]["EMA"]));
                        
                    }
               // console.log(tempdata);
                
                //data2= data1.slice(0,130);
              //  console.log(data2);
               // console.log(data["Technical Analysis: SMA"][data1[0]]);
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["EMA"])); 
                        
                    }
                //console.log(maindatasma);
                data2=maindatasma.slice(0,130);
                data2.reverse();
                
                //console.log(data2);
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         //cat.push("'" + (itemrev.substr(5,2))+ "/"+ (itemrev.substr(8,2))+ "'");
                        /*cat.push( itemrev.substr(5,2));
                        cat.push( "/");   
                        cat.push( itemrev.substr(8,2));
                        cat.push( "'");
                        cat.push( ",");*/
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Exponential Moving Average(EMA)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'EMA'
        },
        labels: {
            formatter: function () {
                return this.value ;
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    series: [ {
        name: '<?php echo $_POST['equity'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: data2,
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        <!-- //Stoch Waala --> 
        
        
        
        
         <script type="text/javascript">
               
            
            function getstoch(){
            var x=document.getElementById("equity1").value;
            console.log(x);
            var url="https://www.alphavantage.co/query?function=STOCH&symbol="+x+"&interval=daily&slowkmatype=1&slowdmatype=1&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: STOCH"];
                var data2=[];
                var data3=[];
                var slowd=[];
                var slowk=[];
                
                 console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                for(var i in data1)
                    {
                       
                         slowd.push(parseFloat( data1[i]["SlowD"])); 
                        slowk.push(parseFloat( data1[i]["SlowK"])); 
                    }
                //console.log(slowd);
               // console.log(slowk);
                data2=slowd.slice(0,130);
                
               data2.reverse();
                data3=slowk.slice(0,130);
                data3.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         //cat.push("'" + (itemrev.substr(5,2))+ "/"+ (itemrev.substr(8,2))+ "'");
                        /*cat.push( itemrev.substr(5,2));
                        cat.push( "/");   
                        cat.push( itemrev.substr(8,2));
                        cat.push( "'");
                        cat.push( ",");*/
                    }
                console.log(cat);
                
                Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Stochastic Oscillator (STOCH)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'STOCH'
        },
        
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
    plotOptions: {
        series: {
            lineWidth: 1,
            
        }
    },
    series: [{
        name: '<?php echo $_POST['equity'] ?> SlowD',
        marker: {
            symbol: 'circle'
        },
        data:data2,
        
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },

    }, {
        name: '<?php echo $_POST['equity'] ?> SlowK',
        marker: {
            symbol: 'circle'
        },
        data:data3,
         marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }]
});      
        
         }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        
        
        
    <!-- //rsi Waala -->     
        
        
        
        
        <script type="text/javascript">
               
            
            function getrsi(){
            var x=document.getElementById("equity1").value;
            console.log(x);
            var url="https://www.alphavantage.co/query?function=RSI&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: RSI"];
                var data2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                //console.log(data["Technical Analysis: SMA"][item[1]]["SMA"]);
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: RSI"][item[i]]["RSI"]));
                        
                    }
               // console.log(tempdata);
                
                //data2= data1.slice(0,130);
              //  console.log(data2);
               // console.log(data["Technical Analysis: SMA"][data1[0]]);
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["RSI"])); 
                        
                    }
                //console.log(maindatasma);
                data2=maindatasma.slice(0,130);
                data2.reverse();
                
                //console.log(data2);
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         //cat.push("'" + (itemrev.substr(5,2))+ "/"+ (itemrev.substr(8,2))+ "'");
                        /*cat.push( itemrev.substr(5,2));
                        cat.push( "/");   
                        cat.push( itemrev.substr(8,2));
                        cat.push( "'");
                        cat.push( ",");*/
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Relative Strength Index (RSI)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'RSI'
        },
        
    },
            legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
            plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    
    series: [ {
        name: '<?php echo $_POST['equity'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: data2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        
        <!-- //cci Waala --> 
        
        
        
        <script type="text/javascript">
               
            
            function getcci(){
            var x=document.getElementById("equity1").value;
            console.log(x);
            var url="https://www.alphavantage.co/query?function=CCI&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: CCI"];
                var data2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                //console.log(data["Technical Analysis: SMA"][item[1]]["SMA"]);
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: CCI"][item[i]]["CCI"]));
                        
                    }
               // console.log(tempdata);
                
                //data2= data1.slice(0,130);
              //  console.log(data2);
               // console.log(data["Technical Analysis: SMA"][data1[0]]);
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["CCI"])); 
                        
                    }
                //console.log(maindatasma);
                data2=maindatasma.slice(0,130);
                data2.reverse();
                
                //console.log(data2);
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         //cat.push("'" + (itemrev.substr(5,2))+ "/"+ (itemrev.substr(8,2))+ "'");
                        /*cat.push( itemrev.substr(5,2));
                        cat.push( "/");   
                        cat.push( itemrev.substr(8,2));
                        cat.push( "'");
                        cat.push( ",");*/
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Commodity Channel Index (CCI)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'CCI'
        },
        
    },
            legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
            plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    series: [ {
        name: '<?php echo $_POST['equity'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: data2,
        
     marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        <!-- //adx Waala --> 
        
        
        <script type="text/javascript">
               
            
            function getadx(){
            var x=document.getElementById("equity1").value;
            console.log(x);
            var url="https://www.alphavantage.co/query?function=ADX&symbol="+x+"&interval=daily&time_period=10&series_type=close&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: ADX"];
                var data2=[];
                var tempdata=[];
                
                console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
                
                //console.log(data["Technical Analysis: SMA"][item[1]]["SMA"]);
                for(var i in item)
                    {
                       
                         tempdata.push(parseFloat(data["Technical Analysis: ADX"][item[i]]["ADX"]));
                        
                    }
               // console.log(tempdata);
                
                //data2= data1.slice(0,130);
              //  console.log(data2);
               // console.log(data["Technical Analysis: SMA"][data1[0]]);
                var maindatasma=[];
                for(var i in data1)
                    {
                       
                          maindatasma.push(parseFloat( data1[i]["ADX"])); 
                        
                    }
                //console.log(maindatasma);
                data2=maindatasma.slice(0,130);
                data2.reverse();
                
                //console.log(data2);
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         //cat.push("'" + (itemrev.substr(5,2))+ "/"+ (itemrev.substr(8,2))+ "'");
                        /*cat.push( itemrev.substr(5,2));
                        cat.push( "/");   
                        cat.push( itemrev.substr(8,2));
                        cat.push( "'");
                        cat.push( ",");*/
                    }
                console.log(cat);
                
        
        Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Average Directional movement indeX (ADX)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'ADX'
        },
       
    },
            legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
            plotOptions: {
        series: {
            lineWidth: 1,
            color: 'red'
        }
    },
    series: [ {
        name: '<?php echo $_POST['equity'] ?>',
        marker: {
            symbol: 'dot'
        },
        data: data2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
           
    
    }],
});
   
                }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        
        <!-- //BBands Waala --> 
        
        <script type="text/javascript">
               
            
            function getbbands(){
            var x=document.getElementById("equity1").value;
            console.log(x);
            var url="https://www.alphavantage.co/query?function=BBANDS&symbol="+x+"&interval=daily&time_period=5&series_type=close&nbdevup=3&nbdevdn=3&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: BBANDS"];
                var data2=[];
                var data3=[];
                var data4=[];
                var lb=[];
                var ub=[];
                var mb=[];
                
                 console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
        
        
                for(var i in data1)
                    {
                       
                         lb.push(parseFloat( data1[i]["Real Lower Band"])); 
                        ub.push(parseFloat( data1[i]["Real Upper Band"]));
                        mb.push(parseFloat( data1[i]["Real Middle Band"])); 
                    }
                
                data2=lb.slice(0,130);
                
               data2.reverse();
                data3=mb.slice(0,130);
                data3.reverse();
                data4=ub.slice(0,130);
               data4.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         
                    }
                console.log(cat);
                
                
                
                Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Bollinger Bands (BBANDS)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat, 
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'BBANDS'
        },
        
    },
                    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
                     plotOptions: {
        series: {
            lineWidth: 1,
            
        }
    },
    
    series: [{
        name: '<?php echo $_POST['equity'] ?> Real Lower Band',
        marker: {
            symbol: 'square'
        },
        data:data2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },

    },{
        name: '<?php echo $_POST['equity'] ?> Real Middle Band',
        marker: {
            symbol: 'diamond'
        },
        data:data3,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    },
        {
        name: '<?php echo $_POST['equity'] ?> Real Upper Band',
        marker: {
            symbol: 'diamond'
        },
        data:data4,
            marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }]
});      
        
         }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        
        <!-- //MACD Waala --> 
        
        <script type="text/javascript">
               
            
            function getmacd(){
            var x=document.getElementById("equity1").value;
            console.log(x);
            var url="https://www.alphavantage.co/query?function=MACD&symbol="+x+"&interval=daily&series_type=close&fastperiod=10&apikey=T0J7V1FAZRAMUL5I";
            
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try{
          var data=JSON.parse(xmlhttp.responseText);
        
        var data1=[];
                data1=data["Technical Analysis: MACD"];
                var data2=[];
                var data3=[];
                var data4=[];
                var his=[];
                var sig=[];
                var norm=[];
                
                 console.log(data1);
                var item=Object.keys(data1);
                var itemrev= item.slice(0,130);
                itemrev.reverse();
                console.log(itemrev);
        
        
                for(var i in data1)
                    {
                       
                         his.push(parseFloat( data1[i]["MACD_Hist"])); 
                        sig.push(parseFloat( data1[i]["MACD_Signal"]));
                        norm.push(parseFloat( data1[i]["MACD"])); 
                    }
                
                data2=his.slice(0,130);
                
               data2.reverse();
                data3=sig.slice(0,130);
                data3.reverse();
                data4=norm.slice(0,130);
               data4.reverse();
                
                var cat=[];    
                
                for(var e=0;e<itemrev.length;e++)
                     {
                         var k=itemrev[e].substr(5,2);
                         var t=itemrev[e].substr(8,2);
                        cat[e]=( k + "/" + t  );
                        
                         //cat.push("'" + (itemrev.substr(5,2))+ "/"+ (itemrev.substr(8,2))+ "'");
                        /*cat.push( itemrev.substr(5,2));
                        cat.push( "/");   
                        cat.push( itemrev.substr(8,2));
                        cat.push( "'");
                        cat.push( ",");*/
                    }
                console.log(cat);
                
                
                
                Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Moving Average Convergence/Divergence(MACD)'
    },
    subtitle: {
        useHTML:true,
        text: '<a href="https://www.alphavantage.co/" target="_blank" style="color: blue" >Source: Alpha Vantage </a>'
    },
    xAxis: {
        categories: cat,
        tickInterval: 5
    },
    yAxis: {
        title: {
            text: 'MACD'
        },
        
    },
                    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
    plotOptions: {
        series: {
            lineWidth: 1,
            
        }
    },
    series: [{
        name: '<?php echo $_POST['equity'] ?> MACD_Hist',
        marker: {
            symbol: 'square'
        },
        data:data2,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },

    },{
        name: '<?php echo $_POST['equity'] ?> MACD_Signal',
        marker: {
            symbol: 'diamond'
        },
        data:data3,
        marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    },
        {
        name: '<?php echo $_POST['equity'] ?> MACD',
        marker: {
            symbol: 'diamond'
        },
        data:data4,
            marker: {
                enabled: true,
                symbol: 'circle',
                radius: 2,
                
            },
    }]
});      
        
         }  catch(err)
          {
              console.log(err.message+"in"+xmlhttp.responseText);
              return;
          }
        
        }
        };
            xmlhttp.open("GET",url,true);              
            xmlhttp.send();
            
                   //maindata.toString();
                
            }
        </script> 
        
        
        <script>
        
        function changeimg()
            {   
                if(document.getElementById("downarr").src=="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png")
                    {
                document.getElementById("downarr").src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png"
                document.getElementById("clicktext").innerHTML="Click here to hide"
                document.getElementById("newstable").style.display="block";
                    }
            
                else 
                    {
                    console.log("hello");    
                document.getElementById("downarr").src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png"
                document.getElementById("clicktext").innerHTML="click to show stock news"
                        document.getElementById("newstable").style.display="none";
                    }
            
            
            
            }
            
            
            
            
        
        
        </script>
        
        
        
        
        
        
        <?php  
        
        
        $xml=simplexml_load_file('https://seekingalpha.com/api/sa/combined/'.$_POST['equity'].'.xml');
        $js=json_encode($xml);
        $jsd=json_decode($js,true);
        //var_dump($jsd);
        //print_r($xml);
        $newsHeadline = $xml->channel->item[0]->title->__toString();
        $newsLink = $xml->channel->item[0]->link->__toString();
        $newspub=$xml->channel->item[0]->pubDate->__toString();
        $newsInfo[0] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
       // $jsonNews = json_encode($newsInfo);
       // print_r ($jsonNews);
        
        
        $newsHeadline = $xml->channel->item[1]->title->__toString();
        $newsLink = $xml->channel->item[1]->link->__toString();
        $newspub=$xml->channel->item[1]->pubDate->__toString();
        $newsInfo[1] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
        
       // $jsonNews = json_encode($newsInfo);
      //  print_r ($jsonNews);
        $newsHeadline = $xml->channel->item[2]->title->__toString();
        $newsLink = $xml->channel->item[2]->link->__toString();
        $newspub=$xml->channel->item[2]->pubDate->__toString();
        $newsInfo[2] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
       // $jsonNews = json_encode($newsInfo);
      //  print_r ($jsonNews);
        $newsHeadline = $xml->channel->item[3]->title->__toString();
        $newsLink = $xml->channel->item[3]->link->__toString();
        $newspub=$xml->channel->item[3]->pubDate->__toString();
        $newsInfo [3]= array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
        
       // $jsonNews = json_encode($newsInfo);
      //  print_r ($jsonNews);
        $newsHeadline = $xml->channel->item[4]->title->__toString();
        $newsLink = $xml->channel->item[4]->link->__toString();
        $newspub=$xml->channel->item[4]->pubDate->__toString();
        $newsInfo[4] = array("Title"=>$newsHeadline,"Link"=>$newsLink,"PubDate"=>$newspub); 
         
        $jsonNews = json_encode($newsInfo);
        //var_dump($jsonNews);
        $tempnews=json_decode($jsonNews,true);
        $keys=array_keys($tempnews);
        $count=0;
       // echo $keys[1];
        //var_dump($keys);
       $article='/article/';
        
       
       //var_dump($jsd);
       // print_r($jsd['channel']['item']);
        //var_dump($tempnews);
        echo '<center><font color="grey"><p id="clicktext">click to show stock news</center></font></p>';
        echo '<center><img src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png" width="30px" height="15px" id="downarr" onclick="changeimg() "/></center><br><br>';
        echo '<div id="newstable" style="display: none">';
        echo '<Table  width="100%">';

        for($i=0;$i<sizeof($jsd['channel']['item']);$i++)
        {   
            //echo "count: ".$count;
            if($count==5){
                break;
            }
            if(preg_match($article,$jsd['channel']['item'][$i]['link']))
            {
        echo '<tr><td><a href="'.$jsd['channel']['item'][$i]['link'].'"target=" ">'.$jsd['channel']['item'][$i]['title'].'</a>';
        $r=  $jsd['channel']['item'][$i]['pubDate'];  
        $sub= substr($r,0,26);
        echo '&nbsp&nbsp Publicated Time: '.$sub.'</td></tr>';
                $count++;
            }
            
            
        }
       
        
        echo '</table></div>';
       
        
        ?>
        
        <?php endif; ?>
        
        
        
        </div>
    </body>
</html>
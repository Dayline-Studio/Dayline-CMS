
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
 <script type="text/javascript">
    $.ajax({
       type: "GET",
       url: "sgv_steam.php?id=xulunix",
       data: "loading",
       success: function(loading){
   
           $('#loading').empty();
           $('<span>'+loading+'</span>').appendTo('#loading');
   
       }
     });   
</script>
 <div id="loading" style="text-align:center;">

<img src="loading.gif" alt="" />
<br />
<div style="color:grey;"><small>Daten werden aufbereitet</small></div>
                           
 </div>

 
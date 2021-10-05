<html>
    <head>
        <title></title>
        <meta charset="UTF-8">        
        <style>
            .on_site_div{
                width:100%;                
                height:100%;                
            }  
            #inner-widget-box
            {
                border:none;
                width:100%;
                height:100%;
                position:relative;
            }
        </style>
        <script src="https://pruffme.com/engine/api/js/library.js"></script>
<script language="javascript" type="text/javascript">
   var api = pruffmeLab({});

   function createParticipant()
   {
      api.loginWebinarViewer(
         "eaf7bb183938b8cba5cd2a82b4cae803",
         [
            
           {
               "name": "Имя и Фамилия",
               "value": "Name2 LastName2",
               "type": 1
            },

         ],
         function(result){
          //  window.location = "https://pruffme.com/webinar/?id=eaf7bb183938b8cba5cd2a82b4cae803"
           window.location = "http://t-ru.ferroli.ru/webinartest"
         },
         function(error){
            alert("ERROR: "+JSON.stringify(error,null,4));
         });
   }
   window.onload = createParticipant();
</script>

    </head>
    <body>        
        <div id="inner-widget-box"></div>             
    </body>
</html>

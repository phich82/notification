<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Title</title>
    <base href="/">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <h1>Real Time Notification Test In Php</h1> 
    <div>
        <a class="customAlert" href="#">Publish Alert 1</a><br>
        <a class="customAlert2" href="#">Publish Alert 2</a>
    </div>  

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="client/Comet.js" type="text/javascript"></script>

    <script type="text/javascript">
        Comet
            .subscribe('customAlert', function(data){
                console.log('customAlert');
                console.log(data);
            })
            .subscribe('customAlert2', function(data){
                console.log('customAlert2');
                console.log(data);
            });
        
        $(document).ready(function() {
            $("a.customAlert").click(function(event) {
                Comet.publish('customAlert');
            });
            
            $("a.customAlert2").click(function(event) {
                Comet.publish('customAlert2');
            });
            Comet.run();
        });
    </script>
</body>
</html>
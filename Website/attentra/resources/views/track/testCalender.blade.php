<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    {{--<link rel="stylesheet" href="http://rawgithub.com/babakhani/pwt.datepicker/master/dist/css/persian-datepicker-0.4.5.min.css" />--}}
    <script src="http://rawgithub.com/babakhani/PersianDate/master/dist/0.1.8/persian-date-0.1.8.min.js"></script>
    <script src="http://rawgithub.com/babakhani/pwt.datepicker/master/dist/js/persian-datepicker-0.4.5.min.js"></script>

</head>
<body>
<script type="text/javascript">
    $(document).ready(function () {
        $("#example1").pDatepicker({
            formatter : function(unix){
                var date =  new Date(unix);
                return date;
            }
        });
    });
</script>
<div id="example1" style="width: 300px;margin: 0 auto;" ></div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<button onclick="myFunction()" style="float: right;" id="print">Print</button>
<img id="image" style="max-width:100%;cursor:pointer;" onclick="edit(this,1);" src="<?php echo $value = (isset($diagramy_data) ? $diagramy_data->diagramy_content : ''); ?>" />
<script>
function myFunction()
{
    document.getElementById('print').style.display='none';
    print();
}
</script>
</body>
</html>
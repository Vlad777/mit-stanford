<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>jQuery UI Autocomplete - Default functionality</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />
<script>
$(function() {
var availableTags = [
<?php
function sub($arr)
{
  return $arr[0];
}
require_once('pdo_connect.php');
$array = fetchAll("SELECT concat(concat(concat(word, ' ('), course_count), ')') as word FROM autocomplete ORDER BY course_count DESC");
//print_r(implode(array_map("sub", $array)));
echo '"' . implode('","', array_map("sub", $array)) . '"';
?>
];
$( "#tags" ).autocomplete({
source: availableTags
});
});
</script>
</head>
<body>
<div class="ui-widget">
<label for="tags">Tags: </label>
<input id="tags" />
</div>
</body>
</html>

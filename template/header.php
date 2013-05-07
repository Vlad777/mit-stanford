<div id="header">

	<div id="logo">
    	<h1><span class="blue">Moocs</span><span class="gold">4u</span></h1>
		<span class="gold">MIT + Stanford </span><span class="blue"><b>Mashup</b></span>
   </div>     
 
<div class="ui-widget">
<form id="search" action="index.php" method="post">
<label for="tags"></label>
<input name="q" id="tags" autofocus />
<script>
if (!("autofocus" in document.createElement("input"))) {
 document.getElementById("tags").focus();
}
</script>
<input type="submit" name="Submit" value="Search" />
</form>
</div>


    <?php		
	include("includes/user_test.php");	?>
</div>


<footer class="footer">
	<div class="container">
		<div class="row">
			<hr></hr>
			<div class="col-md-12">	
        		<p>&copy; <?php echo date('Y')?> <img src="images/footer-logo.png"> - All Rights Reserved.</p>
			</div>
		</div>
	</div>
</footer>


<script type="text/javascript">
	window.onload = date_time('date_time');
	function date_time(id){
	    date = new Date;
	    year = date.getFullYear();
	    month = date.getMonth();
	    months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
	    d = date.getDate();
	    day = date.getDay();
	    days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	    h = date.getHours();
	    if(h<10)
	    {
	            h = "0"+h;
	    }
	    m = date.getMinutes();
	    if(m<10)
	    {
	            m = "0"+m;
	    }
	    s = date.getSeconds();
	    if(s<10)
	    {
	            s = "0"+s;
	    }
	    result = ' '+h+':'+m+':'+s;
	    
	    document.getElementById('date_time').innerHTML = result;
	    setTimeout('date_time("'+id+'");','1000');
	    return true;

	}
</script>
</body>
</html>
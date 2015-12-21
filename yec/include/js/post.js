function joinevent(pid){
	var remarks=prompt(post_joinevent_remarks);
	if(remarks!=null){
		window.location="./joinevent.php?pid="+pid+"&r="+remarks;
		return true;
	}
	else{
		return false;
	}
}
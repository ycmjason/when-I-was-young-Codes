$('input[type=text],textarea,input[type=password]').each(function() {
	if($(this).attr('prevalue')!=undefined&&$(this).attr('prevalue')!=""){
		var default_value = $(this).attr('prevalue');
		var a=0,p=0;
		if(this.value==default_value || this.value==""){
			this.value=default_value;
			$(this).css('color', '#AAA');
		}
		if($(this).attr('type')=="password"){
			p=1;
			$(this).attr('type','text');
		}
		$(this).focus(function() {
			if(this.value == default_value && a==0) {
				this.value = '';
				$(this).css('color', '#333');
				if(p==1){
					$(this).attr('type','password');
				}
			}
			a=1;
		});
		$(this).blur(function() {
			if(this.value == '') {
				$(this).css('color', '#AAA');
				this.value = default_value;
				if(p==1){
					$(this).attr('type','text');
				}
				a=0;
			}else{
				$(this).css('color', '#333');
				if(p==1){
					$(this).attr('type','password');
				}
			}
		});
		$(this).on("keypress","select","change",function(e){
				$(this).css('color', '#333');
				if(p==1){
					$(this).attr('type','password');
				}
		});
	}
});
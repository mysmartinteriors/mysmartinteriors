//utilities

jQuery(function($) {
   var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
   $('ul.side-menu.metismenu a').each(function() { 
      if (this.href === path) {
          $(this).addClass('active');
          $(this).parent().parent().closest("ul").addClass('in');
          $(this).parent().parent().parent().closest("li").addClass('active');
      }
   });
}); 

function check_pass()
{
 	var val=document.getElementById("password").value;
 	var no=0;
 	if(val!="")
 	{
	  // If the password length is less than or equal to 6
	  if(val.length<=6)no=1;

	  // If the password length is greater than 6 and contain any lowercase alphabet or any number or any special character
	  if(val.length>6 && (val.match(/[a-z]/) || val.match(/\d+/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))no=2;

	  // If the password length is greater than 6 and contain alphabet,number,special character respectively
	  if(val.length>6 && ((val.match(/[a-z]/) && val.match(/\d+/)) || (val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) || (val.match(/[a-z]/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))))no=3;

	  // If the password length is greater than 6 and must contain alphabets,numbers and special characters
	  if(val.length>6 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))no=4;

	  // If the password length is greater than 15 and must contain alphabets,numbers and special characters
	  if(val.length>15 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))no=5;

	  if(no==1)
	  {
	   document.getElementById("pass_type").innerHTML="Very Weak";
	  }

	  if(no==2)
	  {
	   document.getElementById("pass_type").innerHTML="Weak";
	  }

	  if(no==3)
	  {
	   document.getElementById("pass_type").innerHTML="Good";
	  }

	  if(no==4)
	  {
	   document.getElementById("pass_type").innerHTML="Strong ";
	  }

	  if(no==4)
	  {
	   document.getElementById("pass_type").innerHTML="Very Strong ";
	  }
 	}

	else
	{
	  document.getElementById("pass_type").innerHTML="";
	}
}

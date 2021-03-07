


function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitAddProducts').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitAddProducts').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();
function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitRestock').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitRestock').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();
function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitAddProducers').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitAddProducers').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();
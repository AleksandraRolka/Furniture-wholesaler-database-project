


function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitPaymentStatus').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitPaymentStatus').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();


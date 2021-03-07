


function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitNewOrder').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitNewOrder').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();


document.getElementById('existingClient').addEventListener('change', function() 
{
  var optionData = this.options[this.selectedIndex].text;
  optionData = optionData.split(',');
  
  document.getElementById('client_name').value = optionData[0];
  document.getElementById('nip').value = optionData[1];
  document.getElementById('email').value = optionData[2];
  document.getElementById('phone').value = optionData[3];
  document.getElementById('street').value = optionData[4];
  document.getElementById('nr').value = optionData[5];
  document.getElementById('postcode').value = optionData[6];
  document.getElementById('city').value = optionData[7];
  
});
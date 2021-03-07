


function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitEditAddressEmployee').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitEditAddressEmployee').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();

document.getElementById('employeeAddressToEdit').addEventListener('change', function() 
{
  var optionData = this.options[this.selectedIndex].text;
  optionData = optionData.split(',');
  
  document.getElementById('street').value = optionData[2];
  document.getElementById('nr').value = optionData[3];
  document.getElementById('postcode').value = optionData[4];
  document.getElementById('city').value = optionData[5];
  
});
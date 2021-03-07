


function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitEditEmployee').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitEditEmployee').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();

document.getElementById('employeeToEdit').addEventListener('change', function() 
{
  var optionData = this.options[this.selectedIndex].text;
  optionData = optionData.split(',');
  
  document.getElementById('name').value = optionData[0];
  document.getElementById('surname').value = optionData[1];
  document.getElementById('email').value = optionData[2];
  document.getElementById('phone').value = optionData[3];
  document.getElementById('log').value = optionData[4];
  
});
<?php
class FormValidator
{
    /**
     * Is form valid;
     *
     * @var bool
     */
    private $isValid = true;
    /**
     * List of errors, assoc array with error messages one per fieldName
     *
     * @var array
     */
    private $errors = [];

    /**
     * Check if form is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Get error message
     *
     * @param $fieldName
     * @return mixed|string
     */
    public function getError($fieldName)
    {
        return isset($this->errors[$fieldName]) ? $this->errors[$fieldName] : '';
    }

    /**
     * @param array $rules list of rules
     * @param array $payload list of form parameters
     * @return bool Return validation result, same as isValid
     */
    public function validate(array $rules, array $payload)
    {
        foreach ($rules as $rule) {

            switch ($rule['type']) {
                case 'file':
                    $this->validateFile($rule, $payload);
                    break;
                case 'string':
                    $this->validateString($rule, $payload);
                    break;
                case 'date':
                    $this->validateDate($rule, $payload);
                    break;
                    //extend with other validation rules as needed
            }
        }

        return $this->isValid();
    }

   

    public function validateFile($rule, $payload)
    {

    	if($payload[$rule['fieldName']]['tmp_name'] == '')
    	{
    		$this->isValid = false;
    		$this->errors[$rule['fieldName']] = 'Please upload csv';

    	}
    	else
    	{
    		if($payload[$rule['fieldName']]['error'])
    		{
    			$this->isValid = false;
    			$this->errors[$rule['fieldName']] = 'File upload error';
    		}
    	}

        // Checkup logic, set $this->isValid to false if not valid, add
        // See add $this->errors[$rule['fieldname']] = 'your message';
    }

    public function validateString($rule, $payload)
    {
       if($payload[$rule['fieldName']] == '')
       {
            $this->isValid = false;
            $this->errors[$rule['fieldName']] = $rule['fieldName'].' Required';
       }
    }

    public function validateDate($rule, $payload)
    {
        if($payload[$rule['fieldName']] == '')
        {
            $this->isValid = false;
            $this->errors[$rule['fieldName']] = $rule['fieldName'].' Required';
        }
       
        if(!(bool)strtotime($payload[$rule['fieldName']]))
        {
            $this->isValid = false;
            $this->errors[$rule['fieldName']] = 'Invalid Date';
        }
    }

}
?>
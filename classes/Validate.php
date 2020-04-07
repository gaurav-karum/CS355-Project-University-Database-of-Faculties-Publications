<?php

class Validate
{
  private $_passed = false,
    $_errors = array(),
    $_db = null;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }


  // FORM VALIDATION
  public function check($source, $items = array())
  {
    foreach ($items as $item => $rules) {
      foreach ($rules as $rule => $rule_value) {
        $value = trim($source[$item]);


        if ($rule === 'required' && empty($value)) {
          $this->addError("{$item} is required");
        } else if (!empty($value)) {
          switch ($rule) {
            case 'min':
              if (strlen($value) < $rule_value) {
                $this->addError("{$item} must be minimum of {$rule_value}");
              }
              break;

            case 'max':
              if (strlen($value) > $rule_value) {
                $this->addError("{$item} must be maximum of {$rule_value}");
              }
              break;

            case 'range':
              if ($value < $rule_value || $value > date("Y")) {
                if ($item === 'jyear') {
                  $this->addError("Year must be between {$rule_value} and" . date("Y"));
                }
                if ($item === 'cyear') {
                  $this->addError("Year must be between {$rule_value} and" . date("Y"));
                }
              }

              break;

            case 'ofpattern':
              if (!preg_match($rule_value, $value)) {
                if ($item === 'Password')
                  $this->addError("{Password must have atleast 7 chars, atmost 20 chars with atleast 1 symbol, 1 uppercase , 1 lowercase and 1 digit");
                else if ($item === 'facrank') {
                  $this->addError("Author Rank must be from 1 to 9");
                }
              }
              break;

            case 'matches':
              if ($value != $source[$rule_value]) {
                $this->addError("{$rule_value} value must match {$item} again");
              }
              break;

            case 'unique':

              if ($item === 'Username') {
                $column = 'l_username';
                $check = $this->_db->get($rule_value, $column, '=', $value);

                if ($check->count()) {
                  $this->addError("Username already exists, Please try another");
                }
              } else {
                $column = 'l_webmail';
                $check = $this->_db->get($rule_value, $column, '=', $value);

                if ($check->count()) {
                  $this->addError("User already exists, User Registration failed");
                }
              }
              break;
          }
        }
      }
    }


    if (empty($this->_errors)) {
      $this->_passed = true;
    }
    return $this;
  }



  // FACULTY ID AND WEBMAIL VERIFICATION
  public function checkfac()
  {
    //checking whether faculty regid webmail exists
    $check = $this->_db->get1('faculty', 'f_regid', '=', $_POST['FacultyId'], 'f_webmail', '=', $_POST['Webmail']);
    // User cant be registered if he doesnt enter correct faculty id / Webmail combn.
    // echo "check count ".$check->count();

    if ($check->count()) {
      // echo"passed=true";
      $this->_passed = true;
      return $this;
    } else {
      // echo "passed=false";
      $this->addError("Wrong Faculty Id/ Webmail - User cant be registered");
      return $this;
    }
  }

  public function checkfreg($Ses_mail)
  {
    // checking whether faculty id exists in the faculty table or not
    $check = $this->_db->get('faculty', 'f_regid', '=', $_POST['fregid']);

    // check if this sql query returns result 
    if (!$check->count()) {
      $this->addError("Wrong FacultyId ");
    } else {
      $this->_data = $check->first();
      if (($this->_data->f_webmail !== $Ses_mail)) {
        $this->addError("Please Enter your correct FacultyId");
      }
    }
    if (empty($this->_errors)) {
      $this->_passed = true;
    }

    return $this;
  }

  // before calling this function $this will have 
  // sql result table 1 row where fregid == supplied id
  public function getfid()
  {
    return $this->_data->f_id;
  }

  // ADD ERRORS TO ERRORS ARRAY
  public function addError($error)
  {
    $this->_errors[] = $error;
  }

  // RETURN ERROR COUNT
  public function errors()
  {
    return $this->_errors;
  }

  // RETURN WHETHER VALIDATION PASSED
  public function passed()
  {
    return $this->_passed;
  }
}



// case 'unique':
              //   $check = $this->_db->get($rule_value, array('l_username', '=', $value));

              //   if ($check->count()) {
              //     $this->addError("{$item} : '$value' already exists, Please try another username");
              //   }
              //   break;

              // case 'unique1':
              //   $check = $this->_db->get($rule_value, array('l_webmail', '=', $value));

              //   if ($check->count()) {
              //     $this->addError("User already exists, User Registration failed");
              //   }
              //   break;

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class email 
{
    public function send()
    {
        return true;
    }
    
    public function to($email)
    {
        return true;
    }
    
    public function from($email, $name)
    {
        return true;
    }
    
    public function subject($subject)
    {
        return true;
    }
    
    public function message($message)
    {
        return true;
    }
}

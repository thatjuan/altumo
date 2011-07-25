<?php

/*
 * This file is part of the Altumo library.
 *
 * (c) Steve Sperandeo <steve.sperandeo@altumo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Altumo\String;
 
   


 /**
 * This class contains a number of string helper functions.
 * 
 * @author Steve Sperandeo <steve.sperandeo@altumo.com>
 */
class String{

    
    /**
    * Inserts one string ($addition) into another ($destination) at a given 
    * string $offset.
    * 
    * @param string $addition //new string to add into $destination
    * @param string $destination //existing string that will contain $addition
    * @param integer $offset //offset in $destination that we will place $addition
    * 
    * @see http://forums.digitalpoint.com/showthread.php?t=182666#post1785645
    * 
    * @return string
    */
    static public function insert( $addition, $destination, $offset ){    
        $left = substr( $destination, 0, $offset );
        $right = substr( $destination, $offset );
        return $left . $addition . $right;
    }
    
    
    /**
    * Makes an underscored string camel case
    * eg.
    *   how_are_you
    * becomes
    *   HowAreYou
    * 
    * 
    * @param string $string
    * @return string
    */
    static public function formatCamelCase( $string ) {
        
        $output = "";
        $string_parts = explode( '_', $string );
        
        foreach( $string_parts as $string_part ){
            $string_part = strtolower($string_part);
            $output .= strtoupper(substr( $string_part, 0, 1 )) . substr( $string_part, 1 ) ;
        }
        
        return $output;
        
    }
    
    
    /**
    * Makes a $string title cased
    * eg.
    *   how are you
    * becomes
    *   How Are You
    * 
    * @see http://blogs.sitepoint.com/title-case-in-php/
    * 
    * @param string $string
    * @return string
    */
    static public function formatTitleCase( $string ) {
        
        // Converts $string to Title Case, and returns the result.
        // Our array of 'small words' which shouldn't be capitalised if 
        // they aren't the first word. Add your own words to taste.
            $smallwordsarray = array( 'of','a','the','and','an','or','nor','but','is','if','then','else','when', 'at','from','by','on','off','for','in','out','over','to','into','with' ); 
            
            // Split the string into separate words 
            $words = explode(' ', $title); 
            foreach( $words as $key => $word ){ 
                // If this word is the first or it's not one of our small words, capitalise it
                if( $key == 0 || !in_array($word, $smallwordsarray) ){
                    $words[$key] = ucwords($word);
                }        
            } // Join the words back into a string 
            $newtitle = implode(' ', $words);
        
        return $newtitle;
        
    }
    
    
    /**
    * Makes a camel case string underscored 
    * eg.
    *   HowAreYou
    * becomes
    *   how_are_you
    * 
    * 
    * @param string $string
    * @return string
    */
    static public function formatUnderscored( $string ) {
        
        $output = "";
        //put underscores before the capitals and lower case all characters
        $output = strtolower( preg_replace('/([A-Z])/', '_\\1', $string) );
        
        //remove the first "_", if there is one
        $output = preg_replace('/^_(.*?)$/m', '\\1', $output);
        
        return $output;
        
    }
    
    
    /**
    * Generates a string $number_of_chars long with the $character_pool as potential characters.
    * 
    * @param integer $number_of_chars
    * @param string $number_of_chars
    */
    static public function generateRandomString( $number_of_chars, $character_pool = '0123456789abcdefghijklmnopqrstuvwxyz' ){
        
        if( !is_integer($number_of_chars) || $number_of_chars < 1 ){
            throw new \Exception('Number of chars must be a positive integer.');
        }
        $output = '';
        $pool_count = strlen($character_pool);
        for( $x = 0; $x < $number_of_chars; $x++ ){
            $index = rand(0,$pool_count-1);
            $output .= $character_pool[$index];
        }        
        return $output;
        
    }
    
    
    /**
    * Generates a url parameter string from the supplied array.
    * Adds the ? to the beginning.
    * Returns an empty string if $parameters is empty.
    * This method will url_encode the values, but not the keys.
    * 
    * @param array $parameters
    * @return string
    */    
    static public function generateUrlParameterString( $parameters = array() ){
        
        if( empty($parameters) ) return '';
        
        //combine and encode the parameters
            $combined_parameters = array();            
            foreach( $parameters as $key => $parameter ){
                $combined_parameters[] = $key . '=' . urlencode($parameter);
            }
        
        //build the request url
            $parameter_string = '';
            if( !empty($parameters) ){
                $parameter_string .= '?' . implode( '&', $combined_parameters );
            }
        
        return $parameter_string;
        
    }
    
    
    /**
    * Formats a number into a human readable string.
    * 
    * eg. 1000
    *   1KB
    * 
    * @param integer $bytes
    * @param integer $precision
    * @return string
    */
    static public function formatBytesToHuman( $bytes, $precision = 2 ){
        
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
      
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
      
        $bytes /= pow(1024, $pow);
      
        return round($bytes, $precision) . ' ' . $units[$pow]; 
        
    }
    
    
    /**
    * Removes all non-alphabetic characters from the given string.
    * 
    * @throws \Exception                    //if string is not a string
    * @param string $string
    */
    static public function stripNonAlphaCharacters( $string ){
        
        \Altumo\Validation\Strings::assertString($string);
        return preg_replace('/[^a-zA-Z]/', '', $string);
        
    }
    
    
    /**
    * Tries to interpret a string value as boolean.
    * 
    * truthy expressions:
    *   - true
    *   - y
    *   - yes
    *   - 1
    *   - t
    * 
    * falsy expressions
    *   - false
    *   - n
    *   - no
    *   - 0
    *   - f
    * 
    * Expressions are evaluated case insensitively.
    * 
    * 
    * @param string $text
    * 
    * @return bool
    */
    static public function convertToBoolean( $text ){
        
        if( is_bool( $text ) ) return $text; 
        
        $true = array( 'true', 'y', 'yes', '1', 't' );
        $false = array( 'false', 'n', 'no', '0', 'f' );
        
        $text = strtolower( trim( $text ) );
        
        if( in_array( $text, $true ) ) {
            return true;
        } elseif( in_array( $text, $false ) ) {
            return false;
        }
        
        return (bool)$text;
    }
    
    
}



<?php

/**#@+
 * @version 0.0.1
 * @since 0.0.1
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * This class will drop " quotes from field names in SQLite results.
 * 
 * @package POT
 * @internal You dont need to know anything about this class.
 * @ignore
 */
class OTS_SQLite_Results extends PDOStatement
{
/**
 * Stripping single array keys.
 * 
 * @param array $record Record row.
 * @return array Record with dropped quotes from field names.
 */
    private function strip($record)
    {
        // if it's end of results
        if(!( is_array($record) || is_object($record) ))
        {
            return $record;
        }

        // our unescaped row
        $row = array();

        // strips quotes from each field
        foreach($record as $name => $value)
        {
            $row[ preg_replace('/^"?(.*?)"?$/', '\\1', $name) ] = $value;
        }

        return $row;
    }

/**
 * Removes quoting delimiters from single row fields.
 * 
 * @return array Record row.
 */
    public function fetch()
    {
        // standard output
        return $this->strip( parent::fetch() );
    }

/**
 * Removes quotes from all result rows.
 * 
 * @return array Records set.
 */
    public function fetchAll()
    {
        $results = array();

        // fetches all results
        foreach( parent::fetchAll() as $index => $record)
        {
            $results[$index] = $this->strip($record);
        }

        return $results;
    }
}

/**#@-*/

?>

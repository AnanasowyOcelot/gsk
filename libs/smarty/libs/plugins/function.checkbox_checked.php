<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {cycle} function plugin
 *
 * Examples:<br>
 * <pre>
 * {cycle values="#eeeeee,#d0d0d0d"}
 * {cycle name=row values="one,two,three" reset=true}
 * {cycle name=row}
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.cycle.php {cycle}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @author credit to Mark Priatel <mpriatel@rogers.com>
 * @author credit to Gerard <gerard@interfold.com>
 * @author credit to Jason Sweat <jsweat_php@yahoo.com>
 * @version  1.3
 * @param array
 * @param Smarty
 * @return string|null
 */
function smarty_function_checkbox_checked($params)
{
    

if (strpos($params['i_warunek'],(string)$params['i_zapytanie']) != '') echo 'checked';
echo "> ";
print_r($params);
echo strpos($params['i_warunek'],(string)$params['i_zapytanie'])." ===";
}

/* vim: set expandtab: */

?>

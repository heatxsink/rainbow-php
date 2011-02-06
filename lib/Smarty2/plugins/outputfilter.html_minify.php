<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty html_minify outputfilter plugin
 *
 * File:     outputfilter.html_minify.php<br>
 * Type:     outputfilter<br>
 * Name:     html_minify<br>
 * Date:     Feb 06, 2011<br>
 * Purpose:  it minifies html yo!
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','html_minify');</code>
 *           from application.
 * @author   Nicholas Granado <ngranado at gmail dot com>
 * @author   Based on outputfilter.trimwhitespace from Monte Ohrt <monte at ohrt dot com>
 * @author   Contributions from Lars Noschinski <lars@usenet.noschinski.de>
 * @version  1.0
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_trimwhitespace($source, &$smarty)
{
    // Pull out the script blocks
    preg_match_all("!<script[^>]*?>.*?</script>!is", $source, $match);
    $_script_blocks = $match[0];
    $source = preg_replace("!<script[^>]*?>.*?</script>!is",
                           '@@@SMARTY:TRIM:SCRIPT@@@', $source);

    // Pull out the pre blocks
    preg_match_all("!<pre[^>]*?>.*?</pre>!is", $source, $match);
    $_pre_blocks = $match[0];
    $source = preg_replace("!<pre[^>]*?>.*?</pre>!is",
                           '@@@SMARTY:TRIM:PRE@@@', $source);

    // Pull out the textarea blocks
    preg_match_all("!<textarea[^>]*?>.*?</textarea>!is", $source, $match);
    $_textarea_blocks = $match[0];
    $source = preg_replace("!<textarea[^>]*?>.*?</textarea>!is",
                           '@@@SMARTY:TRIM:TEXTAREA@@@', $source);

    // remove all leading spaces, tabs and carriage returns NOT
    // preceeded by a php close tag.
    $source = trim(preg_replace('/((?<!\?>)\n)[\s]+/m', '\1', $source));

    //---------- FROM HTML MINIFY

    // remove HTML comments (not containing IE conditional comments).
    $source = preg_replace_callback('/<!--([\\s\\S]*?)-->/', "_commentCB", $source);

    // trim each line.
    // @todo take into account attribute values that span multiple lines.
    $source = preg_replace('/^\\s+|\\s+$/m', '', $source);

    // remove ws around block/undisplayed elements
    $source = preg_replace('/\\s+(<\\/?(?:area|base(?:font)?|blockquote|body'
        .'|caption|center|cite|col(?:group)?|dd|dir|div|dl|dt|fieldset|form'
        .'|frame(?:set)?|h[1-6]|head|hr|html|legend|li|link|map|menu|meta'
        .'|ol|opt(?:group|ion)|p|param|t(?:able|body|head|d|h||r|foot|itle)'
        .'|ul)\\b[^>]*>)/i', '$1', $source);

    // remove ws outside of all elements
    $source = preg_replace_callback('/>([^<]+)</', "_outsideTagCB", $source);

    // use newlines before 1st attribute in open tags (to limit line lengths)
    //$source = preg_replace('/(<[a-z\\-]+)\\s+([^>]+>)/i', "$1\n$2", $source);

	//---------- END HTML MINIFY

    // replace textarea blocks
    smarty_outputfilter_trimwhitespace_replace("@@@SMARTY:TRIM:TEXTAREA@@@",$_textarea_blocks, $source);

    // replace pre blocks
    smarty_outputfilter_trimwhitespace_replace("@@@SMARTY:TRIM:PRE@@@",$_pre_blocks, $source);

    // replace script blocks
    smarty_outputfilter_trimwhitespace_replace("@@@SMARTY:TRIM:SCRIPT@@@",$_script_blocks, $source);

    return $source;
}

function smarty_outputfilter_trimwhitespace_replace($search_str, $replace, &$subject) {
    $_len = strlen($search_str);
    $_pos = 0;
    for ($_i=0, $_count=count($replace); $_i<$_count; $_i++)
        if (($_pos=strpos($subject, $search_str, $_pos))!==false)
            $subject = substr_replace($subject, $replace[$_i], $_pos, $_len);
        else
            break;

}

function _commentCB($m) {
	return (0 === strpos($m[1], '[') || false !== strpos($m[1], '<!['))
			? $m[0]
			: '';
}

function _outsideTagCB($m) {
	return '>' . preg_replace('/^\\s+|\\s+$/', ' ', $m[1]) . '<';
}

?>
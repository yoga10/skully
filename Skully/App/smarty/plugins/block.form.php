<?php
/**
 * Created by Trio Design Team (jay@tgitriodesign.com).
 * Date: 1/17/14
 * Time: 10:35 AM
 */

/**
 * @param array $params
 * @param $content string
 * @param &$smarty Smarty
 * @param &$repeat boolean
 * @return string
 *
 */
function smarty_block_form($params, $content, &$smarty, &$repeat) {
    if (!$repeat) {
        $paramsStr = '';
        $defaultParams = array(
            'method' => 'POST'
        );
        if (!empty($params)) {
            $params = array_merge($defaultParams, $params);
        }
        else {
            $params = $defaultParams;
        }
        foreach ($params as $key => $param) {
            $paramsStr .= $key.'="'.$param.'" ';
        }

        $form = "<form ".$paramsStr.">\n".$content."\n</form>";
        return $form;
    }
}
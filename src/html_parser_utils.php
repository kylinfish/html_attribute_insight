<?php

class HtmlParserUtils
{
    /**
     * getHtmlHeadTagName
     *
     * @param mixed $html_source
     *
     * @return void
     */
    public static function getHtmlHeadTagName($html_source)
    {
        preg_match_all("/<(.*?)\s$)\"/", $html_source, $matches);
        return $matches[0];
    }

    /**
     *
     * @html_source HTML Source Code
     * @return string HTML Head Tag String: body, a, li, div 
     */
    public static function getHtmlHeadString($html_source)
    {
        preg_match_all("/<([a-zA-Z].*?[^?]?)>/", $html_source, $matches);
        return $matches[0];
    }

    public static function getTagName($html_head_tag_str)
    {
        # XXX: Regex Pattern can be improved.
        preg_match("/<(.*?)\s|<(.*)?>/", $html_head_tag_str, $matches);
        return (count($matches) == 3) ? $matches[2] : $matches[1];
    }

    /**
     * getAttrParis
     *
     * @param $source "href="foo.html" class="bar btn">
     *
     * @return array
     */
    public static function getAttrParis($source)
    {
        preg_match_all("/(.*?=\".*?)\"/", $source, $matches);
        return $matches[0];
    }


    /**
     *
     * @param $attr_pair href="foo.html"
     *
     * @return array the key/value pair of parsing attribute
     */
    public static function getAttrKeyValue($attr_pair)
    {
        preg_match_all("/(.*?)=\"(.*?)\"/", $attr_pair, $matches);
        return ["name" => trim($matches[1][0]), "value" => trim($matches[2][0])];
    }
}

<?php
require(__DIR__ . "/src/html_parser_utils.php");
require(__DIR__ . "/src/html_tag_insight.php");


function main($url)
{
    $content = file_get_contents($url, false);
    $html_tag_string_chunks = HtmlParserUtils::getHtmlHeadString($content);

    list(
        $TAG_ATTRS_HASHMAP,
        $TAG_NAME_HASHMAP,
        $CLASS_HASHMAP,
        $SRC_HASHMAP,
        $HREF_HASHMAP,
        $TAG_ATTR_VAL_DISTINCT_HASHMAP,
    ) = parseHtmlChunks($html_tag_string_chunks);


    echo "\n----HTML TAG 統計------\n";
    foreach ($TAG_ATTRS_HASHMAP as $html_tag => $attrs) {
        echo sprintf("%15s - %3d\n", $html_tag, count($attrs));
    }

    echo "\n----A TAG 值分佈--------\n";
    foreach ($TAG_ATTRS_HASHMAP["div"] as $attr_name => $attr_val) {
        echo sprintf("\5%s - %s\n", count($attr_val), $attr_name);
        sort($attr_val);
        foreach ($attr_val as $value) {
            echo sprintf("\t|- %s\n", $value);
        }
    }

    echo "\n----SRC TAG 值分佈------\n";
    foreach ($SRC_HASHMAP as $html_tag => $num) {
        echo sprintf("\5%s - %s\n", $num, $html_tag);
    }

    echo "\n---- 各個標籤屬性獨立統計 ------\n";
    foreach ($TAG_ATTR_VAL_DISTINCT_HASHMAP as $html_tag => $attr) {
        echo $html_tag . ":\n";
        foreach ($attr as $attr_key => $attr_val) {
            echo sprintf("    |- %s\n", $attr_key);
            foreach (array_keys($attr_val) as $value) {
                echo sprintf("        |- %s\n", $value);
            }
        }
    }

    echo "\n---- Class 次數統計------\n";
    foreach ($CLASS_HASHMAP as $class_name => $num) {
        echo sprintf("%50s - %3d\n", $class_name, $num);
    }
}


$url = $argv[1] ?? "https://github.com/";
main($url);

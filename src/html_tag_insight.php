<?php

function parseHtmlChunks(array $html_tag_string_chunks)
{
    $TAG_NAME_HASHMAP = [];
    $TAG_ATTRS_HASHMAP = [];
    $CLASS_HASHMAP = [];
    $SRC_HASHMAP = [];
    $HREF_HASHMAP = [];
    $TAG_ATTR_VAL_DISTINCT_HASHMAP = [];

    foreach ($html_tag_string_chunks as $html_head_tag_string) {
        // 統計 TAG 數量
        // forloop level 1: $tag_name
        $tag_name = HtmlParserUtils::getTagName($html_head_tag_string);
        if (! isset($TAG_NAME_HASHMAP[$tag_name])) {
            $TAG_NAME_HASHMAP[$tag_name] = 0;
        }
        $TAG_NAME_HASHMAP[$tag_name] += 1;

        // 看 Tag 內有什麼屬性
        $attr_slices = explode(" ", $html_head_tag_string, 2);
        $attr_str = $attr_slices[1] ?? "";
        if (empty($attr_str)) {
            # tag without any attributes
            continue;
        }

        $attr_pairs = HtmlParserUtils::getAttrParis($attr_str);
        if (empty($attr_pairs[0])) {
            continue;
        }

        // 屬性統計
        foreach ($attr_pairs as $attr_pair) {
        // forloop level 2: $attr_name, $attr_value
            $attr_result = HtmlParserUtils::getAttrKeyValue($attr_pair);
            $attr_name = $attr_result['name'];
            $attr_value = $attr_result['value'];

            $TAG_ATTRS_HASHMAP[$tag_name][$attr_name][] = $attr_value;

            if ($attr_name == "class") {
                foreach (explode(" ", $attr_value) as $class_attr_val) {
                // forloop level 3: class_attr_val
                    if (!isset($CLASS_HASHMAP[$class_attr_val])) {
                        $CLASS_HASHMAP[$class_attr_val] = 0;
                    }
                    $CLASS_HASHMAP[$class_attr_val] += 1;

                    $TAG_ATTR_VAL_DISTINCT_HASHMAP[$tag_name][$attr_name][$class_attr_val] = "";
                }
            }

            if ($attr_name == "href") {
                if (!isset($HREF_HASHMAP[$attr_value])) {
                    $HREF_HASHMAP[$attr_value] = 0;
                }
                $HREF_HASHMAP[$attr_value] += 1;

                $TAG_ATTR_VAL_DISTINCT_HASHMAP[$tag_name][$attr_name][$attr_value] = "";
            }

            if ($attr_name == "src") {
                if (!isset($SRC_HASHMAP[$attr_value])) {
                    $SRC_HASHMAP[$attr_value] = 0;
                }
                $SRC_HASHMAP[$attr_value] += 1;

                $TAG_ATTR_VAL_DISTINCT_HASHMAP[$tag_name][$attr_name][$attr_value] = "";
            }
        }
    }

    return [
        $TAG_ATTRS_HASHMAP,
        $TAG_NAME_HASHMAP,
        $CLASS_HASHMAP,
        $SRC_HASHMAP,
        $HREF_HASHMAP,
        $TAG_ATTR_VAL_DISTINCT_HASHMAP,
    ];
}

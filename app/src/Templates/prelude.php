<?php

/**
 * @param string $current
 */
function prefecture_options($current = "") {
    $areas = array(
        "北海道・東北地方" => array("北海道","青森県","岩手県","秋田県","宮城県","山形県","福島県"),
        "関東地方" => array("栃木県","群馬県","茨城県","埼玉県","東京都","千葉県","神奈川県"),
        "中部地方" => array("山梨県","長野県","新潟県","富山県","石川県","福井県","静岡県","岐阜県","愛知県"),
        "近畿地方" => array("三重県","滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県"),
        "四国地方" => array("徳島県", "香川県", "愛媛県", "高知県"),
        "中国地方" => array("鳥取県","島根県","岡山県","広島県","山口県"),
        "九州・沖縄地方" => array("福岡県","佐賀県","長崎県","大分県","熊本県","宮崎県","鹿児島県","沖縄県")
    );
    options($areas, $current, false);
}

/**
 * @param string $value
 * @param bool $selected
 * @param string|null $label
 * @param array $attrs
 */
function option($value, $selected = false, $label = null, $attrs = []) {
    $sel = $selected ? "selected=selected" : "";
    $display = $label ?: $value;

    $attrStr = "";
    foreach ($attrs as $attr => $val) {
        $attrStr .= " $attr='$val'";
    }

    echo "<option $attrStr $sel value='$value'>$display</option>";
}

/**
 * @param array $options
 * @param string $current
 * @param bool $labels
 */
function options(array $options, $current = "", $labels = true) {
    foreach ($options as $label => $option) {
        if (is_array($option)) {
            echo "<optgroup label='$label'>";
            options($option, $current, $labels);
            echo "</optgroup>";
        } else {
            option($option, $option === $current, $labels ? $label : null);
        }
    }
}

/**
 * @param string $value
 * @param string $current
 */
function chk($value, $current) {
    echo $value === $current ? "checked=checked" : "";
}

/**
 * @param string $value
 * @param string $current
 */
function slct($value, $current) {
    echo $value === $current ? "selected=selected" : "";
}

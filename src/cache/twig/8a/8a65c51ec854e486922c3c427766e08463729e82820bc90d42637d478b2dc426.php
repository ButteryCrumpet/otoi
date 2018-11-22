<?php

/* @prelude/macros.twig.html */
class __TwigTemplate_b4c96a4d3883100eced6b8f305f092f3f23db69191b93dad6fe39f61e0fc0695 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 22
        echo "
";
        // line 27
        echo "
";
        // line 36
        echo "
";
        // line 48
        echo "
";
    }

    // line 1
    public function macro_prefecture_options($__current__ = "", ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "current" => $__current__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            echo "    ";
            $context["__internal_663333a46f33938a47b316c9665f942e01699ccdc8e3da9a5c2db8a259fe960e"] = $this;
            // line 3
            echo "    ";
            $context["all"] = array("北海道・東北地方" => array(0 => "北海道", 1 => "青森県", 2 => "岩手県", 3 => "秋田県", 4 => "宮城県", 5 => "山形県", 6 => "福島県"), "関東地方" => array(0 => "栃木県", 1 => "群馬県", 2 => "茨城県", 3 => "埼玉県", 4 => "東京都", 5 => "千葉県", 6 => "神奈川県"), "中部地方" => array(0 => "山梨県", 1 => "長野県", 2 => "新潟県", 3 => "富山県", 4 => "石川県", 5 => "福井県", 6 => "静岡県", 7 => "岐阜県", 8 => "愛知県"), "近畿地方" => array(0 => "三重県", 1 => "滋賀県", 2 => "京都府", 3 => "大阪府", 4 => "兵庫県", 5 => "奈良県", 6 => "和歌山県"), "四国地方" => array(0 => "徳島県", 1 => "香川県", 2 => "愛媛県", 3 => "高知県"), "中国地方" => array(0 => "鳥取県", 1 => "島根県", 2 => "岡山県", 3 => "広島県", 4 => "山口県"), "九州・沖縄地方" => array(0 => "福岡県", 1 => "佐賀県", 2 => "長崎県", 3 => "大分県", 4 => "熊本県", 5 => "宮崎県", 6 => "鹿児島県", 7 => "沖縄県"));
            // line 13
            echo "        <option value=\"\">【選択して下さい】</option>
        ";
            // line 14
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["all"] ?? null));
            foreach ($context['_seq'] as $context["area"] => $context["prefs"]) {
                // line 15
                echo "        <optgroup label=\"";
                echo twig_escape_filter($this->env, $context["area"], "html", null, true);
                echo "\">
            ";
                // line 16
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["prefs"]);
                foreach ($context['_seq'] as $context["_key"] => $context["pref"]) {
                    // line 17
                    echo "                ";
                    echo $context["__internal_663333a46f33938a47b316c9665f942e01699ccdc8e3da9a5c2db8a259fe960e"]->macro_option($context["pref"], (($context["current"] ?? null) == $context["pref"]));
                    echo "
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pref'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 19
                echo "        </optgroup>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['area'], $context['prefs'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;

            return ('' === $tmp = ob_get_contents()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 23
    public function macro_option($__value__ = null, $__selected__ = null, $__label__ = "", ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "value" => $__value__,
            "selected" => $__selected__,
            "label" => $__label__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 24
            echo "    ";
            $context["label"] = (((($context["label"] ?? null) == "")) ? (($context["value"] ?? null)) : (($context["label"] ?? null)));
            // line 25
            echo "    <option ";
            if (($context["selected"] ?? null)) {
                echo " selected=\"selected\" ";
            }
            echo " value=\"";
            echo twig_escape_filter($this->env, ($context["value"] ?? null), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, ($context["label"] ?? null), "html", null, true);
            echo "</option>
";

            return ('' === $tmp = ob_get_contents()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 28
    public function macro_errors($__field__ = null, $__wrapper__ = "p", ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "field" => $__field__,
            "wrapper" => $__wrapper__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 29
            echo "    ";
            $context["__internal_0b377e2139cfa25e9ce4368527b2a8096384533f09461e6f544ea9b1759ec0c1"] = $this;
            // line 30
            echo "    ";
            if (twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "errors", array())) {
                // line 31
                echo "        ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "errors", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["err"]) {
                    // line 32
                    echo "            <";
                    echo twig_escape_filter($this->env, ($context["wrapper"] ?? null), "html", null, true);
                    echo ">";
                    echo $context["__internal_0b377e2139cfa25e9ce4368527b2a8096384533f09461e6f544ea9b1759ec0c1"]->macro_get_error_message($context["err"], twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "label", array()), twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "type", array()));
                    echo "</";
                    echo twig_escape_filter($this->env, ($context["wrapper"] ?? null), "html", null, true);
                    echo ">
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['err'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 34
                echo "    ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 37
    public function macro_get_error_message($__error__ = null, $__label__ = null, $__type__ = "text", ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "error" => $__error__,
            "label" => $__label__,
            "type" => $__type__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 38
            echo "    ";
            if ((($context["error"] ?? null) == "required")) {
                // line 39
                echo "        ";
                $context["str"] = ((((($context["type"] ?? null) == "radio") || (($context["type"] ?? null) == "select"))) ? ("※%sを選択してください。") : ("※%sを入力してください。"));
                // line 43
                echo "        ";
                echo twig_escape_filter($this->env, sprintf(($context["str"] ?? null), ($context["label"] ?? null)), "html", null, true);
                echo "
    ";
            } else {
                // line 45
                echo "        ";
                echo twig_escape_filter($this->env, sprintf("正しい%sの形式でご入力頂けますようお願い致します", ($context["label"] ?? null)), "html", null, true);
                echo "
    ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 49
    public function macro_input($__field__ = null, $__class__ = "", $__id__ = "", ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "field" => $__field__,
            "class" => $__class__,
            "id" => $__id__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 50
            echo "    ";
            if ((twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "type", array()) == "select")) {
                // line 51
                echo "    ";
            } elseif ((twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "type", array()) == "radio")) {
                // line 52
                echo "    ";
            } elseif ((twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "type", array()) == "checkbox")) {
                // line 53
                echo "    ";
            } elseif ((twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "type", array()) == "textarea")) {
                // line 54
                echo "    ";
            } else {
                // line 55
                echo "        <input class=\"";
                echo twig_escape_filter($this->env, ($context["class"] ?? null), "html", null, true);
                echo "\"
                id=\"";
                // line 56
                echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
                echo "\"
               value=\"";
                // line 57
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "value", array()), "html", null, true);
                echo "\"
               type=\"";
                // line 58
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "type", array()), "html", null, true);
                echo "\"
               name=\"";
                // line 59
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "name", array()), "html", null, true);
                echo "\"
               value=\"";
                // line 60
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "value", array()), "html", null, true);
                echo "\"
               placeholder=\"";
                // line 61
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["field"] ?? null), "placeholder", array()), "html", null, true);
                echo "\"
        >
    ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "@prelude/macros.twig.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  269 => 61,  265 => 60,  261 => 59,  257 => 58,  253 => 57,  249 => 56,  244 => 55,  241 => 54,  238 => 53,  235 => 52,  232 => 51,  229 => 50,  215 => 49,  202 => 45,  196 => 43,  193 => 39,  190 => 38,  176 => 37,  166 => 34,  153 => 32,  148 => 31,  145 => 30,  142 => 29,  129 => 28,  111 => 25,  108 => 24,  94 => 23,  80 => 19,  71 => 17,  67 => 16,  62 => 15,  58 => 14,  55 => 13,  52 => 3,  49 => 2,  37 => 1,  32 => 48,  29 => 36,  26 => 27,  23 => 22,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% macro prefecture_options(current = \"\") %}
    {% from _self import option %}
    {% set all = {
            \"北海道・東北地方\": [\"北海道\",\"青森県\",\"岩手県\",\"秋田県\",\"宮城県\",\"山形県\",\"福島県\"],
            \"関東地方\": [\"栃木県\",\"群馬県\",\"茨城県\",\"埼玉県\",\"東京都\",\"千葉県\",\"神奈川県\"],
            \"中部地方\": [\"山梨県\",\"長野県\",\"新潟県\",\"富山県\",\"石川県\",\"福井県\",\"静岡県\",\"岐阜県\",\"愛知県\"],
            \"近畿地方\": [\"三重県\",\"滋賀県\",\"京都府\",\"大阪府\",\"兵庫県\",\"奈良県\",\"和歌山県\"],
            \"四国地方\": [\"徳島県\",\"香川県\",\"愛媛県\",\"高知県\"],
            \"中国地方\": [\"鳥取県\",\"島根県\",\"岡山県\",\"広島県\",\"山口県\"],
            \"九州・沖縄地方\": [\"福岡県\",\"佐賀県\",\"長崎県\",\"大分県\",\"熊本県\",\"宮崎県\",\"鹿児島県\",\"沖縄県\"],
        }
    %}
        <option value=\"\">【選択して下さい】</option>
        {% for area,prefs in all %}
        <optgroup label=\"{{ area }}\">
            {% for pref in prefs %}
                {{ option(pref, current == pref) }}
            {% endfor %}
        </optgroup>
        {% endfor %}
{% endmacro %}

{% macro option(value, selected, label = \"\") %}
    {% set label = label == \"\" ? value : label %}
    <option {% if selected %} selected=\"selected\" {% endif %} value=\"{{value}}\">{{label}}</option>
{% endmacro %}

{% macro errors(field, wrapper = \"p\") %}
    {% from _self import get_error_message %}
    {% if field.errors %}
        {% for err in field.errors %}
            <{{ wrapper }}>{{ get_error_message(err, field.label, field.type) }}</{{ wrapper }}>
        {% endfor %}
    {% endif %}
{% endmacro %}

{% macro get_error_message(error, label, type = \"text\") %}
    {% if error == \"required\" %}
        {% set str = type == \"radio\" or type == \"select\"
            ? \"※%sを選択してください。\"
            : \"※%sを入力してください。\"
        %}
        {{ str|format(label)}}
    {% else %}
        {{\"正しい%sの形式でご入力頂けますようお願い致します\"|format(label)}}
    {% endif %}
{% endmacro %}

{% macro input(field, class=\"\", id=\"\") %}
    {% if field.type == \"select\" %}
    {% elseif field.type == \"radio\" %}
    {% elseif field.type == \"checkbox\" %}
    {% elseif field.type == \"textarea\" %}
    {% else %}
        <input class=\"{{ class }}\"
                id=\"{{ id }}\"
               value=\"{{field.value}}\"
               type=\"{{ field.type }}\"
               name=\"{{ field.name }}\"
               value=\"{{ field.value }}\"
               placeholder=\"{{ field.placeholder }}\"
        >
    {% endif %}
{% endmacro %}", "@prelude/macros.twig.html", "/var/www/html/lib/Otoi/src/templates/prelude/macros.twig.html");
    }
}

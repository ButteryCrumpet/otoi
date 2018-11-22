<?php

/* default/index.twig.html */
class __TwigTemplate_33a1b32c51bc7d9672309cee5d78e95074b7e9aad2318389168cc8f8065affde extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig.html", "default/index.twig.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.twig.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["otoi"] = $this->loadTemplate("@prelude/macros.twig.html", "default/index.twig.html", 2);
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "    <form action=\"/contact/confirm\" method=\"post\">
        <dl>
            <dt class=\"hissu\">
                <h3>ご希望内容</h3>
            </dt>
            <dd>
                <div class=\"custom-radio\">
                    <input type=\"radio\" id=\"kibo1\" name=\"ご希望内容\" value=\"お問い合わせ\" required checked /><label for=\"kibo1\">お問い合わせ</label>
                    <input type=\"radio\" id=\"kibo2\" name=\"ご希望内容\" value=\"資料請求\" /><label for=\"kibo2\">資料請求</label>
                    <input type=\"radio\" id=\"kibo3\" name=\"ご希望内容\" value=\"来館予約\" /><label for=\"kibo3\">来館予約</label>
                    <div class=\"err\">";
        // line 15
        echo $context["otoi"]->macro_errors((($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["form"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5["ご希望内容"] ?? null) : null));
        echo "</div>
                </div>
            </dd>
            <dt class=\"hissu\">
                <h3>ご希望会場<br /><span>※複数選択可</span></h3>
            </dt>
            <dd>

                <div class=\"custom-check\">
                    <input type=\"checkbox\" id=\"kaizyou1\" name=\"ご希望会場[]\" value=\"パーティーギャラリーイヤタカ\" required checked /><label for=\"kaizyou1\">パーティーギャラリーイヤタカ</label>
                    <input type=\"checkbox\" id=\"kaizyou2\" name=\"ご希望会場[]\" value=\"ゲストハウスヴァレリアーノ\" /><label for=\"kaizyou2\">ゲストハウスヴァレリアーノ</label>
                    <input type=\"checkbox\" id=\"kaizyou3\" name=\"ご希望会場[]\" value=\"ウェディングヒルズ御所野\" /><label for=\"kaizyou3\">ウェディングヒルズ御所野</label>
                    <input type=\"checkbox\" id=\"kaizyou4\" name=\"ご希望会場[]\" value=\"フレンチレストラン千秋亭\" /><label for=\"kaizyou4\">フレンチレストラン千秋亭</label>
                    <input type=\"checkbox\" id=\"kaizyou5\" name=\"ご希望会場[]\" value=\"ゲストハウス平源\" /><label for=\"kaizyou5\">ゲストハウス平源</label>
                </div>
                <div class=\"err\">";
        // line 30
        echo $context["otoi"]->macro_errors((($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = ($context["form"] ?? null)) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a["ご希望会場"] ?? null) : null));
        echo "</div>
            </dd>

            <dt class=\"hissu\">
                <h3>ご来店希望日</h3>
            </dt>
            <dd>
                <input value=\"";
        // line 37
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = ($context["form"] ?? null)) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57["ご来店希望日"] ?? null) : null), "value", array()), "html", null, true);
        echo "\" name=\"ご来店希望日\" class=\"input\" id=\"myInput\" type=\"text\" />
                <div class=\"err\">";
        // line 38
        echo $context["otoi"]->macro_errors((($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = ($context["form"] ?? null)) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9["ご来店希望日"] ?? null) : null));
        echo "</div>
            </dd>

            <dt class=\"hissu\">
                <h3>ご来店希望時間</h3>
            </dt>
            <dd>
                <div class=\"custom-select\">
                    <select name=\"ご来店希望時間\">
                        <option value=\"\">希望時間を選択してください</option>
                        <option value=\"10:00\">10:00</option>
                        <option value=\"10:30\">10:30</option>
                        <option value=\"11:00\">11:00</option>
                        <option value=\"11:30\">11:30</option>
                        <option value=\"12:00\">12:00</option>
                        <option value=\"12:30\">12:30</option>
                        <option value=\"13:00\">13:00</option>
                        <option value=\"13:30\">13:30</option>
                        <option value=\"14:00\">14:00</option>
                        <option value=\"14:30\">14:30</option>
                        <option value=\"15:00\">15:00</option>
                        <option value=\"15:30\">15:30</option>
                        <option value=\"16:00\">16:00</option>
                        <option value=\"16:30\">16:30</option>
                        <option value=\"17:00\">17:00</option>
                        <option value=\"17:30\">17:30</option>
                        <option value=\"18:00\">18:00</option>
                    </select>
                </div>
                <div class=\"err\">";
        // line 67
        echo $context["otoi"]->macro_errors((($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 = ($context["form"] ?? null)) && is_array($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217) || $__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217 instanceof ArrayAccess ? ($__internal_add9db1f328aaed12ef1a33890510da978cc9cf3e50f6769d368473a9c90c217["ご来店希望時間"] ?? null) : null));
        echo "</div>
            </dd>

            <dt class=\"hissu\">
                <h3>お名前</h3>
            </dt>
            <dd>
                ";
        // line 74
        echo $context["otoi"]->macro_input((($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 = ($context["form"] ?? null)) && is_array($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105) || $__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105 instanceof ArrayAccess ? ($__internal_128c19eb75d89ae9acc1294da2e091b433005202cb9b9351ea0c5dd5f69ee105["お名前"] ?? null) : null));
        echo "
                <div class=\"err\">";
        // line 75
        echo $context["otoi"]->macro_errors((($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 = ($context["form"] ?? null)) && is_array($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779) || $__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779 instanceof ArrayAccess ? ($__internal_921de08f973aabd87ecb31654784e2efda7404f12bd27e8e56991608c76e7779["お名前"] ?? null) : null));
        echo "</div>
            </dd>
            <dt class=\"hissu\">
                <h3>ふりがな</h3>
            </dt>
            <dd>
                ";
        // line 81
        echo $context["otoi"]->macro_input((($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 = ($context["form"] ?? null)) && is_array($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1) || $__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1 instanceof ArrayAccess ? ($__internal_3e040fa9f9bcf48a8b054d0953f4fffdaf331dc44bc1d96f1bb45abb085e61d1["ふりがな"] ?? null) : null));
        echo "
                <div class=\"err\">";
        // line 82
        echo $context["otoi"]->macro_errors((($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0 = ($context["form"] ?? null)) && is_array($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0) || $__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0 instanceof ArrayAccess ? ($__internal_bd1cf16c37e30917ff4f54b7320429bcc2bb63615cd8a735bfe06a3f1b5c82a0["ふりがな"] ?? null) : null));
        echo "</div>
            </dd>
            <dt class=\"hissu\">
                <h3>メールアドレス</h3>
            </dt>
            <dd>
                <input type=\"email\" name=\"メールアドレス\" value=\"\" placeholder=\"例）example@iyataka.co.jp\">
                <div class=\"err\">";
        // line 89
        echo $context["otoi"]->macro_errors((($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866 = ($context["form"] ?? null)) && is_array($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866) || $__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866 instanceof ArrayAccess ? ($__internal_602f93ae9072ac758dc9cd47ca50516bbc1210f73d2a40b01287f102c3c40866["メールアドレス"] ?? null) : null));
        echo "</div>
            </dd>

            <dt class=\"hissu\">
                <h3>電話番号</h3>
            </dt>
            <dd>
                <input type=\"tel\" name=\"電話番号\" value=\"\" placeholder=\"例）090-0000-0000\">
                <div class=\"err\">";
        // line 97
        echo $context["otoi"]->macro_errors((($__internal_de222b1ef20cf829a938a4545cbb79f4996337944397dd43b1919bce7726ae2f = ($context["form"] ?? null)) && is_array($__internal_de222b1ef20cf829a938a4545cbb79f4996337944397dd43b1919bce7726ae2f) || $__internal_de222b1ef20cf829a938a4545cbb79f4996337944397dd43b1919bce7726ae2f instanceof ArrayAccess ? ($__internal_de222b1ef20cf829a938a4545cbb79f4996337944397dd43b1919bce7726ae2f["電話番号"] ?? null) : null));
        echo "</div>
            </dd>

            <dt class=\"hissu\">
                <h3>住所</h3>
            </dt>
            <dd>
                <p class=\"mid\">郵便番号</p>
                <input type=\"text\" name=\"郵便番号\" value=\"\" placeholder=\"例）018-0000\">
                <div class=\"err\">";
        // line 106
        echo $context["otoi"]->macro_errors((($__internal_517751e212021442e58cf8c5cde586337a42455f06659ad64a123ef99fab52e7 = ($context["form"] ?? null)) && is_array($__internal_517751e212021442e58cf8c5cde586337a42455f06659ad64a123ef99fab52e7) || $__internal_517751e212021442e58cf8c5cde586337a42455f06659ad64a123ef99fab52e7 instanceof ArrayAccess ? ($__internal_517751e212021442e58cf8c5cde586337a42455f06659ad64a123ef99fab52e7["郵便番号"] ?? null) : null));
        echo "</div>
                <p class=\"mid\">都道府県</p>
                <div class=\"custom-select\">
                    <select name=\"都道府県\" class=\"mgn\">
                        ";
        // line 110
        echo $context["otoi"]->macro_prefecture_options(twig_get_attribute($this->env, $this->source, (($__internal_89dde7175ba0b16509237b3e9e7cf99ba9e1b72bd3e7efcbe667781538aca289 = ($context["form"] ?? null)) && is_array($__internal_89dde7175ba0b16509237b3e9e7cf99ba9e1b72bd3e7efcbe667781538aca289) || $__internal_89dde7175ba0b16509237b3e9e7cf99ba9e1b72bd3e7efcbe667781538aca289 instanceof ArrayAccess ? ($__internal_89dde7175ba0b16509237b3e9e7cf99ba9e1b72bd3e7efcbe667781538aca289["都道府県"] ?? null) : null), "value", array()));
        echo "
                    </select>
                    <div class=\"err\">";
        // line 112
        echo $context["otoi"]->macro_errors((($__internal_869a4b51bf6f65c335ddd8115360d724846983ee5a04751d683ca60a03391d18 = ($context["form"] ?? null)) && is_array($__internal_869a4b51bf6f65c335ddd8115360d724846983ee5a04751d683ca60a03391d18) || $__internal_869a4b51bf6f65c335ddd8115360d724846983ee5a04751d683ca60a03391d18 instanceof ArrayAccess ? ($__internal_869a4b51bf6f65c335ddd8115360d724846983ee5a04751d683ca60a03391d18["都道府県"] ?? null) : null));
        echo "</div>
                </div>

                <p class=\"mid\">市区町村</p>
                <input type=\"text\" name=\"市区町村\" value=\"\" placeholder=\"例）018-0000\">
                <div class=\"err\">";
        // line 117
        echo $context["otoi"]->macro_errors((($__internal_90d913d778d5b09eba503796cc624cad16d1bef853f6e54f02eb01d7ed891018 = ($context["form"] ?? null)) && is_array($__internal_90d913d778d5b09eba503796cc624cad16d1bef853f6e54f02eb01d7ed891018) || $__internal_90d913d778d5b09eba503796cc624cad16d1bef853f6e54f02eb01d7ed891018 instanceof ArrayAccess ? ($__internal_90d913d778d5b09eba503796cc624cad16d1bef853f6e54f02eb01d7ed891018["市区町村"] ?? null) : null));
        echo "</div>

                <p class=\"mid\">番地マンション・アパート名</p>
                <input type=\"text\" name=\"番地マンション・アパート名\" value=\"\" placeholder=\"例）018-0000\">
                <div class=\"err\">";
        // line 121
        echo $context["otoi"]->macro_errors((($__internal_5c0169d493d4872ad26d34703fc2ce22459eddaa09bc03024c8105160dc27413 = ($context["form"] ?? null)) && is_array($__internal_5c0169d493d4872ad26d34703fc2ce22459eddaa09bc03024c8105160dc27413) || $__internal_5c0169d493d4872ad26d34703fc2ce22459eddaa09bc03024c8105160dc27413 instanceof ArrayAccess ? ($__internal_5c0169d493d4872ad26d34703fc2ce22459eddaa09bc03024c8105160dc27413["番地マンション・アパート名"] ?? null) : null));
        echo "</div>
            </dd>

            <dt class=\"hissu\">
                <h3>ご質問</h3>
            </dt>
            <dd>
                <textarea name=\"ご質問\"></textarea>
                <div class=\"err\">";
        // line 129
        echo $context["otoi"]->macro_errors((($__internal_a5ce050c56e2fa0d875fbc5d7e5a277df72ffc991bd0164f3c078803c5d7b4e7 = ($context["form"] ?? null)) && is_array($__internal_a5ce050c56e2fa0d875fbc5d7e5a277df72ffc991bd0164f3c078803c5d7b4e7) || $__internal_a5ce050c56e2fa0d875fbc5d7e5a277df72ffc991bd0164f3c078803c5d7b4e7 instanceof ArrayAccess ? ($__internal_a5ce050c56e2fa0d875fbc5d7e5a277df72ffc991bd0164f3c078803c5d7b4e7["ご質問"] ?? null) : null));
        echo "</div>
            </dd>
        </dl>
        <div class=\"send-buttons\">
            <p class=\"btn\"><input type=\"submit\" value=\"内容を送信\"></p>
        </div>
    </form>
";
    }

    public function getTemplateName()
    {
        return "default/index.twig.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  212 => 129,  201 => 121,  194 => 117,  186 => 112,  181 => 110,  174 => 106,  162 => 97,  151 => 89,  141 => 82,  137 => 81,  128 => 75,  124 => 74,  114 => 67,  82 => 38,  78 => 37,  68 => 30,  50 => 15,  38 => 5,  35 => 4,  31 => 1,  29 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.twig.html\" %}
{% import \"@prelude/macros.twig.html\" as otoi %}

{% block content %}
    <form action=\"/contact/confirm\" method=\"post\">
        <dl>
            <dt class=\"hissu\">
                <h3>ご希望内容</h3>
            </dt>
            <dd>
                <div class=\"custom-radio\">
                    <input type=\"radio\" id=\"kibo1\" name=\"ご希望内容\" value=\"お問い合わせ\" required checked /><label for=\"kibo1\">お問い合わせ</label>
                    <input type=\"radio\" id=\"kibo2\" name=\"ご希望内容\" value=\"資料請求\" /><label for=\"kibo2\">資料請求</label>
                    <input type=\"radio\" id=\"kibo3\" name=\"ご希望内容\" value=\"来館予約\" /><label for=\"kibo3\">来館予約</label>
                    <div class=\"err\">{{ otoi.errors(form[\"ご希望内容\"]) }}</div>
                </div>
            </dd>
            <dt class=\"hissu\">
                <h3>ご希望会場<br /><span>※複数選択可</span></h3>
            </dt>
            <dd>

                <div class=\"custom-check\">
                    <input type=\"checkbox\" id=\"kaizyou1\" name=\"ご希望会場[]\" value=\"パーティーギャラリーイヤタカ\" required checked /><label for=\"kaizyou1\">パーティーギャラリーイヤタカ</label>
                    <input type=\"checkbox\" id=\"kaizyou2\" name=\"ご希望会場[]\" value=\"ゲストハウスヴァレリアーノ\" /><label for=\"kaizyou2\">ゲストハウスヴァレリアーノ</label>
                    <input type=\"checkbox\" id=\"kaizyou3\" name=\"ご希望会場[]\" value=\"ウェディングヒルズ御所野\" /><label for=\"kaizyou3\">ウェディングヒルズ御所野</label>
                    <input type=\"checkbox\" id=\"kaizyou4\" name=\"ご希望会場[]\" value=\"フレンチレストラン千秋亭\" /><label for=\"kaizyou4\">フレンチレストラン千秋亭</label>
                    <input type=\"checkbox\" id=\"kaizyou5\" name=\"ご希望会場[]\" value=\"ゲストハウス平源\" /><label for=\"kaizyou5\">ゲストハウス平源</label>
                </div>
                <div class=\"err\">{{ otoi.errors(form[\"ご希望会場\"]) }}</div>
            </dd>

            <dt class=\"hissu\">
                <h3>ご来店希望日</h3>
            </dt>
            <dd>
                <input value=\"{{form[\"ご来店希望日\"].value}}\" name=\"ご来店希望日\" class=\"input\" id=\"myInput\" type=\"text\" />
                <div class=\"err\">{{ otoi.errors(form[\"ご来店希望日\"]) }}</div>
            </dd>

            <dt class=\"hissu\">
                <h3>ご来店希望時間</h3>
            </dt>
            <dd>
                <div class=\"custom-select\">
                    <select name=\"ご来店希望時間\">
                        <option value=\"\">希望時間を選択してください</option>
                        <option value=\"10:00\">10:00</option>
                        <option value=\"10:30\">10:30</option>
                        <option value=\"11:00\">11:00</option>
                        <option value=\"11:30\">11:30</option>
                        <option value=\"12:00\">12:00</option>
                        <option value=\"12:30\">12:30</option>
                        <option value=\"13:00\">13:00</option>
                        <option value=\"13:30\">13:30</option>
                        <option value=\"14:00\">14:00</option>
                        <option value=\"14:30\">14:30</option>
                        <option value=\"15:00\">15:00</option>
                        <option value=\"15:30\">15:30</option>
                        <option value=\"16:00\">16:00</option>
                        <option value=\"16:30\">16:30</option>
                        <option value=\"17:00\">17:00</option>
                        <option value=\"17:30\">17:30</option>
                        <option value=\"18:00\">18:00</option>
                    </select>
                </div>
                <div class=\"err\">{{ otoi.errors(form[\"ご来店希望時間\"]) }}</div>
            </dd>

            <dt class=\"hissu\">
                <h3>お名前</h3>
            </dt>
            <dd>
                {{ otoi.input(form[\"お名前\"]) }}
                <div class=\"err\">{{ otoi.errors(form[\"お名前\"]) }}</div>
            </dd>
            <dt class=\"hissu\">
                <h3>ふりがな</h3>
            </dt>
            <dd>
                {{ otoi.input(form[\"ふりがな\"]) }}
                <div class=\"err\">{{ otoi.errors(form[\"ふりがな\"]) }}</div>
            </dd>
            <dt class=\"hissu\">
                <h3>メールアドレス</h3>
            </dt>
            <dd>
                <input type=\"email\" name=\"メールアドレス\" value=\"\" placeholder=\"例）example@iyataka.co.jp\">
                <div class=\"err\">{{ otoi.errors(form[\"メールアドレス\"]) }}</div>
            </dd>

            <dt class=\"hissu\">
                <h3>電話番号</h3>
            </dt>
            <dd>
                <input type=\"tel\" name=\"電話番号\" value=\"\" placeholder=\"例）090-0000-0000\">
                <div class=\"err\">{{ otoi.errors(form[\"電話番号\"]) }}</div>
            </dd>

            <dt class=\"hissu\">
                <h3>住所</h3>
            </dt>
            <dd>
                <p class=\"mid\">郵便番号</p>
                <input type=\"text\" name=\"郵便番号\" value=\"\" placeholder=\"例）018-0000\">
                <div class=\"err\">{{ otoi.errors(form[\"郵便番号\"]) }}</div>
                <p class=\"mid\">都道府県</p>
                <div class=\"custom-select\">
                    <select name=\"都道府県\" class=\"mgn\">
                        {{ otoi.prefecture_options(form[\"都道府県\"].value) }}
                    </select>
                    <div class=\"err\">{{ otoi.errors(form[\"都道府県\"]) }}</div>
                </div>

                <p class=\"mid\">市区町村</p>
                <input type=\"text\" name=\"市区町村\" value=\"\" placeholder=\"例）018-0000\">
                <div class=\"err\">{{ otoi.errors(form[\"市区町村\"]) }}</div>

                <p class=\"mid\">番地マンション・アパート名</p>
                <input type=\"text\" name=\"番地マンション・アパート名\" value=\"\" placeholder=\"例）018-0000\">
                <div class=\"err\">{{ otoi.errors(form[\"番地マンション・アパート名\"]) }}</div>
            </dd>

            <dt class=\"hissu\">
                <h3>ご質問</h3>
            </dt>
            <dd>
                <textarea name=\"ご質問\"></textarea>
                <div class=\"err\">{{ otoi.errors(form[\"ご質問\"]) }}</div>
            </dd>
        </dl>
        <div class=\"send-buttons\">
            <p class=\"btn\"><input type=\"submit\" value=\"内容を送信\"></p>
        </div>
    </form>
{% endblock %}", "default/index.twig.html", "/var/www/html/group_top/contact/templates/default/index.twig.html");
    }
}

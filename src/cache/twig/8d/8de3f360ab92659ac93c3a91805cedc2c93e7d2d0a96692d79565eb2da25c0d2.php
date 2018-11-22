<?php

/* base.twig.html */
class __TwigTemplate_70b14102a115bbf11a5ca3f18e77e6f21ecba444bdc25e4e5154821913da61f7 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<?php
require_once(\$_SERVER['DOCUMENT_ROOT'].'/inc/class.php');
require_once(\$_SERVER['DOCUMENT_ROOT'].'/inc/config.php');
?>
<!DOCTYPE html>
<html>
<head>

    <title>【公式】秋田の結婚式をするならイヤタカウェディング </title>
    <meta charset=\"utf-8\">
    <?php echo getViewport();?>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
    <meta http-equiv=\"content-language\" content=\"ja\">
    <link rel=\"stylesheet\" href=\"/assets/css/style.min.css\">
    <link rel=\"stylesheet\" href=\"//unpkg.com/flatpickr/dist/flatpickr.min.css\">
    <link rel=\"stylesheet\" href=\"https://npmcdn.com/flatpickr/dist/themes/dark.css\">

    <meta name=\"description\" content=\"\">
    <meta property=\"og:title\" content=\"\" />
    <meta property=\"og:type\" content=\"website\" />
    <meta property=\"og:url\" content=\"\" />
    <meta property=\"og:image\" content=\"/assets/images/og/og_img.jpg\" />
    <meta property=\"og:site_name\" content=\"イヤタカブライダル\" />
    <meta property=\"og:description\" content=\"\" />
    <meta name=\"twitter:card\" content=\"summary\" />
    <meta name=\"twitter:title\" content=\"イヤタカブライダル\" />
    <meta name=\"twitter:description\" content=\"\" />
    <meta name=\"twitter:image\" content=\"/assets/images/og/og_img.jpg\" />
    <meta itemprop=\"image\" content=\"/assets/images/og/og_img.jpg\" />
    <meta name=\"format-detection\" content=\"telephone=no\">
    <link rel=\"shortcut icon\" href=\"/assets/images/og/favicon.ico\">
    <link rel=\"icon\" href=\"/assets/images/og/ico_32x32.jpg\" sizes=\"32x32\" />
    <link rel=\"icon\" href=\"/assets/images/og/ico_192x192.jpg\" sizes=\"192x192\" />
    <link rel=\"apple-touch-icon-precomposed\" href=\"/assets/images/og/ico_180x180.jpg\" />
    <link rel=\"canonical\" href=\"\" />
</head>
<body id=\"TOP\">
<div class=\"wrapper\">
    ";
        // line 39
        echo call_user_func_array($this->env->getFunction('require_php')->getCallable(), array((($context["ROOT"] ?? null) . "/inc/header.php")));
        echo "
    ";
        // line 40
        echo call_user_func_array($this->env->getFunction('require_php')->getCallable(), array((($context["ROOT"] ?? null) . "/inc/navi.php")));
        echo "
    <section id=\"contact-co\">
        <div class=\"ulc-title\">
            <figure class=\"bg\" data-bg=\"/assets/images/news/title_back.jpg\"></figure>
            <h1><span>CONTACT</span><br />お問い合わせ・来館予約</h1>
            <p>テキストがはいります。テキストがはいります。テキストがはいります。</p>
        </div>

        <div class=\"form-field\">
            <div class=\"form-block\">
                <div class=\"form-block-inner\">
                    ";
        // line 51
        $this->displayBlock('content', $context, $blocks);
        // line 52
        echo "                </div>
            </div>
        </div>

    </section>

    ";
        // line 58
        echo call_user_func_array($this->env->getFunction('require_php')->getCallable(), array((($context["ROOT"] ?? null) . "/inc/footer.php")));
        echo "
</div>
<script src=\"/assets/js/all.min.js\"></script>
<script src=\"//unpkg.com/flatpickr\"></script>
<script src=\"//unpkg.com/flatpickr/dist/l10n/ja.js\"></script>
<script type=\"text/javascript\">
  flatpickr(\"#myInput\", {
    locale: \"ja\", // 日本語を適応
    dateFormat: \"Y年m月d日\",
  });
</script>
</body>
</html>
";
    }

    // line 51
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "base.twig.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  110 => 51,  92 => 58,  84 => 52,  82 => 51,  68 => 40,  64 => 39,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<?php
require_once(\$_SERVER['DOCUMENT_ROOT'].'/inc/class.php');
require_once(\$_SERVER['DOCUMENT_ROOT'].'/inc/config.php');
?>
<!DOCTYPE html>
<html>
<head>

    <title>【公式】秋田の結婚式をするならイヤタカウェディング </title>
    <meta charset=\"utf-8\">
    <?php echo getViewport();?>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
    <meta http-equiv=\"content-language\" content=\"ja\">
    <link rel=\"stylesheet\" href=\"/assets/css/style.min.css\">
    <link rel=\"stylesheet\" href=\"//unpkg.com/flatpickr/dist/flatpickr.min.css\">
    <link rel=\"stylesheet\" href=\"https://npmcdn.com/flatpickr/dist/themes/dark.css\">

    <meta name=\"description\" content=\"\">
    <meta property=\"og:title\" content=\"\" />
    <meta property=\"og:type\" content=\"website\" />
    <meta property=\"og:url\" content=\"\" />
    <meta property=\"og:image\" content=\"/assets/images/og/og_img.jpg\" />
    <meta property=\"og:site_name\" content=\"イヤタカブライダル\" />
    <meta property=\"og:description\" content=\"\" />
    <meta name=\"twitter:card\" content=\"summary\" />
    <meta name=\"twitter:title\" content=\"イヤタカブライダル\" />
    <meta name=\"twitter:description\" content=\"\" />
    <meta name=\"twitter:image\" content=\"/assets/images/og/og_img.jpg\" />
    <meta itemprop=\"image\" content=\"/assets/images/og/og_img.jpg\" />
    <meta name=\"format-detection\" content=\"telephone=no\">
    <link rel=\"shortcut icon\" href=\"/assets/images/og/favicon.ico\">
    <link rel=\"icon\" href=\"/assets/images/og/ico_32x32.jpg\" sizes=\"32x32\" />
    <link rel=\"icon\" href=\"/assets/images/og/ico_192x192.jpg\" sizes=\"192x192\" />
    <link rel=\"apple-touch-icon-precomposed\" href=\"/assets/images/og/ico_180x180.jpg\" />
    <link rel=\"canonical\" href=\"\" />
</head>
<body id=\"TOP\">
<div class=\"wrapper\">
    {{ require_php(ROOT ~ \"/inc/header.php\")|raw }}
    {{ require_php(ROOT ~ \"/inc/navi.php\")|raw }}
    <section id=\"contact-co\">
        <div class=\"ulc-title\">
            <figure class=\"bg\" data-bg=\"/assets/images/news/title_back.jpg\"></figure>
            <h1><span>CONTACT</span><br />お問い合わせ・来館予約</h1>
            <p>テキストがはいります。テキストがはいります。テキストがはいります。</p>
        </div>

        <div class=\"form-field\">
            <div class=\"form-block\">
                <div class=\"form-block-inner\">
                    {% block content %}{% endblock %}
                </div>
            </div>
        </div>

    </section>

    {{ require_php(ROOT ~ \"/inc/footer.php\")|raw }}
</div>
<script src=\"/assets/js/all.min.js\"></script>
<script src=\"//unpkg.com/flatpickr\"></script>
<script src=\"//unpkg.com/flatpickr/dist/l10n/ja.js\"></script>
<script type=\"text/javascript\">
  flatpickr(\"#myInput\", {
    locale: \"ja\", // 日本語を適応
    dateFormat: \"Y年m月d日\",
  });
</script>
</body>
</html>
", "base.twig.html", "/var/www/html/group_top/contact/templates/base.twig.html");
    }
}

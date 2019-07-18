<?php

/* html-header.twig */
class __TwigTemplate_1a55c9ca4ecc7b160c12144e8c860bac1e23caf1f6aa9bd3a452c2771e921067 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<!--[if lt IE 7]><html class=\"no-js ie ie6 lt-ie9 lt-ie8 lt-ie7\" ";
        // line 2
        echo $this->getAttribute(($context["site"] ?? null), "language_attributes", array());
        echo "> <![endif]-->
<!--[if IE 7]><html class=\"no-js ie ie7 lt-ie9 lt-ie8\" ";
        // line 3
        echo $this->getAttribute(($context["site"] ?? null), "language_attributes", array());
        echo "> <![endif]-->
<!--[if IE 8]><html class=\"no-js ie ie8 lt-ie9\" ";
        // line 4
        echo $this->getAttribute(($context["site"] ?? null), "language_attributes", array());
        echo "> <![endif]-->
<!--[if gt IE 8]><!--><html class=\"no-js\" ";
        // line 5
        echo $this->getAttribute(($context["site"] ?? null), "language_attributes", array());
        echo "> <!--<![endif]-->
<head>
";
        // line 8
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cloud.typography.com/7014452/7646592/css/fonts.css\" />
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
<meta name=\"format-detection\" content=\"telephone=no\">
<link rel=\"pingback\" href=\"";
        // line 13
        echo $this->getAttribute(($context["site"] ?? null), "pingback_url", array());
        echo "\" />
";
        // line 14
        echo call_user_func_array($this->env->getFunction('function')->getCallable(), array("wp_head"));
        echo "
</head>
";
    }

    public function getTemplateName()
    {
        return "html-header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 14,  46 => 13,  39 => 8,  34 => 5,  30 => 4,  26 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "html-header.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/html-header.twig");
    }
}

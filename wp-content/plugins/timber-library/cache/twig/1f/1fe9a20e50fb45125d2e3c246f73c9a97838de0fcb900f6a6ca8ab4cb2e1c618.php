<?php

/* partials/nav/nav-desktop-top.twig */
class __TwigTemplate_864d931c385c8b5319639961cc0db6e8a8d0e3153ee8dac5fb11b968b72d504a extends Twig_Template
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
        echo "<div class=\"es-nav-top es-hidden-xs es-show-md-flex justify-flex-end sm-pt-15 sm-pb-20\" role=\"navigation\">
  <ul class=\"list-unstyled flex align-center\">
    <li class=\"text-13-medium sm-pr-15 sm-pl-15 link-nav-top text-dark-50 flex align-center pointer z4 search-open\"  tabindex=\"0\">
      <img class=\"es-nav-icon icon-search\" src=\"";
        // line 4
        echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
        echo "/assets/images/search-icon@2x.png\" alt=\"Small icon of a magnifying glass\">
        Search
    </li>
    ";
        // line 7
        $context["phone_url"] = twig_replace_filter($this->getAttribute(($context["options"] ?? null), "top_nav_phone", array()), array(" " => "", "(" => "", ")" => "", "-" => ""));
        // line 8
        echo "    ";
        if (($context["phone_url"] ?? null)) {
            // line 9
            echo "    <li class=\"sm-pr-15 sm-pl-15\"><a href=\"tel:";
            echo ($context["phone_url"] ?? null);
            echo "\" class=\"text-13-medium text-blue decoration-none\">";
            echo $this->getAttribute(($context["options"] ?? null), "top_nav_phone", array());
            echo "</a></li>
    ";
        }
        // line 11
        echo "
    ";
        // line 12
        if ($this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_contact", array()), "text", array())) {
            // line 13
            echo "    <li class=\"text-13-medium text-dark-50 sm-pr-15 sm-pl-15\"><a href=\"";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_contact", array()), "url", array());
            echo "\" class=\"link-nav-top text-dark-50 decoration-none flex align-center\" ";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_contact", array()), "target", array());
            echo " >";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_contact", array()), "text", array());
            echo "</a></li>
    ";
        }
        // line 15
        echo "
    ";
        // line 16
        if ($this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_signin", array()), "text", array())) {
            // line 17
            echo "      ";
            if (twig_test_empty($this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_support", array()), "text", array()))) {
                // line 18
                echo "        <li class=\"text-13-medium sm-pl-15\">
      ";
            } else {
                // line 20
                echo "        <li class=\"text-13-medium sm-pr-15 sm-pl-15\">
      ";
            }
            // line 22
            echo "      <a href=\"";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_signin", array()), "url", array());
            echo "\" class=\"text-dark-50 link-nav-top decoration-none\" ";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_signin", array()), "target", array());
            echo ">";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_signin", array()), "text", array());
            echo "</a>
    </li>
    ";
        }
        // line 25
        echo "
    ";
        // line 26
        if ($this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_support", array()), "text", array())) {
            // line 27
            echo "    <li class=\"text-13-medium text-dark-50 sm-pl-15\">
      <a href=\"";
            // line 28
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_support", array()), "url", array());
            echo "\" class=\"link-nav-top text-dark-50 decoration-none flex align-center\" ";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_support", array()), "target", array());
            echo " >
        <img class=\"es-nav-icon icon-support\" src=\"";
            // line 29
            echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
            echo "/assets/images/headphones-icon@2x.png\" alt=\"Small icon of headphones\">
        ";
            // line 30
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "top_nav_support", array()), "text", array());
            echo "
      </a>
    </li>
    ";
        }
        // line 34
        echo "
  </ul>
</div>
";
    }

    public function getTemplateName()
    {
        return "partials/nav/nav-desktop-top.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  110 => 34,  103 => 30,  99 => 29,  93 => 28,  90 => 27,  88 => 26,  85 => 25,  74 => 22,  70 => 20,  66 => 18,  63 => 17,  61 => 16,  58 => 15,  48 => 13,  46 => 12,  43 => 11,  35 => 9,  32 => 8,  30 => 7,  24 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/nav/nav-desktop-top.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/partials/nav/nav-desktop-top.twig");
    }
}

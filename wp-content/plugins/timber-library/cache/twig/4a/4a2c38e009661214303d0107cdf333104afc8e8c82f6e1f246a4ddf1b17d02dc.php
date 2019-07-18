<?php

/* partials/nav/nav-mobile-top.twig */
class __TwigTemplate_746506abc203bd0b32f9adfa021e46110950cb10570aaa9eeebc8d57ad7371cb extends Twig_Template
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
        echo "<div class=\"es-nav-top es-nav-top-mobile es-hidden-md xs-pl-20 xs-pr-20 xs-pt-10 xs-pb-10 fill-blue\">
  <ul class=\"list-unstyled flex justify-space-between align-center\">
    <li class=\"search-open\"  tabindex=\"0\"><img class=\"es-nav-icon icon-search pointer\" src=\"";
        // line 3
        echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
        echo "/assets/images/search-icon-white@2x.png\" alt=\"Small icon of a magnifying glass\"><div class=\"es-nav-spacer es-hidden-xs es-show-sm\"></div></li>
    <li><a href=\"";
        // line 4
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "request_demo", array()), "url", array());
        echo "\" class=\"text-14-medium text-white link-white decoration-none\" ";
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "request_demo", array()), "target", array());
        echo ">";
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "request_demo", array()), "text", array());
        echo "</a></li>
    <li>
      <span class=\"es-hidden-xs es-show-sm-flex text-14-medium text-white link-white\">";
        // line 6
        echo $this->getAttribute(($context["options"] ?? null), "top_nav_phone_mobile", array());
        echo "</span>
      <a class=\"es-hidden-sm\" href=\"tel:+18004114700\" aria-label=\"call serenova\">
        <img class=\"es-nav-icon\" src=\"";
        // line 8
        echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
        echo "/assets/images/phone-icon@2x.png\" alt=\"telephone icon\">
      </a>
    </li>
  </ul>
</div>
";
    }

    public function getTemplateName()
    {
        return "partials/nav/nav-mobile-top.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  41 => 8,  36 => 6,  27 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/nav/nav-mobile-top.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/partials/nav/nav-mobile-top.twig");
    }
}

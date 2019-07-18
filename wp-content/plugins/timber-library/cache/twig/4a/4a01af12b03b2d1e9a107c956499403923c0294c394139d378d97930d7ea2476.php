<?php

/* partials/nav/nav-search.twig */
class __TwigTemplate_f2d73c75dddd659f1b7ea133fd7f0a2ea75e62d584b87e4c0e6ad814c81f51bc extends Twig_Template
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
        echo "<div class=\"es-nav-search xs-pl-20 xs-pr-20 sm-pl-40 sm-pr-40 xl-pl-80 xl-pr-80 fill-white\">
  <div class=\"relative xs-pt-15 xs-pb-15 flex justify-space-between align-center\">
     <form class=\"es-span-sm-4 flex align-center\" role=\"search\" method=\"get\" id=\"searchform\" action=\"";
        // line 3
        echo $this->getAttribute(($context["site"] ?? null), "url", array());
        echo "\">
      <img class=\"es-nav-search-icon xs-mr-10\" src=\"";
        // line 4
        echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
        echo "/assets/images/search-icon-blue@2x.png\" alt=\"Small icon of a magnifying glass\">
      <input class=\"es-nav-search-input text-20 text-dark-20\" value=\"\" name=\"s\" type=\"text\" placeholder=\"Search the site\" aria-label=\"search-text\">
    </form>
    <div class=\"es-nav-search-close es-nav-search-icon\" id=\"searchClose\" aria-label=\"close search\" tabindex=\"0\" ><img src=\"";
        // line 7
        echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
        echo "/assets/images/close-icon-dark@2x.png\" alt=\"Close icon\"></div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "partials/nav/nav-search.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  33 => 7,  27 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/nav/nav-search.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/partials/nav/nav-search.twig");
    }
}

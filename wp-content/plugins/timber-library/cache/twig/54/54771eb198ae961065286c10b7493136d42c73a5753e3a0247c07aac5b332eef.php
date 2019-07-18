<?php

/* partials/nav/nav-mobile-triggers.twig */
class __TwigTemplate_fdd1e0364f9efe639befc80ab40ee4a9ca6f9819e3a908d50bc2c24880be0dbd extends Twig_Template
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
        echo "
<div class=\"align-center xs-pt-25 xs-pb-25 xs-pl-20 xs-pr-20 es-nav-mobile-menu -show\" tabindex=\"0\" role=\"button\" aria-label=\"Menu\" aria-expanded=\"false\">
  <span class=\"text-14-medium text-dark-20 link-nav xs-mr-5\">Menu</span>
  <svg width=\"20\" height=\"15\" xmlns=\"http://www.w3.org/2000/svg\">
    <path d=\"M0 14h16v-2H0v2zM0 0v2h20V0H0zm0 8h13V6H0v2z\" fill=\"#000\" fill-rule=\"evenodd\"/>
  </svg>
</div>
<div class=\"align-center xs-pt-25 xs-pb-25 xs-pl-20 xs-pr-20 es-nav-mobile-close -hide fill-blue-dark\" role=\"button\" tabindex=\"0\" aria-label=\"Menu\" aria-expanded=\"true\">
  <span class=\"text-14-medium text-white link-lighten xs-mr-5\">Close</span>
  <img src=\"";
        // line 10
        echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
        echo "/assets/images/close-icon@2x.png\" alt=\"Close Menu Icon\">
</div>
";
    }

    public function getTemplateName()
    {
        return "partials/nav/nav-mobile-triggers.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 10,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/nav/nav-mobile-triggers.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/partials/nav/nav-mobile-triggers.twig");
    }
}

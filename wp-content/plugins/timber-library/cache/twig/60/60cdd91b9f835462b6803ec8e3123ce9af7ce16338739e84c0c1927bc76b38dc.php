<?php

/* core/nav.twig */
class __TwigTemplate_2f3c765d486c317b9f8919d5ecd875288ef3fe6a1a01276593de255a4f1c9b51 extends Twig_Template
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
        $this->loadTemplate("partials/nav/nav-announcement-bar.twig", "core/nav.twig", 1)->display($context);
        // line 2
        $this->loadTemplate("partials/nav/nav-search.twig", "core/nav.twig", 2)->display($context);
        // line 3
        echo "<nav class=\"es-nav fill-white\">
  ";
        // line 5
        echo "  ";
        $this->loadTemplate("partials/nav/nav-mobile-top.twig", "core/nav.twig", 5)->display($context);
        // line 6
        echo "  <div class=\"xs-pl-20 sm-pl-15 sm-pr-15 md-pl-30 md-pr-30 xl-pl-80 xl-pr-80 relative\">
    ";
        // line 8
        echo "    ";
        $this->loadTemplate("partials/nav/nav-desktop-top.twig", "core/nav.twig", 8)->display($context);
        // line 9
        echo "    <div class=\"flex justify-space-between align-center\" role=\"navigation\">
      <a href=\"";
        // line 10
        echo $this->getAttribute(($context["site"] ?? null), "link", array());
        echo "\" class=\"es-nav-logo md-pb-15\">
        <img src=\"";
        // line 11
        echo $this->getAttribute(($context["options"] ?? null), "logo_desktop", array());
        echo "\" alt=\"Serenova Logo\">
      </a>
      ";
        // line 14
        echo "      ";
        $this->loadTemplate("partials/nav/nav-desktop.twig", "core/nav.twig", 14)->display($context);
        // line 15
        echo "      ";
        // line 16
        echo "      ";
        $this->loadTemplate("partials/nav/nav-mobile-triggers.twig", "core/nav.twig", 16)->display($context);
        // line 17
        echo "    </div>
    ";
        // line 18
        $this->loadTemplate("partials/nav/nav-mobile.twig", "core/nav.twig", 18)->display($context);
        // line 19
        echo "  </div>
</nav>
";
    }

    public function getTemplateName()
    {
        return "core/nav.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 19,  58 => 18,  55 => 17,  52 => 16,  50 => 15,  47 => 14,  42 => 11,  38 => 10,  35 => 9,  32 => 8,  29 => 6,  26 => 5,  23 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "core/nav.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/core/nav.twig");
    }
}

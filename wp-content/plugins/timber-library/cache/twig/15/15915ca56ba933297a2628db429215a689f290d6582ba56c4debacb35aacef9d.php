<?php

/* partials/nav/nav-announcement-bar.twig */
class __TwigTemplate_2fb471a066eee977705916fecc468053a6744117c23bd6ea368498dd16be2483 extends Twig_Template
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
        if ($this->getAttribute($this->getAttribute(($context["options"] ?? null), "announcement_bar", array()), "text", array())) {
            // line 2
            echo "<div class=\"es-announcement\">
  <a href=\"";
            // line 3
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "announcement_bar", array()), "url", array());
            echo "\" class=\"xs-pl-20 xs-pr-20 xs-pb-10 xs-pt-10 flex justify-center align-center decoration-none fill-blue-dark\" ";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "announcement_bar", array()), "target", array());
            echo ">
    <h2 class=\"text-15-bold caps text-white\">";
            // line 4
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "announcement_bar", array()), "text", array());
            echo "</h2>
  </a>
</div>
";
        }
    }

    public function getTemplateName()
    {
        return "partials/nav/nav-announcement-bar.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 4,  24 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/nav/nav-announcement-bar.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/partials/nav/nav-announcement-bar.twig");
    }
}

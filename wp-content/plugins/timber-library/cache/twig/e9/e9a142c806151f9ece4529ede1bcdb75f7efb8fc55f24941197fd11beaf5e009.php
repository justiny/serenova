<?php

/* core/404.twig */
class __TwigTemplate_a6957b2f39ab122eee6949eef8c0cefd65a67f81e655edbad21d7f40359c7af6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layouts/base.twig", "core/404.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layouts/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "<section class=\"es-404\">
  <div class=\"es-simple-header xs-pt-40 sm-pt-75 sm-pb-80 sm-pr-0 md-pb-100 md-pt-100 sm-pl-0 fill-white\">
    <div class=\"es-grid\">
      <div class=\"es-row\">
        <h1 class=\"title-1 title-cap text-dark-10 xs-mb-20 es-col-xs-6 es-offset-sm-2 es-col-sm-3\">404 Not Found</h1>
        <div class=\"text-20 text-dark-30 xs-mb-30 es-col-xs-6 es-offset-sm-2 es-col-sm-4\">What you're looking for doesn't seem to exist.</div>
        <div class=\"es-col-xs-6 es-offset-sm-2 es-col-sm-4\">
          <a href=\"";
        // line 11
        echo $this->getAttribute(($context["site"] ?? null), "link", array());
        echo "\" class=\"button button-blue button-medium xs-mb-25 md-mb-0 md-mr-40\">Visit Home</a>
        </div>
      </div>
    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "core/404.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  40 => 11,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "core/404.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/core/404.twig");
    }
}

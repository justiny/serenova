<?php

/* components/testimonial.twig */
class __TwigTemplate_1869fb67141f8d003d78d449e42056193c224da68957eeb48133b9e61beb376e extends Twig_Template
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
        echo "<section class=\"xs-pt-20 xs-pb-20 sm-pt-60 sm-pb-60 fill-white\">
  <div class=\"es-grid\">
    <div class=\"es-row\">
      <div class=\"es-col-xs-6 es-col-sm-1 es-testimonial-avatar xs-mb-15 sm-mb-0\">
        ";
        // line 5
        if ( !twig_test_empty($this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "avatar", array()))), "src", array()))) {
            // line 6
            echo "        <img src=\"";
            echo Timber\ImageHelper::resize($this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "avatar", array()))), "src", array()), 100, 100);
            echo "\" alt=\"";
            echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "avatar", array()))), "alt", array());
            echo "\">
        ";
        }
        // line 8
        echo "      </div>
      <div class=\"es-col-xs-6 es-col-sm-4\">
        <h2 class=\"es-testimonial-quote text-38-italic text-blue-dark xs-mb-25 sm-mb-30\"><span class=\"open-quote\">&ldquo;</span>";
        // line 10
        echo $this->getAttribute(($context["item"] ?? null), "quote_text", array());
        echo "<span class=\"close-quote\">&rdquo;</span></h2>
        <p class=\"text-20-bold text-dark-10\">";
        // line 11
        echo $this->getAttribute(($context["item"] ?? null), "author_name", array());
        echo "</p>
        <p class=\"text-20 text-dark-30 xs-mt-0\">";
        // line 12
        echo $this->getAttribute(($context["item"] ?? null), "author_title", array());
        echo "</p>
      </div>
    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/testimonial.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 12,  43 => 11,  39 => 10,  35 => 8,  27 => 6,  25 => 5,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/testimonial.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/testimonial.twig");
    }
}

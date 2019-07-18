<?php

/* components/logo-blocks.twig */
class __TwigTemplate_56fde1aa4d7d5ff3dc10559e2fd3afd842f09a7d56269a959927d5a31c46fe1f extends Twig_Template
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
        echo "<section class=\"logo-blocks xs-pt-40 xs-pb-40 sm-pt-60 sm-pb-60 fill-white\">
  <div class=\"logo-blocks-header flex flex-column justify-center\">
    ";
        // line 3
        if (( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "logo_title", array())) ||  !twig_test_empty($this->getAttribute(($context["item"] ?? null), "logo_text", array())))) {
            // line 4
            echo "      ";
            if ($this->getAttribute(($context["item"] ?? null), "logo_title", array())) {
                // line 5
                echo "      <div class=\"es-col-xs-6 es-col-sm-4 es-offset-sm-1 text-center-left\">
        <h2 class=\"title-1 text-dark-10 md-pl-50 md-pr-50\">";
                // line 6
                echo $this->getAttribute(($context["item"] ?? null), "logo_title", array());
                echo "</h2>
      </div>
      ";
            }
            // line 9
            echo "      ";
            if ($this->getAttribute(($context["item"] ?? null), "logo_text", array())) {
                // line 10
                echo "      <div class=\"es-col-xs-6 es-col-sm-4 es-offset-sm-1 xs-mt-20 text-center-left\">
        <p class=\"text-20 text-dark-30 xs-pr-0 xs-pl-0 sm-pr-80 sm-pl-80 md-pr-100 md-pl-100\">";
                // line 11
                echo $this->getAttribute(($context["item"] ?? null), "logo_text", array());
                echo "</p>
      </div>
      ";
            }
            // line 14
            echo "      </div>
    ";
        }
        // line 16
        echo "  </div>
  <div class=\"logo-blocks-items flex flex-wrap xs-mt-40\">
    ";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["item"] ?? null), "logo_blocks", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["lb"]) {
            // line 19
            echo "    ";
            $context["logo_image"] = Timber\ImageHelper::resize(Timber\ImageHelper::img_to_jpg($this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute($context["lb"], "logo_image", array()))), "src", array())), 720, 418);
            // line 20
            echo "    ";
            $context["logo_alt"] = $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute($context["lb"], "logo_image", array()))), "alt", array());
            // line 21
            echo "    <div class=\"logo-blocks-item flex\">
      <img src=\"";
            // line 22
            echo ($context["logo_image"] ?? null);
            echo "\" alt=\"";
            echo ($context["logo_alt"] ?? null);
            echo "\">
    </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['lb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/logo-blocks.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 25,  70 => 22,  67 => 21,  64 => 20,  61 => 19,  57 => 18,  53 => 16,  49 => 14,  43 => 11,  40 => 10,  37 => 9,  31 => 6,  28 => 5,  25 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/logo-blocks.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/logo-blocks.twig");
    }
}

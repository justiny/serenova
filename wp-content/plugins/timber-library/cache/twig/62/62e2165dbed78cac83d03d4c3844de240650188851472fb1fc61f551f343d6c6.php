<?php

/* components/button-cta.twig */
class __TwigTemplate_873cc6744ee4e012e2ae04913062248fac67cb2eac164d1b130ea8c81cc7c9cd extends Twig_Template
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
        echo "<section class=\"es-button-cta xs-pb-20 xs-pt-20 sm-pb-60 sm-pt-60 relative\">
  <div class=\"es-grid\">
    ";
        // line 4
        echo "      <div class=\"es-button-cta-inner relative\">
        <div class=\"fill-blue-dark\">
          ";
        // line 7
        echo "            <div class=\"es-row\">
              <div class=\"es-col-xs-6 es-col-lg-5 xs-pb-40 xs-pt-40 sm-pb-85 sm-pt-95 flex justify-space-between align-center flex-wrap relative\">
                <img src=\"";
        // line 9
        echo $this->getAttribute(($context["theme"] ?? null), "link", array());
        echo "/assets/images/button-cta@2x.png\" alt=\"Icon with messaging icon inside text bubble\" class=\"es-button-cta-icon\">
                ";
        // line 10
        if (( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "bc_title", array())) ||  !twig_test_empty($this->getAttribute(($context["item"] ?? null), "bc_text", array())))) {
            // line 11
            echo "                  <div class=\"es-button-cta-content\">
                  ";
            // line 12
            if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "bc_title", array()))) {
                // line 13
                echo "                      <h2 class=\"title-1 text-white\">";
                echo $this->getAttribute(($context["item"] ?? null), "bc_title", array());
                echo "</h2>
                  ";
            }
            // line 15
            echo "                  ";
            if ($this->getAttribute(($context["item"] ?? null), "bc_text", array())) {
                // line 16
                echo "                      <p class=\"text-20 text-white xs-mt-15 sm-mt-0\">";
                echo $this->getAttribute(($context["item"] ?? null), "bc_text", array());
                echo "</p>
                  ";
            }
            // line 18
            echo "                  </div>
                ";
        }
        // line 20
        echo "                ";
        if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "bc_button", array()))) {
            // line 21
            echo "                  <div class=\"xs-mt-40 md-mt-0 sm-pr-20 lg-pr-0 es-button-cta-button\">
                    <a href=\"";
            // line 22
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "bc_button", array()), "url", array());
            echo "\" class=\"button button-light button-xlarge text-15-bold text-blue-dark ";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "bc_button", array()), "target", array());
            echo "\">";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "bc_button", array()), "text", array());
            echo "</a>
                  </div>
                ";
        }
        // line 25
        echo "              </div>
            ";
        // line 27
        echo "          </div>
        </div>
      ";
        // line 30
        echo "    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/button-cta.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 30,  80 => 27,  77 => 25,  67 => 22,  64 => 21,  61 => 20,  57 => 18,  51 => 16,  48 => 15,  42 => 13,  40 => 12,  37 => 11,  35 => 10,  31 => 9,  27 => 7,  23 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/button-cta.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/button-cta.twig");
    }
}

<?php

/* components/image-cta.twig */
class __TwigTemplate_53778ae688d9a8a2891221c1c90d284889445cf08ce12f01b94a5b0c7b8c2061 extends Twig_Template
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
        echo "<section class=\"es-image-cta xs-pt-20 xs-pb-20 sm-pt-60 sm-pb-60\">
  <div class=\"fill-white\">
    <div class=\"es-grid\">
      <div class=\"es-row\">
        <div class=\"es-col-xs-6 es-col-sm-4 es-col-md-4 es-col-lg-3 xs-pt-70 xs-pb-70\">
          <div class=\"es-image-cta-content";
        // line 6
        echo ((($this->getAttribute(($context["item"] ?? null), "image_cta_position", array()) == "Right")) ? (" -is-right") : (""));
        echo "\">
            ";
        // line 7
        if (($this->getAttribute(($context["item"] ?? null), "title_type", array()) == "Text")) {
            // line 8
            echo "            <h2 class=\"title-1 text-dark-10 title-cap xs-mb-20\">";
            echo $this->getAttribute(($context["item"] ?? null), "image_cta_title", array());
            echo "</h2>
            ";
        } else {
            // line 10
            echo "            <img src=\"";
            echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "image_title", array()))), "src", array());
            echo "\" alt=\"";
            echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "image_title", array()))), "alt", array());
            echo "\" style=\"max-width: ";
            echo $this->getAttribute(($context["item"] ?? null), "image_title_max_width", array());
            echo "px\" class=\"xs-mb-20\">
            ";
        }
        // line 12
        echo "            <div class=\"text-20 text-dark-30\">";
        echo $this->getAttribute(($context["item"] ?? null), "image_cta_text", array());
        echo "</div>
            ";
        // line 13
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_cta_link", array()), "text", array()))) {
            // line 14
            echo "            <div class=\"flex flex-wrap align-center xs-flex-column sm-flex-row xs-mt-40\">
              ";
            // line 15
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_cta_link", array()), "text", array()))) {
                // line 16
                echo "                <a href=\"";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_cta_link", array()), "url", array());
                echo "\" class=\"text-15-medium caps link-blue link-arrow-blue decoration-none\" ";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_cta_link", array()), "target", array());
                echo ">";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_cta_link", array()), "text", array());
                echo "</a>
              ";
            }
            // line 18
            echo "            </div>
            ";
        }
        // line 20
        echo "          </div>
        </div>
        <div class=\"es-image-cta-image es-col-sm-2 es-col-md-2 es-col-lg-3 relative ";
        // line 22
        echo ((($this->getAttribute(($context["item"] ?? null), "image_cta_position", array()) == "Right")) ? (" -is-right") : (""));
        echo "\">
          ";
        // line 23
        if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "image_cta_image", array()))) {
            // line 24
            echo "            <div class=\"es-image-cta-image-img absolute\"
              style=\"background-image:url(";
            // line 25
            echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "image_cta_image", array()))), "src", array());
            echo ")\">
            </div>
          ";
        } else {
            // line 28
            echo "            <div class=\"es-image-cta-image-placeholder absolute\"
              style=\"background-image:url(";
            // line 29
            echo $this->getAttribute(($context["theme"] ?? null), "link", array());
            echo "/assets/images/image-cta-graphic@2x.png);\">
            </div>
          ";
        }
        // line 32
        echo "        </div>
      </div>
    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/image-cta.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 32,  96 => 29,  93 => 28,  87 => 25,  84 => 24,  82 => 23,  78 => 22,  74 => 20,  70 => 18,  60 => 16,  58 => 15,  55 => 14,  53 => 13,  48 => 12,  38 => 10,  32 => 8,  30 => 7,  26 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/image-cta.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/image-cta.twig");
    }
}

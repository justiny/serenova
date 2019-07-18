<?php

/* components/image-spread.twig */
class __TwigTemplate_5795b7b43afc5d8da900b135990778e1ddd19a6c3af46a5b528befd8ffd1682b extends Twig_Template
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
        echo "<section class=\"es-image-spread xs-pb-20 xs-pt-20 sm-pt-60 sm-pb-60\">
  <div class=\"fill-white\">
    <div class=\"es-grid\">
      <div class=\"es-row\">
        <div class=\"es-col-xs-6 es-col-sm-3 xs-pt-20 sm-pt-60 xs-pb-20 sm-pb-60\">
          <div class=\"es-image-spread-content";
        // line 6
        echo ((($this->getAttribute(($context["item"] ?? null), "image_spread_position", array()) == "Right")) ? (" -is-right") : (""));
        echo "\">
            ";
        // line 7
        if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "image_spread_title", array()))) {
            // line 8
            echo "            <h2 class=\"title-1 text-dark-10 title-cap xs-mb-20\">";
            echo $this->getAttribute(($context["item"] ?? null), "image_spread_title", array());
            echo "</h2>
            ";
        }
        // line 10
        echo "            <div class=\"text-20 text-dark-30\">";
        echo $this->getAttribute(($context["item"] ?? null), "image_spread_text", array());
        echo "</div>
            ";
        // line 11
        if (( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_button", array()), "text", array())) ||  !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_link", array()), "text", array())))) {
            // line 12
            echo "            <div class=\"flex flex-wrap align-center xs-flex-column sm-flex-row xs-mt-40\">
              ";
            // line 13
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_button", array()), "text", array()))) {
                // line 14
                echo "                <a href=\"";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_button", array()), "url", array());
                echo "\" class=\"button button-blue button-medium button-shadow xs-mb-20 sm-mb-0 xs-mr-0 sm-mr-40\" ";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_button", array()), "target", array());
                echo ">";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_button", array()), "text", array());
                echo "</a>
              ";
            }
            // line 16
            echo "              ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_link", array()), "text", array()))) {
                // line 17
                echo "                <a href=\"";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_link", array()), "url", array());
                echo "\" class=\"text-15-medium caps link-blue link-arrow-blue decoration-none\" ";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_link", array()), "target", array());
                echo ">";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "image_spread_link", array()), "text", array());
                echo "</a>
              ";
            }
            // line 19
            echo "            </div>
            ";
        }
        // line 21
        echo "          </div>
        </div>
        <div class=\"es-image-spread-image es-hidden-xs es-show-sm es-col-sm-3 relative ";
        // line 23
        echo ((($this->getAttribute(($context["item"] ?? null), "image_spread_position", array()) == "Right")) ? (" -is-right") : (""));
        echo "\">
          ";
        // line 24
        if ($this->getAttribute(($context["item"] ?? null), "image_spread_contain", array())) {
            // line 25
            echo "          <img src=\"";
            echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "image_spread_image", array()))), "src", array());
            echo "\" alt=\"";
            echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["options"] ?? null), "press_cc_left_icon", array()))), "alt", array());
            echo "\">
          ";
        } else {
            // line 27
            echo "          <div class=\"es-image-spread-image-img absolute\"
              style=\"background-image:url(";
            // line 28
            echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "image_spread_image", array()))), "src", array());
            echo ")\">
          </div>
          ";
        }
        // line 31
        echo "        </div>
      </div>
    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/image-spread.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  104 => 31,  98 => 28,  95 => 27,  87 => 25,  85 => 24,  81 => 23,  77 => 21,  73 => 19,  63 => 17,  60 => 16,  50 => 14,  48 => 13,  45 => 12,  43 => 11,  38 => 10,  32 => 8,  30 => 7,  26 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/image-spread.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/image-spread.twig");
    }
}

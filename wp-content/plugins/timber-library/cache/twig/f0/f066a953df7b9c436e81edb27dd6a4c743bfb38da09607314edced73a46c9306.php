<?php

/* components/card-cta.twig */
class __TwigTemplate_f079ece5d9b1c8e3d6722f2847e6c66ca031693f4c045854bb5b6efdbcc743a9 extends Twig_Template
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
        echo "<section class=\"card-cta xs-pb-0 xs-pt-20 sm-pb-60 sm-pt-60\">
  <div class=\"es-grid\">
    <div class=\"es-row\">
      <div class=\"card-cta-full es-col-xs-6 xs-mb-20 sm-mb-30\">
        <div class=\"card-cta-full-inner flex xs-flex-column md-flex-row fill-white xs-pt-40 xs-pr-15 xs-pb-40 xs-pl-15 sm-pr-80 sm-pl-30 md-pt-60 md-pr-140 md-pb-40\">
          <img class=\"xs-mb-10 md-mb-0 sm-mr-40\" src=\"";
        // line 6
        echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "cc_icon", array()))), "src", array());
        echo "\"
              alt=\"";
        // line 7
        echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "cc_icon", array()))), "alt", array());
        echo "\"
            >
          <div class=\"card-cta-inner-content\">
            ";
        // line 10
        if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_title", array()))) {
            // line 11
            echo "            <h3 class=\"title-1 xs-mb-20 text-dark-10\">";
            echo $this->getAttribute(($context["item"] ?? null), "cc_title", array());
            echo "</h3>
            ";
        }
        // line 13
        echo "
            ";
        // line 15
        echo "            <div class=\"card-cta-full-inner-buttons flex xs-flex-column sm-flex-row xs-align-center\">
              ";
        // line 16
        if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_button", array()))) {
            // line 17
            echo "              <a href=\"";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_button", array()), "url", array());
            echo "\" class=\"button button-blue button-medium xs-mr-0 sm-mr-40 caps\" ";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_button", array()), "target", array());
            echo ">";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_button", array()), "text", array());
            echo "</a>
              ";
        }
        // line 19
        echo "              ";
        if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_link", array()))) {
            // line 20
            echo "              <a href=\"";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_link", array()), "url", array());
            echo "\" class=\"link-blue link-arrow-blue link-arrow-blue-no-animate text-blue decoration-none caps text-15-medium xs-mt-30 sm-mt-0\" ";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_link", array()), "target", array());
            echo ">";
            echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_link", array()), "text", array());
            echo "</a>
              ";
        }
        // line 22
        echo "            </div>
          </div>
        </div>
      </div>

      ";
        // line 27
        if (($this->getAttribute(($context["item"] ?? null), "add_cards", array()) == true)) {
            // line 28
            echo "
        ";
            // line 29
            if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_left_title", array()))) {
                // line 30
                echo "          <div class=\"card-cta-half-bottom es-col-xs-6 es-col-sm-3 xs-mb-20 sm-mb-0\">
            <div class=\"card-cta-half-bottom-inner flex xs-flex-column md-flex-row md-align-center fill-white xs-pt-40 xs-pr-15 xs-pb-40 xs-pl-15 sm-pt-45 sm-pl-30 sm-pr-30 sm-pb-30\">

              <img src=\"";
                // line 33
                echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "cc_left_icon", array()))), "src", array());
                echo "\"
                class=\"xs-mb-10 md-mb-0 sm-mr-40\"
                alt=\"";
                // line 35
                echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "cc_left_icon", array()))), "alt", array());
                echo "\"
              >

              <div>
                ";
                // line 39
                if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_left_title", array()))) {
                    // line 40
                    echo "                <h3 class=\"text-20-bold xs-mb-20 text-dark-10\">";
                    echo $this->getAttribute(($context["item"] ?? null), "cc_left_title", array());
                    echo "</h3>
                ";
                }
                // line 42
                echo "
                ";
                // line 43
                if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_left_text", array()))) {
                    // line 44
                    echo "                <p class=\"text-16 xs-mb-20 relative\">";
                    echo $this->getAttribute(($context["item"] ?? null), "cc_left_text", array());
                    echo "</p>
                ";
                }
                // line 46
                echo "
                ";
                // line 47
                if ((($this->getAttribute(($context["item"] ?? null), "link_style_left", array()) == "Link") &&  !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_left_link", array())))) {
                    // line 48
                    echo "                <a href=\"";
                    echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_left_link", array()), "url", array());
                    echo "\" class=\"link-blue link-arrow-blue link-arrow-blue-no-animate relative text-blue decoration-none caps text-15-medium\" ";
                    echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_left_link", array()), "target", array());
                    echo ">";
                    echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_left_link", array()), "text", array());
                    echo "</a>
                ";
                } elseif ((($this->getAttribute(                // line 49
($context["item"] ?? null), "link_choice_left", array()) == "Plain Text") &&  !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_left_plain", array())))) {
                    // line 50
                    echo "                <p class=\"text-blue-dark caps text-15-medium xs-mt-0\">";
                    echo $this->getAttribute(($context["item"] ?? null), "cc_left_plain", array());
                    echo "</p>
                ";
                }
                // line 52
                echo "
              </div>

            </div>
          </div>
        ";
            }
            // line 58
            echo "
        ";
            // line 59
            if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_right_title", array()))) {
                // line 60
                echo "          <div class=\"card-cta-half-top es-col-xs-6 es-col-sm-3 xs-mb-20 sm-mb-0\">
            <div class=\"card-cta-half-top-inner flex xs-flex-column md-flex-row md-align-center fill-white xs-pt-40 xs-pr-15 xs-pb-40 xs-pl-15 sm-pt-45 sm-pl-30 sm-pr-30 sm-pb-30\">

              <img src=\"";
                // line 63
                echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "cc_right_icon", array()))), "src", array());
                echo "\"
                class=\"xs-mb-10 md-mb-0 sm-mr-40\"
                alt=\"";
                // line 65
                echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "cc_right_icon", array()))), "alt", array());
                echo "\"
              >

              <div>
                ";
                // line 69
                if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_right_title", array()))) {
                    // line 70
                    echo "                <h3 class=\"text-20-bold xs-mb-20 text-dark-10\">";
                    echo $this->getAttribute(($context["item"] ?? null), "cc_right_title", array());
                    echo "</h3>
                ";
                }
                // line 72
                echo "
                ";
                // line 73
                if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_right_text", array()))) {
                    // line 74
                    echo "                <p class=\"text-16 xs-mb-20\">";
                    echo $this->getAttribute(($context["item"] ?? null), "cc_right_text", array());
                    echo "</p>
                ";
                }
                // line 76
                echo "
                ";
                // line 77
                if ((($this->getAttribute(($context["item"] ?? null), "link_style_right", array()) == "Link") &&  !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_right_link", array())))) {
                    // line 78
                    echo "                <a href=\"";
                    echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_right_link", array()), "url", array());
                    echo "\" class=\"link-blue link-arrow-blue link-arrow-blue-no-animate relative text-blue decoration-none caps text-15-medium\" ";
                    echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_right_link", array()), "target", array());
                    echo ">";
                    echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "cc_right_link", array()), "text", array());
                    echo "</a>
                ";
                } elseif ((($this->getAttribute(                // line 79
($context["item"] ?? null), "link_style_right", array()) == "Plain Text") &&  !twig_test_empty($this->getAttribute(($context["item"] ?? null), "cc_right_plain", array())))) {
                    // line 80
                    echo "                <p class=\"text-blue-dark caps text-15-medium xs-mt-0\">";
                    echo $this->getAttribute(($context["item"] ?? null), "cc_right_plain", array());
                    echo "</p>
                ";
                }
                // line 82
                echo "
              </div>

            </div>
          </div>
        ";
            }
            // line 88
            echo "
      ";
        }
        // line 90
        echo "      </div>
    </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/card-cta.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  230 => 90,  226 => 88,  218 => 82,  212 => 80,  210 => 79,  201 => 78,  199 => 77,  196 => 76,  190 => 74,  188 => 73,  185 => 72,  179 => 70,  177 => 69,  170 => 65,  165 => 63,  160 => 60,  158 => 59,  155 => 58,  147 => 52,  141 => 50,  139 => 49,  130 => 48,  128 => 47,  125 => 46,  119 => 44,  117 => 43,  114 => 42,  108 => 40,  106 => 39,  99 => 35,  94 => 33,  89 => 30,  87 => 29,  84 => 28,  82 => 27,  75 => 22,  65 => 20,  62 => 19,  52 => 17,  50 => 16,  47 => 15,  44 => 13,  38 => 11,  36 => 10,  30 => 7,  26 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/card-cta.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/card-cta.twig");
    }
}

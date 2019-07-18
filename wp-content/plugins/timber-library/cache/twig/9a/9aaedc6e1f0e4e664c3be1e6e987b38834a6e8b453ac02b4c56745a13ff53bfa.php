<?php

/* components/card-callout.twig */
class __TwigTemplate_4e560efa8487e82ccd07c3cc300d7ee6d78c13f97c59125a389a155a76c771b4 extends Twig_Template
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
        // line 2
        $context["col_class"] = "";
        // line 3
        echo "
";
        // line 4
        if (($this->getAttribute(($context["item"] ?? null), "cards_per_row", array()) == 2)) {
            // line 5
            echo "  ";
            $context["col_class"] = "es-col-xs-6 es-col-sm-3";
        } else {
            // line 7
            echo "  ";
            $context["col_class"] = "es-col-xs-6 es-col-sm-2";
        }
        // line 9
        echo "
<section class=\"card-callout xs-pb-0 xs-pt-20 sm-pb-0 sm-pt-40\">
  <div class=\"es-grid\">
    <div class=\"es-row\">

      ";
        // line 14
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["item"] ?? null), "card_callout_items", array()));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["card"]) {
            // line 15
            echo "
        <div class=\"card-callout";
            // line 16
            if (($this->getAttribute($context["loop"], "index0", array()) % 2 == 1)) {
                echo "-bottom";
            } else {
                echo "-top";
            }
            echo " ";
            echo ($context["col_class"] ?? null);
            echo " xs-mb-20 sm-mb-40 ";
            if (($this->getAttribute($context["loop"], "index0", array()) % 2 == 1)) {
                echo "-bottom";
            }
            echo "\">

          <a href=\"";
            // line 18
            echo $this->getAttribute($this->getAttribute($context["card"], "card_callout_title", array()), "url", array());
            echo "\" ";
            echo $this->getAttribute($this->getAttribute($context["card"], "card_callout_title", array()), "target", array());
            echo " class=\"flex decoration-none card-callout-wrapper\">

            <div class=\"card-callout";
            // line 20
            if (($this->getAttribute($context["loop"], "index0", array()) % 2 == 1)) {
                echo "-bottom";
            } else {
                echo "-top";
            }
            if (($this->getAttribute($context["loop"], "index0", array()) % 2 == 1)) {
                echo "-odd";
            } else {
                echo "-even";
            }
            echo " flex xs-flex-column md-flex-row fill-white xs-pt-40 xs-pb-40 xs-pl-15 xs-pr-15 md-pr-40 md-pl-30 md-pt-45\">

              ";
            // line 22
            if ((($this->getAttribute(($context["item"] ?? null), "cards_per_row", array()) == 2) &&  !twig_test_empty($this->getAttribute($context["card"], "card_callout_icon", array())))) {
                // line 23
                echo "               <img class=\"xs-mb-10 md-mb-0 md-mr-40\" src=\"";
                echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute($context["card"], "card_callout_icon", array()))), "src", array());
                echo "\"
                alt=\"";
                // line 24
                echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute($context["card"], "card_callout_icon", array()))), "alt", array());
                echo "\">
              ";
            }
            // line 26
            echo "
              <div class=\"card-callout-content\">
                ";
            // line 28
            if ( !twig_test_empty($this->getAttribute($context["card"], "card_callout_title", array()))) {
                // line 29
                echo "                <h3 class=\"xs-mb-20 text-20-bold text-dark-10 decoration-none\">";
                echo $this->getAttribute($this->getAttribute($context["card"], "card_callout_title", array()), "text", array());
                echo "</h3>
                ";
            }
            // line 31
            echo "
                ";
            // line 32
            if ( !twig_test_empty($this->getAttribute($context["card"], "card_callout_text", array()))) {
                // line 33
                echo "                <p class=\"text-16 text-dark-30\">";
                echo $this->getAttribute($context["card"], "card_callout_text", array());
                echo "</p>
                ";
            }
            // line 35
            echo "              </div>

            </div>
          </a>
        </div>

      ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['card'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "
    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/card-callout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  152 => 42,  132 => 35,  126 => 33,  124 => 32,  121 => 31,  115 => 29,  113 => 28,  109 => 26,  104 => 24,  99 => 23,  97 => 22,  83 => 20,  76 => 18,  61 => 16,  58 => 15,  41 => 14,  34 => 9,  30 => 7,  26 => 5,  24 => 4,  21 => 3,  19 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/card-callout.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/card-callout.twig");
    }
}

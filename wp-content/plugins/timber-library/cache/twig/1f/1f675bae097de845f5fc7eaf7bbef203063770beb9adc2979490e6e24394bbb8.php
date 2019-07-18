<?php

/* partials/nav/nav-mobile.twig */
class __TwigTemplate_76df1b1d6755482ca2dad06627d0786b1069d1e39daee2478fb576911f19de01 extends Twig_Template
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
        echo "<div class=\"es-nav-mobile absolute\">
  <div class=\"es-nav-mobile-inner xs-pt-40 xs-pb-60 fill-blue-dark\">
    <ul class=\"es-nav-mobile-links list-unstyled\">

      ";
        // line 5
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["menu"] ?? null), "items", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 6
            echo "      <li class=\"";
            if ($this->getAttribute($context["item"], "children", array())) {
                echo "has-dropdown";
            }
            echo " flex justify-space-between flex-wrap xs-pb-20\">
        <a href=\"";
            // line 7
            echo $this->getAttribute($context["item"], "link", array());
            echo "\" class=\"text-14-medium text-white link-white decoration-none xs-pb-20 xs-pl-35\">";
            echo $this->getAttribute($context["item"], "title", array());
            echo "</a>

        ";
            // line 9
            if ($this->getAttribute($context["item"], "children", array())) {
                // line 10
                echo "        <div class=\"es-nav-mobile-dropdown xs-pb-20 flex justify-flex-end\">
          <img class=\"xs-mr-35\" src=\"";
                // line 11
                echo $this->getAttribute($this->getAttribute(($context["site"] ?? null), "theme", array()), "link", array());
                echo "/assets/images/white-arrow@2x.png\" alt=\"arrow denoting dropdown menu\"  tabindex=\"0\">
        </div>
        ";
            }
            // line 14
            echo "
        ";
            // line 15
            if ($this->getAttribute($context["item"], "children", array())) {
                // line 16
                echo "          <ul class=\"es-nav-mobile-sub list-unstyled xs-pt-25 xs-pr-35 xs-pl-35 xs-mt-15\">
            ";
                // line 17
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["item"], "children", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                    // line 18
                    echo "               <li class=\"xs-mb-10\">
                    <a class=\"text-14-medium text-white link-white decoration-none xs-pb-15\" href=\"";
                    // line 19
                    echo $this->getAttribute($context["child"], "link", array());
                    echo "\">";
                    echo $this->getAttribute($context["child"], "title", array());
                    echo "</a>
                </li>
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 22
                echo "          </ul>
        ";
            }
            // line 24
            echo "
      </li>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "    </ul>
    ";
        // line 28
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "text", array()))) {
            // line 29
            echo "      <div class=\"xs-pl-35 xs-pr-35\">
        <a href=\"";
            // line 30
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "url", array());
            echo "\" class=\"button button-small button-red\" ";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "target", array());
            echo ">";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "text", array());
            echo "</a>
      </div>
    ";
        }
        // line 33
        echo "  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "partials/nav/nav-mobile.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  110 => 33,  100 => 30,  97 => 29,  95 => 28,  92 => 27,  84 => 24,  80 => 22,  69 => 19,  66 => 18,  62 => 17,  59 => 16,  57 => 15,  54 => 14,  48 => 11,  45 => 10,  43 => 9,  36 => 7,  29 => 6,  25 => 5,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/nav/nav-mobile.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/partials/nav/nav-mobile.twig");
    }
}

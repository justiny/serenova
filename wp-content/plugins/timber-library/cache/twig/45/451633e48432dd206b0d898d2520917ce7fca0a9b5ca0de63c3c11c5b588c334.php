<?php

/* partials/nav/nav-desktop.twig */
class __TwigTemplate_7acda0886d187c39d39b3abb1f6b5dc84b68dffc50b059e0970118a14abd14f8 extends Twig_Template
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
        echo "<ul class=\"es-hidden-xs es-show-sm-flex list-unstyled flex align-baseline\">

    ";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["menu"] ?? null), "items", array()));
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
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 4
            echo "
      ";
            // line 5
            $context["padding"] = "";
            // line 6
            echo "      ";
            if ($this->getAttribute($context["loop"], "first", array())) {
                // line 7
                echo "        ";
                $context["padding"] = "sm-pr-10 md-pr-15";
                // line 8
                echo "      ";
            } elseif (($this->getAttribute($context["loop"], "last", array()) && twig_test_empty($this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "text", array())))) {
                // line 9
                echo "        ";
                $context["padding"] = "sm-pl-15";
                // line 10
                echo "      ";
            } else {
                // line 11
                echo "        ";
                $context["padding"] = "sm-pr-10 md-pr-15 sm-pl-10 md-pl-15";
                // line 12
                echo "      ";
            }
            // line 13
            echo "
    <li class=\"text-14-medium ";
            // line 14
            echo ($context["padding"] ?? null);
            echo " sm-pb-30 sm-pt-30 relative ";
            if ((($this->getAttribute($context["item"], "slug", array()) == twig_lower_filter($this->env, ($context["wp_title"] ?? null))) || $this->getAttribute($context["item"], "current", array()))) {
                echo " -active";
            }
            echo " ";
            if ($this->getAttribute($context["item"], "children", array())) {
                echo "has-dropdown";
            }
            echo "\" ";
            if ($this->getAttribute($context["item"], "children", array())) {
                echo "tabindex=\"0\"";
            }
            echo ">
      <a href=\"";
            // line 15
            echo $this->getAttribute($context["item"], "link", array());
            echo "\" class=\"link-nav text-dark-20 decoration-none\">";
            echo $this->getAttribute($context["item"], "title", array());
            echo "</a>
      ";
            // line 16
            if ($this->getAttribute($context["item"], "children", array())) {
                // line 17
                echo "        <ul class=\"es-nav-desktop-dropdown fill-white sm-pt-20 sm-pb-15 sm-pr-15 sm-pl-15 list-unstyled\">
          <li class=\"es-nav-desktop-dropdown-carat\"></li>
          ";
                // line 19
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["item"], "children", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                    // line 20
                    echo "            <li class=\"";
                    echo (($this->getAttribute($context["child"], "current", array())) ? ("-active") : (""));
                    echo "\">
              <a class=\"sm-mt-5 sm-mb-5 text-14-medium link-nav text-dark-20 decoration-none\" href=\"";
                    // line 21
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
                // line 24
                echo "         </ul>
      ";
            }
            // line 26
            echo "    </li>
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        echo "
  ";
        // line 29
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "text", array()))) {
            // line 30
            echo "    <li class=\"es-hidden-sm es-show-md-flex text-14-medium sm-pl-15 sm-pb-15\">
      <a href=\"";
            // line 31
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "url", array());
            echo "\" class=\"button button-small button-red decoration-none\" ";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "target", array());
            echo ">";
            echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "nav_button", array()), "text", array());
            echo "</a>
    </li>
  ";
        }
        // line 34
        echo "
</ul>
";
    }

    public function getTemplateName()
    {
        return "partials/nav/nav-desktop.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  154 => 34,  144 => 31,  141 => 30,  139 => 29,  136 => 28,  121 => 26,  117 => 24,  106 => 21,  101 => 20,  97 => 19,  93 => 17,  91 => 16,  85 => 15,  69 => 14,  66 => 13,  63 => 12,  60 => 11,  57 => 10,  54 => 9,  51 => 8,  48 => 7,  45 => 6,  43 => 5,  40 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/nav/nav-desktop.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/partials/nav/nav-desktop.twig");
    }
}

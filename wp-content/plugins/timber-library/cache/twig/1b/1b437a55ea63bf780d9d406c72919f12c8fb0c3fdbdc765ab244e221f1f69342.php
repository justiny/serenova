<?php

/* core/footer.twig */
class __TwigTemplate_7a5827f989c023917b02ab2e95eff70965290f736632292391c4152c4782213f extends Twig_Template
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
        echo "<section class=\"xs-pt-80 xs-pb-40 fill-blue-green es-main\">
  <div class=\"es-footer\">
    <div class=\"flex flex-row flex-wrap justify-center\">
      <div class=\"es-footer-col xs-pl-30 xs-pr-30 xs-pb-30 xs-mb-25 md-mb-0\">
        <h3 class=\"xs-mb-20\">
          <a href=\"";
        // line 6
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f1_title", array()), "url", array());
        echo "\" class=\"text-15-footer caps text-white decoration-none link-footer\">";
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f1_title", array()), "text", array());
        echo "</a>
        </h3>
        <ul class=\"list-unstyled\">
          ";
        // line 9
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["options"] ?? null), "f1_links", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 10
            echo "          <li class=\"xs-mb-15\"><a href=\"";
            echo $this->getAttribute($this->getAttribute($context["item"], "f1_link", array()), "url", array());
            echo "\" class=\"text-14-regular text-dark-80 decoration-none link-footer\">";
            echo $this->getAttribute($this->getAttribute($context["item"], "f1_link", array()), "text", array());
            echo "</a></li>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 12
        echo "        </ul>
      </div>
      <div class=\"es-footer-col xs-pl-30 xs-pr-30 xs-pb-30 xs-mb-25 md-mb-0\">
        <h3 class=\"xs-mb-20\">
          <a href=\"";
        // line 16
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f2_title", array()), "url", array());
        echo "\" class=\"text-15-footer caps text-white decoration-none link-footer\">";
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f2_title", array()), "text", array());
        echo "</a>
        </h3>
        <ul class=\"list-unstyled\">
          ";
        // line 19
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["options"] ?? null), "f2_links", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 20
            echo "          <li class=\"xs-mb-15\"><a href=\"";
            echo $this->getAttribute($this->getAttribute($context["item"], "f2_link", array()), "url", array());
            echo "\" class=\"text-14-regular text-dark-80 decoration-none link-footer\">";
            echo $this->getAttribute($this->getAttribute($context["item"], "f2_link", array()), "text", array());
            echo "</a></li>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        echo "        </ul>
      </div>
      <div class=\"es-footer-col xs-pl-30 xs-pr-30 xs-pb-30 xs-mb-25 md-mb-0\">
        <h3 class=\"xs-mb-20\">
          <a href=\"";
        // line 26
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f3_title", array()), "url", array());
        echo "\" class=\"text-15-footer caps text-white decoration-none link-footer\">";
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f3_title", array()), "text", array());
        echo "</a>
        </h3>
        <ul class=\"list-unstyled\">
          ";
        // line 29
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["options"] ?? null), "f3_links", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 30
            echo "          <li class=\"xs-mb-15\"><a href=\"";
            echo $this->getAttribute($this->getAttribute($context["item"], "f3_link", array()), "url", array());
            echo "\" class=\"text-14-regular text-dark-80 decoration-none link-footer\">";
            echo $this->getAttribute($this->getAttribute($context["item"], "f3_link", array()), "text", array());
            echo "</a></li>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "        </ul>
      </div>
      <div class=\"es-footer-col xs-pl-30 xs-pr-30 xs-pb-30 xs-mb-25 md-mb-0\">
        <h3 class=\"xs-mb-20\">
          <a href=\"";
        // line 36
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f4_title", array()), "url", array());
        echo "\" class=\"text-15-footer caps text-white decoration-none link-footer\">";
        echo $this->getAttribute($this->getAttribute(($context["options"] ?? null), "f4_title", array()), "text", array());
        echo "</a>
        </h3>
        <div class=\"es-footer-wrap-last\">
          <span class=\"text-13 text-dark-80 es-hidden-xs es-show-sm\">Call Us ";
        // line 39
        echo $this->getAttribute(($context["options"] ?? null), "f_phone", array());
        echo "</span>
          ";
        // line 40
        $context["phone_url"] = twig_replace_filter($this->getAttribute(($context["options"] ?? null), "f_phone", array()), array(" " => "", "(" => "", ")" => "", "-" => ""));
        // line 41
        echo "          <a href=\"tel:";
        echo ($context["phone_url"] ?? null);
        echo "\" class=\"text-13 text-dark-80 decoration-none es-hidden-sm\">";
        echo $this->getAttribute(($context["options"] ?? null), "f_phone", array());
        echo "</a>
          <ul class=\"xs-mt-30 list-unstyled\">
            ";
        // line 43
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["options"] ?? null), "f_social_links", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 44
            echo "            <li class=\"flex flex-row align-center xs-mb-15\"><img src=\"";
            echo $this->getAttribute($this->getAttribute($context["item"], "f_social_icon", array()), "url", array());
            echo "\" alt=\"";
            echo $this->getAttribute($this->getAttribute($context["item"], "f_social_icon", array()), "alt", array());
            echo "\" class=\"xs-mr-10\" style=\"max-width:";
            echo $this->getAttribute($context["item"], "icon_max_width", array());
            echo "px\"><a href=\"";
            echo $this->getAttribute($this->getAttribute($context["item"], "f_social_link", array()), "url", array());
            echo "\" class=\"text-13 text-dark-80 decoration-none link-footer\">";
            echo $this->getAttribute($this->getAttribute($context["item"], "f_social_link", array()), "text", array());
            echo "</a></li>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        echo "          </ul>
        </div>
      </div>
    </div>
    <div class=\"es-footer-base xs-pl-30 xs-pr-30\">
      <hr class=\"es-footer-wrap-divide hr fill-dark-80 xs-mb-40\">
      <a href=\"";
        // line 52
        echo $this->getAttribute(($context["home"] ?? null), "link", array());
        echo "\"><img src=\"";
        echo $this->getAttribute(($context["theme"] ?? null), "link", array());
        echo "/assets/images/footer-logo@2x.png\" style=\"max-width:127px\" class=\"xs-mb-25\" alt=\"Serenova Logo\"></a>
      <div class=\"es-footer-base-copyright\">
        <ul class=\"flex xs-flex-column sm-flex-row list-unstyled\">
          <li class=\"text-13 text-dark-70 xs-mb-20 sm-mb-30 xs-pl-0 flex-grow\">&copy; ";
        // line 55
        echo call_user_func_array($this->env->getFilter('date')->getCallable(), array("now", "Y"));
        echo " ";
        echo $this->getAttribute(($context["site"] ?? null), "name", array());
        echo "</li>
          ";
        // line 56
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["options"] ?? null), "base_links", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 57
            echo "          <li class=\"xs-pl-0 sm-pl-30\">
            <a href=\"";
            // line 58
            echo $this->getAttribute($this->getAttribute($context["item"], "base_link", array()), "url", array());
            echo "\" class=\"text-13 text-dark-70 decoration-none link-footer\">";
            echo $this->getAttribute($this->getAttribute($context["item"], "base_link", array()), "text", array());
            echo "</a>
          </li>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 61
        echo "        </ul>
      </div>
    </div>
  </div>
</section>
";
        // line 66
        echo call_user_func_array($this->env->getFunction('function')->getCallable(), array("edit_post_link", "✍", "<div class=\"edit-link\">", "</div>"));
        echo "
";
    }

    public function getTemplateName()
    {
        return "core/footer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  203 => 66,  196 => 61,  185 => 58,  182 => 57,  178 => 56,  172 => 55,  164 => 52,  156 => 46,  139 => 44,  135 => 43,  127 => 41,  125 => 40,  121 => 39,  113 => 36,  107 => 32,  96 => 30,  92 => 29,  84 => 26,  78 => 22,  67 => 20,  63 => 19,  55 => 16,  49 => 12,  38 => 10,  34 => 9,  26 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "core/footer.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/core/footer.twig");
    }
}

<?php

/* layouts/base.twig */
class __TwigTemplate_b37b7680c26e58a8bdb402150ab92c3d4bd98744b20adecc8996f18935511652 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'html_head_container' => array($this, 'block_html_head_container'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('html_head_container', $context, $blocks);
        // line 4
        echo "<body class=\"";
        echo ($context["body_class"] ?? null);
        echo " fill-dark-90\">
  ";
        // line 5
        if (($this->getAttribute(($context["options"] ?? null), "add_gtm", array()) == 1)) {
            // line 6
            echo "  ";
            echo call_user_func_array($this->env->getFunction('function')->getCallable(), array("gtm4wp_the_gtm_tag"));
            echo "
  ";
        }
        // line 8
        echo "  <script>
    if (window.NodeList && !NodeList.prototype.forEach) {
      NodeList.prototype.forEach = Array.prototype.forEach;
    }
  </script>

  ";
        // line 14
        if (($this->getAttribute(($context["options"] ?? null), "add_extra_js", array()) == 1)) {
            // line 15
            echo "  <noscript><!-- Google Tag Manager (noscript) -->
    <iframe src=\"https://www.googletagmanager.com/ns.html?id=";
            // line 16
            echo $this->getAttribute(($context["options"] ?? null), "extra_js", array());
            echo "\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe>
  </noscript>
  ";
        }
        // line 19
        echo "  ";
        $this->loadTemplate("core/nav.twig", "layouts/base.twig", 19)->display($context);
        // line 20
        echo "  <main class=\"es-main\" role=\"main\">
    ";
        // line 21
        $this->displayBlock('content', $context, $blocks);
        // line 24
        echo "  </main>
  ";
        // line 25
        $this->displayBlock('footer', $context, $blocks);
        // line 28
        echo "  ";
        echo ($context["wp_footer"] ?? null);
        echo "

  <script>
  window.intercomSettings = {
  app_id: \"r4b0c3yr\"
  };
  </script>

<script>( function() { var w=window;var ic=w.Intercom;if(typeof ic===\"function\"){ic('reattach_activator');ic('update',intercomSettings); } else { var d=document; var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/r4b0c3yr';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
</body>
</html>
";
    }

    // line 1
    public function block_html_head_container($context, array $blocks = array())
    {
        // line 2
        $this->loadTemplate("html-header.twig", "layouts/base.twig", 2)->display($context);
    }

    // line 21
    public function block_content($context, array $blocks = array())
    {
        // line 22
        echo "    <!-- The template’s main content will go here. -->
    ";
    }

    // line 25
    public function block_footer($context, array $blocks = array())
    {
        // line 26
        echo "    ";
        $this->loadTemplate("core/footer.twig", "layouts/base.twig", 26)->display($context);
        // line 27
        echo "  ";
    }

    public function getTemplateName()
    {
        return "layouts/base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  107 => 27,  104 => 26,  101 => 25,  96 => 22,  93 => 21,  89 => 2,  86 => 1,  69 => 28,  67 => 25,  64 => 24,  62 => 21,  59 => 20,  56 => 19,  50 => 16,  47 => 15,  45 => 14,  37 => 8,  31 => 6,  29 => 5,  24 => 4,  22 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layouts/base.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/layouts/base.twig");
    }
}

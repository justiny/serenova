<?php

/* components/video-spread.twig */
class __TwigTemplate_ad21a85b9097895698c6a292db8c1747351654c56c4b4e22565c71dadf87d584 extends Twig_Template
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
        echo "<section class=\"es-image-spread xs-pb-20 xs-pt-20 sm-pt-80 sm-pb-80\" id=\"";
        echo $this->getAttribute(($context["item"] ?? null), "video_id", array());
        echo "\">
  <div class=\"fill-white xs-pt-40 xs-pb-40\">
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
        echo "            <div class=\"text-20 text-dark-30 xs-pr-40\">";
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
        <script>
        ";
        // line 25
        echo "        </script>
        <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/plyr/3.3.23/plyr.css\">
        <script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/plyr/3.3.23/plyr.polyfilled.min.js\"></script>
        <div class=\"es-image-spread-image es-col-xs-6 es-col-sm-3 relative ";
        // line 28
        echo ((($this->getAttribute(($context["item"] ?? null), "image_spread_position", array()) == "Right")) ? (" -is-right") : (""));
        echo "\">
            <div class=\"xs-pt-60\">
              <div class=\"plyr-bg\"></div>
              ";
        // line 31
        if ((($this->getAttribute(($context["item"] ?? null), "video_type", array()) == "vimeo") || ($this->getAttribute(($context["item"] ?? null), "video_type", array()) == "youtube"))) {
            // line 32
            echo "              <a class=\"video-callout-item-video venobox\" data-autoplay=\"true\" data-vbtype=\"video\" href=\"http://youtu.be/";
            echo $this->getAttribute(($context["item"] ?? null), "video_url", array());
            echo "\">
              ";
        } elseif (($this->getAttribute(        // line 33
($context["item"] ?? null), "video_type", array()) == "local")) {
            // line 34
            echo "              <a class=\"video-callout-item-video venobox\" data-autoplay=\"true\" data-vbtype=\"iframe\" href=\"";
            echo $this->getAttribute(($context["item"] ?? null), "video_local_mp4", array());
            echo "\">
              ";
        }
        // line 36
        echo "                <div class=\"video-wrap relative\">
                  <img src=\"";
        // line 37
        echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "video_poster", array()))), "src", array());
        echo "\" alt=\"\" class=\"es-image-spread-image-video-img\">
                  <img src=\"";
        // line 38
        echo $this->getAttribute(($context["theme"] ?? null), "link", array());
        echo "/assets/images/serenova-play@2x.png\" class=\"video-play\">
                </div>
              </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/video-spread.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  117 => 38,  113 => 37,  110 => 36,  104 => 34,  102 => 33,  97 => 32,  95 => 31,  89 => 28,  84 => 25,  79 => 21,  75 => 19,  65 => 17,  62 => 16,  52 => 14,  50 => 13,  47 => 12,  45 => 11,  40 => 10,  34 => 8,  32 => 7,  28 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/video-spread.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/video-spread.twig");
    }
}

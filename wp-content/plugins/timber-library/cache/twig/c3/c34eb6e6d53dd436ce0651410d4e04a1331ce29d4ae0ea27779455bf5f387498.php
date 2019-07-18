<?php

/* components/image-header.twig */
class __TwigTemplate_a8618181e95421a06d9f46490eeb78f8667cef51d4f390bc7834248e751da298 extends Twig_Template
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
        echo "<section class=\"xs-pb-20 sm-pb-80\">
  <div class=\"es-image-header xs-pr-10 xs-pl-10 xs-pt-40 xs-pb-40 sm-pt-0 sm-pb-0 sm-pr-0 sm-pl-0 sm-mb-150\" style=\"background-image: url(";
        // line 2
        echo $this->getAttribute(call_user_func_array($this->env->getFunction('TimberImage')->getCallable(), array($this->getAttribute(($context["item"] ?? null), "background_image", array()))), "src", array());
        echo ");\">
    <div class=\"sm-nmb-120 flex flex-column\">
      <div class=\"es-image-header-content sm-pt-120\">
        <div class=\"es-image-header-card fill-white header-card-shadow xs-pt-40 xs-pb-40 xs-pr-15 xs-pl-15 sm-pb-65 sm-pt-65 sm-pr-40 sm-pb-55 xl-pt-95 xl-pr-90 xl-pb-80 sm-nmb-50\">
          <div class=\"es-image-header-card-inner\">
            <h1 class=\"title-1 text-dark-10 xs-mb-20\">";
        // line 7
        echo $this->getAttribute(($context["item"] ?? null), "card_title", array());
        echo "</h1>
            ";
        // line 8
        if ( !twig_test_empty($this->getAttribute(($context["item"] ?? null), "card_text", array()))) {
            // line 9
            echo "              <div class=\"text-20 text-dark-30 xs-mb-25\">";
            echo $this->getAttribute(($context["item"] ?? null), "card_text", array());
            echo "</div>
            ";
        }
        // line 11
        echo "            ";
        if (( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_button", array()), "text", array())) ||  !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_link", array()), "text", array())))) {
            // line 12
            echo "            <div class=\"flex flex-wrap align-center xs-flex-column sm-flex-row es-image-header-card-actions\">
              ";
            // line 13
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_button", array()), "text", array()))) {
                // line 14
                echo "                <a href=\"";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_button", array()), "url", array());
                echo "\" class=\"button button-blue button-medium xs-mb-25 md-mb-0 md-mr-40\" ";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_button", array()), "target", array());
                echo ">";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_button", array()), "text", array());
                echo "</a>
              ";
            }
            // line 16
            echo "              ";
            if (( !twig_test_empty($this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_link", array()), "text", array())) && ($this->getAttribute(($context["item"] ?? null), "add_link_or_video", array()) == "link"))) {
                // line 17
                echo "                <a href=\"";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_link", array()), "url", array());
                echo "\" class=\"text-15-medium caps link-blue link-arrow-blue link-arrow-blue-no-animate decoration-none\" ";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_link", array()), "target", array());
                echo ">";
                echo $this->getAttribute($this->getAttribute(($context["item"] ?? null), "card_link", array()), "text", array());
                echo "</a>
              ";
            }
            // line 19
            echo "              ";
            if ((($this->getAttribute(($context["item"] ?? null), "add_link_or_video", array()) == "video") && ($this->getAttribute(($context["item"] ?? null), "video_link_type", array()) == "local"))) {
                // line 20
                echo "                <a class=\"video-callout-item-video venobox text-15-medium caps link-blue link-arrow-blue link-arrow-blue-no-animate decoration-none\" data-autoplay=\"true\" data-vbtype=\"iframe\" href=\"";
                echo $this->getAttribute(($context["item"] ?? null), "local_url", array());
                echo "\">";
                echo $this->getAttribute(($context["item"] ?? null), "link_text", array());
                echo "</a>
              ";
            } elseif ((($this->getAttribute(            // line 21
($context["item"] ?? null), "add_link_or_video", array()) == "video") && ($this->getAttribute(($context["item"] ?? null), "video_link_type", array()) == "vimeo"))) {
                // line 22
                echo "                <a class=\"video-callout-item-video venobox text-15-medium caps link-blue link-arrow-blue link-arrow-blue-no-animate decoration-none\" data-autoplay=\"true\" data-vbtype=\"video\" href=\"http://youtu.be/";
                echo $this->getAttribute(($context["item"] ?? null), "vimeo_id", array());
                echo "\">";
                echo $this->getAttribute(($context["item"] ?? null), "link_text", array());
                echo "</a>
              ";
            }
            // line 24
            echo "            </div>
            ";
        }
        // line 26
        echo "          </div>
        </div>
      </div>
    </div>
  </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "components/image-header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 26,  93 => 24,  85 => 22,  83 => 21,  76 => 20,  73 => 19,  63 => 17,  60 => 16,  50 => 14,  48 => 13,  45 => 12,  42 => 11,  36 => 9,  34 => 8,  30 => 7,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "components/image-header.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/components/image-header.twig");
    }
}

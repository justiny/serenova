<?php

/* core/page.twig */
class __TwigTemplate_9921f7dafb30a01667a30c0a82559ac23246de084198c965cd988ecb717364ad extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layouts/base.twig", "core/page.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layouts/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_content($context, array $blocks = array())
    {
        // line 3
        echo "  ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["post"] ?? null), "get_field", array(0 => "es_layout"), "method"));
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
            echo "    ";
            if (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "simple_header")) {
                // line 5
                echo "      ";
                $this->loadTemplate("components/simple-header.twig", "core/page.twig", 5)->display($context);
                // line 6
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "ui_header")) {
                // line 7
                echo "      ";
                $this->loadTemplate("components/ui-header.twig", "core/page.twig", 7)->display($context);
                // line 8
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "testimonial")) {
                // line 9
                echo "      ";
                $this->loadTemplate("components/testimonial.twig", "core/page.twig", 9)->display($context);
                // line 10
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "image_header")) {
                // line 11
                echo "      ";
                $this->loadTemplate("components/image-header.twig", "core/page.twig", 11)->display($context);
                // line 12
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "card_cta")) {
                // line 13
                echo "      ";
                $this->loadTemplate("components/card-cta.twig", "core/page.twig", 13)->display($context);
                // line 14
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "featured_cards")) {
                // line 15
                echo "      ";
                $this->loadTemplate("components/featured-cards.twig", "core/page.twig", 15)->display($context);
                // line 16
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "locations")) {
                // line 17
                echo "      ";
                $this->loadTemplate("components/locations.twig", "core/page.twig", 17)->display($context);
                // line 18
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "logo_blocks")) {
                // line 19
                echo "      ";
                $this->loadTemplate("components/logo-blocks.twig", "core/page.twig", 19)->display($context);
                // line 20
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "button_cta")) {
                // line 21
                echo "      ";
                $this->loadTemplate("components/button-cta.twig", "core/page.twig", 21)->display($context);
                // line 22
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "related_posts")) {
                // line 23
                echo "      ";
                $this->loadTemplate("components/related-posts.twig", "core/page.twig", 23)->display($context);
                // line 24
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "card_callout")) {
                // line 25
                echo "      ";
                $this->loadTemplate("components/card-callout.twig", "core/page.twig", 25)->display($context);
                // line 26
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "ui_spread")) {
                // line 27
                echo "      ";
                $this->loadTemplate("components/ui-spread.twig", "core/page.twig", 27)->display($context);
                // line 28
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "text_block")) {
                // line 29
                echo "      ";
                $this->loadTemplate("components/text-block.twig", "core/page.twig", 29)->display($context);
                // line 30
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "value_props")) {
                // line 31
                echo "      ";
                $this->loadTemplate("components/value-props.twig", "core/page.twig", 31)->display($context);
                // line 32
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "image_spread")) {
                // line 33
                echo "      ";
                $this->loadTemplate("components/image-spread.twig", "core/page.twig", 33)->display($context);
                // line 34
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "video_spread")) {
                // line 35
                echo "      ";
                $this->loadTemplate("components/video-spread.twig", "core/page.twig", 35)->display($context);
                // line 36
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "image_cta")) {
                // line 37
                echo "      ";
                $this->loadTemplate("components/image-cta.twig", "core/page.twig", 37)->display($context);
                // line 38
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "stats_block")) {
                // line 39
                echo "      ";
                $this->loadTemplate("components/stats-block.twig", "core/page.twig", 39)->display($context);
                // line 40
                echo "      ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "table")) {
                // line 41
                echo "      ";
                $this->loadTemplate("components/table.twig", "core/page.twig", 41)->display($context);
                // line 42
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "bio_repeater")) {
                // line 43
                echo "      ";
                $this->loadTemplate("components/bio-repeater.twig", "core/page.twig", 43)->display($context);
                // line 44
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "plain_rich_text")) {
                // line 45
                echo "      ";
                $this->loadTemplate("components/plain-rich-text.twig", "core/page.twig", 45)->display($context);
                // line 46
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "video")) {
                // line 47
                echo "      ";
                $this->loadTemplate("components/video.twig", "core/page.twig", 47)->display($context);
                // line 48
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "image")) {
                // line 49
                echo "      ";
                $this->loadTemplate("components/image.twig", "core/page.twig", 49)->display($context);
                // line 50
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "column_text")) {
                // line 51
                echo "      ";
                $this->loadTemplate("components/column-text.twig", "core/page.twig", 51)->display($context);
                // line 52
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "product_header")) {
                // line 53
                echo "      ";
                $this->loadTemplate("components/product-header.twig", "core/page.twig", 53)->display($context);
                // line 54
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "pricing_table")) {
                // line 55
                echo "      ";
                $this->loadTemplate("components/pricing-table.twig", "core/page.twig", 55)->display($context);
                // line 56
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "form_embed")) {
                // line 57
                echo "      ";
                $this->loadTemplate("components/form-embed.twig", "core/page.twig", 57)->display($context);
                // line 58
                echo "    ";
            } elseif (($this->getAttribute($context["item"], "acf_fc_layout", array()) == "roi_calculator")) {
                // line 59
                echo "      ";
                $this->loadTemplate("components/roi-calculator.twig", "core/page.twig", 59)->display($context);
                // line 60
                echo "    ";
            }
            // line 61
            echo "  ";
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
    }

    public function getTemplateName()
    {
        return "core/page.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  220 => 61,  217 => 60,  214 => 59,  211 => 58,  208 => 57,  205 => 56,  202 => 55,  199 => 54,  196 => 53,  193 => 52,  190 => 51,  187 => 50,  184 => 49,  181 => 48,  178 => 47,  175 => 46,  172 => 45,  169 => 44,  166 => 43,  163 => 42,  160 => 41,  157 => 40,  154 => 39,  151 => 38,  148 => 37,  145 => 36,  142 => 35,  139 => 34,  136 => 33,  133 => 32,  130 => 31,  127 => 30,  124 => 29,  121 => 28,  118 => 27,  115 => 26,  112 => 25,  109 => 24,  106 => 23,  103 => 22,  100 => 21,  97 => 20,  94 => 19,  91 => 18,  88 => 17,  85 => 16,  82 => 15,  79 => 14,  76 => 13,  73 => 12,  70 => 11,  67 => 10,  64 => 9,  61 => 8,  58 => 7,  55 => 6,  52 => 5,  49 => 4,  31 => 3,  28 => 2,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "core/page.twig", "/srv/bindings/edda85c2676142e1ad0f6d8a7b02288d/code/wp-content/themes/serenova/views/core/page.twig");
    }
}

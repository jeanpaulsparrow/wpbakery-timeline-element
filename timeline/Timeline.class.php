<?php
if (!class_exists('iwebyou_timeline_Shortcode')) {

    class iwebyou_timeline_Shortcode
    {

        /**
         * Main constructor
         */
        public function __construct()
        {

            // Registers the shortcode in WordPress
            add_shortcode('iwebyou_timeline', __CLASS__ . '::output');

            // Map shortcode to WPBakery so you can access it in the builder
            if (function_exists('vc_lean_map')) {
                vc_lean_map('iwebyou_timeline', __CLASS__ . '::map');
            }
        }

        /**
         * Shortcode output
         */
        public static function output($atts, $content = null)
        {

            // Extract shortcode attributes (based on the vc_lean_map function - see next function)
            $atts = vc_map_get_attributes('iwebyou_timeline', $atts);

            $fasi = json_decode(urldecode($atts['fasi']), true);

            if (!$fasi) {
                return;
            }

            // Define output and open element div.
            $output = '<div class="iwebyou_element_timeline">';

            $output .= '<div class="timeline">';
            foreach ($fasi as $key => $fase) {
                $class_odd =  $key % 2 == 0 ? 'left-fase' : 'right-fase';
                $style = iwebyou_timeline_Shortcode::create_style($fase);
                $subtitle_style = $fase['colore_sottotitolo'] ? ' style="color: ' . esc_html($fase['colore_sottotitolo']) . '"' : '';
                if ($fase['colore_fase']) {
                    if ($class_odd === 'left-fase') {
                        $output .= '<style>.fase-' . $key . '::before{border-color: transparent transparent transparent ' . esc_html($fase['colore_fase']) . ' !important}</style>';
                    } else {
                        $output .= '<style>.fase-' . $key . '::before{border-color: transparent ' . esc_html($fase['colore_fase']) . ' transparent transparent;}</style>';
                    }
                }
                $output .= '<div class="container-fase fase-' . $key . ' ' . $class_odd . '">';
                $output .= '<div ' . $subtitle_style . ' class="fase-subtitle">' . esc_html($fase['sottotitolo']) . '</div>';
                $output .= '<div ' . $style . ' class="content-fase">';
                $output .= '<p><strong>' . esc_html($fase['heading']) . '</strong></p>';
                $output .= '<p>' . esc_html($fase['testo']) . '</p>';
                $output .= '</div>';
                $output .= '</div>';
            }
            $output .= '</div>';




            // Close element.
            $output .= '</div>';
            if ($atts['colore_timeline']) {
                $output .= '<style>.timeline::after {background-color:' . $atts['colore_timeline'] . ';} .container-fase::after{border-color: ' . $atts['colore_timeline'] . '}</style>';
            }

            // Return output
            return $output;
        }

        /**
         * Crea fase style
         * 
         */
        public static function create_style($fase)
        {
            $background_fase = $fase['colore_fase'] ? ' background-color: ' . esc_html($fase['colore_fase']) . ';' : '';
            $color_fase = $fase['colore_font_fase'] ? ' color: ' . esc_html($fase['colore_font_fase']) . ';' : '';
            if ($color_fase || $background_fase) {
                return ' style="' . $background_fase . $color_fase . '"';
            }
            return '';
        }

        /**
         * Map shortcode to WPBakery
         *
         * This is an array of all your settings which become the shortcode attributes ($atts)
         * for the output. See the link below for a description of all available parameters.
         *
         * @since 1.0.0
         * @link https://kb.wpbakery.com/docs/inner-api/vc_map/
         */
        public static function map()
        {
            return array(
                'name' => esc_html__('Timeline', 'iwebyou-timeline'),
                'description' => esc_html__('La timeline delle fasi di lavorazione', 'iwebyou-timeline'),
                'base' => 'iwebyou_timeline',
                'params' => array(
                    array(
                        'type' => 'param_group',
                        'param_name' => 'fasi',
                        'group' => esc_html__('Fasi', 'iwebyou-timeline'),
                        'value' => urlencode(json_encode(array())),
                        'params' => array(
                            array(
                                'type' => 'textfield',
                                'heading' => esc_html__('Nome fase', 'iwebyou-timeline'),
                                'param_name' => 'heading',
                                'admin_label' => true,
                            ),
                            array(
                                'type' => 'textfield',
                                'heading' => esc_html__('Sottotitolo fase', 'iwebyou-timeline'),
                                'param_name' => 'sottotitolo',
                                'admin_label' => true,
                            ),
                            array(
                                'type' => 'textarea',
                                'heading' => esc_html__('Testo', 'iwebyou-timeline'),
                                'param_name' => 'testo',
                            ),
                            array(
                                'type' => 'colorpicker',
                                'heading' => esc_html__(
                                    'Colore background fase',
                                    'iwebyou-timeline'
                                ),
                                'param_name' => 'colore_fase',
                                'css' => [
                                    'selector' => '.vcex-social-links__item',
                                    'property' => 'color',
                                ],
                            ),
                            array(
                                'type' => 'colorpicker',
                                'heading' => esc_html__(
                                    'Colore font fase',
                                    'iwebyou-timeline'
                                ),
                                'param_name' => 'colore_font_fase',
                                'css' => [
                                    'selector' => '.vcex-social-links__item',
                                    'property' => 'color',
                                ],
                            ),
                        ),

                    ),
                    array(
                        'type' => 'colorpicker',
                        'group' => esc_html__('Generale', 'iwebyou-timeline'),
                        'heading' => esc_html__(
                            'Colore Timeline',
                            'iwebyou-timeline'
                        ),
                        'param_name' => 'colore_timeline',
                        'css' => [
                            'selector' => '.vcex-social-links__item',
                            'property' => 'color',
                        ],
                    ),
                ),
            );
        }
    }
}
new iwebyou_timeline_Shortcode;

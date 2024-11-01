<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       https://webria.fr
* @since      1.0.0
*
* @package    Variantspictures
* @subpackage Variantspictures/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Variantspictures
* @subpackage Variantspictures/admin
* @author     benoit fremont <contact@site-web.net>
*/

class Variantspictures_Admin {

    /**
    * The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_name    The ID of this plugin.
    */
    private $plugin_name;

    /**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
    private $version;

    /**
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_name       The name of this plugin.
    * @param      string    $version    The version of this plugin.
    */

    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_filter( 'doing_it_wrong_trigger_error', function () {
            return false;
        }, 10, 0 );

        add_filter( 'woocommerce_product_data_tabs', 'Variantspictures_add_my_FLY_product_data_tab' );
        add_action( 'woocommerce_product_data_panels', 'Variantspictures_add_my_FLY_product_data_fields' );
        add_action( 'wp_ajax_FLYupdateVariants', 'Variantspictures_FLYupdateVariants' );
        add_action( 'wp_ajax_nopriv_FLYupdateVariants', 'Variantspictures_FLYupdateVariants' );
    }

    /**
    * Register the stylesheets for the admin area.
    *
    * @since    1.0.0
    */

    public function enqueue_styles() {
        wp_enqueue_style( "FLYcodejs", plugin_dir_url( __FILE__ ) . 'css/variantspictures-admin.css', array(), $this->version, 'all' );
    }

    /**
    * Register the JavaScript for the admin area.
    *
    * @since    1.0.0
    */

    public function enqueue_scripts() {
        wp_enqueue_script( "FLYcodejs", plugin_dir_url( __FILE__ ) . 'js/variantspictures-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( "FLYcodejs", 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    }

}

// START CODE Variantspictures

if (!function_exists('Variantspictures_FLYupdateVariants')) { 
    function Variantspictures_FLYupdateVariants() {

        $FLYListedeclitRGBTABtmp = array(
            "version" => "1"
        );
        $FLYPOSTaction2             = "";
        $FLYPOSTvaleur              = "";
        $FLYPOSTlistSQLVariants     = "";
        // SANITIZE  input
        if(isset($_POST['action2'])){
            $FLYPOSTaction2 = sanitize_text_field($_POST['action2']);
        }
        if(isset($_POST['valeur'])){
            $FLYPOSTvaleur  = sanitize_text_field($_POST['valeur']);
        }
        if(isset($_POST['listSQLVariants'])){
            $FLYPOSTlistSQLVariants = sanitize_text_field($_POST['listSQLVariants']);
        }
    
        $FLYListedeclitRGBTABtmp = Variantspictures_filetotab( plugin_dir_path( __FILE__ ).'rgbdecli.don' );
        if ( $FLYPOSTaction2 == "FLYActualise" ) {
            $tmpchFLY = Variantspictures_add_my_FLY_product_data_fieldsAjax();
            echo esc_attr("ok");
        } elseif ( $FLYPOSTaction2 == "FLYupdateRGBfic" ) {
            $FLYcreeTableau = $FLYPOSTvaleur;
            $FLYretourinfos = "";
            $array = explode( '[id]', $FLYcreeTableau );
            foreach ( $array as $values ) {
                $array2 = explode( '[val]', $values );
                $FLYListedeclitRGBTABtmp[$array2[0]] = $array2[1];
                $FLYretourinfos .= "FLYListedeclitRGBTABtmp[array2[0]] ".$FLYListedeclitRGBTABtmp[$array2[0]]." array2[0]".$array2[0]." array2[1]".$array2[1];
            }
            Variantspictures_tabtofile( $FLYListedeclitRGBTABtmp, plugin_dir_path( __FILE__ ).'rgbdecli.don' );

        } else {
            $tmpretour = "";
            $valeurimg = intval($FLYPOSTvaleur);
            $array = explode( ',', $FLYPOSTlistSQLVariants);
            foreach ( $array as $values ) {
                $tmpretour .=  intval( $values )." ".$valeurimg." -- ";
                set_post_thumbnail( intval( $values ), $valeurimg );
            }
        }
        wp_die();
    }
}

if (!function_exists('Variantspictures_add_my_FLY_product_data_tab')) {
    function Variantspictures_add_my_FLY_product_data_tab( $product_data_tabs ) {
        $product_data_tabs['my-FLY-tab'] = array(
            'label' => __( 'Photos of variations', 'variantspictures' ),
            'target' => 'my_FLY_product_data',
        );
        return $product_data_tabs;
    }
}

if (!function_exists('Variantspictures_filter_variation_attributes')) {
    function Variantspictures_filter_variation_attributes( $attribute ) {
        return true === $attribute->get_variation();
    }
}
if (!function_exists('Variantspictures_add_my_FLY_product_data_fields')) {
    function Variantspictures_add_my_FLY_product_data_fields() {
        $resultFLY = Variantspictures_add_my_FLY_product_data_fieldsAjax();
        $allowed_atts = array(
            'align'      => array(),
            'class'      => array(),
            'type'       => array(),
            'id'         => array(),
            'dir'        => array(),
            'lang'       => array(),
            'style'      => array(),
            'xml:lang'   => array(),
            'src'        => array(),
            'alt'        => array(),
            'href'       => array(),
            'rel'        => array(),
            'rev'        => array(),
            'target'     => array(),
            'novalidate' => array(),
            'type'       => array(),
            'value'      => array(),
            'name'       => array(),
            'tabindex'   => array(),
            'action'     => array(),
            'method'     => array(),
            'for'        => array(),
            'width'      => array(),
            'height'     => array(),
            'data'       => array(),
            'title'      => array(),
            'role'       => array(),
            'namecolo'   => array(),
            'checked'   => array(),
            'msgerr'   => array(),
            'namedeclifly'=> array(),
            'namecolo'=> array(),
        );

        $allowedposttags['form']     = $allowed_atts;
        $allowedposttags['label']    = $allowed_atts;
        $allowedposttags['input']    = $allowed_atts;
        $allowedposttags['strong']   = $allowed_atts;
        $allowedposttags['table']    = $allowed_atts;
        $allowedposttags['span']     = $allowed_atts;
        $allowedposttags['div']      = $allowed_atts;

        $allowedposttags['h1']       = $allowed_atts;
        $allowedposttags['h2']       = $allowed_atts;
        $allowedposttags['h3']       = $allowed_atts;
        $allowedposttags['h4']       = $allowed_atts;
        $allowedposttags['h5']       = $allowed_atts;
        $allowedposttags['h6']       = $allowed_atts;
        $allowedposttags['ol']       = $allowed_atts;
        $allowedposttags['ul']       = $allowed_atts;
        $allowedposttags['em']       = $allowed_atts;
        $allowedposttags['hr']       = $allowed_atts;
        $allowedposttags['br']       = $allowed_atts;
        $allowedposttags['tr']       = $allowed_atts;
        $allowedposttags['td']       = $allowed_atts;
        $allowedposttags['p']        = $allowed_atts;
        $allowedposttags['a']        = $allowed_atts;
        $allowedposttags['b']        = $allowed_atts;
        $allowedposttags['i']        = $allowed_atts;
        $allowedposttags['img']      = $allowed_atts;
        $allowedposttags['select']   = array(
            'class'  => array(),
            'id'     => array(),
            'name'   => array(),
            'value'  => array(),
            'type'   => array(),
        );        
        $allowedposttags['option'] = array(
            'selected' => array(),
            'idoption' => array(),
            'value' => array(),
        );
        $allowedposttags['button'] = array(
            'type' => array(),
            'class' => array(),
            'tabindex' => array(),
        );
        $allowedposttags['li']    = array(
            'tabindex' => array(),
            'role' => array(),
            'aria-label' => array(),
            'aria-checked' => array(),
            'data-id' => array(),
            'class' => array(),
            'style' => array(),
        );
       echo wp_kses( $resultFLY,$allowedposttags);
    }
}
// ************************************************************************************************
if (!function_exists('Variantspictures_add_my_FLY_product_data_fieldsAjax')) {
    function Variantspictures_add_my_FLY_product_data_fieldsAjax() {
        $FLYreturnHTML = "";
        $FLYListedeclitRGB = "";
        global $post, $wpdb, $product_object;
        $variation_attributes     = $product_object->get_attributes();
        $default_attributes     = $product_object->get_default_attributes();
        $variations_count       = absint( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'product_variation' AND post_status IN ('publish', 'private')", $post->ID ) ), $post->ID );

        $variations_per_page    = absint( 5000 );
        $variations_total_pages = ceil( $variations_count / $variations_per_page );

        $FLYreturnHTML .= '
            <div id="my_FLY_product_data" class="panel woocommerce_options_panel">
            <div id="flydeclicontent">
            ';
        if ( ! count( $variation_attributes ) || $variations_count==0  ) :
            $FLYreturnHTML .= '
            <div  class="inline notice woocommerce-message">
                <p> '.__("Before you can see the images of your variations you need to add attributes and variations ", 'variantspictures').' <strong>'.__(" in the Attributes then Variations tabs").'</strong>.
                <br> '.__("Remember to save your changes by pressing the button ", 'variantspictures').' <strong>'.__("update").'</strong>.</p>
                <p><a class="button-primary" href="https://docs.woocommerce.com/document/variable-product/" target="_blank">'.__("Learn more", 'variantspictures').'</a></p>
            </div>
                '; 

        else :
        $featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );

        $FLYfeatured_title = get_the_title(get_the_ID());
        $FLYreturnHTML .= '
                    <div class="variations-filtre flycontentdeclipopup">
                        <input id="FLYimagepostfull" type="hidden" value="'.$featured_img_url.'">
                        <input id="titleimaFLY" type="hidden" value="'.$FLYfeatured_title.'">

                        <span class="libFLYfiltredecli flycontentdeclipopup">
                        '.__( 'Select variations : ', 'variantspictures' ).'
                        </span>';

        $FLYListedeclitoexport = "";
        $FLYListedeclitRGBTAB = Variantspictures_filetotab( plugin_dir_path( __FILE__ ).'rgbdecli.don' );

        foreach ( $variation_attributes as $attribute ) {

            if ( array_key_exists( esc_attr( sanitize_title( $attribute->get_name() ) ), $FLYListedeclitRGBTAB ) ) {
                $FLYchecked = $FLYListedeclitRGBTAB[esc_attr( sanitize_title( $attribute->get_name() ) )];
            } else {
                $FLYchecked = "";
            }

            if ( $FLYchecked == "checked" ) {
                $FLYListedeclitoexport .= "[at]".wc_attribute_label( sanitize_title($attribute->get_name()) );
                $FLYHiddendivdecli = "";
            } else {
                $FLYHiddendivdecli = "FLYhidden";
            }

            $FLYListedeclitRGB .= '<div class="FLYcolorselectContent ">';
            $FLYListedeclitRGB .= '<div class="FLYcolorselectContenttytle ">';
            $FLYListedeclitRGB .= wc_attribute_label( sanitize_title($attribute->get_name()) );
            $FLYListedeclitRGB .= ' <input namedeclifly='.wc_attribute_label( sanitize_title($attribute->get_name() )).'  value="'.$FLYchecked.'" '.$FLYchecked.' type="checkbox" class="FLYsaveArray" id="'.esc_attr( sanitize_title( $attribute->get_name() ) ).'" >';

            $FLYListedeclitRGB .= '&nbsp;<span class="FLYinfossiCOLORIE">'.__( '( Check if it is a colore variation )', 'variantspictures' ).'</span>';
            
            $FLYListedeclitRGB .= '</div>';

            $FLYaDEFINIRALL=false;
            $FLYreturnHTML .= '        
            <select id = "FLY_'.esc_attr( sanitize_title( $attribute->get_name() ) ).'" class = "flyfiltredecli" name = "FLydefault_attribute_'.esc_attr( sanitize_title( $attribute->get_name() ) ).'" >
            <option value = "00">'.esc_html( sprintf( __( 'All %s&hellip;', 'variantspictures' ), wc_attribute_label( $attribute->get_name() ) ) ).'</option>';
            $FLYreturnHTML .= '<div class="FLYlistvaluedecli flycontentdeclipopup">';
            if ( $attribute->is_taxonomy() ) : 
        foreach ( $attribute->get_terms() as $option ) : 
                $FLYreturnHTML .= ' 
                <option  idoption = "" value = "'.$attribute->get_id().'-'.esc_attr( $option->slug ).'">';
                $FLYreturnHTML .=  esc_html( apply_filters( 'woocommerce_variation_option_name', $option->name, $option, $attribute->get_name(), $product_object ) );
                $FLYreturnHTML .='</option>';

                $FLYoptionidtmp = $attribute->get_id().'-'. esc_attr( $option->slug );
                $FLYaDEFINIR="";
                if ( array_key_exists( $FLYoptionidtmp, $FLYListedeclitRGBTAB ) ) {
                    $colorRGBfly = $FLYListedeclitRGBTAB[$FLYoptionidtmp];
                    if ($colorRGBfly =="#00000000"){
                        $FLYaDEFINIR ="FLYaDEFINIR";
                        $FLYaDEFINIRALL=true;                   
                    }
                } else {
                    $colorRGBfly = "#00000000";
                    $FLYaDEFINIR ="FLYaDEFINIR";
                    $FLYaDEFINIRALL=true;
                }

                $FLYnomcolorie = esc_html( apply_filters( 'woocommerce_variation_option_name', $option->name, $option, $attribute->get_name(), $product_object ) );
                //gestion colorie RGB
                $FLYListedeclitRGB .= '<div class="FLYnamevaluedecli box'.esc_attr( sanitize_title( $attribute->get_name() ) ).' '.$FLYHiddendivdecli.'">';

                $FLYListedeclitRGB .= ' <input type="color" class="FLYcolorselectbtn FLYsaveArray '.$FLYaDEFINIR.'" id="'.$FLYoptionidtmp.'" namecolo="'.$FLYnomcolorie.'" value="'.$colorRGBfly.'"><br>';
                $FLYListedeclitRGB .= $FLYnomcolorie;

                $FLYListedeclitRGB .= '</div>';
                //gestion chaine url decli
                if ( $FLYchecked == "checked" ) {
                    $FLYListedeclitoexport .= "[de]".$FLYnomcolorie;
                    $FLYListedeclitoexport .= "[co]".$colorRGBfly;
                }
            endforeach;

            if ($FLYaDEFINIRALL){
                $FLYListedeclitRGB .='<div id="FLYaDEFINIRALL" class="inline notice woocommerce-message box'.esc_attr( sanitize_title( $attribute->get_name() ) ).' '.$FLYHiddendivdecli.'"">';
                $FLYListedeclitRGB .='<p>'.__( 'You must set the color of the above variants', 'variantspictures').'</p>';
                $FLYListedeclitRGB .='</div>';
            }
            $FLYreturnHTML .= '</div>';
            $FLYListedeclitRGB .= '</div>'; 
        else :
                foreach ( $attribute->get_options() as $option ) : 
                    $FLYreturnHTML .= '
                    <option  value = "'.esc_attr( $option ).'">'.esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute->get_name(), $product_object ) );
                    $FLYreturnHTML .= '</option>';
                endforeach;
            endif;
            $FLYreturnHTML .= '</select>';
            
        }
        $FLYreturnHTML .= '<span id="FLYupdatemsg">'.__( 'Remember to save your modifications', 'variantspictures').'</span>';
        $FLYreturnHTML .= '</div>';
        $FLYreturnHTML .= '
        <div class = "FLYalaligne flycontentdeclipopup">
            <a id = "FLYmodifgroupphotos" class = "button bulk_edit">'.__( 'Set image of selected variations below', 'variantspictures').'</a>
            <div class="openvariantspicturesContent Flyincludepopup"><a class="openvariantspictures button button-primary button-large">'.__( 'Colorize the product image', 'variantspictures').'</a></div>
                <input type = "hidden" id = "FLYListedeclitoexport" value = "'.$FLYListedeclitoexport.'">
                <input type = "hidden" id = "FLYLurlplugin" value = "'.plugin_dir_url( __FILE__ ).'">
                <input type = "hidden" id = "FLYLmsgconfirm" value = "'.__( 'Do you confirm the assignment of the images of the variations selected ?', 'variantspictures').'">
            </div>
            <div id = "flycontentdecli" class="flycontentdeclipopup"  >';
        //  *****************************
        ob_start();
        global $post;

        $loop           = 0;
        $product_id     = absint( $post->ID );
        $post           = get_post( $product_id );
        // 
        $product_object = wc_get_product( $product_id );
        $per_page       = 500000;
        $page           = 1;
        $variations     = wc_get_products(
            array(
                'status'  => array( 'private', 'publish' ),
                'type'    => 'variation',
                'parent'  => $product_id,
                'limit'   => $per_page,
                'page'    => $page,
                'orderby' => array(
                    'menu_order' => 'ASC',
                    'ID'         => 'DESC',
                ),
                'return'  => 'objects',
            )
        );

        if ( $variations ) {
            wc_render_invalid_variation_notice( $product_object );
            $FLYreturnHTML .= '
            <ul tabindex = "-1" class = "attachments ui-sortable ui-sortable-disabled" id = "FLYattachments-view">
            ';
            foreach ( $variations as $variation_object ) {
                $variation_id   = $variation_object->get_id();
                $variation      = get_post( $variation_id );
                $variation_data = array_merge( get_post_custom( $variation_id ), wc_get_product_variation_attributes( $variation_id ) );
                $attribute_values = $variation_object->get_attributes( 'edit' );
                $ajclass = "";
                $namedecli = "";
                foreach ( $product_object->get_attributes( 'edit' ) as $attribute ) {
                    if ( ! $attribute->get_variation() ) {
                        continue;
                    }
                    $selected_value = $attribute_values[ sanitize_title( $attribute->get_name() ) ]  ;
                    $ajclass .= " ".$attribute->get_id()."-".$selected_value;
                    $namedecli .= " ".$selected_value;
                }

                $FLYimageurl =  $variation_object->get_image_id( 'edit' ) ? esc_url( wp_get_attachment_thumb_url( $variation_object->get_image_id( 'edit' ) ) ) : esc_url( wc_placeholder_img_src() );

                $FLYreturnHTML .= '
                <li tabindex = "0" role = "checkbox" aria-label = "'.$namedecli.'" aria-checked = "true" data-id = "'.$variation_id.'" class = "attachment save-ready selected variantspictures_decli'.$ajclass.'">
                    <span class="FlyInfosthumbnail">
                    '.$namedecli.'
                    </span>
                    <div class = "attachment-preview js--select-attachment type-image subtype-png landscape">
                        <div class = "thumbnail">
                            <div class = "centered">
                            <img id = "img'.$variation_id.'" src = "'.$FLYimageurl.'" draggable = "false" alt = "">
                            </div>
                        </div>
                    </div>
                    <button type = "button" class = "check" tabindex = "-1">
                    <span class = "media-modal-icon"></span><span class = "screen-reader-text">'.__( 'deselected', 'variantspictures').'</span>
                    </button>
                </li>

                ';

                $loop++;
            }
        }

        $FLYreturnHTML .= '
            </ul>
            </div>
        </div>';

        endif; 
        $FLYreturnHTML .= '
        <div id = "FMYmodifRGBbackround" ></div>
    
        <div id = "FMYmodifRGB" class = "FLYpopupdecli">
            <div id="FLYgoTOdecli" >
                <img id="FLYgoTOdecliIMG" msgerr="'.__( 'Select a image first.', 'variantspictures').'" src="'.get_the_post_thumbnail_url(get_the_ID()).'"><br>
                <a id="FLYgoTOdeclimodifphotos" class="button bulk_edit">'.__( 'Select a image', 'variantspictures').'</a><br>
                <span class="FLYinfossiCOLORIE">'.__( 'Does not modify the product image.', 'variantspictures').'</span>
            </div>
            <div id="FLYgoTOdecli2" >
            '.$FLYListedeclitRGB.'
            </div>
            <div id="FLYgoTOdecli3" >
                <span class="FLYinfossiCOLORIE FLYgoTOdecliLegal">'.__( '* By clicking on "colorize my image", I authorize the decli.fr web application to use my images and its variation information in order to do the job. No information will be saved on the decli.fr server.', 'variantspictures').'</span>
                <div id="FLYgoTOdecli3b" >
                    <div id="FLYgoTOdecliGO" class="button button-primary button-large">'.__( 'Colorize my image*', 'variantspictures').'</div>
                    <div class="openvariantspictures button bulk_edit">'.__( 'CLOSE', 'variantspictures').'</div>
                </div>
            </div>

        </div>';
        
        $FLYreturnHTML .= '</div>';
        return $FLYreturnHTML;

    }
}
//  *************************************** FONCTION OUTILS
if (!function_exists('Variantspictures_tabtostring')) {
    function Variantspictures_tabtostring( $tab ) {
        if ( !is_array( $tab ) ) return false;
        $string = "array";
        foreach ( $tab as $key => $content ) {
            if ( is_array( $tab[$key] ) && gettype( $key ) != "object" && gettype( $key ) !=
            "resource" && gettype( $key ) != "unknown" ) {
                $string .= "\n".gettype( $key )."\n".base64_encode( $key )."\n".
                Variantspictures_tabtostring( $content );
                // Récursivité
            } else {
                if ( gettype( $key ) != "object" && gettype( $key ) != "resource" && gettype(
                    $key ) != "unknown" && gettype( $content ) != "object" && gettype( $content ) !=
                    "resource" && gettype( $content ) != "unknown" ) {
                        $string .= "\n".gettype( $key )."\n".base64_encode( $key )."\n".
                        gettype( $content )."\n".base64_encode( $content );
                    }
                }

            }
            $string .= "\nendarray";
            return $string;
    }
}

if (!function_exists('Variantspictures_stringtabtotab')) {
    function Variantspictures_stringtabtotab( $stringtab, $begin ) {
        if ( !is_array( $stringtab ) ) return false;
        $i = $begin;
        while( $i<( count( $stringtab )-1 ) ) {
            if ( @$stringtab[$i+2] == "array" ) {
                $cle = base64_decode( $stringtab[$i+1] );
                settype( $cle, $stringtab[$i] );
                $tab[$cle] = Variantspictures_stringtabtotab( $stringtab, $i+3 );
                while( $stringtab[$i] != "endarray" ) {
                    $i++;
                }
                $i++;
            } elseif ( $stringtab[$i] == "endarray" ) {
                return $tab;
            } else {
                $cle = base64_decode( $stringtab[$i+1] );
                $valeur = base64_decode( $stringtab[$i+3] );
                settype( $cle, $stringtab[$i] );
                settype( $valeur, $stringtab[$i+2] );
                $tab[$cle] = $valeur;
                $i += 4;
            }
        }
        return $tab;
    }
}

if (!function_exists('Variantspictures_tabtofile')) {
    function Variantspictures_tabtofile( $tab, $filename ) {
        $donnees = Variantspictures_tabtostring( $tab );
        if ( ( $fp = fopen( $filename, 'w' ) ) && $donnees ) {
            fwrite( $fp, $donnees );
            fclose( $fp );
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('Variantspictures_filetotab')) {
    function Variantspictures_filetotab( $filename ) {
        if ( !file_exists( $filename ) ) return false;
        $donnees = file( $filename );
        foreach ( $donnees as &$content ) $content = trim( $content );
        return Variantspictures_stringtabtotab( $donnees, 1 );
    }
}
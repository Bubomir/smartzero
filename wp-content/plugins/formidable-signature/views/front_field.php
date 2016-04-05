<?php 
$sig_val = is_array( $field['value'] ) ? ( isset( $field['value']['output'] ) && ( ! empty( $field['value']['output'] ) ) ? $field['value']['output'] : '' ) : $field['value'];

if ( $entry_id && $field['value'] && ! empty( $sig_val ) ) {
    $width = 198;
    $height = 55;
    if ( is_array( $field['value'] ) && isset( $field['value']['height'] ) ) {
        $width = $field['value']['width'];
        $height = $field['value']['height'];
    }
    ?>
<div class="sigPad<?php echo (int) $field['id'] ?> signed">
    <div class="sigWrapper">
        <canvas class="pad" width="<?php echo (int) $width ?>" height="<?php echo (int) $height ?>"></canvas>
    </div>
</div>
<input type="hidden" name="<?php echo esc_attr( $field_name ) ?>[typed]" value="<?php echo esc_attr( ( is_array( $field['value'] ) && isset( $field['value']['typed'] ) ) ? $field['value']['typed'] : ( is_array( $field['value'] ) ? reset( $field['value'] ) : $field['value'] ) ) ?>" />
<input type="hidden" name="<?php echo esc_attr( $field_name ) ?>[output]" id="frmsig_<?php echo (int) $field['id'] ?>_output" class="output" value="<?php echo esc_attr( ( is_array( $field['value'] ) && isset( $field['value']['output'] ) ) ? $field['value']['output'] : '' ) ?>" />
<input type="hidden" name="<?php echo esc_attr( $field_name ) ?>[width]" value="<?php echo esc_attr( $width ) ?>" />
<input type="hidden" name="<?php echo esc_attr( $field_name ) ?>[height]" value="<?php echo esc_attr( $height ) ?>" />

<?php
    if ( isset( $field['value']['output'] ) && ! empty( $field['value']['output'] ) ) { ?>
<script type="text/javascript">
jQuery(document).ready(function($){
$('.sigPad<?php echo (int) $field['id'] ?>').signaturePad({displayOnly:true}).regenerate( document.getElementById('frmsig_<?php echo (int) $field['id'] ?>_output').value);
});
</script>
<?php
    }
} else {
    $width = (int)  $field['size'];
    $height = (int) ( ! empty( $field['max'] ) ) ? $field['max'] : 150;
    $hide_tabs = isset( $field['restrict'] ) ? $field['restrict'] : false;

?>
<style type="text/css">.sigWrapper.current{border-color:#<?php echo esc_attr( $style_settings['border_color'] ) ?>;}</style>
<div class="sigPad" id="sigPad<?php echo (int) $field['id'] ?>" style="max-width:<?php echo (int) $width ?>px;">

<ul class="sigNav <?php echo $hide_tabs ? 'sigHideTabs' : ''; ?>">
    <li class="drawIt"><a href="#draw-it" class="current"><?php echo esc_html( $field['label1'] ) ?></a></li>
    <li class="typeIt"><a href="#type-it" ><?php echo esc_html( $field['label2'] ) ?></a></li>
    <li class="clearButton"><a href="#clear"><?php echo esc_html( $field['label3'] ) ?></a></li>
</ul>
<div class="sig sigWrapper" style="height:<?php echo (int) $height ?>px;border-color:#<?php echo esc_attr( $style_settings['border_color'] ) ?>;">
    <div class="typed" style="height:<?php echo (int) $height ?>px;">
        <input type="text" name="<?php echo esc_attr( $field_name ) ?>[typed]" class="name" id="field_<?php echo esc_attr( $field['field_key'] ) ?>" autocomplete="off" style="width:<?php echo ($width-20) ?>px;" value="<?php echo esc_attr( ( is_array( $field['value'] ) && isset( $field['value']['typed'] ) ) ? $field['value']['typed'] : '' ) ?>" />
    </div>
    <canvas class="pad" width="<?php echo ($width-2) ?>" height="<?php echo (int) $height ?>"></canvas>
    <input type="hidden" name="<?php echo esc_attr( $field_name ) ?>[output]" class="output" value="<?php echo esc_attr( $sig_val ) ?>" />
    <input type="hidden" name="<?php echo esc_attr( $field_name ) ?>[width]" value="<?php echo esc_attr( $width-2 ) ?>" />
    <input type="hidden" name="<?php echo esc_attr( $field_name ) ?>[height]" value="<?php echo esc_attr( $height ) ?>" />
    </div>
</div>
<?php } ?>
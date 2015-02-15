<?php $sizes=$this->get_image_sizes(); ?>
<script type="text/html" id="tmpl-wppress-custom">

    <div class="wppress_dummy_image_wrap">
        <table width="100%">
            <tr>
                <th>
                    <?php _e( 'Width', 'wppress'); ?>
                </th>
                <th>
                    <?php _e( 'Height', 'wppress'); ?>
                </th>
                <th>
                    <?php _e( 'Text Color', 'wppress'); ?>
                </th>
                <th>
                    <?php _e( 'Background Color', 'wppress'); ?>
                </th>
            </tr>
            <tr>
                <td>
                    <input type="number" class="dummy_width" min="1" value="1024">
                </td>
                <td>
                    <input type="number" class="dummy_height" min="1" value="768">
                </td>
                <td>
                    <input type="text" class="dummy_color dummy_colorpicker" data-default-color="#FFFFFF" value="#FFFFFF">
                </td>
                <td>
                    <input type="text" class="dummy_bg dummy_colorpicker" data-default-color="#999999" value="#999999">
                </td>

            </tr>
            <tr>
                <td colspan="2" width="50%">
                    <p><b><?php _e('Use Registered Image Sizes','wppress'); ?></b>
                    </p>
                    <?php foreach($sizes as $size){ ?>
                    <button class="button dummy_sizes" data-width="<?php echo $size['width']; ?>" data-height="<?php echo $size['height']; ?>">
                        <?php echo $size[ 'label']; ?> (
                        <?php echo $size[ 'width']. "x".$size[ 'height']; ?>)</button>
                    <?php } ?>
                </td>
                <td colspan="2">
                    <p><b><?php _e('Ignore plain colors and upload Image?','wppress'); ?></b>
                    </p>
                    <select class="dummy_keyword">
                        <option value="use_color">
                            <?php _e( 'Use Plain Colors', 'wppress'); ?>
                        </option>
                        <?php foreach($this->typeKeywords as $type=>$label){ ?>
                        <option value="<?php echo strtolower($type); ?>">
                            <?php echo $label; ?>
                        </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>
        <p>
            <button class="dummy_upload button button-hero button-primary">
                <?php _e( 'Upload Dummy Image', 'wppress'); ?>
            </button>
        </p>
        <p>
            <?php _e( 'Enter the dimension as per your need and image will be uploaded for you', 'wppress'); ?>
        </p>
        <p>
            <?php echo sprintf(__( 'Images uploaded are generated via %s and %s', 'wppress'), '<a href="http://placehold.it" target="_blank">http://placehold.it</a>', '<a href="http://lorempixel.com/" target="_blank">http://lorempixel.com/</a>'); ?>
        </p>
        <p><b><?php echo sprintf(__('Brought to you by %s','wppress'),'<a href="http://wppress.net" target="_new">WPPress.net</a>'); ?></b>
        </p>

    </div>
</script>
<style>
.wppress_dummy_image_wrap{
    padding-top:20px;
}
.wppress_dummy_image_wrap,
.wppress_dummy_image_wrap td {
    text-align: center;
}
.wppress_dummy_image_wrap th,
.wppress_dummy_image_wrap td {
    vertical-align: top
}
.wppress_dummy_image_wrap .desc {
    font-weight: 400;
    font-size: 80%;
}
</style>

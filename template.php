<?php $sizes=$this->get_image_sizes(); ?>
<script type="text/html" id="tmpl-wppress-custom">

    <div class="wppress_dummy_image_wrap uploader-inline-contents">
        <table width="100%">
            <tr>
                <th>Width</th>
                <th>Height</th>
                <th>Text Color</th>
                <th>Background Color</th>
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
                <td colspan=4>
                    <?php foreach($sizes as $size){ ?>
                    <button class="button dummy_sizes" data-width="<?php echo $size['width']; ?>" data-height="<?php echo $size['height']; ?>">
                        <?php echo $size[ 'label']; ?> (
                        <?php echo $size[ 'width']. "x".$size[ 'height']; ?>)</button>
                    <?php } ?>
                </td>
            </tr>
        </table>


        <p>
            <button class="dummy_upload button button-hero button-primary">Upload Dummy Image</button>
        </p>
        <p>Enter the dimension as per your need and image will be uploaded for you</p>
    </div>
</script>
<style>
.wppress_dummy_image_wrap {
    text-align: center;
}
</style>


add_action('wp_footer', 'merge_columns_script');

function merge_columns_script() {
    if (is_product_category(array('parfums', 'findecollection', 'coffrets'))) {
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                const columnsContainer = document.querySelector('.wp-block-columns.is-layout-flex.wp-container-core-columns-is-layout-2.wp-block-columns-is-layout-flex');
                if (columnsContainer) {
                    const columns = columnsContainer.querySelectorAll('.wp-block-column');
                    if (columns.length === 2) {
                        // Move the content of the second column to the first column
                        while (columns[1].childNodes.length > 0) {
                            columns[0].appendChild(columns[1].childNodes[0]);
                        }
                        // Remove the second column
                        columns[1].remove();
                        // Adjust the style of the remaining column to take full width
                        columns[0].style.flexBasis = '100%';
                        columns[0].style.padding = '0';

                        // Adjust the size of the products inside the column
                        const products = columns[0].querySelectorAll('.product');
                        products.forEach(product => {
                            product.style.transform = 'scale(0.8)'; // Adjust the scale as needed
                            product.style.margin = '10px'; // Adjust the margin as needed
                        });
                    }
                }
            });
        </script>
        <?php
    }
}


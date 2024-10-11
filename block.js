const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { SelectControl, PanelBody } = wp.components;
const { useState, useEffect } = wp.element;

registerBlockType('custom/category-banner', {
    title: 'Custom Category Banner',
    icon: 'category',
    category: 'common',
    attributes: {
        categoryId: {
            type: 'number',
            default: 0
        }
    },
    edit({ attributes, setAttributes }) {
        const [categories, setCategories] = useState([]);

        useEffect(() => {
            wp.apiFetch({ path: '/wc/v3/products/categories' }).then((categories) => {
                setCategories(categories);
            });
        }, []);

        return (
            <>
                <InspectorControls>
                    <PanelBody title="Category Settings">
                        <SelectControl
                            label="Select Product Category"
                            value={attributes.categoryId}
                            options={categories.map((category) => ({
                                label: category.name,
                                value: category.id
                            }))}
                            onChange={(value) => setAttributes({ categoryId: parseInt(value) })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div>
                    {attributes.categoryId === 0 ? (
                        <p>Select a category in the block settings.</p>
                    ) : (
                        <p>Displaying banner for category ID {attributes.categoryId}</p>
                    )}
                </div>
            </>
        );
    },
    save() {
        return null;
    }
});

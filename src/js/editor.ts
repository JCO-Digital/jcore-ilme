/**
 * Block editor additions, loaded only in the editor (includes/assets.php).
 *
 * Images the theme crops with CSS (object-fit: cover) get the image's own
 * Scale "Cover" turned on, which makes JCORE Kuva Kohde show its focal point
 * picker for them (the plugin only shows it for cover-scaled images); the
 * picked point is the crop's centre. Those images are:
 *
 * - the image style "Wide band" (includes/gutenberg.php),
 * - images in an image-text section (a jcore/grid with the class
 *   is-image-text, see the style guide).
 *
 * When an image stops being cropped (style removed, moved out of the
 * section), Cover is turned off again unless an aspect ratio still needs it.
 */
declare const wp: any;

const BAND = /(^|\s)is-style-band(\s|$)/;
const IMAGE_TEXT = /(^|\s)is-image-text(\s|$)/;

const withCroppedCover = wp.compose.createHigherOrderComponent(
	(BlockEdit: any) => (props: any) => {
		const { name, clientId, attributes, setAttributes } = props;
		const isImage = name === 'core/image';

		const inImageText = wp.data.useSelect(
			(select: any) => {
				if (!isImage) {
					return false;
				}
				const editor = select('core/block-editor');
				return editor
					.getBlockParents(clientId)
					.some((id: string) => {
						const parent = editor.getBlock(id);
						return parent?.name === 'jcore/grid' && IMAGE_TEXT.test(parent.attributes.className || '');
					});
			},
			[clientId, isImage]
		);

		const isCropped = isImage && (BAND.test(attributes.className || '') || inImageText);

		wp.element.useEffect(() => {
			if (!isImage) {
				return;
			}
			if (isCropped && attributes.scale !== 'cover') {
				setAttributes({ scale: 'cover' });
			} else if (!isCropped && attributes.scale === 'cover' && !attributes.aspectRatio) {
				setAttributes({ scale: undefined, focalPoint: undefined });
			}
		}, [isCropped]);

		return wp.element.createElement(BlockEdit, props);
	},
	'withCroppedCover'
);

wp.hooks.addFilter('editor.BlockEdit', 'jcore/image-cropped-cover', withCroppedCover);

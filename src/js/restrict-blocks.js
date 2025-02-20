wp.domReady( () => {

	// Unregister core blocks.
    const doNotUseBlocks = [
        'core/quote',
        'core/column',
        'core/columns',
        'core/more',
        'core/media-text',
        'core/verse',
        'core/query',
        'core/navigation',
        'core/latest-posts',
        'core/social-link',
        'core/social-links',
        'core/tag-cloud',
        'core/page-list',
        'core/latest-comments',
        'core/archives',
        'core/calendar',
        'core/categories',
        'core/search',
        'core/widget-group',
        'core/nextpage',

    ]
	
    doNotUseBlocks.forEach( function( blockType ) {
        wp.blocks.unregisterBlockType( blockType );
    } );

    // Unregister core block variations.
    const doNotUseGroupVariation = [
        'group-grid',
        'group-stack',
        'group-row',
    ]
    doNotUseGroupVariation.forEach( function( blockVariation ) {
    wp.blocks.unregisterBlockVariation( 'core/group', blockVariation );    
    } );

     // Unregister embed block variations.
    const doNotUseEmbedVariation = [
        'smugmug',
        'twitter',
        'ted',
        'bluesky',
        'wolfram-cloud',
        'amazon-kindle',
        'speakdeck',
        'scribd',
        'videopress',
        'tumblr',
        'pocket-casts',
        'reddit',
        'reverbnation',
        'soundcloud',
        'flickr',
        'vimeo',
        'animoto',
        'tiktok',
        'cloudup',
        'crowdsignal',
        'imgur',
        'issuu',
        'dailymotion',
        'kickstarter',
        'mixcloud',
        'screencast',
        'speaker-deck',
        'wordpress-tv',
        'pinterest',
        'wordpress',
        'spotify',
    ]
    doNotUseEmbedVariation.forEach( function( blockVariation ) {
    wp.blocks.unregisterBlockVariation( 'core/embed', blockVariation );    
    } );
} );
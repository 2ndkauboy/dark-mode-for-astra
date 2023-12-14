/**
 * Live-update changed settings in real time in the Customizer preview.
 **/

( function ( $ ) {
	var body_tag = document.querySelector( 'body' );
	var color_options = [
		{
			setting: 'dmfa-light-button-color',
			property: 'button-text-color',
			selector: 'html:not(.is-dark-theme)'
		},
		{
			setting: 'dmfa-light-button-h-color',
			property: 'button-text-color-active',
			selector: 'html:not(.is-dark-theme)'
		},
		{
			setting: 'dmfa-light-button-bg-color',
			property: 'button-background-color',
			selector: 'html:not(.is-dark-theme)'
		},
		{
			setting: 'dmfa-light-button-bg-h-color',
			property: 'button-background-color-active',
			selector: 'html:not(.is-dark-theme)'
		},
		{
			setting: 'dmfa-light-color-button-border-group-border-color',
			property: 'button-border-color',
			selector: 'html:not(.is-dark-theme)'
		},
		{
			setting: 'dmfa-light-color-button-border-group-border-h-color',
			property: 'button-border-color-active',
			selector: 'html:not(.is-dark-theme)'
		},
		{
			setting: 'dmfa-dark-button-color',
			property: 'button-text-color',
			selector: 'html.is-dark-theme'
		},
		{
			setting: 'dmfa-dark-button-h-color',
			property: 'button-text-color-active',
			selector: 'html.is-dark-theme'
		},
		{
			setting: 'dmfa-dark-button-bg-color',
			property: 'button-background-color',
			selector: 'html.is-dark-theme'
		},
		{
			setting: 'dmfa-dark-button-bg-h-color',
			property: 'button-background-color-active',
			selector: 'html.is-dark-theme'
		},
		{
			setting: 'dmfa-dark-color-button-border-group-border-color',
			property: 'button-border-color',
			selector: 'html.is-dark-theme'
		},
		{
			setting: 'dmfa-dark-color-button-border-group-border-h-color',
			property: 'button-border-color-active',
			selector: 'html.is-dark-theme'
		},
	];
	color_options.forEach( function ( color_option ) {
		wp.customize( 'astra-settings[' + color_option.setting + ']', function ( value ) {
			value.bind( function ( new_value ) {
				var style_tag = document.getElementById( color_option.setting );
				// Create an empty style tag, if it is not there, yet.
				if ( !style_tag ) {
					body_tag.insertAdjacentHTML(
						'beforeend',
						'<style id="' + color_option.setting + '"></style>'
					);
					// Select the newly created style tag.
					style_tag = document.getElementById( color_option.setting );
				}
				// Assign the new valued style tag.
				style_tag.innerHTML = color_option.selector + '{ --ast-dark-mode--' + color_option.property + ':' + new_value + '; }';
			} );
		} );
	} );

} )( jQuery );

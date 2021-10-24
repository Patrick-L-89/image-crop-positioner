<?php

namespace Mentosmenno2\ImageCropPositioner\Admin\Settings\JSFacesDetection;

use Mentosmenno2\ImageCropPositioner\Admin\Settings\Menu;

class Section {

	public const NAME = 'image_crop_positioner_options_js_faces_detection';

	public function register_hooks(): void {
		add_action( 'admin_init', array( $this, 'register_section' ) );
	}

	public function register_section(): void {
		add_settings_section( self::NAME, $this->get_label(), array( $this, 'render_description' ), Menu::NAME );
	}

	public function get_label(): string {
		return __( 'JavaScript faces detection', 'image-crop-positioner' );
	}

	/**
	 * Render a settings section description based on the section ID
	 */
	public function render_description( array $args ): void {
		$text = esc_html__( 'Here you can change the JS faces detection settings.', 'image-crop-positioner' );
		if ( empty( $text ) ) {
			return;
		}
		echo wp_kses_post( '<p>' . $text . '</p>' );
	}
}

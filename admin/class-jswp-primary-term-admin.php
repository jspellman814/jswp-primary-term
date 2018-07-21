<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Primary_Term
 * @subpackage Primary_Term/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Register a meta box using a class.
 */
if ( ! class_exists( 'JSWP_Primary_Term' ) ) {

	class JSWP_Primary_Term {

		/**
		 * JSWP_Primary_Term constructor.
		 */
		public function __construct() {
			if ( is_admin() ) {
				add_action( 'load-post.php', array( $this, 'init_metabox' ) );
				add_action( 'load-post-new.php', array(
					$this,
					'init_metabox'
				) );
			}
		}

		/**
		 * Meta box initialization.
		 */
		public function init_metabox() {
			add_action( 'add_meta_boxes', array(
				$this,
				'jswp_add_metabox'
			) );
			add_action( 'save_post', array(
				$this,
				'jswp_save_metabox'
			), 10, 2 );
		}

		/**
		 * Adds the meta box.
		 */
		public function jswp_add_metabox() {
			// Available to all public post types
			$post_types = get_post_types( array( 'public' => TRUE ) );

			add_meta_box(
				'jswp-primary-term',
				__( 'Primary Term', 'jswp' ),
				array( $this, 'jswp_render_metabox' ),
				$post_types,
				'side',
				'high'
			);
		}

		/**
		 * Renders the meta box.
		 */
		public function jswp_render_metabox( $post ) {
			// Add nonce for security and authentication.
			wp_nonce_field( 'jswp_save_primary_term', 'jswp_pt_nonce' );
			$taxonomies = get_post_taxonomies( $post ); ?>

            <label for="jwp_pt_field">Set the primary term for this post</label>
            <select name="jwp_pt_field" id="jwp_pt_field" class="postbox">

                <option value=""><?php echo esc_attr_e( '--Select a term--', 'jswp' ); ?></option>
				<?php foreach ( $taxonomies as $taxonomy ) :
					// Hacky attempt to limit the options in the select box to only selected terms
					$terms = get_the_terms( $post->ID, $taxonomy );

					foreach ( $terms as $term ) { ?>
                        <option value="<?php _e( $term->term_id ); ?>" <?php selected( $term->term_id, get_post_meta( $post->ID, 'jswp_pt', TRUE ), TRUE ) ?>>
							<?php _e( $term->name ); ?>
                        </option>
					<?php }
				endforeach; ?>

            </select>

		<?php }

		/**
		 * Handles saving the meta box.
		 *
		 * @param int $post_id Post ID.
		 * @param WP_Post $post Post object.
		 *
		 * @return null
		 */
		public function jswp_save_metabox( $post_id, $post ) {
			// Add nonce for security and authentication.
			$nonce_name   = isset( $_POST['jswp_pt_nonce'] ) ? $_POST['jswp_pt_nonce'] : '';
			$nonce_action = 'jswp_save_primary_term';

			// Check if nonce is set.
			if ( ! isset( $nonce_name ) ) {
				return;
			}

			// Check if nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}

			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			// Check if not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			// Save/update selection in wp_postmeta table
			if ( isset( $_POST['jwp_pt_field'] ) ) {
				update_post_meta( $post_id, 'jswp_pt', sanitize_text_field( $_POST['jwp_pt_field'] ) );
			} else {
				update_post_meta( $post_id, 'jswp_pt', '' );
			}

		}

	}

	new JSWP_Primary_Term();
}
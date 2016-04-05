<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Bti_Layout_Master_Admin_Layout {

	function __construct() {

		// Register new tab
		add_action( 'admin_init', array( $this, 'register_tab' ), 100 );

		// AJAX Request Handler
		add_action( 'wp_ajax_bti_layout_master_update_fields', array( $this, 'update_fields' ) );
	}

	/**
	* Adds new options tab to Ninja Forms
	*/
	function register_tab() {

		$args = array(
			'name'               => __( 'Layout', 'bti_layout_master' ),
			'page'               => 'ninja-forms',
			'display_function'   => array( $this, 'layout_page' ),
			'save_function'      => array( $this, 'save_layout_page' ),
			'show_save'          => false,
			'disable_no_form_id' => true,
			'tab_reload'         => true,
		);

		ninja_forms_register_tab( 'layout', $args );
	}

	/**
	 * Layout tab content (page content)
	 */
	function layout_page() {

		// Form Settings
		$form_id   = isset ( $_REQUEST['form_id'] ) ? $_REQUEST['form_id'] : '';
		$settings  = ninja_forms_get_form_by_id( $form_id );
		$columns   = isset( $settings['data']['bti_layout_master']['cols'] ) ? $settings['data']['bti_layout_master']['cols'] : 1;
		$rendering = isset( $settings['data']['bti_layout_master']['rendering'] ) ? $settings['data']['bti_layout_master']['rendering'] : '';

		// Form Fields
		$fields = ninja_forms_get_fields_by_form_id( $form_id );
		?>

			<div class="wrap bti_layout_master">
				<!-- Layout Settings -->
				<table class="form-table">
					<tbody>

						<tr>

							<!-- Number of columns -->
							<th scope="row">
								<label for="bti_layout_master_cols"><?php _e( 'Columns', 'bti_layout_master' ); ?></label>
							</th>
							<td>
								<select name="bti_layout_master_cols" id="bti_layout_master_cols" onchange="this.form.submit()">
									<?php for ( $i=1; $i<=4; $i++ ) : ?>
										<option value="<?php echo esc_attr( $i ); ?>"<?php echo ( $columns == $i ) ? ' selected' : ''; ?>><?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
							</td>

							<!-- Frontend Rendering -->
							<th scope="row">
								<label for="bti_layout_master_rendering"><?php _e( 'Front-end Rendering', 'bti_layout_master' ); ?></label>
							</th>
							<td>
								<select name="bti_layout_master_rendering" id="bti_layout_master_rendering" onchange="this.form.submit()">
									<option value="" <?php echo ( '' == $rendering ) ? ' selected' : ''; ?>><?php _e( 'Rows (Default)', 'bti_layout_master' ); ?></option>
									<option value="columns" <?php echo ( 'columns' == $rendering ) ? ' selected' : ''; ?>><?php _e( 'Columns', 'bti_layout_master' ); ?></option>
								</select>
							</td>

						</tr>

					</tbody>
				</table>
				<!-- End of Layout Settings -->

				<!-- Gridster -->
				<?php
					$gridster_items = '';
					$move = 0;

					foreach ( $fields as $field ) {
						$label      = isset( $field['data']['label'] ) ? $field['data']['label'] : 'Field ID: '. $field['id'];
						$bti_layout = isset( $field['data']['bti_layout_master'] ) ? $field['data']['bti_layout_master'] : array( 'row' => 1, 'col' => 1, 'sizex' => 1 );

						// Update max column size if layout was changed
						if ( '' == $rendering ) {
							// Rendering: ROWS
							if ( $bti_layout['sizex'] > $columns ) {
								$bti_layout['sizex'] = $columns;
							}
						} else {
							// Rendering: COLUMNS
							$bti_layout['sizex'] = 1;
						}

						// Update column and row
						$position = $bti_layout['sizex'] + $bti_layout['col'] - 1;
						if ( $position > $columns ) {
							$bti_layout['col'] = 1;
							$bti_layout['row'] += 1;
							$move++;
						}

						// Move widget by 'move' rows
						$bti_layout['row'] = ( $move ) ? $bti_layout['row'] + $move : $bti_layout['row'];

						$gridster_items .= sprintf(
							'<li data-row="%d" data-col="%d" data-sizex="%d" data-sizey="1">
								<div data-field-id="%d">%s <span>(%s: %d)</span></div>
							</li>',
							$bti_layout['row'], $bti_layout['col'], $bti_layout['sizex'], $field['id'], $label, __( 'Field ID', 'bti_layout_master' ), $field['id']
						);
					}
				?>

				<div class="gridster" data-columns="<?php echo $columns; ?>" data-max-size-x="<?php echo ( 'columns' == $rendering ) ? 1 : $columns; ?>">
					<ul>
						<?php echo $gridster_items; ?>
					</ul>
				</div>
				<!-- End Of Gridster -->

			</div>

		<?php
	}

	/**
	 * Saves layout settings (number of columns )
	 */
	function save_layout_page( $form_id, $data ) {

		global $wpdb;

		// Get the form
		$form = ninja_forms_get_form_by_id( $form_id );

		// Update settings
		if ( NF_PLUGIN_VERSION >= '2.9' ) {
			// New Ninja Form release
			Ninja_Forms()->form( $form_id )->update_setting(
				'bti_layout_master',
				array(
					'cols'      => $data['bti_layout_master_cols'],
					'rendering' => $data['bti_layout_master_rendering']
				)
			);
		} else {
			// Support for old structures
			$settings = $form['data'];
			$settings['bti_layout_master']['cols'] = $data['bti_layout_master_cols'];
			$settings['bti_layout_master']['cols'] = $data['bti_layout_master_rendering'];

			// Store settings
			$wpdb->update( NINJA_FORMS_TABLE_NAME, array( 'data' => serialize( $settings ) ), array( 'id' => $form_id ) );
		}

		return false;
	}

	/**
	 * Processes layout changes
	 */
	function update_fields() {

		if ( isset( $_POST['gridster_data'] ) && is_array( $_POST['gridster_data'] ) ) {

			// Retrieve form and all form fields
			$field_id  = $_POST['gridster_data'][0]['field_id'];
			$form      = ninja_forms_get_form_by_field_id( $field_id );
			$form_lm   = isset( $form['data']['bti_layout_master'] ) ? $form['data']['bti_layout_master'] : false;
			$rendering = ( $form_lm && isset( $form_lm['rendering'] ) ) ? $form_lm['rendering'] : '';
			$fields    = ninja_forms_get_fields_by_form_id( $form['id'] );

			// Sort POST fields
			if ( 'columns' == $rendering ) {
				$sorted = $this->sort_col_fields( $_POST['gridster_data'] );
			} else {
				$sorted = $this->sort_row_fields( $_POST['gridster_data'] );
			}

			// Process fields
			$order = 0;
			$items = count( $sorted );
			for ( $i=0; $i < $items; $i++ ) {
				$p_field = ( $i > 0 ) ? $sorted[( $i - 1 )] : null;
				$c_field = $sorted[$i];
				$n_field = ( $i < ( $items - 1 ) ) ? $sorted[( $i + 1)] : null;

				// Field details
				$field_id = $c_field['field_id'];
				$data     = array(
					'row'       => $c_field['row'],
					'col'       => $c_field['col'],
					'sizex'     => $c_field['sizex'],
					'order'     => $c_field['order'],      // Since version 1.4
					'last'      => false,                  // Since version 1.4
					'rendering' => $rendering,             // Since version 1.5
					'form_last' => false                   // Since version 1.5
				);

				// Add empty column(s) in front ?
				if ( null != $p_field && $c_field['col'] > 1 ) {
					// No columns in front
					if ( $c_field['row'] != $p_field['row'] ) {
						$data['before'] = $c_field['col'] - 1;
					}

					// Empty space between columns
					if ( $c_field['row'] == $p_field['row'] && ( $p_field['col'] + $p_field['sizex'] ) != $c_field['col'] ) {
						$data['before'] = $c_field['col'] - ( $p_field['col'] + $p_field['sizex'] );
					}
				}

				// Break current row ?
				if ( $c_field['row'] < $n_field['row'] && ( $c_field['col'] + $c_field['sizex'] - 1 ) < $form['data']['bti_layout_master']['cols'] ) {
					$data['after'] = $form['data']['bti_layout_master']['cols'] - ( $c_field['col'] + $c_field['sizex'] - 1 );
				}

				// Is this last column ?
				if ( '' == $rendering ) {
					if ( isset( $sorted[($i+1)] ) && $c_field['row'] < $sorted[($i+1)]['row'] ) {
						$data['last'] = true;
					}
				} else {
					if ( isset( $sorted[($i+1)] ) && $c_field['col'] < $sorted[($i+1)]['col'] ) {
						$data['last'] = true;
					}
				}

				// Form last
				if ( ! isset( $sorted[($i+1)] ) ) {
					$data['form_last'] = true;
				}

				// Update field metadata
				$this->save_field( $field_id, $data, $fields );

				$order++;
			}
		}

		exit();
	}

	/**
	 * Save field details
	 */
	function save_field( $field_id, $data, $fields ) {
		global $wpdb;

		foreach ( $fields as $field ) {
			if ( $field_id == $field['id'] ) {
				$field['data']['bti_layout_master'] = $data;
				$wpdb->update(
					NINJA_FORMS_FIELDS_TABLE_NAME,
					array( 'data' => serialize( $field['data'] ) ),
					array( 'id' => $field_id )
				);

				break;
			}
		}
	}

	/**
	 * Sorts fields by the way they were arranged in layout tab (row/default) and
	 * assigns correct order number to individual items.
	 */
	function sort_row_fields( $raw_fields ) {
		$rows = array();
		$tmp = array();
		$result = array();

		// Sorts the rows
		foreach ( $raw_fields as $field ) {

			// Make sure array initialized
			if ( ! isset( $rows[ $field['row'] ] ) ) {
				$rows[ $field['row'] ] = array();
			}

			// Add field to row
			$rows[ $field['row'] ][ $field['col'] ] = $field;
		}

		ksort($rows);

		// Sorts columns and store everything in tmp array
		foreach ( $rows as $row ) {
			ksort($row);
			$tmp[] = $row;
		}

		// Assign order # and place everything in single dimention array
		$order = 0;
		foreach ( $tmp as $row ) {
			foreach ( $row as $col ) {
				$col['order'] = $order;
				$result[] = $col;

				$order++;
			}
		}

		return $result;
	}

	/**
	 * Sorts fields by the way they were arranged in layout tab (row/default) and
	 * assigns correct order number to individual items.
	 */
	function sort_col_fields( $raw_fields ) {
		$cols = array();
		$tmp = array();
		$result = array();

		// Sorts the columns
		foreach ( $raw_fields as $field ) {

			// Make sure array initialized
			if ( ! isset( $cols[ $field['col'] ] ) ) {
				$cols[ $field['col'] ] = array();
			}

			// Add field to row
			$cols[ $field['col'] ][ $field['row'] ] = $field;
		}

		ksort($cols);

		// Sorts rows and store everything in tmp array
		foreach ( $cols as $col ) {
			ksort($col);
			$tmp[] = $col;
		}


		// Assign order # and place everything in single dimention array
		$order = 0;
		foreach ( $tmp as $col ) {
			foreach ( $col as $row ) {
				$row['order'] = $order;
				$result[] = $row;

				$order++;
			}
		}

		return $result;
	}

}

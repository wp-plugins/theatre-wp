<?php
/**
 * TWP_Spectacles_Widget class.
 *
 * Plugin Spectacle widget class
 *
 * @package TheatreWP
 * @author  Jose Bolorino <jose.bolorino@gmail.com>
 */

if ( realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME']) )
    exit('Do not access this file directly.');

class TWP_Spectacles_Widget extends WP_Widget {

    public $id = 'twp-show-spectacles';
    public $title = 'Productions ';
    public $description = 'Display a list of your productions';


    /**
     * @var TWP_Spectacle
     */
    public $spectacle;

    /**
     * Register widget with WordPress.
     */
    function __construct() {

        $this->spectacle = new TWP_Spectacle;

        parent::__construct(
            $this->id, // Base ID
            __( $this->title, 'theatrewp' ), // Name
            array( 'description' => __( $this->description, 'theatrewp' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        if ( ! $spectacles = $this->spectacle->get_spectacles( $instance['number'], $instance['sortby'], $instance['sort'] ) ) {
            return false;
        }

        echo $spectacles;

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'theatrewp' );
        }

        $number = ( isset( $instance['number'] ) && intval( $instance['number'] ) > 0 ? intval( $instance['number'] ) : get_option( 'twp_widget_spectacles_number' ) );

        $sortby = ( isset( $instance['sortby'] ) ? $instance['sortby'] : get_option( 'twp_widget_spectacles_sortby' ) );

        $sort = ( isset( $instance['sort'] ) ? $instance['sort'] : get_option( 'twp_widget_spectacles_sort' ) );

        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
        <label for="widget-show-spectacles-number"><?php _e( 'Number of spectacles to show (0 for all):', 'theatrewp' ); ?></label>
        <input id="widget-show-spectacles-number" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" size="3" value="<?php echo $number; ?>">
        </p>

        <p>
        <label for="widget-show-spectacles-sortby"> <?php _e( 'Sort by', 'theatrewp' ); ?></label>
        <select id="widget-show-spectacles-sortby" name="<?php echo $this->get_field_name( 'sortby' ); ?>">
            <option <?php if ( $sortby == 'post_date' ) echo 'selected="selected"'; ?> value="post_date"><?php echo __( 'Date' , 'theatrewp' ); ?></option>
            <option <?php if ( $sortby == 'title' ) echo 'selected="selected"'; ?> value="title"><?php echo __( 'Title' , 'theatrewp' ); ?></option>
        </select>

        <select id="widget-show-spectacles-sort" name="<?php echo $this->get_field_name( 'sort' ); ?>">
            <option <?php if ( $sort == 'ASC' ) echo 'selected="selected"'; ?> value="ASC"><?php echo __( 'ASC' , 'theatrewp' ); ?></option>
            <option <?php if ( $sort == 'DESC' ) echo 'selected="selected"'; ?> value="DESC"><?php echo __( 'DESC' , 'theatrewp' ); ?></option>
        </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? intval( $new_instance['number'] ) : 0;
        $instance['sortby'] = ( ! empty( $new_instance['sortby'] ) ) ? strip_tags( $new_instance['sortby'] ) : '';
        $instance['sort'] = ( ! empty( $new_instance['sort'] ) ) ? strip_tags( $new_instance['sort'] ) : 'ASC';

        return $instance;
    }

    public function set_valid_widgets() {
        $this->valid_widgets = array(
            'twp-show-spectacles',
            'twp-show-next-performances',
            'twp-next-performances',
            'twp-production-sponsors'
        );
    }

    public function is_valid_widget( $widget_id ) {
        if ( ! in_array( $widget_id, $this->valid_widgets ) ) {
            return false;
        }

        return true;
    }

} // class TWP_Spectacles_Widget

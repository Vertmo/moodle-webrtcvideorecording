<?php
/**
 * Defines the view event.
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

namespace mod_wrtcvr\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_wrtcvr instance viewed event class
 *
 * If the view mode needs to be stored as well, you may need to
 * override methods get_url() and get_legacy_log_data(), too.
 *
 * @package    mod_wrtcvr
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_module_viewed extends \core\event\course_module_viewed {

    /**
     * Initialize the event
     */
    protected function init() {
        $this->data['objecttable'] = 'wrtcvr';
        parent::init();
    }
}

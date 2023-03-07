<?php
/**
 * Periodical execution module
 *
 * phpcs:disable WordPress.DateTime.RestrictedFunctions.date_date
 *
 * @package wpmu-dev-seo
 */

/**
 * Cron controller
 */
class Smartcrawl_Controller_Cron {

	use Smartcrawl_Singleton;

	const ACTION_CRAWL = 'wds-cron-start_service';

	const ACTION_LIGHTHOUSE = 'wds-cron-start_lighthouse';

	const ACTION_LIGHTHOUSE_RESULT = 'wds-cron-lighthouse_result';

	/**
	 * Controller actively running flag
	 *
	 * @var bool
	 */
	private $is_running = false;

	/**
	 * Boots controller interface
	 *
	 * @return bool
	 */
	public function run() {
		if ( ! $this->is_running() ) {
			$this->add_hooks();
		}

		return $this->is_running();
	}

	/**
	 * Check whether controller interface is active
	 *
	 * @return bool
	 */
	public function is_running() {
		return ! ! $this->is_running;
	}

	/**
	 * Sets up controller listening interface
	 *
	 * Also sets up controller running flag approprietly.
	 *
	 * @return void
	 */
	private function add_hooks() {
		add_filter( 'cron_schedules', array( $this, 'add_cron_schedule_intervals' ) );

		$this->is_running = true;
	}

	/**
	 * Gets prefixed filter action
	 *
	 * @param string $what Filter action suffix.
	 *
	 * @return string Full filter action
	 */
	public function get_filter( $what ) {
		return 'wds-controller-cron-' . $what;
	}

	/**
	 * Gets next scheduled event time
	 *
	 * @param string $event Optional event name, defaults to service start.
	 *
	 * @return int|bool UNIX timestamp or false if no next event
	 */
	public function get_next_event( $event = false ) {
		$event = ! empty( $event ) ? $event : self::ACTION_CRAWL;

		return wp_next_scheduled( $this->get_filter( $event ) );
	}

	/**
	 * Unschedules a particular event
	 *
	 * @param string $event Optional event name, defaults to service start.
	 *
	 * @return bool
	 */
	public function unschedule( $event = false ) {
		return false; // Not in the free version.
	}

	/**
	 * Controller interface stop
	 *
	 * @return bool
	 */
	public function stop() {
		if ( $this->is_running() ) {
			$this->remove_hooks();
		}

		return $this->is_running();
	}

	/**
	 * Tears down controller listening interface
	 *
	 * Also sets up controller running flag approprietly.
	 *
	 * @return void
	 */
	private function remove_hooks() {

		remove_action( $this->get_filter( self::ACTION_CRAWL ), array( $this, 'start_crawl' ) );
		remove_filter( 'cron_schedules', array( $this, 'add_cron_schedule_intervals' ) );

		$this->is_running = false;
	}

	/**
	 * Checks whether we have a next event scheduled
	 *
	 * @param string $event Optional event name, defaults to service start.
	 *
	 * @return bool
	 */
	public function has_next_event( $event = false ) {
		return ! ! $this->get_next_event( $event );
	}

	/**
	 * Sets up overall schedules
	 *
	 * @uses Smartcrawl_Controller_Cron::set_up_crawler_schedule()
	 * @return void
	 */
	public function set_up_schedule() {
		Smartcrawl_Logger::debug( 'Setting up schedules' );
		$this->set_up_crawler_schedule();
	}

	/**
	 * Sets up crawl service schedule
	 *
	 * @return bool
	 */
	public function set_up_crawler_schedule() {
		return false; // Not in the free version.
	}

	/**
	 * Gets estimated next event time based on parameters
	 *
	 * @param int    $pivot     Pivot time - base estimation relative to this (UNIX timestamp).
	 * @param string $frequency Valid frequency interval.
	 * @param int    $dow       Day of the week (0-6).
	 * @param int    $tod       Time of day (0-23).
	 *
	 * @return int Estimated next event time as UNIX timestamp
	 */
	public function get_estimated_next_event( $pivot, $frequency, $dow, $tod ) {
		$start                = $this->get_initial_pivot_time( $pivot, $frequency );
		$offset               = $start + ( $dow * DAY_IN_SECONDS );
		$time                 = strtotime( date( "Y-m-d {$tod}:00", $offset ) );
		$current_month_length = (int) date( 'd', strtotime( 'last day of this month' ) );
		$freqs                = array(
			'daily'   => DAY_IN_SECONDS,
			'weekly'  => 7 * DAY_IN_SECONDS,
			'monthly' => $current_month_length * DAY_IN_SECONDS,
		);
		if ( $time > $pivot ) {
			return $this->convert_to_utc( $time );
		}

		$freq = $freqs[ $this->get_valid_frequency( $frequency ) ];

		return $this->convert_to_utc( $time + $freq );
	}

	private function convert_to_utc( $timestamp ) {
		$date_time = new DateTime( date( 'Y-m-d H:i:s', $timestamp ), wp_timezone() );
		$date_time->setTimezone( new DateTimeZone( 'UTC' ) );

		return $date_time->format( 'U' );
	}

	/**
	 * Gets primed pivot time for a given frequency value
	 *
	 * @param int    $pivot     Raw pivot UNIX timestamp.
	 * @param string $frequency Frequency interval.
	 *
	 * @return int Zeroed pivot time for given frequency interval
	 */
	public function get_initial_pivot_time( $pivot, $frequency ) {
		$frequency = $this->get_valid_frequency( $frequency );

		if ( 'daily' === $frequency ) {
			return strtotime( date( 'Y-m-d 00:00', $pivot ) );
		}

		if ( 'weekly' === $frequency ) {
			$monday = strtotime( 'this monday', $pivot );
			if ( $monday > $pivot ) {
				return $monday - ( 7 * DAY_IN_SECONDS );
			}

			return $monday;
		}

		if ( 'monthly' === $frequency ) {
			$day   = (int) date( 'd', $pivot );
			$today = strtotime( date( 'Y-m-d H:i', $pivot ) );

			return $today - ( $day * DAY_IN_SECONDS );
		}

		return $pivot;
	}

	/**
	 * Gets validated frequency interval
	 *
	 * @param string $freq Raw frequency string.
	 *
	 * @return string
	 */
	public function get_valid_frequency( $freq ) {
		if ( in_array( $freq, array_keys( $this->get_frequencies() ), true ) ) {
			return $freq;
		}

		return $this->get_default_frequency();
	}

	/**
	 * Gets a list of frequency intervals
	 *
	 * @return array
	 */
	public function get_frequencies() {
		return array(
			'daily'   => __( 'Daily', 'smartcrawl-seo' ),
			'weekly'  => __( 'Weekly', 'smartcrawl-seo' ),
			'monthly' => __( 'Monthly', 'smartcrawl-seo' ),
		);
	}

	/**
	 * Gets default frequency interval (fallback)
	 *
	 * @return string
	 */
	public function get_default_frequency() {
		return 'weekly';
	}

	/**
	 * Schedules a particular event
	 *
	 * @param string $event      Event name.
	 * @param int    $time       UNIX timestamp.
	 * @param string $recurrence Event recurrence.
	 *
	 * @return bool
	 */
	public function schedule( $event, $time, $recurrence = false ) {
		return false; // Not in the free version.
	}

	/**
	 * Starts crawl
	 *
	 * @return bool
	 */
	public function start_crawl() {
		return false; // Not in the free version.
	}

	/**
	 * Set up cron schedule intervals
	 *
	 * @param array $intervals Intervals known this far.
	 *
	 * @return array
	 */
	public function add_cron_schedule_intervals( $intervals ) {
		if ( ! is_array( $intervals ) ) {
			return $intervals;
		}

		if ( ! isset( $intervals['daily'] ) ) {
			$intervals['daily'] = array(
				'display'  => __( 'SmartCrawl Daily', 'smartcrawl-seo' ),
				'interval' => DAY_IN_SECONDS,
			);
		}

		if ( ! isset( $intervals['weekly'] ) ) {
			$intervals['weekly'] = array(
				'display'  => __( 'SmartCrawl Weekly', 'smartcrawl-seo' ),
				'interval' => 7 * DAY_IN_SECONDS,
			);
		}

		if ( ! isset( $intervals['monthly'] ) ) {
			$intervals['monthly'] = array(
				'display'  => __( 'SmartCrawl Monthly', 'smartcrawl-seo' ),
				'interval' => 30 * DAY_IN_SECONDS,
			);
		}

		return $intervals;
	}

	/**
	 * Clone
	 */
	private function __clone() {
	}

	public function set_up_lighthouse_schedule() {
		return false;
	}

	public function start_lighthouse() {
		return false;
	}

	public function schedule_lighthouse_result_check() {
		return false;
	}

	public function check_lighthouse_result() {
		return false;
	}
}

